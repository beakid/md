<?php
/*
	File: helloworld.php

	Test / example page demonstrating the basic xajax implementation.
	
	Title: Hello world sample page.
	
	Please see <copyright.inc.php> for a detailed description, copyright
	and license information.
*/

/*
	@package xajax
	@version $Id: helloworld.php 362 2007-05-29 15:32:24Z calltoconstruct $
	@copyright Copyright (c) 2005-2006 by Jared White & J. Max Wilson
	@license http://www.xajaxproject.org/bsd_license.txt BSD License
*/

/*
	Section: Standard xajax startup
	
	- include <xajax.inc.php>
	- instantiate main <xajax> object
*/
	require ('../xajax_core/xajax.inc.php');
	$xajax = new xajax(); 

/*
	- enable deubgging if desired
*/
	//$xajax->setFlag("debug", true);

	/*
		Function: helloWorld
		
		Modify the innerHTML of div1.
	*/
	function helloWorld($isCaps)
	{
		if ($isCaps)
			$text = "HELLO WORLD!";
		else
			$text = "Hello World!";
			
		$objResponse = new xajaxResponse();
		$objResponse->assign("div1","innerHTML",$text);
		
		return $objResponse;
	}

	/*
		Function: setColor
		
		Modify the style.color of div1
	*/
	function setColor($sColor)
	{
		$objResponse = new xajaxResponse();
		$objResponse->assign("div1","style.color", $sColor);
		
		return $objResponse;
	}

	/*
		Section:  Register functions
		
		- <helloWorld>
		- <setColor>
	*/
	$xajax->registerFunction("helloWorld");
	$xajax->registerFunction("setColor");

	/*
		Section: processRequest
		
		This will detect an incoming xajax request, process it and exit.  If this is
		not a xajax request, then it is a request to load the initial contents of the page
		(HTML).
		
		Everything prior to this statement will be executed upon each request (whether it
		is for the initial page load or a xajax request.  Everything after this statement
		will be executed only when the page is first loaded.
	*/
	$xajax->processRequest();

	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>xajax example</title>
<?php
	// output the xajax javascript. This must be called between the head tags
	$xajax->printJavascript('../');
?>
	<script type='text/javascript'>
		/* <![CDATA[ */
		window.onload = function() {
			// call the helloWorld function to populate the div on load
			xajax_helloWorld(0);
			// call the setColor function on load
			xajax_setColor(document.getElementById('colorselect').value);
		}
		/* ]]> */
	</script>
</head>
<body style="text-align:center;">
	<div id="div1" name="div1">&#160;</div>
	<br/>
	
	<button onclick="xajax_helloWorld(0)" >Click Me</button>
	<button onclick="xajax_helloWorld(1)" >CLICK ME</button>
	<select id="colorselect" name="colorselect"
		onchange="xajax_setColor(document.getElementById('colorselect').value);">
		<option value="black" selected="selected">Black</option>
		<option value="red">Red</option>
		<option value="green">Green</option>
		<option value="blue">Blue</option>
	</select>
</body>
</html>