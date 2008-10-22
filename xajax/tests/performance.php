<?php
require_once dirname(dirname(dirname(__FILE__))) . '/Benchmark/Timer.php';

$timer = new Benchmark_Timer();
$timer->start();

require('../xajax_core/xajax.inc.php');

$timer->setMarker('xajax included');

$xajax = new xajax();

$xajax->configure('requestURI', 'performance.php');
$xajax->configure('scriptLoadTimeout', 0);
$xajax->configure('deferScriptGeneration', true);
//$xajax->configure('debug', true);

$timer->setMarker('xajax constructed');

$trips = 30;
	
function roundTrip($nTimes) {
	global $timer;
	global $trips;
	$objResponse = new xajaxResponse();
	if ($nTimes < $trips) {
		$nTimes += 1;
		$objResponse->script('xajax_roundTrip(' . $nTimes . ');');
		$objResponse->assign('submittedDiv', 'innerHTML', 'Working...');
	} else {
		$objResponse->assign('submittedDiv', 'innerHTML', 'Done');
	}
	$timer->stop();
	$objResponse->call('accumulateTime', $timer->timeElapsed());
	$objResponse->call('printTime');
	return $objResponse;
}

function compress()
{
	global $xajax;
	$xajax->_compressSelf(null);
	
	$objResponse = new xajaxResponse();
	$objResponse->assign('submittedDiv', 'innerHTML', 'Compressed');
	return $objResponse;
}

if (class_exists('xajaxUserFunction'))
{
	$xajax->registerFunction('roundTrip');
	$xajax->registerFunction('compress');
}

$timer->setMarker('begin process request');

if (class_exists('xajaxUserFunction'))
{
	$xajax->processRequest();
}

$timer->setMarker('after process request');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Performance Test</title>
<?php
if (class_exists('xajaxUserFunction'))
{
	$xajax->printJavascript('../');
}
?>
<script type='text/javascript'>
	nCumulativeTime = 0;
	nTrips = 0;
	
	accumulateTime = function(nTime) {
		nCumulativeTime += (nTime * 1);
		nTrips += 1;
	}
	
	printTime = function() {
		xajax.$('result').innerHTML = 
			'Trips: ' + nTrips + 
			'<br />Total time: ' + nCumulativeTime +
			'<br />Average time: ' + nCumulativeTime / nTrips;
	}
</script>
</head>
<body>

<h2><a href="index.php">xajax Tests</a></h2>
<h1>Redirect Test</h1>

<form id="testForm1" onsubmit="return false;">
<p><input type='submit' value='Begin' name='begin' id='begin' onclick='nCumulativeTime = 0; nTrips=0; xajax_roundTrip(0); return false;' /></p>
<p><input type='submit' value='Compress' name='compress' id='compress' onclick='xajax_compress(); return false;' /></p>
</form>

<div id="submittedDiv"></div>
<div id="result"></div>

<?php
$timer->stop();
$timer->display();
?>

</body>
</html>