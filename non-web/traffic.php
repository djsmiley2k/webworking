<?php

$trafficFile = '/backups/router/traffic';
$trafficHistory = '/var/www/localhost/htdocs/milkme.co.uk/trafficHistory';

$traffic = file_get_contents($trafficFile) or die("Cannot open " . $trafficFile);

$storedValues = fopen($trafficHistory, "r") or die ("Cannot open " . $trafficHistory);
$history = array_filter(explode(PHP_EOL, json_decode(fread($storedValues, filesize($trafficHistory)))));
fclose($storedValues);

// Read history Values into arrays

$upValues[] = '';
$downValues[] = '';


if($history) {
        $x = 0;
        while ($x < 25) { // 0 - 4
                $upValues[$x] = $history[$x];
                $x++;
        }

        while ($x < 50) { // 5 - 9
                $downValues[($x -5)] =  $history[$x];
                $x++;
        }
}


if ($traffic) {
	$readValues = array_filter(explode(PHP_EOL, $traffic));
	if ($readValues[1] != $upValues[0]) {
		array_unshift($upValues, $readValues[1]);
	}
	if ($readValues[0] != $downValues[0]) {
		array_unshift($downValues, $readValues[0]);
	}
	$upValues = array_slice($upValues,0,25);
	$downValues = array_slice($downValues,0,25);
}


// Save out $upValues and $downValues arrays to file
$data = implode(PHP_EOL,array_filter($upValues)) . "\n" . implode(PHP_EOL,array_filter($downValues));
file_put_contents($trafficHistory, json_encode($data));

?>

