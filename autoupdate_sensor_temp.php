<?php
/////////////////////////////////////////////////////////////
//                                                         //
//  THIS FILE WILL *ONLY* ADD NEW VALUES TO THE DATABASE.  //
//  SUITED FOR CRONTAB USE.                                //
//                                                         //
/////////////////////////////////////////////////////////////

$db_hostname = 'localhost';
$db_username = '1wire';
$db_password = 'c5r3d5pRSWTzfTXV';
$db_name     = '1wire';
$link        = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Error " . mysqli_error($link));

$sql = "SELECT * FROM Sensors ORDER BY Name";
$res = $link->query($sql);

while ($row = $res->fetch_assoc()) {
	if ($row['SensorType'] == 0) {
		$tmp1 = exec("/bin/cat /mnt/1wire/uncached/".$row['Serial']."/temperature");
		$tmp1 = round($tmp1, 1);
		$upd = $link->query("INSERT INTO SensorLogs(ID, SensorID, Temp_C, Date) VALUES('', '".$row['ID']."', '$tmp1', '0', '0', NOW())");
	}
}
?>