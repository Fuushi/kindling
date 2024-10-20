######################################
#KINDLING PRIVATE EBOOK WEBAPP SERVER#
######################################



Installation guide:

Requirements
-Apache Webserver
-PHP Interpreter
-Python 3
->PyPDF2
->pymupdf, fitz
->PIL

Setup
Run as an apache webserver on port 80 (library will be empty to start)


TO ADD BOOKS TO LIBRARY!
Drop a PDF of your novel in the novels folder, and run
'process.py', it will convert all pdfs in the folder,
after which they will appear in your library.