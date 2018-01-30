<?php
include 'connect.inc.php';

echo "<link rel=\"stylesheet\" href=\"css/sortable-theme-finder.css\" /><script src=\"js/sortable.min.js\"></script>";
echo "<a href=index.php>Last 5 Games</a></br>";

$result = mysql_query("select SUM(carrytime),COUNT(*),SUM(goals),SUM(intercepts),SUM(losses),name,SUM(own_goals),SUM(passes),SUM(save_fails),SUM(touches) from games group by name order by name asc");
echo "<table class=\"sortable-theme-finder\" data-sortable><thead>";
echo "<tr>";
echo "<th>Name</th><th>Games</th><th>Goals</th><th>Own Goals</th><th>Carry Time</th><th>Passes</th><th>Intercepts</th><th>Touches</th><th>Losses</th><th>Save Fails</th><th>Goals per game</th><th>Carry Per Game</th><th>Passes Per Game</th><th>Intercepts Per Game</th><th>Touches Per Game</th><th>Losses Per Game</th>";
echo "</tr></thead>";
while ($row = mysql_fetch_array($result))
{
 echo "<tr><td>";
 echo $row['name'];
 echo "</td><td>";
 echo $row['COUNT(*)'];
 echo "</td><td>";
 echo $row['SUM(goals)'];
 echo "</td><td>";
 echo $row['SUM(own_goals)'];
 echo "</td><td>";
 echo $row['SUM(carrytime)'];
 echo "</td><td>";
 echo $row['SUM(passes)'];
 echo "</td><td>";
 echo $row['SUM(intercepts)'];
 echo "</td><td>";
 echo $row['SUM(touches)'];
 echo "</td><td>";
 echo $row['SUM(losses)'];
 echo "</td><td>";
 echo $row['SUM(save_fails)'];
 echo "</td><td>";
 echo $row['SUM(goals)'] / $row['COUNT(*)'];
 echo "</td><td>";
 echo $row['SUM(carrytime)'] / $row['COUNT(*)'];
 echo "</td><td>";
 echo $row['SUM(passes)'] / $row['COUNT(*)'];
 echo "</td><td>";
 echo $row['SUM(intercepts)'] / $row['COUNT(*)'];
 echo "</td><td>";
 echo $row['SUM(touches)'] / $row['COUNT(*)'];
 echo "</td><td>";
 echo $row['SUM(losses)'] / $row['COUNT(*)'];
 echo "</td></tr>";
}
echo "</table></ br>";
