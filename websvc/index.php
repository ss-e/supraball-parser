<?php
include 'connect.inc.php';

echo "<link rel=\"stylesheet\" href=\"css/sortable-theme-finder.css\" /><script src=\"js/sortable.min.js\"></script>";
echo "<a href=index.html>Back</a></br>";

$result = mysql_query("select distinct gameid from `games` order by gameid desc LIMIT 0,5");
$i=0;
$gameid= array();
while ($row = mysql_fetch_array($result))
{
 $gameid[$i] = $row['gameid'];
 $i++;
}

for ($i=0; $i<5;$i++)
{
$result = mysql_query("select carrytime,goals,intercepts,losses,name,own_goals,passes,save_fails,team,touches from `games` where gameid ='$gameid[$i]' order by team asc");
echo "<br/> Game '$gameid[$i]'<br/>";
echo "<table class=\"sortable-theme-finder\" data-sortable><thead>";
echo "<tr>";
echo "<th>Team</th><th>Name</th><th>Goals</th><th>Own Goals</th><th>Carry Time</th><th>Passes</th><th>Intercepts</th><th>Touches</th><th>Losses</th><th>Save Fails</th><th>Intercepts per Loss</th><th>Avg Carry Time</th><th>Scoring % (Est)</th>";
echo "</tr></thead>";
while ($row = mysql_fetch_array($result))
{
 echo "<tr><td>";
 echo $row['team'];
 echo "</td><td>";
 echo $row['name'];
 echo "</td><td>";
 echo $row['goals'];
 echo "</td><td>";
 echo $row['own_goals'];
 echo "</td><td>";
 echo $row['carrytime'];
 echo "</td><td>";
 echo $row['passes'];
 echo "</td><td>";
 echo $row['intercepts'];
 echo "</td><td>";
 echo $row['touches'];
 echo "</td><td>";
 echo $row['losses'];
 echo "</td><td>";
 echo $row['save_fails'];
 echo "</td><td>";
 echo $row['intercepts']/$row['losses'];
 echo "</td><td>";
 echo $row['carrytime']/$row['touches'];
 echo "</td><td>";
 echo ( $row['goals']/ ( $row['losses']+$row['goals'] ) )*100;
 echo "</td></tr>";
}
echo "</table><br/>";
$result = mysql_query("select SUM(goals),team from `games` where gameid ='$gameid[$i]' group by team  order by team asc");
while ($row = mysql_fetch_array($result))
{
echo $row['team'];
echo " ";
echo $row['SUM(goals)'];
echo " ";
}

$result = mysql_query("select SUM(carrytime),SUM(goals),SUM(intercepts),SUM(losses),SUM(own_goals),SUM(passes),SUM(save_fails),team,SUM(touches) from `games` where gameid ='$gameid[$i]' group by team order by team asc");
#echo "<br/> Game '$gameid[$i]'<br/>";
echo "<table class=\"sortable-theme-finder\" data-sortable><thead>";
echo "<tr>";
echo "<th>Team</th><th>Goals</th><th>Own Goals</th><th>Carry Time</th><th>Passes</th><th>Intercepts</th><th>Touches</th><th>Losses</th><th>Save Fails</th><th>Intercepts per Loss</th><th>Avg Carry Time</th><th>Scoring % (Est)</th>";
echo "</tr></thead>";
while ($row = mysql_fetch_array($result))
{
 echo "<tr><td>";
 echo $row['team'];
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
 echo $row['SUM(intercepts)']/$row['SUM(losses)'];
 echo "</td><td>";
 echo $row['SUM(carrytime)']/$row['SUM(touches)'];
 echo "</td><td>";
 echo ( $row['SUM(goals)']/ ( $row['SUM(losses)']+$row['SUM(goals)'] ) )*100;
 echo "</td></tr>";
}
echo "</table>";
echo "<br/><br/>";
}
?>
