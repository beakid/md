<?php
	$xajax->configure('javascript URI', '../../');

	if (isset($_GET['debugging']))
		if (0 != $_GET['debugging'])
			$xajax->configure("debug", true);
	if (isset($_GET['verbose']))
		if (0 != $_GET['verbose'])
			$xajax->configure("verboseDebug", true);
	if (isset($_GET['status']))
		if (0 != $_GET['status'])
			$xajax->configure("statusMessages", true);
	if (isset($_GET['synchronous']))
		if (0 != $_GET['synchronous'])
			$xajax->configure("defaultMode", "synchronous");
	if (isset($_GET['useEncoding']))
		$xajax->configure("characterEncoding", $_GET['useEncoding']);
	if (isset($_GET['outputEntities']))
		$xajax->configure("outputEntities", $_GET['outputEntities']);	
	if (isset($_GET['decodeUTF8Input']))
		$xajax->configure("decodeUTF8Input", $_GET['decodeUTF8Input']);
	if (isset($_GET['scriptDeferral']))
		$xajax->configure('deferScriptGeneration', true);
	
	// When using the URL to set the deferScriptGeneration, it is likely
	// that the requestURI will appear to change when the actual script
	// generation is requested, thus, the hash will appear to change
	// as well; this configuration option instructs the script plugin
	// to generate the script despite the hash mismatch.
	$xajax->configure('deferScriptValidateHash', false);
?>