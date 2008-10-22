<?php
	/*
		File: index.php
		
		Workbench for testing xajax scripts.
	*/

	require("../../xajax_core/xajax.inc.php");

	$xajax =& new xajax();

	$xajax->configure('requestURI', basename(__FILE__));
	$xajax->configure('javascript URI', '../../');
	$xajax->configure('deferScriptGeneration', true);
//  $xajax->configure('debug', true);

	$sRoot = dirname(dirname(dirname(__FILE__)));
	$sCoreFolder = $sRoot . '/xajax_core';
	$sControlsFolder = $sRoot . '/xajax_controls';
	
	require_once $sCoreFolder . '/xajaxControl.inc.php';
	
	$aRequests = $xajax->register(XAJAX_CALLABLE_OBJECT, new clsRequests());
	$aRequests['gototest']->addParameter(XAJAX_FORM_VALUES, "settings");

	$objResponse = new xajaxResponse();

	$xajax->processRequest();

	foreach (array(
		'/document.inc.php',
		'/doctype.inc.php',
		'/head.inc.php',
		'/title.inc.php',
		'/html.inc.php',
		'/body.inc.php',
		'/iframe.inc.php',
		'/div.inc.php',
		'/span.inc.php',
		'/literal.inc.php',
		'/select.inc.php',
		'/anchor.inc.php',
		'/button.inc.php',
		'/input.inc.php',
		'/form.inc.php',
		'/label.inc.php',
		'/break.inc.php') as $sFile)
		require $sControlsFolder . $sFile;

	$brk = new clsBreak();

	$objTitle = new clsTitle(array(
		'children' => array(new clsLiteral('xajax Test Suite'))
		));

	ob_start();
?>
	#control_panel {
		border: 1px solid #8888aa;
		background: #ddddff;
		padding: 5px;
	}
	#testFrame {
		width: 100%;
		height: 600px;
		border: 1px solid #aa8888;
	}
	#encoding {
		padding: 5px;
		border: 1px dashed #999999;
	}
	#statusMessage {
		padding: 3px;
		color: red;
	}
