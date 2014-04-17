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
//		$upd = $link->query("INSERT INTO SensorLogs(ID, SensorID, Temp_C, Count_A, Count_B, Date) VALUES('', '".$row['ID']."', '$tmp1', '0', '0', NOW())");
	}
	elseif ($row['SensorType'] == 1) {
		$tmp1 = trim(exec("/bin/cat /mnt/1wire/uncached/".$row['Serial']."/counter.A"));
		$tmp2 = trim(exec("/bin/cat /mnt/1wire/uncached/".$row['Serial']."/counter.B"));
		
		// Hämta senaste värdet ifrån databasen.
		$c_sql = "SELECT Count_A, Count_B FROM SensorLogs WHERE SensorID=".$row['ID']." ORDER BY Date DESC LIMIT 1";
		$c_res = $link->query($c_sql);
		$c_row = $c_res->fetch_assoc();
		
		// Dra ifrån db-värdet från inläst värde.
		$c_1 = $tmp1 - $c_row['Count_A'];
		$c_2 = $tmp2 - $c_row['Count_B'];
		
		// Lagra mellanskillnaden, dvs. förbrukning sedan senast inläsning.		
		$upd = $link->query("INSERT INTO SensorLogs(ID, SensorID, Temp_C, Count_A, Count_B, Date) VALUES('', '".$row['ID']."', '0', '$c_1', '$c_2', NOW())");
		$upd = $link->query("INSERT INTO SensorLogs(ID, SensorID, Temp_C, Count_A, Count_B, Date) VALUES('', '".$row['ID']."', '0', '$tmp1', '$tmp2', NOW())");
	}
}
?>