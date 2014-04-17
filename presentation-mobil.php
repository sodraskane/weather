<?php

$ute['max_week'] = 0;
$ute['min_week'] = 0;
$ute['max_today'] = 0;
$ute['min_today'] = 0;
$ute['now'] = 0;

$inne['max_week'] = 0;
$inne['min_week'] = 0;
$inne['max_today'] = 0;
$inne['min_today'] = 0;
$inne['now'] = 0;


$min = 0;
$max = 0;


function doQuery($index) {
	global $min, $max;
	require("db.php");
	
	$sql = array(
		"SELECT MIN(Temp_C), MAX(Temp_C) FROM SensorLogs WHERE SensorID=1 AND Date > SUBDATE(NOW(), INTERVAL 7 DAY)",
		"SELECT MIN(Temp_C), MAX(Temp_C) FROM SensorLogs WHERE SensorID=2 AND Date > SUBDATE(NOW(), INTERVAL 7 DAY)",
		"SELECT MIN(Temp_C), MAX(Temp_C) FROM SensorLogs WHERE SensorID=1 AND DATE(Date) = CURDATE()",
		"SELECT MIN(Temp_C), MAX(Temp_C) FROM SensorLogs WHERE SensorID=2 AND DATE(Date) = CURDATE()",
		"SELECT Temp_C FROM SensorLogs WHERE SensorID=1 ORDER BY Date DESC LIMIT 1",
		"SELECT Temp_C FROM SensorLogs WHERE SensorID=2 ORDER BY Date DESC LIMIT 1"
	);

	$result = $link->query($sql[$index]) or die("[ERROR] ".mysqli_error($link));
	
	if ($row = $result->fetch_assoc()) {
		if (($index == 4) || ($index == 5)) { $min = $row['Temp_C']; }
		else { $min = $row['MIN(Temp_C)']; $max = $row['MAX(Temp_C)']; }
	}
}

doQuery(0);
$inne['min_week'] = $min; $inne['max_week'] = $max;

doQuery(1);
$ute['min_week'] = $min; $ute['max_week'] = $max;

doQuery(2);
$inne['min_day'] = $min; $inne['max_day'] = $max;

doQuery(3);
$ute['min_day'] = $min; $ute['max_day'] = $max;

doQuery(4);
$inne['now'] = $min;

doQuery(5);
$ute['now'] = $min;

?>

<html>
<head>
<link rel="stylesheet" href="presentation_mobile_style.css"  type="text/css" />
<meta http-equiv="refresh" content="600" />

<script type="text/javascript">
<!--
    apa=0;

    function timingex(){
	if (apa==0){
	    document.getElementById('ute').style.display = 'none';
	    document.getElementById('inne').style.display = 'block';
	    document.getElementById('graf').style.display = 'none';
	    apa++;
	}
	else if (apa==1 ){
	    document.getElementById('ute').style.display = 'none';
	    document.getElementById('inne').style.display = 'none';
	    document.getElementById('graf').style.display = 'block';
	    apa++;
	}
	else if (apa==2){
	    document.getElementById('ute').style.display = 'block';
	    document.getElementById('inne').style.display = 'none';
	    document.getElementById('graf').style.display = 'none';
	    apa=0;
	}
    }

    //setInterval("timingex();", 8000);
// -->
</script>

<?php
print '<title>Temperatur</title></head>
<body>
<div id="ute">
 <h1>Temperatur Utomhus</h1>
 <table>
 <tr>
 <td colspan="2" class="rubrik">Denna veckan</td></tr>
 <tr><td>L&auml;gsta temp: ' . $ute['min_week'] . '&deg;C</td><td>H&ouml;gsta temp: '. $ute['max_week'] .'&deg;C</td></tr>
 <td colspan="2" class="rubrik">I dag</td></tr>
 <tr><td>L&auml;gsta temp: '.  $ute['min_day'] .'&deg;C</td><td>H&ouml;gsta temp: '. $ute['max_day'] .'&deg;C</td></tr>
 </table>
 <div class="rubrik">Aktuell temperatur:</div>
 <div class="now">'.$ute['now'].'&deg;C</div>
</div>

<div id="inne">
 <h1>Temperatur Inomhus</h1>
 <table>
 <tr>
 <td colspan="2" class="rubrik">Denna veckan</td></tr>
 <tr><td>L&auml;gsta temp: ' . $inne['min_week'] . '&deg;C</td><td>H&ouml;gsta temp: '. $inne['max_week'] .'&deg;C</td></tr>
 <td colspan="2" class="rubrik">I dag</td></tr>
 <tr><td>L&auml;gsta temp: '.  $inne['min_day'] .'&deg;C</td><td>H&ouml;gsta temp: '. $inne['max_day'] .'&deg;C</td></tr>
 </table>
 <div class="rubrik">Aktuell temperatur:</div>
 <div class="now">'.$inne['now'].'&deg;C</div>
</div>';
?>

<!--div id="graf">
<h1>Senaste veckans graf</h1>
<img src="tempplot.gif?rand=<?php print rand(1,1000);?>" />
</div-->

</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
