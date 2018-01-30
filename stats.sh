#!/bin/bash
#grabs stats files from FTP
my_dir="$(dirname "$0")"

source "$my_dir/mysql.conf"
source "$my_dir/ftp.sh"
source "$my_dir/import.sh"

LINECOUNT=$(cat $my_dir/ftp.conf | wc -l)
if [ $LINECOUNT -eq 0 ]; then
echo ERROR: Missing ftp.conf
exit
fi

#grab ftp.conf and turn it into the proper arrays
while IFS=, read site username password table
do
SITE[$i]=$site
USERNAME[$i]=$username
PASSWORD[$i]=$password
TABLE[$i]=$table
i=$((i+1))
done < ftp.conf

download $TABLE
import $TABLE
