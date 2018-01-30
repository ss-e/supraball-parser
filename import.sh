#!/bin/bash
#statistic import

function import {
TABLE=$1
cd $my_dir/$TABLE/parse

#export json to mysql, adding gameid stat
FILES=*.json
FILECOUNT=$(ls -b *.json 2>/dev/null | wc -l)

#are there files available for us to import?
if [ $FILECOUNT -eq 0 ]; then
exit
fi

#go through the files
for f in $FILES
do
#get the gameid
GAMEID=$(echo ${f%.*} | sed "s/stats-//g" | sed "s/\.//g" )

#remove escape characters
sed 's/\\//g' $f > $f.temp
mv $f.temp $f

#strip the weird characters
iconv -c -t ascii $f -o $f

#json to csv for easier editing
in2csv -f json $f -k players >> $GAMEID.csv
LINE=$(awk 'END{print NR}' $GAMEID.csv)

#only import games that are over 5 players
if [ "$LINE" -gt "6" ]; then
#add the gameid to the csv file
awk '/./' $GAMEID.csv > tmp.awk && mv tmp.awk $GAMEID.csv
awk "NR==1{print;exit}" $GAMEID.csv | sed 's/$/,gameid/' > tmp.awk
awk 'NR>1' $GAMEID.csv | sed "s/$/,$GAMEID/" >> tmp.awk && cat tmp.awk > $GAMEID.csv

else
rm -f $GAMEID.csv
fi
rm -f $f
done

FILECOUNT=$(ls -b *.csv 2>/dev/null | wc -l)
#generate a big csv file, if we have csv files
if [ $FILECOUNT -eq 0 ]; then
exit
fi
if [ $FILECOUNT -eq 1 ]; then
mv *.csv temp.csv
else
csvstack 2*.csv > "temp.csv"
fi

#export to mysql database
mysql -h $HOST --database="$DATABASE" --execute="CREATE TABLE temp LIKE \`$TABLE\`;
LOAD DATA LOCAL INFILE 'temp.csv' INTO TABLE temp FIELDS TERMINATED BY ',' ESCAPED BY '' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES;
delete from temp where carrytime=0.0 && touches=0;
DELETE temp
FROM temp
INNER JOIN \`$TABLE\` as g
ON temp.carrytime = g.carrytime and temp.touches = g.touches and temp.name = g.name
WHERE temp.carrytime = g.carrytime and temp.touches = g.touches and temp.name = g.name;
INSERT INTO \`$TABLE\` SELECT * FROM temp;
DROP TABLE temp;"

#cleanup
rm tmp.awk
rm *.csv
}
