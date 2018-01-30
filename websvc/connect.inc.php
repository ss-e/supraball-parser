<?php
// connect to mysql database
mysql_connect("127.0.0.1", "user_account", "password") or die(mysql_error());
mysql_select_db("suprastats") or die(mysql_error());
?>