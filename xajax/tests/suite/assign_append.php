<?php
	/*
		File: assign_append.php
		
		Test script that uses the following <xajaxResponse> commands:
			- <xajaxResponse->assign>
			- <xajaxResponse->append>
	*/

	require("../../xajax_core/xajax.inc.php");
	
	$xajax = new xajax();
	
	$xajax->configure('requestURI', basename(__FILE__));
	
	include_once('options.inc.php');
		
	$objResponse = new xajaxResponse();
	
	class clsPage {
		function clsPage() {
		}
		
		function sendAssignInnerHTML() {
			global $objResponse;
			$objResponse->assign("content", "innerHTML", "Message from the php function sendAssignInnerHTML.");
			return $objResponse;
		}
		
		function sendAssignStyleBackground($color) {
			global $objResponse;
			$objResponse->assign("content", "style.backgroundColor", $color);
			return $objResponse;
		}
		
		function sendAssignOuterHTML() {
			global $objResponse;
			$objResponse->assign("content", "innerHTML", "<div id=\"ImStaying\">This div should appear and remain here.</div><div id=\"ReplaceMe\">This div should appear then disappear.</div><div id=\"ImNotGoing\">This div should appear and remain here also.</div>");
			$objResponse->assign("ReplaceMe", "outerHTML", "<div id=\"TheReplacement\">Successfully replaced the old element with this element via outerHTML</div>");
			return $objResponse;
		}
		
		function sendAppendInnerHTML() {
			global $objResponse;
			$objResponse->append("content", "innerHTML", "<div>This div should be appended to the end.</div>");
			return $objResponse;
		}
	}
	
	$page = new clsPage();
	
	$aRequests = $xajax->register(XAJAX_CALLABLE_OBJECT, $page);

	$xajax->processRequest();
	
	$sRoot = dirname(dirname(dirname(__FILE__)));

	$sCore = '/xajax_core';
	include_once($sRoot . $sCore . '/xajaxControl.inc.php');

	$sControls = '/xajax_controls';
	foreach (array(
		'/document.inc.php',
		'/doctype.inc.php',
		'/html.inc.php',
		'/head.inc.php',
		'/title.inc.php',
		'/body.inc.php',
		'/literal.inc.php',
		'/anchor.inc.php',
		'/div.inc.php'
		) as $sInclude)
		include $sRoot . $sControls . $sInclude;
	
	$litLineBreak = new clsLiteral('<br />');
	$litSpace = new clsLiteral(' ');

	$divPage = new clsDiv();

	$aSAIH = new clsAnchor();
	$aSAIH->setEvent('onclick', $aRequests['sendassigninnerhtml']);
	$aSAIH->addChild(new clsLiteral('Update Content via an assign on the innerHTML property.'));
	$divPage->addChild($aSAIH);
	$divPage->addChild($litLineBreak);
	
	$divPage->addChild(new clsLiteral('Update style.background property via assign: '));
	$divPage->addChild($litLineBreak);

	$aSASB_Red = new clsAnchor();
	$aSASB_Red->setEvent('onclick', $aRequests['sendassignstylebackground'], array(array(0, XAJAX_QUOTED_VALUE, '#ff5555')));
	$aSASB_Red->addChild(new clsLiteral('Red'));
	$divPage->addChild($aSASB_Red);
	$divPage->addChild($litSpace);

	$aSASB_Green = new clsAnchor();
	$aSASB_Green->setEvent('onclick', $aRequests['sendassignstylebackground'], array(array(0, XAJAX_QUOTED_VALUE, '#55ff55')));
	$aSASB_Green->addChild(new clsLiteral('Green'));
	$divPage->addChild($aSASB_Green);
	$divPage->addChild($litSpace);

	$aSASB_Blue = new clsAnchor();
	$aSASB_Blue->setEvent('onclick', $aRequests['sendassignstylebackground'], array(array(0, XAJAX_QUOTED_VALUE, '#5555ff')));
	$aSASB_Blue->addChild(new clsLiteral('Blue'));
	$divPage->addChild($aSASB_Blue);
	$divPage->addChild($litSpace);

	$aSASB_White = new clsAnchor();
	$aSASB_White->setEvent('onclick', $aRequests['sendassignstylebackground'], array(array(0, XAJAX_QUOTED_VALUE, '#ffffff')));
	$aSASB_White->addChild(new clsLiteral('White'));
	$divPage->addChild($aSASB_White);
	$divPage->addChild($litLineBreak);
	
	$aSAOH = new clsAnchor();
	$aSAOH->setEvent('onclick', $aRequests['sendassignouterhtml']);
	$aSAOH->addChild(new clsLiteral('Test an update using the outerHTML property.'));
	$divPage->addChild($aSAOH);
	$divPage->addChild($litLineBreak);
	
	$aSPIH = new clsAnchor();
	$aSPIH->setEvent('onclick', $aRequests['sendappendinnerhtml']);
	$aSPIH->addChild(new clsLiteral('Test an append using the innerHTML property.'));
	$divPage->addChild($aSPIH);
	$divPage->addChild($litLineBreak);

	$divPage->addChild($litLineBreak);
	
	$divContent = new clsDiv(array(
		'attributes' => array('id' => 'content'),
		'children' => array(
			new clsLiteral('This content has not been modified, click an option above to execute a test.')
			)
		));
	$divPage->addChild($divContent);
	
	$title = new clsTitle();
	$title->addChild(new clsLiteral('xajax Test Suite - Assign / Append'));
	
	$style = new clsStyle();
	$style->setAttribute('type', 'text/css');
	$style->addChild(new clsLiteral('#content { border: 1px solid #555555; }'));
	
	$head = new clsHead();
	$head->addChild($title);
	$head->setXajax($xajax);
	$head->addChild($style);
	
	$body = new clsBody();
	$body->addChild($divPage);
	
	$html = new clsHtml();
	$html->addChild($head);
	$html->addChild($body);

	$document = new clsDocument();
	$document->addChild(new clsDocType('HTML', '4.01', 'STRICT'));
	$document->addChild($html);

	$document->printHTML();