<?php

	$objStyle = new clsStyle(array(
		'attributes' => array(
			'type' => 'text/css',
			'charset' => 'UTF-8'
			),
		'child' => new clsLiteral(ob_get_clean())
		));

	$objHead = new clsHead(array(
		'xajax' => $xajax,
		'children' => array(
			$objTitle,
			$objStyle
			)
		));

	$objForm = new clsForm(array(
		'attributes' => array('id' => 'settings'),
		'children' => array(
			new clsSpan(array(
				'attributes' => array('id' => 'testSelection')
				)),
			new clsAnchor(array(
				'child' => new clsLiteral('Next Test'),
				'event' => array(
					'onclick',
					$aRequests['nexttest'],
					array(array(0, XAJAX_FORM_VALUES, 'settings'))
					)
				)),
			new clsInput(array(
				'attributes' => array(
					'type' => 'checkbox',
					'name' => 'control_panel_visible',
					'id' => 'control_panel_visible',
					'style' => 'visibility: hidden; position: absolute;',
					'value' => 1,
					'checked' => 'checked'
					)
				)),
			new clsAnchor(array(
				'child' => new clsLiteral('Show/Hide Control Panel'),
				'event' => array(
					'onclick',
					$aRequests['togglecontrolpanel'],
					array(array(0, XAJAX_CHECKED_VALUE, 'control_panel_visible'))
					)
				)),
			new clsAnchor(array(
				'child' => new clsLiteral('Compress Javascript Files'),
				'event' => array('onclick', $aRequests['recompressjavascript'])
				)),
			new clsAnchor(array(
				'child' => new clsLiteral('Refresh Test Page'),
				'event' => array('onclick', $aRequests['gototest'])
				)),
			new clsDiv(array(
				'attributes' => array('id' => 'control_panel'),
				'children' => array(
					new clsInputWithLabel('Enable Debugging', 'right', array(
						'attributes' => array(
							'type' => 'checkbox',
							'name' => 'debug',
							'id' => 'debug',
							'value' => '1'
							),
						'event' => array(
							'onclick', 
							$aRequests['gototest'], 
							array(), 
							'', 
							'; return true;'
							)
						)),
					$brk,
					new clsInputWithLabel('Enable Verbose Debugging', 'right', array(
						'attributes' => array(
							'type' => 'checkbox',
							'name' => 'verbose',
							'id' => 'verbose',
							'value' => '1'
							),
						'event' => array(
							'onclick', 
							$aRequests['gototest'], 
							array(), 
							'', 
							'; return true;'
							)
						)),
					$brk,
					new clsInputWithLabel('Enable Status Messages', 'right', array(
						'attributes' => array(
							'type' => 'checkbox',
							'name' => 'status',
							'id' => 'status',
							'value' => '1'
							),
						'event' => array(
							'onclick', 
							$aRequests['gototest'], 
							array(), 
							'', 
							'; return true;'
							)
						)),
					$brk,
					new clsInputWithLabel('Enable Synchronous Mode (default)', 'right', array(
						'attributes' => array(
							'type' => 'checkbox',
							'name' => 'synchronous',
							'id' => 'synchronous',
							'value' => '1'
							),
						'event' => array(
							'onclick', 
							$aRequests['gototest'], 
							array(), 
							'', 
							'; return true;'
							)
						)),
					$brk,
					new clsInputWithLabel('Inline javascript', 'right', array(
						'attributes' => array(
							'type' => 'checkbox',
							'name' => 'inlineJS',
							'id' => 'inlineJS',
							'value' => '1',
							'checked' => 'checked'
							),
						'event' => array(
							'onclick', 
							$aRequests['gototest'], 
							array(), 
							'', 
							'; return true;'
							)
						)),
					$brk,
					new clsDiv(array(
						'attributes' => array('id' => 'encoding'),
						'children' => array(
							new clsInputWithLabel('Encoding: ', 'left', array(
								'attributes' => array(
									'type' => 'text',
									'name' => 'useEncoding',
									'id' => 'useEncoding',
									'value' => 'UTF-8'
									)
								)),
							$brk,
							new clsLiteral('Output HTML Entities? '),
							new clsInputWithLabel('Yes', 'right', array(
								'attributes' => array(
									'type' => 'radio',
									'name' => 'htmlEntities',
									'id' => 'htmlEntitiesYes',
									'value' => 1
									)
								)),
							new clsInputWithLabel('No', 'right', array(
								'attributes' => array(
									'type' => 'radio',
									'name' => 'htmlEntities',
									'id' => 'htmlEntitiesNo',
									'value' => 0,
									'checked' => 'checked'
									)
								)),
							$brk,
							new clsLiteral('Decode UTF-8 Input? '),
							new clsInputWithLabel('Yes', 'right', array(
								'attributes' => array(
									'type' => 'radio',
									'name' => 'decodeUTF8Input',
									'id' => 'decodeUTF8InputYes',
									'value' => 1
									)
								)),
							new clsInputWithLabel('No', 'right', array(
								'attributes' => array(
									'type' => 'radio',
									'name' => 'decodeUTF8Input',
									'id' => 'decodeUTF8InputNo',
									'value' => 0,
									'checked' => 'checked'
									)
								)),
							$brk,
							new clsInput(array(
								'attributes' => array(
									'type' => 'submit',
									'name' => 'set_options',
									'value' => 'Set Options'
									),
								'event' => array(
									'onclick', 
									$aRequests['gototest']
									)
								))
							)
						)),
					new clsDiv(array(
						'attributes' => array('id' => 'statusMessage')
						))
					)
				))
			)
		));
	
	$objIframe = new clsIframe(array(
		'attributes' => array(
			'id' => 'testFrame',
			'src' => './none.php'
			)
		));

	$objBody = new clsBody(array(
		'children' => array(
			$objForm,
			$objIframe
			),
		'event' => array(
			'onload', 
			$aRequests['loadtests']
			)
		));

	$document = new clsDocument(array(
		'children' => array(
			new clsDocType('HTML', '4.01', 'STRICT'),
			new clsHTML(array(
				'children' => array(
					$objHead,
					$objBody
					)
				))
			)
		));

	$document->printHTML();


	class clsRequests {
		var $tests = array();

		function clsRequests() {
			$this->tests['./none.php'] = '--- Select Test ---';
			$this->tests['./alert_confirm.php'] = 'Alert and Confirm Commands';
			$this->tests['./assign_append.php'] = 'Assign and Append';
			$this->tests['./tables.php'] = 'Tables';
			$this->tests['./transport.php'] = 'Transport';
			$this->tests['./delayEvents.php'] = 'Callbacks';
			$this->tests['./events.php'] = 'Client-side Events';
			$this->tests['./iframe.php'] = 'iFrame';
			$this->tests['./css.php'] = 'CSS';
			$this->tests['./functions.php'] = 'Functions';
			$this->tests['./scriptContext.php'] = 'Script Context';
			$this->tests['./server_events.php'] = 'Server-side Events';
			$this->tests['./pluginTest.php'] = 'Response Plugin';
			$this->tests['./callScriptTest.php'] = 'Call javascript Function';
		}

		function loadTests() {
			global $objResponse;
			global $aRequests;
			global $sControlsFolder;
			
			require $sControlsFolder . '/select.inc.php';
			require $sControlsFolder . '/literal.inc.php';
			
			$select = new clsSelect(array(
				'attributes' => array(
					'id' => 'selectedTest',
					'name' => 'selectedTest'
					),
				'event' => array('onchange', $aRequests['gototest'])
				));

			foreach ($this->tests as $key => $value)
				$select->addChild(
					new clsOption(array(
						'attributes' => array('value' => $key),
						'children' => array(new clsLiteral($value))
						))
					);

			$objResponse->assign("testSelection", "innerHTML", $select->getHTML());
			return $objResponse;
		}

		function nextTest($values) {
			global $objResponse;
			$test = $values['selectedTest'];
			$last = '';
			$count = 0;
			foreach ($this->tests as $key => $value) {
				if ($test == $last) {
					$objResponse->assign('selectedTest', 'selectedIndex', $count);
					$values['selectedTest'] = $key;
					return $this->gotoTest($values);
				}
				$last = $key;
				$count += 1;
			}
			return $objResponse;
		}

		function recompressJavascript() {
			global $xajax;
			global $objResponse;
			global $aRequests;
			$xajax->autoCompressJavascript("../../xajax_js/xajax_core.js", true);
			$xajax->autoCompressJavascript("../../xajax_js/xajax_debug.js", true);
			$xajax->autoCompressJavascript("../../xajax_js/xajax_legacy.js", true);
			$xajax->autoCompressJavascript("../../xajax_js/xajax_verbose.js", true);
			sleep(1);
			$objResponse->assign('statusMessage', 'innerHTML', 'xajax javascript files recompressed...');
			$objResponse->script($aRequests['gototest']->getScript());
			$objResponse->script("setTimeout(function() { xajax.\$('statusMessage').innerHTML = ''; }, 4000);");
			return $objResponse;
		}

		function gotoTest($values) {
			global $objResponse;
			$test = $values['selectedTest'];
			$delimiter = "?";
			if (isset($values['status'])) {
				$test .= $delimiter;
				$test .= "status=1";
				$delimiter = "&";
			}
			if (isset($values['debug'])) {
				$test .= $delimiter;
				$test .= "debugging=1";
				$delimiter = "&";
			}
			if (isset($values['verbose'])) {
				$test .= $delimiter;
				$test .= "verbose=1";
				$delimiter = "&";
			}
			if (isset($values['useEncoding'])) {
				$test .= $delimiter;
				$test .= "useEncoding=";
				$test .= $values['useEncoding'];
				$delimiter = "&";
			}
			if (isset($values['htmlEntities'])) {
				$test .= $delimiter;
				$test .= "htmlEntities=";
				$test .= $values['htmlEntities'];
				$delimiter = "&";
			}
			if (isset($values['decodeUTF8'])) {
				$test .= $delimiter;
				$test .= "decodeUTF8=";
				$test .= $values['decodeUTF8'];
				$delimiter = "&";
			}
			if (isset($values['synchronous'])) {
				$test .= $delimiter;
				$test .= "synchronous=1";
				$delimiter = "&";
			}
			if (false == isset($values['inlineJS'])) {
				$test .= $delimiter;
				$test .= "scriptDeferral=1";
				$delimiter = "&";
			}

			$objResponse->assign('testFrame', 'src', '');
			$objResponse->assign('testFrame', 'src', $test);
			return $objResponse;
		}

		function toggleControlPanel($bControlPanelVisible)
		{
			$objResponse = new xajaxResponse();

			if ('true' == $bControlPanelVisible)
			{
				$objResponse->assign('control_panel_visible', 'checked', false);
				$objResponse->assign('control_panel', 'style.visibility', 'hidden');
				$objResponse->assign('control_panel', 'style.display', 'none');
				$objResponse->assign('control_panel', 'style.position', 'absolute');
			}
			else
			{
				$objResponse->assign('control_panel_visible', 'checked', true);
				$objResponse->assign('control_panel', 'style.visibility', 'visible');
				$objResponse->assign('control_panel', 'style.display', 'block');
				$objResponse->assign('control_panel', 'style.position', 'static');
			}
			return $objResponse;
		}
	}
