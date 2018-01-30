<?php
include 'connect.inc.php';

//create the query
$result = mysql_query("SELECT SUM(goals), name FROM  `games`  GROUP BY name ORDER BY SUM(goals) DESC LIMIT 0 , 30");

//return the array and loop through each row
$i=0;
echo "<table>";
echo "<tr>";
echo "<td>#</td><td>Name</td><td>Goals</td>";
echo "</tr>";
while ($row = mysql_fetch_array($result))
{
 $i++;
 echo "<tr><td>$i</td>";
 echo "<td>";
 echo $row['name'];
 echo "</td><td>";
 echo $row['SUM(goals)'];
 echo"</td></tr>";
}
echo "</table>";
?>
