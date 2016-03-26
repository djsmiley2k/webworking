#!/usr/bin/php -q
<?php
$historyCount = 25;

$trafficFile = '/backups/router/traffic';

$trafficHistoryUP = '/var/www/localhost/htdocs/milkme.co.uk/trafficHistoryUP';
$trafficHistoryDOWN = '/var/www/localhost/htdocs/milkme.co.uk/trafficHistoryDOWN';

$traffic = file_get_contents($trafficFile) or die("Cannot open " . $trafficFile);

$storedValuesUP = fopen($trafficHistoryUP, "r") or die ("Cannot open " . $trafficHistoryUP);
$storedValuesDOWN = fopen($trafficHistoryDOWN, "r") or die ("Cannot open " . $trafficHistoryDOWN);

$historyUP = json_decode(fread($storedValuesUP, filesize($trafficHistoryUP)));
$historyDOWN = json_decode(fread($storedValuesDOWN, filesize($trafficHistoryDOWN)));

fclose($storedValuesUP);
fclose($storedValuesDOWN);

// Read history Values into arrays

$upValues[] = '';
$downValues[] = '';


if($historyUP && $historyDOWN) {
        $x = 0;
        while ($x <= ($historyCount - 1)) {
                $upValues[$x] = $historyUP[$x];
                $x++;
        }
	$x = 0;
        while ($x <= ($historyCount - 1)) {
                $downValues[$x] =  $historyDOWN[$x];
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
file_put_contents($trafficHistoryUP, json_encode($upValues));
file_put_contents($trafficHistoryDOWN, json_encode($downValues));

?>
