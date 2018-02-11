<!DOCTYPE html>
<html>
<head>
  <title>AmbiLamp</title>
  <link rel="stylesheet" type="text/css" href="assets/css/index.css">
  <script src="assets/js/jscolor.js"></script>
</head>
<body>

<?php
  include "GPIO.php";
  include "header.php";

  /* BEGIN COLOR */
  $default_color = "EFFFC9";
  $db = connectMongo();
  $color_data = $db->color;

  if (isset($_POST['set_default'])) {
    $num_entries = $color_data->count();
    $color_dict = array('color' => $_POST['color'], 'entry' => $num_entries + 1$);
    $color_data->insert($color_dict);
  }

  $cursorColor = $color_data->find()->sort(array('entry' => -1))->limit(1);
  foreach($cursorColor as $doc) {
    $default_color = $doc['color'];
  }

  if (isset($_POST['set_default'])) {
    $color_data->insert($_POST['color']);
  }

  $color = "EFFFC9";
  if (isset($_POST['set_color'])) {
	  $color = $_POST['color'];
  }

  $red = new GPIO(22,"out",4);
  $green = new GPIO(27,"out",3);
  $blue = new GPIO(17,"out",1);
  $colorArray = $color.str_split();

  $red->pwm_write(hexdec($colorArray[0].$colorArray[1]));
  $green->pwm_write(hexdec($colorArray[2].$colorArray[3]));
  $blue->pwm_write(hexdec($colorArray[4].$colorArray[5]));

  /* Connect to the DB */
  $db = connectMongo();
  $sounds = $db->sound;
  $temperatures = $db->temp;
  $soundCursor = $sounds->find()->sort(array('entry' => -1))->limit(168);
  $temperatureCursor = $temperatures->find()->sort(array('entry' => -1))->limit(168);

  /* BEGIN SOUND DATA PARSING */
  $hourSums = array_fill(0,24,0);
  $hourCounts = array_fill(0,24,0);

  foreach ($soundCursor as $doc){
    $time = split('[-:]', $doc['time'])[3];
    $hourCounts[$time] = $hourCounts[$time] + 1;
    $hourSums[$time] = $hourSums[$time] + $doc['audio'];
  }

  $soundMin = 1000;
  $soundMax = 0;
  $soundDataDay = '[';
  $soundDataNight = '[';
  for ($i = 0; $i < 24; $i = $i + 1) {
    $hourSums[$i] = $hourSums[$i]/$hourCounts[$i];

    if ((float)$hourSums[$i] > $soundMax) {
      $soundMax = (float)$hourSums[$i];
    }

    if ((float)$hourSums[$i] < $soundMin) {
      $soundMin = (float)$hourSums[$i];
    }

    if ($i <12) {
      $soundDataDay = $soundDataDay . (float)$hourSums[$i] . ",";
    } else {
      $soundDataNight = $soundDataNight . (float)$hourSums[$i] . ",";
    }
  }
  /* END SOUND DATA PARSING */
  /* Assign arrays to JS variables */
  echo "<script>";
  echo "var soundDataDay = " . $soundDataDay . ";";
  echo "var soundDataNight = " . $soundDataNight . ";";
  echo "var soundMin = " . $soundMin . ";";
  echo "var soundMax = " . $soundMax . ";";
  echo "</script>";

  /* BEGIN TEMPERATURE DATA PARSING */
  $hourSums = array_fill(0,24,0);
  $hourCounts = array_fill(0,24,0);

  foreach ($temperatureCursor as $doc){
    $time = split('[-:]', $doc['time'])[3];
    $hourCounts[$time] = $hourCounts[$time] + 1;
    $hourSums[$time] = $hourSums[$time] + $doc['audio'];
  }

  $temperatureMin = 1000;
  $temperatureMax = 0;
  $temperatureDataDay = '[';
  $temperatureDataNight = '[';
  for ($i = 0; $i < 24; $i = $i + 1) {
    $hourSums[$i] = $hourSums[$i]/$hourCounts[$i];

    if ((float)$hourSums[$i] > $temperatureMax) {
      $temperatureMax = (float)$hourSums[$i];
    }

    if ((float)$hourSums[$i] < $temperatureMin) {
      $temperatureMin = (float)$hourSums[$i];
    }

    if ($i <12) {
      $temperatureDataDay = $temperatureDataDay . (float)$hourSums[$i] . ",";
    } else {
      $temperatureDataNight = $temperatureDataNight . (float)$hourSums[$i] . ",";
    }
  }
  /* END TEMPERATURE DATA PARSING */
  /* Assign arrays to JS variables */
  echo "<script>";
  echo "var temperatureDataDay = " . $temperatureDataDay . ";";
  echo "var temperatureDataNight = " . $temperatureDataNight . ";";
  echo "var temperatureMin = " . $temperatureMin . ";";
  echo "var temperatureMax = " . $temperatureMax . ";";
  echo "</script>";
