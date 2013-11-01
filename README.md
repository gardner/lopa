LOPA Campaign Site
===

Requirements
====
* linux
* apache
* mysql 
* php

Installation
====

* create the database ```mysqladmin -uroot -p create lopa```
* add the lopa user:

    CREATE USER 'lopa'@'localhost' IDENTIFIED BY '${password}';
    GRANT ALL PRIVILEGES ON lopa.* TO 'lopa'@'localhost'

* in the directory with the READM.md create a file named db.ini which is only readable by the user that the web server runs as and fill it with two entries:

    username=lopa
    password=8mYtLPhyshe4

* run install/install.sh

Uses BSD licenced cat images from https://github.com/maxogden/cats. (Thanks Max!)
