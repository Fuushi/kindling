##
import os
import sys

# extract_doc_info.py

from PyPDF2 import PdfReader
import pymupdf as fitz
import json

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
                image_name = f"{os.path.splitext(os.path.basename(pdf_path))[0]}_page{page_number+1}_img{img_index+1}.png".replace("/","").replace(" ","")
                image_path = os.path.join(output_folder, image_name)
                
                pix.save(image_path)  # Save the image
                
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
    path = os.getcwd()
    print(path)

    files = os.listdir(path)

    for file in files:
        if ".pdf" in file:
            outputPath=file.replace(".pdf", "/")

            #RE ENABLE
            #if os.path.exists(outputPath): continue
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

                #increment page num
                i+=1


            #pack metadata
            packed_metadata = {
                "author" : metadata.author,
                "author_raw" : metadata.author_raw,
                "creation_date_raw" : metadata.creation_date_raw,
                "producer" : metadata.producer,
                "subject" : metadata.subject,
                "subjuect_raw" : metadata.subject_raw,
                "title" : metadata.title,
                "title_raw" : metadata.title_raw,
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
            print(metadata)

    return

if __name__ == "__main__":
    main()