?>

<!-- JSCOLOR PICKER -->
<input type="button" class="JSCOLOR" id="picker" onchange="update(this.jscolor)" onfocusout="apply()" value=<?php echo "'" . $color . "'"; ?>>

<!-- FORM -->
<form method="POST">
	<input type="text" id="color" name="color">
  <input type="submit" id="smt" name="set_color" hidden>
	<input type="submit" value="Set as Default" id="set_default" name="set_default">
</form>

<!-- CHARTS -->
<div id="charts-container">
	<canvas id="temp-chart" class="chart" height="350" width="550"></canvas>
	<canvas id="sound-chart" class="chart" height="350" width="550"></canvas>
</div>

<!-- ABOUT -->
<div id="about">
	<h1>About</h1>

	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus venenatis lorem vitae neque scelerisque dignissim. Integer viverra, turpis accumsan lacinia luctus, risus sem scelerisque quam, nec ultrices eros erat at lorem. Ut ac diam a nulla auctor vulputate. Nullam odio nibh, tincidunt ac rhoncus quis, posuere vitae erat. Donec at gravida elit. Donec fringilla dui eu odio ullamcorper mollis. Cras mollis feugiat metus id tincidunt. Quisque ut dictum urna, eget placerat lectus. Nunc a tempor libero, ut tempor risus. Nam hendrerit quis diam quis finibus.</p>

	<p>Duis metus massa, consequat et nunc vitae, auctor mattis lectus. In consequat vel nibh eu ullamcorper. Suspendisse sodales aliquet lectus sit amet accumsan. Nullam consectetur justo enim, condimentum fermentum orci cursus sed. Cras ac pretium est. Duis commodo facilisis lacus, vitae feugiat nisi pretium a. Suspendisse id ligula sit amet nulla aliquet vulputate. Cras sit amet odio pulvinar, laoreet nisl eget, fringilla tellus. Phasellus eu porta massa. Ut felis nulla, suscipit in ex eget, sagittis cursus ligula. Fusce at leo scelerisque mi facilisis sodales.</p>

	<p>Aenean urna enim, sagittis eget nunc ac, ullamcorper luctus enim. Curabitur eu nunc et velit sodales porta sit amet sed augue. Praesent pharetra elementum leo, sit amet elementum leo gravida sit amet. Vestibulum consectetur nunc mauris. Sed ut aliquet ex, at sodales orci. In vel arcu ante. In auctor, erat eget elementum fermentum, nisi neque bibendum est, sit amet vestibulum neque quam eu felis. Donec placerat in odio id luctus. Praesent ac feugiat arcu, feugiat tincidunt ligula. Sed pellentesque non nibh tincidunt interdum. Fusce eget mi pretium, tempus ipsum quis, lobortis lacus.</p>

	<p>Aenean rhoncus lorem lacus, at convallis libero mattis eu. Suspendisse potenti. Nullam libero leo, laoreet vel posuere quis, pretium sit amet odio. Nam tempor tellus vel malesuada fringilla. Duis vulputate ipsum at consequat viverra. Donec id placerat justo. Praesent condimentum feugiat eros eget mattis. Quisque aliquam maximus neque. Nullam magna nisl, laoreet eget est id, commodo gravida quam. Fusce tincidunt venenatis neque ac hendrerit. Aliquam iaculis, dui quis hendrerit posuere, nibh est scelerisque dui, vitae posuere ligula neque ac libero. Aliquam sed erat congue, cursus ligula ac, hendrerit purus. Sed mattis tempus neque et pretium. Suspendisse sagittis laoreet dictum. Vestibulum et odio vitae sem euismod aliquam. Integer eget nunc magna.</p>

	<p>Pellentesque at metus erat. Maecenas ut orci mauris. Vivamus non ullamcorper mi, sed lacinia justo. Nam lorem neque, efficitur vitae congue quis, vehicula id orci. In hac habitasse platea dictumst. Nam sed consectetur arcu, id consequat tortor. Nulla lorem nulla, ultricies a lorem a, condimentum ornare odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In gravida felis vel auctor lobortis. Sed efficitur sapien in ullamcorper bibendum. Etiam luctus bibendum imperdiet. Phasellus ac facilisis libero. Curabitur dictum bibendum molestie. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent vel mi sodales, fermentum nunc at, tristique risus. Fusce et aliquet metus, vitae eleifend nunc.</p>
</div>

<script type="text/javascript" src="assets/js/index.js"></script>
</body>
</html>
