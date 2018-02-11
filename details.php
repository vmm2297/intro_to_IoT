<!DOCTYPE html>
<html>
<head>
	<title>AmbiLamp</title>
	<link rel="stylesheet" type="text/css" href="assets/css/details.css">
	</head>
<body>

<?php
  include "header.php";

  /* Connect to the DB */
  $db = connectMongo();
  $sounds = $db->sound;
  $temperatures = $db->temp;
  $soundCursor = $sounds->find()->sort(array('entry' => -1))->limit(24);
  $temperatureCursor = $temperatures->find()->sort(array('entry' => -1))->limit(24);

  /* Parse temperature data */
  $temperatureX = "[";
  $temperatureData = "[";
  foreach ($temperatureCursor as $doc) {
  	$time = split('[ ]', $doc['time']);
  	$temperatureX = $temperatureX . "'" . $time[1] . "',";
  	$temperatureData = $temperatureData . $doc['val']. ",";
  }
  // strip the trailing commas and add the closing bracket
  $temperatureX = trim($temperatureX, ",");
  $temperatureX = $temperatureX . "]";
  $temperatureData = trim($temperatureData, ",");
  $temperatureData = $temperatureData . "]";
  /* End temperature parse */
  /* Assign arrays to JS variables */
  echo "<script>";
  echo "var temperatureData = " . $temperatureData . ";";
  echo "var temperatureX = " . $temperatureX . ";";
  echo "</script>";

  /* Parse sound data */
  $soundX = "[";
  $soundData = "[";
  foreach ($soundCursor as $doc) {
  	$time = split('[ ]', $doc['time']);
  	$soundX = $soundX . "'" . $time[1] . "',";
  	$soundData = $soundData . $doc['val']. ",";
  }
  // strip the trailing commas and add the closing bracket
  $soundX = trim($soundX, ",");
  $soundX = $soundX . "]";
  $soundData = trim($soundData, ",");
  $soundData = $soundData . "]";
  /* End sound parse */
  /* Assign arrays to JS variables */
  echo "<script>";
  echo "var soundData = " . $soundData . ";";
  echo "var soundX = " . $soundX . ";";
  echo "</script>";

  /* New Cursors */
  $soundCursor = $sounds->find()->sort(array('entry' => -1))->limit(24);
  $temperatureCursor = $temperatures->find()->sort(array('entry' => -1))->limit(24);
?>

<!-- BUTTONS and CANVAS -->
<input type="button" id="temp-btn" class="btn" value="View Temperature Chart" onclick="drawTemp()">
<canvas id="temp-chart-long" class="chart" width="900" height="350" hidden></canvas>
<input type="button" id="sound-btn" class="btn" value="View Sound Chart" onclick="drawSound()">
<canvas id="sound-chart-long" class="chart" width="900" height="350" hidden></canvas>

<!-- TABLES -->
<div id="tables-container">
	<div class="table">
		<table id="temp-table">
			<tr>
			    <th>Time</th>
			    <th>Temperature</th>
		  	</tr>
		  	<?php
		  		foreach ($temperatureCursor as $doc) {
		  			echo "<tr>";
		  				echo "<td>".$doc['time']."</td>";
		  				echo "<td>".$doc['val']."</td>";
		  			echo "</tr>";
		  		}
		  	?>
		</table>
	</div>
	<div class="table">
		<table id="sound-table">
			<tr>
			    <th>Time</th>
			    <th>Amplitude</th>
		  	</tr>
		  	<?php
		  		foreach ($soundCursor as $doc) {
		  			echo "<tr>";
		  				echo "<td>".$doc['time']."</td>";
		  				echo "<td>".$doc['val']."</td>";
		  			echo "</tr>";
		  		}
		  	?>
		</table>
	</div>
</div>

<script type="text/javascript" src="assets/js/details.js"></script>
</body>
</html>