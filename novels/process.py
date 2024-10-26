##
import os
import sys

#ENV
print(sys.argv)

##get args
FORCE_REBUILD = "--force-rebuild" in sys.argv
try: COMPRESS_FACTOR = int(sys.argv[sys.argv.index("--compression-factor")+1])
except ValueError: COMPRESS_FACTOR = 4

try: SELECT = (sys.argv[sys.argv.index("--select")+1])
except ValueError: SELECT = None

print(f"FORCE_REBUILD: {FORCE_REBUILD}\nCOMPRESSION_FACTOR: {COMPRESS_FACTOR}")

DELIMIT = not ("--no-delimit" in sys.argv)


#imports
from PyPDF2 import PdfReader
import pymupdf as fitz
import json
from PIL import Image
import sys

def extract_information(pdf_path):
    with open(pdf_path, 'rb') as f:
        pdf = PdfReader(f)
        information = pdf.metadata
        number_of_pages = len(pdf.pages)

        txt = f"""
        Information about {pdf_path}: 

        Author: {information.author}
        Creator: {information.creator}
        Producer: {information.producer}
        Subject: {information.subject}
        Title: {information.title}
        Number of pages: {number_of_pages}
        """

        #print(txt)
        return information


def extract_all_images(pdf_path, output_folder):
    image_data = []  # List to store image data

    with fitz.open(pdf_path) as pdf:
        for page_number in range(len(pdf)):
            page = pdf[page_number]  # Get each page
            images = page.get_images(full=True)

            for img_index, img in enumerate(images):
                xref = img[0]  # Get the xref number
                pix = fitz.Pixmap(pdf, xref)  # Create a Pixmap for the image

                # Define the image file name
                try:
                    image_name = f"{os.path.splitext(os.path.basename(pdf_path))[0]}_page{page_number+1}_img{img_index+1}.jpeg".replace("/","").replace(" ","")
                    image_path = os.path.join(output_folder, image_name)
                    dist_image_path=os.path.join(output_folder, f"dist_{image_name}")
                    pix.save(image_path)  # Save the raw image
                except:
                    image_name = f"{os.path.splitext(os.path.basename(pdf_path))[0]}_page{page_number+1}_img{img_index+1}.png".replace("/","").replace(" ","")
                    image_path = os.path.join(output_folder, image_name)
                    dist_image_path=os.path.join(output_folder, f"dist_{image_name}")
                    pix.save(image_path)  # Save the raw image

                try:
                    #save low res image
                    with Image.open(image_path) as img_pillow:
                        # Calculate the new dimensions
                        new_height = 300 #int(img_pillow.height // COMPRESS_FACTOR)  # Adjust as needed
                        new_width = int(new_height * 0.625)  # Enforcing 1.6:1 aspect ratio

                        # Resize the image
                        low_res_image = img_pillow.resize((new_width, new_height), Image.ANTIALIAS)

                        # Save or display the image
                        low_res_image.save(dist_image_path)
                except:
                    low_res_image.save(dist_image_path.replace('.jpeg', '.png'))

                # Get the position of the image (bounding box)
                bbox = page.get_image_bbox(img)
                
                # Append image data to the list
                image_data.append({
                    "file_name": image_path,
                    "page_number": page_number,
                    "image_index": img_index,
                    "bbox": "bbox"  # Bounding box as (x0, y0, x1, y1)
                })
                
                pix = None  # Release the Pixmap

    return image_data

def extract_text_page_by_page(pdf_path):
    text_output = {}  # Dictionary to store text by page number

    with fitz.open(pdf_path) as pdf:
        for page_number in range(len(pdf)):
            page = pdf[page_number]  # Get each page
            text = page.get_text()  # Extract text from the page
            text_output[page_number + 1] = text  # Store the text with page number as key

    return text_output

def main():
    ##get args
    

    path = os.getcwd()
    print(path)

    files = os.listdir(path)

    for file in files:
        if ".pdf" in file:
            #select
            if (SELECT):
                if not ".pdf" in file:
                    continue
                if not (file == SELECT):
                    continue
                        
            
            #generate output path
            outputPath=file.replace(".pdf", "/")

            #ignore if made and not force rebuild
            if os.path.exists(outputPath): 
                if not FORCE_REBUILD: continue

            #attempt to create path
            try:
                os.mkdir(outputPath)
            except FileExistsError:
                pass

            #extract metadata info
            metadata = extract_information(file)

            #extract image info
            img_data = extract_all_images(file, outputPath)

            #extract text
            text=extract_text_page_by_page(file)

            #fix rich formatting (removes header, may not be universal)
            #disabled
            out={}
            i = 0
            for pg in text.values():
                if DELIMIT:
                    #use page number as delimiter
                    segments = pg.split(str(i))

                    #remove first segment
                    if len(segments) > 1:
                        segments[0]=f" "

                    #rebuild
                    r=""
                    for segment in segments:
                        r = r+segment

                    #assign to array
                    out[i] = r

                else:
                    out[i]=pg

                #increment page num
                i+=1


            #pack metadata
            packed_metadata = {
                "author" : metadata.author,
                "producer" : metadata.producer,
                "subject" : metadata.subject,
                "title" : metadata.title,
                "page_count" : len(text)
            }


            #dump to new file
            dump = {
                "metadata" : packed_metadata,
                "img_data" : img_data,
                "text" : out
            }
            with open(os.path.join(outputPath, "data.json"), 'w') as fp:
                fp.write(json.dumps(dump, indent=4))


            #disp
            print(f"Author: {packed_metadata['author']}\nTitle: {packed_metadata['title']}\nPage count: {packed_metadata['page_count']}\nImages Encoded: {len(img_data)}\n----")

    return

if __name__ == "__main__":
    main()