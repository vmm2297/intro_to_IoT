<?php
	$conn = new MongoClient("mongodb://admin:admin@ds046867.mlab.com:46867/intro_to_iot");
	$db = $conn->intro_to_iot;
	$collection = $db->collection1;
	$newData = array('num' => 131, 'val' => 57, 'time' => '8pm');
	$collection->insert($newData);

	$cursor = $collection->find();
	echo "First loop: <br>";
	foreach ($cursor as $doc) {
		echo $doc['num'] . "<br>";
	}
	echo "<br>";

	$cursor = $collection->find(array('num' => 131));
	echo "Second loop: <br>";
	foreach ($cursor as $doc) {
		echo $doc['num'] . "<br>";
	}
?>
