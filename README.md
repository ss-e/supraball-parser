# Simple Supraball .json stats file parser
Converts Supraball .json files into handy mysql format, then displays them using the magic of PHP
Uses FTP transfer mechanism, most accessible way to access files from limited functionality game servers
Multiple stat tables are supported, change name of table in schema file

## Getting Started

### How to use
* Make stats folder on game servers visible via FTP
* Add FTP credentials to ftp.conf
* Edit connect.inc.php to include mysql credentials
* Import schema.sql to mysql database
* Add crontab.txt to user crontab
* After data import is complete, view index.php

### Requirements
* csvstack
* in2csv
