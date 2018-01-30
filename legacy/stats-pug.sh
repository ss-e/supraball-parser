#!/bin/bash
#export json to mysql, adding gameid stat
#to be run on same server
cd /srv/supraball/.wine/drive_c/Program\ Files/Supraball-pug/UDKGame/Stats/
FILES=*.json
FILECOUNT=$(ls -b *.json 2>/dev/null | wc -l)

if [ $FILECOUNT -eq 0 ]; then
#echo No files to import.
exit
fi

for f in $FILES
do
cp $f backup/json/$f
GAMEID=$(echo ${f%.*} | sed "s/stats-//g" | sed "s/\.//g" )
#strip the weird characters
iconv -c -t ascii $f -o $f
#remove escape characters
sed 's/\\//g' $f > $f.temp
mv $f.temp $f
#json to csv for easier editing
in2csv -f json $f -k players >> $GAMEID.csv
LINE=$(awk 'END{print NR}' $GAMEID.csv)
#only import games that are over 5 players
if [ "$LINE" -gt "6" ]; then
awk '/./' $GAMEID.csv > tmp.awk && mv tmp.awk $GAMEID.csv
awk "NR==1{print;exit}" $GAMEID.csv | sed 's/$/,gameid/' > tmp.awk #&& mv tmp.awk $GAMEID.csv
awk 'NR>1' $GAMEID.csv | sed "s/$/,$GAMEID/" >> tmp.awk && cat tmp.awk > $GAMEID.csv
else
rm -f $GAMEID.csv
fi
rm -f $f
done

FILECOUNT=$(ls -b *.csv 2>/dev/null | wc -l)
#generate a big csv file
if [ $FILECOUNT -eq 0 ]; then
#echo No files to import.
exit
fi
if [ $FILECOUNT -eq 1 ]; then
mv *.csv temp.csv
else
csvstack 2*.csv > "temp.csv"
fi
awk 'NR>1' temp.csv >> backup/backup.csv

#export to mysql database
mysql -h stats.server.here --database="suprastats" --execute="LOAD DATA LOCAL INFILE 'temp.csv' INTO TABLE \`games-pug\` FIELDS TERMINATED BY ',' ESCAPED BY '' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES;"
mysql -h stats.server.here --database="suprastats" --execute="delete from \`games-pug\` where carrytime=0.0 && touches=0;"

#cleanup
rm tmp.awk
rm *.csv

mysql -h stats.server.here --database="suprastats" --execute="CREATE TABLE temporary_table LIKE \`games-pug\`;
LOAD DATA LOCAL INFILE 'temp.csv' INTO TABLE temporary_table FIELDS TERMINATED BY ',' ESCAPED BY '' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES;
delete from temporary_table where carrytime=0.0 && touches=0;
DELETE temporary_table
FROM temporary_table
INNER JOIN \`games-pug\` as gp
ON temporary_table.carrytime = gp.carrytime and temporary_table.touches = gp.touches and temporary_table.name = gp.name
WHERE temporary_table.carrytime = gp.carrytime and temporary_table.touches = gp.touches and temporary_table.name = gp.name;
#DROP TABLE temporary_table;"
