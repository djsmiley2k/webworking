<?php

$trafficFile = '/backups/router/traffic';

$traffic = file_get_contents($trafficFile) or die("Cannot open " . $trafficFile);

if ($traffic) {
	$readValues = array_filter(explode(PHP_EOL, $traffic));
}

echo '<hr>';
echo 'Downstream Usage: ' . round($readValues[0]/1024, 2) . 'kb/s ';
echo '<br>';
echo 'Upstream Usage: ' . round($readValues[1]/1024, 2) . 'kb/s ';

?>

