#!/bin/bash

function download{
TABLE = $1
cd $my_dir/$TABLE/download
#get the files
i=0
while [ $i -lt $LINECOUNT ]
do
wget -nH -l1 -nc --append-output=logfile.txt --ftp-user=${USERNAME[i]} --ftp-password=${PASSWORD[i]} ${SITE[i]}
i=$[$i+1]
done

##move relevant files into parse directory for parsing
#grab the file names downloaded
FILES=`cat logfile.txt | egrep -e "--" | egrep -v "\*" | rev | cut -d / -f 1 | rev`

for f in $FILES
do
#copy the file into the correct directory
cp $FILES ../parse/
done

#remove the wget logfile when completed
rm logfile.txt
}