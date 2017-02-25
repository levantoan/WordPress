/*
1. Copy the above PHP script and paste in a file say, ‘dbconversion.php’.
2. Now put this file in your server (development/production).
3. Run this script from ‘yourdomain.com/dbconversion.php’.
4. It’s all. It will convert everything and you will get a success message.
Note: to wp-config.php find and change to define(‘DB_CHARSET’, ‘utf8’);

File in : https://gist.github.com/blbwd/57b2cdf96d952d207a5d#file-dbconversion-php
*/

<?php
$dbname = 'your-database-name';
mysql_connect('your-database-hostname', 'your-database-username', 'your-database-password');
mysql_query("ALTER DATABASE `$dbname` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
$result = mysql_query("SHOW TABLES FROM `$dbname`");
while($row = mysql_fetch_row($result)) {
 $query = "ALTER TABLE {$dbname}.`{$row[0]}` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
 mysql_query($query);
 $query = "ALTER TABLE {$dbname}.`{$row[0]}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
 mysql_query($query);
}
echo 'All the tables have been converted successfully';
?>
<?php
$dbname = '__YOUR_DATABASE_NAME__';
mysql_connect('__YOUR_DATABASE_HOSTNAME__', '__YOUR_DATABASE_USER__', '__YOUR_DATABASE_PASS__');
mysql_query("ALTER DATABASE `$dbname` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
$result = mysql_query("SHOW TABLES FROM `$dbname`");
while($row = mysql_fetch_row($result)) {
	$query = "ALTER TABLE {$dbname}.`{$row[0]}` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
	mysql_query($query);
	$query = "ALTER TABLE {$dbname}.`{$row[0]}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
	mysql_query($query);
}
echo 'All the tables have been converted successfully';
?>
