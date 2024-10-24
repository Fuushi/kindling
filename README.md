
KINDLING PRIVATE EBOOK WEBAPP SERVER

Installation guide:

Requirements
-Apache Webserver
-PHP Interpreter (all packages are included)
-Python 3
->PyPDF2
->pymupdf, fitz
->PIL

Setup:

WINDOWS:
    Install Apache_http server:
        You can compile apache yourself or use a pre-compiled binary
        see binaries at: https://www.apachelounge.com/download/
        PHP interpreter comes prepackaged with Apache

    Install Python3:
        There are as many ways to install python as there are clouds in the sky
        In the name of Simplicity, you can download it from the windows store.

    Install Packages:
        run the following commands:
            pip install PyPDF2
            pip install pymupdf
            pip install PIL
        if the above don't work, try appending --user to the end of the commands

    INSTALL KINDLING:
        Drop the Kindling folder in the running directory of Apache,
        Configure? 
        Then access over localhost:80
    
    Your library will be empty to start!
    Drop pdfs in the novels folder, and run process.py, it will convert the pdfs
    into a more easily indexable format
    enjoy!

DEBIAN:
    -i will write this portion when i deploy to my debian webserver
    -please be patient






TO ADD BOOKS TO LIBRARY!
Drop a PDF of your novel in the novels folder, and run
'process.py', it will convert all pdfs in the folder,
after which they will appear in your library.
This will be done automatically in future updates


TO LOGIN!
Default login information

username: admin
password: not_hashed

username: guest
password: guest123

TO CREATE AN ACCOUNT:
passwords are sha256 hashed, account creation tool does not exist yet,
modify users.json with your username and sha256 hashed password.