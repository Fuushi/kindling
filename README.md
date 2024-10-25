
# Kindling: Private eBook WebApp Server

## Overview

**Kindling** is a lightweight, private eBook server designed to help you manage and read eBooks on your own server. It processes PDFs into an easily indexable format and serves them through a simple web interface.

## Installation Guide

### Requirements

- **Apache Web Server**  
  - PHP Interpreter (prepackaged with Apache)
- **Python 3**
  - `PyPDF2`
  - `pymupdf` (`fitz`)
  - `Pillow` (`PIL`)

---

### Setup Instructions

#### **Windows**

1. **Install Apache HTTP Server**:
   - You can compile Apache yourself or use a precompiled binary.
   - Download binaries from: [Apache Lounge](https://www.apachelounge.com/download/)
   - The PHP interpreter comes prepackaged with Apache.

2. **Install Python 3**:
   - Download Python from the [Windows Store](https://apps.microsoft.com/store/detail/python-39/9P7QFQMJRFP7) or from [python.org](https://www.python.org/downloads/).

3. **Install Required Python Packages**:
   - Open a terminal and run the following commands:
     ```bash
     pip install PyPDF2
     pip install pymupdf
     pip install Pillow
     ```
     If the above commands fail, try adding the `--user` flag:
     ```bash
     pip install PyPDF2 --user
     pip install pymupdf --user
     pip install Pillow --user
     ```

4. **Install Kindling**:
   - Copy the **Kindling** folder into the root directory of your Apache server.
   - Configure Apache as needed to point to this directory.
   - Access the web app by navigating to `localhost` in your browser (default port: 80).

5. **Initialize Your Library**:
   - Your library will start empty.
   - Drop your PDFs into the `novels` folder, then run `process.py` to convert them into an easily indexable format.

#### **Debian (Linux)**

- A Debian-specific setup guide will be written once deployment to a Debian server is completed. Please stay tuned!

---

### Adding Books to Your Library

To add books to your library:
1. Drop a PDF of your novel into the `novels` folder.
2. Run the `process.py` script:
   ```bash
   python process.py
   ```
   This will convert all the PDFs in the folder, and they will then appear in your library.

_Note: In future updates, this process will be automated._

---

### Logging In

- Default login information:

| Username | Password    |
| -------- | ----------- |
| `admin`  | `not_hashed` |
| `guest`  | `guest123`   |

---

### Creating an Account

Currently, account creation must be done manually. Passwords are stored using SHA-256 hashing. To create a new account:

1. Modify the `users.json` file with your username and hashed password.
2. Hash your password using a tool like Python:
   ```python
   import hashlib
   print(hashlib.sha256(b'your_password').hexdigest())
   ```

---

Enjoy using **Kindling** and feel free to contribute improvements or suggestions!

