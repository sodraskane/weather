<?php

	$db = @mysql_connect('localhost', '1wire', 'c5r3d5pRSWTzfTXV') or die("Connection Error: " . mysql_error());
	mysql_select_db('1wire') or die("Error connecting to db.");



	$sql = 'SELECT ROUND(AVG(Temp_C),1), ROUND(MIN(Temp_C),1), ROUND(MAX(Temp_C),1) from SensorLogs where SensorID=1 AND `Date` > SUBDATE( CURRENT_TIMESTAMP, INTERVAL 1 DAY) GROUP BY DATE( Date ), HOUR( Date ) ORDER BY Date ASC';
	$result = mysql_query($sql);
	$lilla = array();		 
	while ($row = mysql_fetch_array($result)) {
		$lilla[]     = floatval($row['ROUND(AVG(Temp_C),1)']);
	}
	$lilla = json_encode($lilla);
	
	$sql = 'SELECT AVG(Count_A), SUM(Count_B) from SensorLogs where SensorID=3 AND `Date` > SUBDATE( CURRENT_TIMESTAMP, INTERVAL 1 day) GROUP BY DATE( Date ), HOUR(Date) ORDER BY Date ASC';
	$result = mysql_query($sql);	
	$strom_lilla = array();		 
	while ($row = mysql_fetch_array($result)) {
		$strom_lilla[]     = floatval($row['AVG(Count_A)'] /100 );
	}
	$strom_lilla = json_encode($strom_lilla);
	
	$sql = 'SELECT AVG(Count_B) from SensorLogs where SensorID=3 AND `Date` > SUBDATE( CURRENT_TIMESTAMP, INTERVAL 1 day) GROUP BY DATE( Date ), HOUR(Date) ORDER BY Date ASC';
	$result = mysql_query($sql);
	$strom_stora = array();		 
	while ($row = mysql_fetch_array($result)) {
		$strom_stora[]     = floatval($row['SUM(Count_B)'] /100 );
	}
	$strom_stora = json_encode($strom_stora);


	$sql = 'SELECT ROUND(AVG(Temp_C),1),ROUND(MIN(Temp_C),1), ROUND(MAX(Temp_C),1) from SensorLogs where SensorID=2 AND `Date` > SUBDATE( CURRENT_TIMESTAMP, INTERVAL 1 day) GROUP BY DATE( Date ), HOUR( Date ) ORDER BY Date ASC';
	$result = mysql_query($sql);
	$out = array();		 
	while ($row = mysql_fetch_array($result)) {
		$out[]     = floatval($row['ROUND(AVG(Temp_C),1)']);

	}
	$out = json_encode($out);

	
#	$sql = 'SELECT Date from SensorLogs where `Date` > SUBDATE( CURRENT_TIMESTAMP, INTERVAL 1 DAY) GROUP BY DATE( Date ), HOUR( Date ) ORDER BY Date ASC';
#	$result = mysql_query($sql);
#	while ($row = mysql_fetch_array($result)) {
#		echo date("H", strtotime($row['Date'])). ", "; 
#	}


?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>
Aktuell temperatur
</title>
</head>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Aktuell Temperatur'
            },
            subtitle: {
                text: 'Senaste 24 timmarna'
            },
			exporting: {
         		enabled: false
			},
            xAxis: [{
                categories:[<?php 
				$sql = 'SELECT Date from SensorLogs where `Date` > SUBDATE( CURRENT_TIMESTAMP, INTERVAL 1 DAY) GROUP BY DATE( Date ), HOUR( Date ) ORDER BY Date ASC';
				$result = mysql_query($sql);
				while ($row = mysql_fetch_array($result)) {
					echo date("H", strtotime($row['Date'])). ", "; 
				} 
				?>],
            }],
			plotOptions: {
            series: {
                animation: {
                    duration: 2000,
                }
            }
        	},
            yAxis: [{ // Temp yAxis
                title: {
                    text: 'Temperatur',
                    style: {
                        color: '#4572A7'
                    }
                },
                labels: {
                    format: '{value} °C',
                    style: {
                        color: '#4572A7'
                    }
                }
    
            }, { // kW yAxis
				title: {
                    text: 'Förbrukning',
                    style: {
                        color: '#d44e00'
                    }
                },
                labels: {
                    format: '{value} kW',
                    style: {
                        color: '#d44e00'
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
			legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 100,
                floating: true,
                backgroundColor: ''
			},
            series: [{
                name: 'Förbrukning Lilla Huset',
				color: '#d44e00',
                type: 'spline',
				yAxis: 1,
                data: <?php echo $strom_lilla;?>,
                marker: {
					enabled: false
				},
				dashStyle: 'ShortDash',
				tooltip: {
                    valueSuffix: 'kW'
                }
    
            },{
                name: 'Lilla huset',
                color: '#4572A7',
                type: 'spline',
                data: <?php echo $lilla; ?>,
				marker: {
					enabled: false
				},
                dashStyle: 'Solid',
                tooltip: {
                    valueSuffix: ' °C'
                }
    
            }, {
                name: 'Utomhus',
                color: '#89A54E',
                type: 'spline',
                data: <?php echo $out; ?>,
				marker: {
					enabled: false
				},
                dashStyle: 'Solid',
                tooltip: {
                    valueSuffix: ' °C'
                }
            }]
        });
    });

		</script>
<body>
<center>
<!--h1>Aktuell temperatur</h1-->


<script src="charts/js/highcharts.js"></script>
<script src="charts/js/modules/exporting.js"></script>

<div id="container" style="min-width: 400px; max-width:800px; height: 250px; margin: 0 auto"></div>

<a href="/presentation.php">Aktuell temperatur, sammanfattning min/max p&aring; dag och veckobasis</a><br/>
<a href="/presentation-mobil.php">Mobilversion: Aktuell temperatur, sammanfattning min/max p&aring; dag och veckobasis</a></br>
<a href="/log.php">mer detaljerad log (f&ouml;r import till excel)</a>
</center>
</body>
</html>
