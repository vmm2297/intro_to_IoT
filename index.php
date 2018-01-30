<!DOCTYPE html>
<html>
<head>
  <title>AmbiLamp</title>
  <link rel="stylesheet" type="text/css" href="assets/css/index.css">
  <script src="jscolor.js"></script>
</head>
<body>

<?php
  include "GPIO.php";
  include "header.php";

  $color = "EFFFC9";
  if (isset($_POST['set_color'])) {
    $color = $_POST['color'];
  }
?>

<!-- JSCOLOR PICKER -->
<input type="button" class="JSCOLOR" id="picker" onchange="update(this.jscolor)" onfocusout="apply()" value=<?php echo "'" . $color . "'"; ?>>

<!-- FORM -->
<form method="POST">
	<input type="text" id="color" name="color">
  <input type="submit" id="smt" name="set_color" hidden>
	<input type="submit" value="Set as Default" id="set_default">
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
