<?
#seatstatus
#0 = joinat draften, men draften är inte startad
#1 = confirmat att man är där, men draften har inte startat
#2 = pickar just nu
#3 = jag har pickat, väntar på att andra ska bli klara
if(!$_SESSION["color_array"]) $_SESSION["color_array"] = array("L","A","G","R","B","U","W");
if(!$_SESSION["sort_order"]) $_SESSION["sort_order"] = "color";



$path = "http://localhost/~beakid/magicdraft";
require_once("xajax/xajax_core/xajax.inc.php");
$xajax = new xajax();
$xajax->registerFunction("login");
$xajax->registerFunction("chat");
$xajax->registerFunction("reloadchat");
$xajax->registerFunction("reloadDrafterlist");
$xajax->registerFunction("addCard2Deck");
$xajax->registerFunction("sortDeck");
$xajax->registerFunction("updateBasics");
$xajax->registerFunction("printStats");
$xajax->registerFunction("confirmDraft");
$xajax->registerFunction("addSeat");
$xajax->registerFunction("removeSeat");
$xajax->registerFunction("filterColors");

$xajax->processRequest();

if($_GET["logout"])
{
	logout();
}

function logout()
{
	session_destroy();
	header("Location: ".str_replace("?logout=true","",$_SERVER[REQUEST_URI])); die();
}
function printHeader($title, $page, $draft_id = false)
{
	global $path;
	global $xajax;

	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="<?=$path;?>/css/stylesheet.css.php" type="text/css" media="screen" charset="utf-8">
		<script src="<?=$path;?>/javascript/script.js" type="text/javascript"></script>
		<script src="<?=$path;?>/javascript/ajax.js" type="text/javascript"></script>
		<script src="<?=$path;?>/javascript/prototype.js" type="text/javascript"></script>
		<script src="<?=$path;?>/javascript/scriptaculous.js" type="text/javascript"></script>
		<title><?=$title;?></title>
		<? if($xajax) {$xajax->printJavascript($path."/xajax");} ?>
		
	</head>
	<body onkeydown="cardcloseup(event);" onkeyup="cardclosedown(event);">
		<form id="coordinates" name="coordinates">
		<input type="text" name="MouseX" value="0" size="4"><input type="text" name="MouseY" value="0" size="4">
		</form>
		<div id="blackscreen">
			<img src="<?=$path;?>/images/magicdraft_ani_black.gif" style="opacity: 1; z-index: 11; margin-top: 300px;"/><br />
			<h1 id="blackscreen_title"></h1>
		</div>
		<span id="sound"></span>
		<img src="<?=$path;?>/images/indicator.gif" id="indicator" alt="" />
		<div id="top">
			<a href="<?=$path;?>"><img src="<?=$path;?>/images/logo.png" alt="MagicDraft" id="logotype"/></a>
			<div id="next_to_logo">
				<div id="login">
					<?=printLogin($page)?>
					<? if($draft_id && $_SESSION["md_userid"]) {?>
					<div class="draftchat"><div id="chat_<?=$draft_id;?>" class="chatdiv mini"><?=printChat($draft_id);?></div></div>
					<? } ?>
				</div>
			</div>
			<div class="breaker"></div>
		</div>
		<div class="breaker"></div>
		<div id="menu_holder">
			<?=printMenu($page)?>
		</div>
	<?
}
#prints the menu and makes the current page active
#also prints specific buttons if the visitor is a logged in user
function printMenu($_active, $_logged_in = false)
{
	global $path;
	$active_array = array();
	$active_array[$_active] = "_active";
	if(!$_SESSION["md_userid"]) $output = "<div id=\"menu\" class=\"visitor\">"; else $output = "<div id=\"menu\" class=\"user\">";
	$output .= "<a href=\"$path/\"><img src=\"$path/images/menu_home".$active_array["home"].".png\" id=\"menu_home\" class=\"active\" alt=\"Home\" title=\"Home\"";
		if(!$active_array["home"]) { $output .= "onmouseover=\"this.src='$path/images/menu_home_hover.png';\" onmouseout=\"this.src='$path/images/menu_home.png';\""; }
	$output .= "/></a>";
	
	$output .= "<a href=\"$path/draft\"><img src=\"$path/images/menu_draft".$active_array["draft"].".png\" id=\"menu_draft\" class=\"active\" alt=\"Draft\" title=\"Draft\"";
		if(!$active_array["draft"]) { $output .= "onmouseover=\"this.src='$path/images/menu_draft_hover.png';\" onmouseout=\"this.src='$path/images/menu_draft.png';\""; }
	$output .= "/></a>";
	
	$output .= "<a href=\"$path/stats\"><img src=\"$path/images/menu_stats".$active_array["stats"].".png\" id=\"menu_stats\" class=\"active\" alt=\"Stats\" title=\"Stats\"";
		if(!$active_array["stats"]) { $output .= "onmouseover=\"this.src='$path/images/menu_stats_hover.png';\" onmouseout=\"this.src='$path/images/menu_stats.png';\""; }
	$output .= "/></a>";

	$output .= "<a href=\"$path/forum\"><img src=\"$path/images/menu_forum".$active_array["forum"].".png\" id=\"menu_forum\" class=\"active\" alt=\"Forum\" title=\"Forum\"";
		if(!$active_array["forum"]) { $output .= "onmouseover=\"this.src='$path/images/menu_forum_hover.png';\" onmouseout=\"this.src='$path/images/menu_forum.png';\""; }
	$output .= "/></a>";
	
	$output .= "<a href=\"$path/about\"><img src=\"$path/images/menu_about".$active_array["about"].".png\" id=\"menu_about\" class=\"active\" alt=\"About\" title=\"About\"";
		if(!$active_array["about"]) { $output .= "onmouseover=\"this.src='$path/images/menu_about_hover.png';\" onmouseout=\"this.src='$path/images/menu_about.png';\""; }
	$output .= "/></a>";

	$output .= "<a href=\"$path/mystuff\"><img "; if(!$_SESSION["md_userid"]) { $output .= "style=\"display: none;\""; } 
	$output .= "src=\"$path/images/menu_mystuff".$active_array["mystuff"].".png\" id=\"menu_mystuff\" alt=\"My stuff\" title=\"My stuff\"";
	if(!$active_array["mystuff"]) { $output .= "onmouseover=\"this.src='".$path."/images/menu_mystuff_hover.png';\" onmouseout=\"this.src='".$path."/images/menu_mystuff.png';\""; } 
	$output .= "/></a>
	</div>
	<div class=\"breaker\"></div>";
	return $output;
}
#prints an errormessage including pic
function errormess($_text, $_class = "error")
{
	global $path;
	return "<img src=\"".$path."/images/error.png\" class=\"errorpic\" alt=\"Error!\" /><span class=\"text\"><span class=\"".$_class."\">".$_text."</span>";
}
function printLogin($page, $error = false, $form = false)
{
	global $path;
	global $_REQUEST;
	if(!$_SESSION["md_userid"])
	{
		$output = '<form id="loginform" action="javascript:void(null);" onsubmit="submitLogin();">
			<div id="login_fields">
				<div>USERNAME<br />
					<input type="text" class="textfield" name="user_name" value="'.$form[user_name].'" />
				</div>
				<div>PASSWORD<br />
					<input type="password" class="textfield" name="user_password" value="'.$form[user_password].'" />
				</div>
				<div><br />
					<input type="hidden" name="page" value="'.$page.'" />
					<input type="image" class="loginbutton" src="'.$path.'/images/button_login.png" alt="Login" value="submit" />
				</div>
			</div>
		
			<div class="mini"><br />
				<div id="login_extras">
				<span id="logging_in">'; if($error) $output .= $error."<br />"; $output .= '</span>
				> <a href="'.$path.'/getpassword">Forgot your password?</a><br />
				> Not a user? <a href="'.$path.'/register">Register for free!</a>
			</div>
		</form>';
	}
	else
	{
		$output = '<div id="loggedin_info" class="mini"><br />
			Logged in as '.$_SESSION[md_username].'<br />
			<a href="?logout=true">Logout</a>
		</div>';
#		<div id="chat_1" class="chatdiv mini">'.printChat().'</div>';
	}
	return $output;
}

#ajax-stuff
function chat($form)
{
	$objResponse = new xajaxResponse();
	if(!empty($form[chat_message])) mysql_query("INSERT INTO md_chat VALUES('','".$form[draft_id]."', '".$form[user_id]."', '".addslashes($form[chat_message])."', NOW())");
	$objResponse->assign("chat_".$form[draft_id],"innerHTML",printChat($form[draft_id]));
	$objResponse->assign("chat_input_".$form[draft_id],"focus()",true);
	return $objResponse;
}
function updateBasics($_land, $_nmb, $_draft_id)
{
	mysql_query("UPDATE md_basicland SET nmb_".$_land." = $_nmb WHERE fk_draft_id = $_draft_id AND fk_user_id = $_SESSION[md_userid]");
}
function confirmDraft($_draft_id)
{
	$objResponse = new xajaxResponse();
	$_SESSION["draft_start"] = false;
	mysql_query("UPDATE md_draft2user SET seat_status = '1' WHERE fk_draft_id = $_draft_id AND fk_user_id = $_SESSION[md_userid]");
	return $objResponse;
}
function login($form,$url)
{
	global $path;
	$objResponse = new xajaxResponse();
	if ($form["user_name"] && $form["user_password"])
	{
		$user = mysql_query("SELECT pk_user_id, user_name, user_rating FROM md_user WHERE user_name = '$form[user_name]' AND user_password = '$form[user_password]'");
		if(mysql_num_rows($user))
		{
			$info = mysql_fetch_array($user);
			$_SESSION["md_userid"] = $info[pk_user_id];
			$_SESSION["md_username"] = $info[user_name];
			$_SESSION["md_rating"] = $info[user_rating];
			$output = printLogin($form["page"]);
			$menu = printMenu($form["page"]);
			$objResponse->assign("login","innerHTML",$output);
			$objResponse->assign("menu_holder","innerHTML",$menu);
			$objResponse->redirect($url);
		}
		else
		{
			$error = errormess("Wrong details","white");
			$output = printLogin($page, $error, $form);
			$objResponse->assign("blackscreen","style.display","none");
			$objResponse->assign("login","innerHTML",$output);
		}
	}
	else
	{
		$error = errormess("Fill in all fields","white");
		$output = printLogin($page, $error, $form);
		$objResponse->assign("login","innerHTML",$output);
	}
	return $objResponse;
}
function reloadchat($_draft_id)
{
	if($_SESSION["md_userid"])
	{
		$objResponse = new xajaxResponse();
		$messages = printChatMessages($_draft_id);
		$objResponse->assign("chatmessages_".$_draft_id,"innerHTML",$messages);
	}
	else $objResponse = false;
	return $objResponse;
}
function reloadDrafterlist($_draft_id,$_picking,$draft_status)
{
	$objResponse = new xajaxResponse();
	if($_SESSION["md_userid"])
	{
		#just reload the page if the status is not the same as the one i draftlist, then we need fresh data and a new page
		$draft_info = mysql_fetch_array(mysql_query("SELECT * FROM md_draft WHERE pk_draft_id = $_draft_id"));
		if($draft_info["draft_status"] != $draft_status)
		{
			$_SESSION["draft_start"] = false;
			$objResponse->redirect("draft.php?id=".$_draft_id);
		}
		else
		{
			#are all there in an non-started draft?
			$seat_status = mysql_query("SELECT seat_status FROM md_draft2user WHERE fk_draft_id = $_draft_id ORDER BY seat_status ASC");
			if(mysql_result($seat_status,0) != "0" && mysql_num_rows($seat_status) == $draft_info["max_players"])
			{
				#start the draft (if it's status 1 -> waiting to start)
				mysql_query("UPDATE md_draft SET draft_status = '2' WHERE draft_status = '1' AND pk_draft_id = $_draft_id");
				#startades den?
				if(mysql_affected_rows()) 
				{
					mysql_query("UPDATE md_draft2user SET seat_status = '2' WHERE fk_draft_id = $_draft_id");
					$_SESSION["draft_start"] = false;
					$objResponse->redirect("draft.php?id=".$_draft_id);
					return $objResponse;
				}
				#ok, då var den redan startad och igång. då kollar vi om man väntar på ett pack ($_picking = false, dvs man pickar inte)
				#och isf om det packet är redo för pickning
				elseif($_picking == "false")
				{
					$present_pack = $draft_info["present_pack"];
					#kollar om det finns ett pack jag ska packa i draften
					$pack_id_result = mysql_query("SELECT a1.pk_pack_id FROM md_pack as a1, md_draft2user as a2 WHERE 
						a2.fk_draft_id = $_draft_id aND a2.fk_user_id = $_SESSION[md_userid] AND 
						a2.seat_number = a1.seat_number AND a1.fk_draft_id = $_draft_id AND 
						a1.pack_number = $present_pack AND a1.pack_status = 0");
					#fanns det nåt pack att picka? ladda isf om sidan
					if(mysql_num_rows($pack_id_result))
					{
						$_SESSION["draft_start"] = false;
						$objResponse->redirect("draft.php?id=".$_draft_id);
						return $objResponse;
					}
				}
			}
			$drafterlist = drafterList($_draft_id);
			$objResponse->assign("drafterlist","innerHTML",$drafterlist);
		}
		return $objResponse;
	}
	else return false;
}
function addCard2Deck($_card_id, $part)
{
	$indeck = "1";
	if($part == "sideboard") {$indeck = "0"; }
	
	$_card_id = eregi_replace("card","",$_card_id);
	mysql_query("UPDATE md_packcard SET in_deck = $indeck WHERE pk_packcard_id = $_card_id");
}
function sortDeck($_part, $_draft_id, $_sort_order)
{
	$objResponse = new xajaxResponse();
	if($_part == "picks") $output = printDraftPicks($_draft_id, $_sort_order);
	else $output = printdeck($_draft_id, $_part, $_sort_order);
	$objResponse->assign($_part,"innerHTML",$output);
	return $objResponse;
}
function printStats($_draft_id)
{
	$objResponse = new xajaxResponse();
	$output = deckstats($_draft_id);
	$objResponse->assign("deckstats","innerHTML",$output);
	return $objResponse;
}

function deckstats($draft_id)
{
	global $draft_info;
	if(!$draft_info) $draft_info = mysql_fetch_array(mysql_query("SELECT * FROM md_draft
		WHERE pk_draft_id = $draft_id"));
	$indeck = "1";
	if($draft_info["draft_status"] != 3) $indeck = "0";

	$nmb_deck = mysql_num_rows(mysql_query("SELECT fk_card_id
		FROM md_packcard, md_pack,md_cards 
		WHERE fk_card_id = pk_card_id AND fk_pack_id = pk_pack_id AND md_packcard.fk_user_id = $_SESSION[md_userid] AND md_pack.fk_draft_id = $draft_id AND in_deck = '$indeck'"));
		
	$nmb_creatures = mysql_num_rows(mysql_query("SELECT fk_card_id
		FROM md_packcard, md_pack,md_cards 
		WHERE fk_card_id = pk_card_id AND fk_pack_id = pk_pack_id AND md_packcard.fk_user_id = $_SESSION[md_userid] AND md_pack.fk_draft_id = $draft_id AND in_deck = '$indeck'
		AND card_toughness != ''"));
		
	$nmb_lands = $nmb_basiclands = 0;
	if($draft_info["draft_status"] == 3) {
	$nmb_lands = mysql_num_rows(mysql_query("SELECT fk_card_id
		FROM md_packcard, md_pack,md_cards 
		WHERE fk_card_id = pk_card_id AND fk_pack_id = pk_pack_id AND md_packcard.fk_user_id = $_SESSION[md_userid] AND md_pack.fk_draft_id = $draft_id AND in_deck = '$indeck'
		AND card_toughness = '' AND (card_type LIKE 'Basic Land%' OR card_type  LIKE 'Land%')"));
		
	$nmb_basiclands = mysql_result(mysql_query("SELECT (nmb_island+nmb_plains+nmb_mountain+nmb_swamp+nmb_forest) AS totallands FROM md_basicland
	WHERE fk_draft_id = $draft_id AND fk_user_id = $_SESSION[md_userid]"),0); }
	
	$nmb_spells = $nmb_deck-($nmb_creatures+$nmb_lands);

	$nmb_lands += $nmb_basiclands;
	$nmb_deck += $nmb_basiclands;

	if($draft_info["draft_status"] != 3)
	{
		$output = " <span class=\"grey\"><span id=\"nmb_creatures_in_deck\">".$nmb_creatures."</span> creatures, <span id=\"nmb_spells_in_deck\">".$nmb_spells."</span> spells</span>";
	}
	else
	{
		$output = " (<span id=\"nmb_cards_in_deck\">".$nmb_deck."</span> cards) <span class=\"grey\"><span id=\"nmb_creatures_in_deck\">".$nmb_creatures."</span> creatures, <span id=\"nmb_spells_in_deck\">".$nmb_spells."</span> spells, <span id=\"nmb_land_in_deck\">".$nmb_lands."</span> land</span>";
		if($nmb_deck > 39) $output .= " <a href=\"makedeck.php?draft_id=".$draft_id."\" title=\"Save deck\"><img style=\"float: right;\" src=\"../images/button_savedeck.png\" alt=\"Save deck\" /></a>";
		else $output .= " <img style=\"float: right;\" src=\"../images/button_savedeck_grey.png\" alt=\"Save deck\" title=\"Your deck needs ".(40-$nmb_deck)." more cards before you can save\"/>";
	}
	return $output;
}

function printdeck($draft_id, $part = "deck", $_sort_order = false, $_color_array = false)
{
	if(!$_color_array) $_color_array = $_SESSION["color_array"];
	if(!$_sort_order) $_sort_order = $_SESSION["sort_order"];

	$_SESSION["sort_order"] = $_sort_order;
	$_SESSION["color_array"] = $_color_array;
	
	if($part == "deck")
		$indeck = "1";
	elseif($part == "sideboard")
		{
			$indeck = "0";
			$color_filter = " AND (";
			foreach($_color_array as $one_color)
			{
				$color_filter .= "FIND_IN_SET('".$one_color."',card_color) OR ";
			}
			$color_filter = substr($color_filter,0,-4).")";
		}
		
	if($_sort_order == "color") $sort_order = "find_in_set(card_color, 'L,A,G,R,B,U,W') DESC, card_cmcost DESC";
	elseif($_sort_order == "cost") $sort_order = "card_cmcost";
	elseif($_sort_order == "cardtype") $sort_order = "card_toughness DESC, card_type";
		
	include_once("../include/kortparm_functions.php");
	$cards = mysql_query($apa = "SELECT card_name, '' as version, fk_card_id, exp_name, packcard_is_foil, IF(CHAR_LENGTH(card_color) > 1,'Multi',card_color) AS card_color, pk_packcard_id, card_rarity, card_cmcost, card_type, card_toughness
		FROM md_packcard, md_pack,md_cards 
		INNER JOIN md_exp ON pk_exp_id = md_cards.fk_exp_id
		WHERE fk_card_id = pk_card_id AND fk_pack_id = pk_pack_id AND md_packcard.fk_user_id = $_SESSION[md_userid] AND md_pack.fk_draft_id = $draft_id AND in_deck = '$indeck'
		$color_filter
		ORDER BY $sort_order");
		
	$nmb_deck = mysql_num_rows($cards);
	if($part == "deck") 
	{
		$nmb_basiclands = mysql_result(mysql_query("select (nmb_island+nmb_plains+nmb_mountain+nmb_swamp+nmb_forest) AS totallands FROM md_basicland
		WHERE fk_draft_id = $draft_id AND fk_user_id = $_SESSION[md_userid]"),0);
		$nmb_deck+=$nmb_basiclands;	
	}
	$x = 0;
	$stacks = 0;
	$marginleft = 0;
	$x_in_stack = 0;
	$lastbiggestpile = 0;
	$rows = 1;
	$output = "<h2>".ucwords($part);
	if($part == "deck") $output .= "<span id=\"deckstats\">".deckstats($draft_id)."</span>";
	else $output .= " (<span id=\"nmb_cards_in_sideboard\">".$nmb_deck."</span> cards)";
	$output .= "</h2>";
	
	if($part == "deck") $output .= "<p class=\"small\">Order by <span class=\"orange pointer\" onclick=\"javascript:setXYposition('indicator',event); javascript:orderDeck('".$part."','".$draft_id."','color',false);\">color</span> | <span class=\"orange pointer\" onclick=\"javascript:setXYposition('indicator',event); javascript:orderDeck('".$part."','".$draft_id."','cost',false);\">cost</span> | <span class=\"orange pointer\" onclick=\"javascript:setXYposition('indicator',event); javascript:orderDeck('".$part."','".$draft_id."','cardtype',false);\">cardtype</span></p>";
	$stacks_per_row = 5;
	if($part == "sideboard") 
	{
		$stacks_per_row = 3;
		$output .= '<br />
		<div class="filter_buttons">
			<img id="color_W" src="../images/button_white.png"'; if(array_search("W",$_color_array) !== false) $output .= ' class="active"'; $output .= ' onclick="setXYposition(\'indicator\',event); filterColors(\'W\',\''.$draft_id.'\',\'sideboard\',\''.$_sort_order.'\');" alt="" />
			<img id="color_U" src="../images/button_blue.png"'; if(array_search("U",$_color_array) !== false) $output .= ' class="active"'; $output .= ' onclick="setXYposition(\'indicator\',event); filterColors(\'U\',\''.$draft_id.'\',\'sideboard\',\''.$_sort_order.'\');" alt="" />
			<img id="color_B" src="../images/button_black.png"'; if(array_search("B",$_color_array) !== false) $output .= ' class="active"'; $output .= ' onclick="setXYposition(\'indicator\',event); filterColors(\'B\',\''.$draft_id.'\',\'sideboard\',\''.$_sort_order.'\');" alt="" />
			<img id="color_R" src="../images/button_red.png"'; if(array_search("R",$_color_array) !== false) $output .= ' class="active"'; $output .= ' onclick="setXYposition(\'indicator\',event); filterColors(\'R\',\''.$draft_id.'\',\'sideboard\',\''.$_sort_order.'\');" alt="" />
			<img id="color_G" src="../images/button_green.png"'; if(array_search("G",$_color_array) !== false) $output .= ' class="active"'; $output .= ' onclick="setXYposition(\'indicator\',event); filterColors(\'G\',\''.$draft_id.'\',\'sideboard\',\''.$_sort_order.'\');" alt="" />
			<img id="color_A" src="../images/button_artifacts.png"'; if(array_search("A",$_color_array) !== false) $output .= ' class="active"'; $output .= ' onclick="setXYposition(\'indicator\',event); filterColors(\'A\',\''.$draft_id.'\',\'sideboard\',\''.$_sort_order.'\');" alt="" />
		</div>
		';
	}
	
	while($card = mysql_fetch_array($cards))
	{
		if($_sort_order == "cardtype")
		{
			if(strpos($card[card_type], "Creature") > -1 || $card[card_toughness] != "") { $cardtype = "Creature"; }
			elseif(strpos($card[card_type], "Enchantment") > -1) { $cardtype = "Enchantment"; }
			elseif(strpos($card[card_type], "Artifact") > -1) { $cardtype = "Artifact"; }
			elseif(strpos($card[card_type], "Sorcery") > -1) { $cardtype = "Sorcery"; }
			elseif(strpos($card[card_type], "Instant") > -1 || strrpos($card[card_type], "Interrupt") > -1) { $cardtype = "Instant"; }
			elseif(strpos($card[card_type], "Land") > -1) { $cardtype = "Land"; }
		}
		$bildexp = eregi_replace(' ',"", stripslashes($card[exp_name]));
		$bildexp = eregi_replace("'","", strtolower($bildexp));
		if(($card[card_color] != $lastcolor && $_sort_order == "color") || ($card[card_cmcost] != $lastcmcost && $_sort_order == "cost") || ($cardtype != $lastcardtype && $_sort_order == "cardtype"))
		{
			if(!$biggestpile) $biggestpile = $x_in_stack;
			elseif($x_in_stack>$biggestpile) $biggestpile = $x_in_stack;
			$x_in_stack = 0;
			$stacks++;
			if($stacks == ($stacks_per_row+1) or $stacks == ($stacks_per_row*2+1)) $rows++;
			if($rows == 1)
			{
				$marginleft = 110*($stacks-1);
				$margintop = 0;
			}
			elseif($rows == 2)
			{
				if($stacks == ($stacks_per_row+1)) {$lastbiggestpile = $biggestpile; $biggestpile = false; }
				$marginleft = 110*($stacks-($stacks_per_row+1));
				$margintop = (15*$lastbiggestpile)+120;
			}
			elseif($rows == 3)
			{
				if($stacks == ($stacks_per_row*2+1)){ $lastlastbiggestpile = $lastbiggestpile + $biggestpile; }
				$marginleft = 110*($stacks-($stacks_per_row*2+1));
				$margintop = (15*$lastlastbiggestpile)+240;
			}
		}
		$x_in_stack++;
		$x++;
		$margintop = $margintop+15;
		
		$mouseover = "onmouseover=\"viewCard('http://www.svenskamagic.com/kortbilder/".$bildexp."/".cardname2filename($card[card_name],$card[version])."');\"
			onclick=\"javascript:increaseZindex('card".$card[pk_packcard_id]."');\"";
	$output .= '<div alt="'.stripslashes($card[card_name]).'" class="card shadow '.$part.'_card" style="margin-top: '.$margintop.'px; left: '.$marginleft.'px; 
				position: absolute; z-index: '.$x.'" id="card'.$card[pk_packcard_id].'" '.$mouseover.'>'; 
	
	if($card[packcard_is_foil]) { $output .= '<div class="foil" '.$mouseover.'></div>'; } 
	
	$output .= '<img id="cardimg_'.$x.'" src="http://www.svenskamagic.com/kortbilder/'.$bildexp.'/'.cardname2filename($card[card_name],$card[version]).'"'; 
	
	if(!$card[packcard_is_foil]) $output .= " ".$mouseover; 
	
	$output .= 'alt="'.stripslashes($card[card_name]).'" class="cardpic" /></div>';
			$output .= '<script type="text/javascript">
			new Draggable(\'card'.$card[pk_packcard_id].'\',{starteffect: false, endeffect: false});
			</script>';
	$lastcolor = $card[card_color];
	$lastcmcost = $card[card_cmcost];
	$lastcardtype = $cardtype;
	}

	#basiclands
	if($part == "deck")
	{
		$deck_margintop = $lastbiggestpile*15 + $lastlastbiggestpile*15 + $biggestpile*15 + 120;
		$basiclands = mysql_fetch_array(mysql_query("SELECT * FROM md_basicland WHERE fk_user_id = ".$_SESSION["md_userid"]." AND fk_draft_id = ".$draft_id));
		for ($i=0; $i < 5; $i++) 
		{ 
			if($i == 0) $land = "island"; elseif($i == 1) $land = "plains"; elseif($i == 2) $land = "forest";
			elseif($i == 3) $land = "mountain"; elseif($i == 4) $land = "swamp";
			if($land != "plains") $lands = ucwords($land)."s"; else $lands = "Plains";

			$x = 0;
			$margintop = $deck_margintop+165;
			$marginleft = 110*$i;
			$output .= "<div class=\"mini\" style=\"margin-left: ".$marginleft."px; margin-top: ".$margintop."px; position: absolute;\"><select name=\"nmb_".$land."\" size=\"1\" onchange=\"showBasicLands('".$land."', this.value, '".$draft_id."'); printStats('".$draft_id."');\">";
			for ($iet=0; $iet < 19; $iet++) { 
				$output .= "<option value=\"".$iet."\"";
				if($iet == $basiclands["nmb_".$land]) $output .= " selected=\"selected\"";
				$output .= ">".$iet."</option>";
			}
			$output .= "</select> ".$lands."</div>";

			$margintop += 10;
			$output .= "<div id=\"basiclands_".$land."\" style=\"position: absolute;\">";
			for ($x=0; $x < 18; $x++) 
			{ 
				$display = "";
				if($x >= $basiclands["nmb_".$land])
				{
					$display = "display: none; ";
				}
				$margintop = $margintop+15;
				$output .= '<div alt="'.ucwords($land).'" class="basicland" style="'.$display.'margin-top: '.$margintop.'px; left: '.$marginleft.'px; 
							position: absolute; z-index: '.$x.'">'; 
				$output .= '<img id="cardimg_'.$x.'" src="http://www.svenskamagic.com/kortbilder/10thedition/'.$land.'1.full.jpg" alt="'.ucwords($land).'" class="cardpic" /></div>';
			}
			$output .= "</div>";
		}
	}
	return $output;
}


function printChat($_draft_id = 1)
{
	$output = "<ul id=\"chatmessages_".$_draft_id."\">";
	$output .= printChatMessages($_draft_id);
	$output .= '</ul>
	<form id="chatform_'.$_draft_id.'" action="javascript:void(null);" onsubmit="submitChat('.$_draft_id.');">
	<input type="text" id="chat_input_'.$_draft_id.'" class="mini" maxlength="55" name="chat_message" />
	<input type="hidden" name="draft_id" value="'.$_draft_id.'" />
	<input type="hidden" name="user_id" value="'.$_SESSION[md_userid].'" />
	</form>';
	return $output;
}
function printChatMessages($_draft_id)
{
	$the_limit = 3;
	global $path;
	$output = "";
	$latest_3 = mysql_query("SELECT pk_chat_id FROM md_chat
		INNER JOIN md_user ON fk_user_id = pk_user_id
		WHERE fk_draft_id = '$_draft_id'
		ORDER BY chat_date DESC LIMIT 0,$the_limit");
		$sql = "(";
	while($late = mysql_fetch_array($latest_3))
	{
		$sql .= "pk_chat_id = '$late[pk_chat_id]' OR ";
	}
	$sql = " AND ".substr($sql,0,-4).")";
	if(!mysql_num_rows($latest_3)) $sql = "";
	$chats = mysql_query("SELECT user_name, user_country, chat_message, date_format(chat_date, '%H:%i:%s') as chat_datum FROM md_chat 
	INNER JOIN md_user ON fk_user_id = pk_user_id
	WHERE fk_draft_id = '$_draft_id' $sql
	ORDER BY chat_date");
	while($chat = mysql_fetch_array($chats))
	{
		if(!$odd) $odd = "odd"; else $odd = "even";
		$output .= "<li class=\"".$odd."\">".$chat[chat_datum]." <img src=\"".$path."/images/flags/".$chat[user_country].".png\" class=\"avatar\"/> &lt;".$chat[user_name]."&gt; ".stripslashes($chat[chat_message])."</li>";
		if($odd == "even") $odd = false;
	}
	$output .= '<script type="text/javascript">setTimeout("reloadChat('.$_draft_id.')",5000);</script>';
	return $output;
}
function drafterList($draft_id)
{
	global $path;
	$draftinfo = mysql_fetch_array(mysql_query("SELECT * FROM md_draft WHERE pk_draft_id = $draft_id"));
	$output = '<ul id="drafter_list">';
		$drafters = mysql_query("SELECT pk_user_id, user_name, user_country, seat_status, seat_number FROM md_draft2user
		INNER JOIN md_user ON fk_user_id = pk_user_id
		WHERE fk_draft_id = $draft_id
		ORDER BY seat_number");
		$xet = 0;
		while($drafter = mysql_fetch_array($drafters))
		{
			if($drafter["pk_user_id"] == $_SESSION["md_userid"]) {$mystatus = $drafter[seat_status]; $myseat = $drafter["seat_number"];}
			$xet++;
			if($drafter[seat_status] == 0 || $drafter[seat_status] == 2) $class = "waiting"; else $class = "done";
			$output .= '<li class="'.$class.'"';
			if($xet == 1) $output .= " style=\"padding-top: 7px;\"";
			$output .= '>
			<span class="mini">'.$xet.'</span>
			<img src="'.$path.'/images/flags/'.$drafter[user_country].'.png" class="avatar" alt="" /> '.$drafter[user_name].'</li>';
		}
		$picking_or_thinking = "false";
		if($mystatus == 0 || $mystatus == 2) $picking_or_thinking = "true";
		#printing free seats
		for ($xet=$xet+1; $xet <= $draftinfo["max_players"]; $xet++) { 
			$output .= '
			<li class="open">
				<span class="mini">'.$xet.'</span> - seat open -';
				#if you're seat #1 (creator) you can add and delete open seats
				if($myseat == 1 && $xet == $draftinfo["max_players"])
				{
					$output .= '<img src="../images/close.png" class="remove_user pointer" alt="Close" onclick="javascript:setXYposition(\'indicator\',event); javascript:removeSeat('.$draft_id.');" />';
				}
			$output .='</li>';
		}
	$output .= '</ul>';
	
	#place a add-seat-button if you're a) creator and b) not full draft
	if($myseat == 1 && $draftinfo["max_players"] < 8 && $draftinfo["draft_status"] == '0')
	{
		$output .= '<span onclick="javascript:setXYposition(\'indicator\',event); javascript:addSeat('.$draft_id.');"><img src="../images/add.png" class="pointer vertmiddle" alt="Add a seat" /> <a class="adduser" href="#">Add a seat</a></span>';
	}
	
	#klockan
	$clock = "";
	if($picking_or_thinking == "true" && $draftinfo["draft_status"] == 2)
	{
		$tiden = time();
		if(!$_SESSION["draft_start"]) $_SESSION["draft_start"] = $tiden;
		if($tiden - $_SESSION["draft_start"] <= 10) $clock = '<img class="clock" src="../images/draftclock_green.gif" alt="" /> <span class="clock_green">Browse the pack</span>';
		elseif($tiden - $_SESSION["draft_start"] <= 15) $clock = '<img class="clock" src="../images/draftclock_yellow.gif" alt="" /> <span class="clock_yellow">Make your choice</span>';
		elseif($tiden - $_SESSION["draft_start"] <= 25) $clock = '<img class="clock" src="../images/draftclock_red.gif" alt="" /> <span class="clock_red">Pick your card!</span>';
		else
		{
			$clock = '<img class="clock" src="../images/draftclock_ringing.gif" alt="" /> <span class="clock_ringing">Pick! Pick! Pick!</span>';
			if($tiden - $_SESSION["draft_start"] > 30) $clock .= "<span id=\"autograb\"></span>";
		}
		$output .= "<span id=\"picking\"></span>";
	}
	$clock = "";
	$output .= "<span id=\"draft_status\">".$draftinfo["draft_status"]."</span>";
	$output .= $clock.'<script type="text/javascript">setTimeout("reloadDrafterlist('.$draft_id.','.$picking_or_thinking.')",3000);</script>';
	return $output;
}
function numeric($number)
{
	$output = "";
	if(substr($number,-1) == 1) $output = $number."st";
	elseif(substr($number,-1) == 2) $output = $number."nd";
	elseif(substr($number,-1) == 3) $output = $number."rd";
	else $output = $number."th";
	return $output;
}
function draftPacks($draft_info)
{
	#if the draft isn't started, no pack show be shown as present
	if($draft_info[draft_status] != 2) $draft_info[present_pack] = false;
	global $path;
	$pack1name = mysql_result(mysql_query("SELECT exp_name FROM md_exp WHERE pk_exp_id = $draft_info[pack_1]"),0);
	$pack2name = mysql_result(mysql_query("SELECT exp_name FROM md_exp WHERE pk_exp_id = $draft_info[pack_2]"),0);
	$pack3name = mysql_result(mysql_query("SELECT exp_name FROM md_exp WHERE pk_exp_id = $draft_info[pack_3]"),0);
	?>
	<div id="drafting_these_packs">
		<div class="pack<? if($draft_info[present_pack] == 1) echo " currently_drafting"; ?>">
			<? if($draft_info[present_pack] == 1) echo "<img src=\"../images/arrow_right.png\" class=\"arrow\" alt=\"\" /> ";?><img src="<?=$path;?>/images/pack_<?=$draft_info[pack_1];?>.jpg" class="packpic" alt="<?=$pack1name;?>" /><div class="packname"><?=$pack1name;?></div>
			<div class="breaker"></div>
		</div>
		<div class="breaker"></div>
		<div class="pack<? if($draft_info[present_pack] == 2) echo " currently_drafting"; ?>">
			<? if($draft_info[present_pack] == 2) echo "<img src=\"../images/arrow_right.png\" class=\"arrow\" alt=\"\" /> ";?><img src="<?=$path;?>/images/pack_<?=$draft_info[pack_2];?>.jpg" class="packpic" alt="<?=$pack2name;?>" /><div class="packname"><?=$pack2name;?></div>
			<div class="breaker"></div>
		</div>
		<div class="breaker"></div>
		<div class="pack<? if($draft_info[present_pack] == 3) echo " currently_drafting"; ?>">
			<? if($draft_info[present_pack] == 3) echo "<img src=\"../images/arrow_right.png\" class=\"arrow\" alt=\"\" /> ";?><img src="<?=$path;?>/images/pack_<?=$draft_info[pack_3];?>.jpg" class="packpic" alt="<?=$pack3name;?>" /><div class="packname"><?=$pack3name;?></div>
			<div class="breaker"></div>
		</div>
		<div class="breaker"></div>
	</div>
	<?
}
function countrylist($country)
{
	?>
	<option value="BE"<? if($country =="BE") echo " selected";?>>Belgium</option>
	<option value="DK"<? if($country =="DK") echo " selected";?>>Denmark</option>
	<option value="NO"<? if($country =="NO") echo " selected";?>>Norway</option>
	<option value="FR"<? if($country =="FR") echo " selected";?>>France</option>
	<option value="DE"<? if($country =="DE") echo " selected";?>>Germany</option>
	<option value="IT"<? if($country =="IT") echo " selected";?>>Italy</option>
	<option value="JP"<? if($country =="JP") echo " selected";?>>Japan</option>
	<option value="NL"<? if($country =="NL") echo " selected";?>>Netherlands</option>
	<option value="ES"<? if($country =="ES") echo " selected";?>>Spain</option>
	<option value="SE"<? if($country =="SE") echo " selected";?>>Sweden</option>
	<option value="GB"<? if($country =="GB") echo " selected";?>>United Kingdom</option>
	<option value="US"<? if($country =="US") echo " selected";?>>U.S.A</option>
	<option value="">-------------</option>
	<option value="AL"<? if($country =="AL") echo " selected";?>>Albania</option>
	<option value="DZ"<? if($country =="DZ") echo " selected";?>>Algeria</option>
	<option value="AD"<? if($country =="AD") echo " selected";?>>Andorra</option>
	<option value="AO"<? if($country =="AO") echo " selected";?>>Angola</option>
	<option value="AI"<? if($country =="AI") echo " selected";?>>Anguilla</option>
	<option value="AG"<? if($country =="AG") echo " selected";?>>Antigua And Barbuda</option>
	<option value="AE"<? if($country =="AE") echo " selected";?>>Arab Emirates</option>
	<option value="AR"<? if($country =="AR") echo " selected";?>>Argentina</option>
	<option value="AM"<? if($country =="AM") echo " selected";?>>Armenia</option>
	<option value="AW"<? if($country =="AW") echo " selected";?>>Aruba</option>
	<option value="AU"<? if($country =="AU") echo " selected";?>>Australia</option>
	<option value="AT"<? if($country =="AT") echo " selected";?>>Austria</option>
	<option value="AZ"<? if($country =="AZ") echo " selected";?>>Azerbaijan</option>
	<option value="BS"<? if($country =="BS") echo " selected";?>>The Bahamas</option>
	<option value="BH"<? if($country =="BH") echo " selected";?>>Bahrain</option>
	<option value="BD"<? if($country =="BD") echo " selected";?>>Bangladesh</option>
	<option value="BB"<? if($country =="BB") echo " selected";?>>Barbados</option>
	<option value="BY"<? if($country =="BY") echo " selected";?>>Belarus</option>
	<option value="BZ"<? if($country =="BZ") echo " selected";?>>Belize</option>
	<option value="BM"<? if($country =="BM") echo " selected";?>>Bermuda</option>
	<option value="BO"<? if($country =="BO") echo " selected";?>>Bolivia</option>
	<option value="BA"<? if($country =="BA") echo " selected";?>>Bosnia And Herzegovina</option>
	<option value="BW"<? if($country =="BW") echo " selected";?>>Botswana</option>
	<option value="BR"<? if($country =="BR") echo " selected";?>>Brazil</option>
	<option value="BN"<? if($country =="BN") echo " selected";?>>Brunei</option>
	<option value="BG"<? if($country =="BG") echo " selected";?>>Bulgaria</option>
	<option value="KH"<? if($country =="KH") echo " selected";?>>Cambodia</option>
	<option value="CM"<? if($country =="CM") echo " selected";?>>Cameroon</option>
	<option value="CA"<? if($country =="CA") echo " selected";?>>Canada</option>
	<option value="KY"<? if($country =="KY") echo " selected";?>>Cayman Islands</option>
	<option value="TD"<? if($country =="TD") echo " selected";?>>Chad</option>
	<option value="CL"<? if($country =="CL") echo " selected";?>>Chile</option>
	<option value="HK"<? if($country =="HK") echo " selected";?>>China,Hong Kong S.A.R.</option>
	<option value="CN"<? if($country =="CN") echo " selected";?>>China P.Rep.</option>
	<option value="CC"<? if($country =="CC") echo " selected";?>>Cocos Islands</option>
	<option value="CO"<? if($country =="CO") echo " selected";?>>Colombia</option>
	<option value="CD"<? if($country =="CD") echo " selected";?>>Congo, Democratic Republic Of</option>
	<option value="CK"<? if($country =="CK") echo " selected";?>>Cook Islands</option>
	<option value="CR"<? if($country =="CR") echo " selected";?>>Costa Rica</option>
	<option value="HR"<? if($country =="HR") echo " selected";?>>Croatia</option>
	<option value="CU"<? if($country =="CU") echo " selected";?>>Cuba</option>
	<option value="CY"<? if($country =="CY") echo " selected";?>>Cyprus</option>
	<option value="CZ"<? if($country =="CZ") echo " selected";?>>Czech Republic</option>
	<option value="CS"<? if($country =="CS") echo " selected";?>>Czechoslovakia</option>
	<option value="DM"<? if($country =="DM") echo " selected";?>>Dominica</option>
	<option value="DO"<? if($country =="DO") echo " selected";?>>Dominican Repl.</option>
	<option value="EC"<? if($country =="EC") echo " selected";?>>Ecuador</option>
	<option value="EG"<? if($country =="EG") echo " selected";?>>Egypt</option>
	<option value="SV"<? if($country =="SV") echo " selected";?>>El Salvador</option>
	<option value="EE"<? if($country =="EE") echo " selected";?>>Estonia</option>
	<option value="ET"<? if($country =="ET") echo " selected";?>>Ethiopia</option>
	<option value="FK"<? if($country =="FK") echo " selected";?>>Falkland Islands (Malvinas)</option>
	<option value="FO"<? if($country =="FO") echo " selected";?>>Faroe Islands</option>
	<option value="FJ"<? if($country =="FJ") echo " selected";?>>Fiji</option>
	<option value="FI"<? if($country =="FI") echo " selected";?>>Finland</option>
	<option value="PF"<? if($country =="PF") echo " selected";?>>Fr. Polynesia</option>
	<option value="GF"<? if($country =="GF") echo " selected";?>>French Guiana</option>
	<option value="GM"<? if($country =="GM") echo " selected";?>>Gambia</option>
	<option value="GA"<? if($country =="GA") echo " selected";?>>Gabon</option>
	<option value="GE"<? if($country =="GE") echo " selected";?>>Georgia (Republic Of)</option>
	<option value="GH"<? if($country =="GH") echo " selected";?>>Ghana</option>
	<option value="GI"<? if($country =="GI") echo " selected";?>>Gibraltar</option>
	<option value="GR"<? if($country =="GR") echo " selected";?>>Greece</option>
	<option value="GL"<? if($country =="GL") echo " selected";?>>Greenland</option>
	<option value="GD"<? if($country =="GD") echo " selected";?>>Grenada</option>
	<option value="GP"<? if($country =="GP") echo " selected";?>>Guadeloupe</option>
	<option value="GT"<? if($country =="GT") echo " selected";?>>Guatemala</option>
	<option value="GN"<? if($country =="GN") echo " selected";?>>Guinea</option>
	<option value="GY"<? if($country =="GY") echo " selected";?>>Guyana</option>
	<option value="HT"<? if($country =="HT") echo " selected";?>>Haiti</option>
	<option value="HN"<? if($country =="HN") echo " selected";?>>Honduras</option>
	<option value="HU"<? if($country =="HU") echo " selected";?>>Hungary</option>
	<option value="IS"<? if($country =="IS") echo " selected";?>>Iceland</option>
	<option value="IN"<? if($country =="IN") echo " selected";?>>India</option>
	<option value="ID"<? if($country =="ID") echo " selected";?>>Indonesia</option>
	<option value="IR"<? if($country =="IR") echo " selected";?>>Iran</option>
	<option value="IQ"<? if($country =="IQ") echo " selected";?>>Iraq</option>
	<option value="IE"<? if($country =="IE") echo " selected";?>>Ireland</option>
	<option value="IL"<? if($country =="IL") echo " selected";?>>Israel</option>
	<option value="CI"<? if($country =="CI") echo " selected";?>>Ivory Coast</option>
	<option value="JM"<? if($country =="JM") echo " selected";?>>Jamaica</option>
	<option value="JO"<? if($country =="JO") echo " selected";?>>Jordan</option>
	<option value="KZ"<? if($country =="KZ") echo " selected";?>>Kazakhstan</option>
	<option value="KE"<? if($country =="KE") echo " selected";?>>Kenya</option>
	<option value="KW"<? if($country =="KW") echo " selected";?>>Kuwait</option>
	<option value="KG"<? if($country =="KG") echo " selected";?>>Kyrgyzstan</option>
	<option value="LA"<? if($country =="LA") echo " selected";?>>Laos</option>
	<option value="LV"<? if($country =="LV") echo " selected";?>>Latvia</option>
	<option value="LB"<? if($country =="LB") echo " selected";?>>Lebanon</option>
	<option value="LR"<? if($country =="LR") echo " selected";?>>Liberia</option>
	<option value="LY"<? if($country =="LY") echo " selected";?>>Libya</option>
	<option value="LI"<? if($country =="LI") echo " selected";?>>Liechtenstein</option>
	<option value="LT"<? if($country =="LT") echo " selected";?>>Lithuania</option>
	<option value="LU"<? if($country =="LU") echo " selected";?>>Luxembourg</option>
	<option value="MO"<? if($country =="MO") echo " selected";?>>Macau</option>
	<option value="MK"<? if($country =="MK") echo " selected";?>>Macedonia, Former Yugoslav Rep.</option>
	<option value="MG"<? if($country =="MG") echo " selected";?>>Madagascar</option>
	<option value="MW"<? if($country =="MW") echo " selected";?>>Malawi</option>
	<option value="MY"<? if($country =="MY") echo " selected";?>>Malaysia</option>
	<option value="ML"<? if($country =="ML") echo " selected";?>>Mali</option>
	<option value="MT"<? if($country =="MT") echo " selected";?>>Malta</option>
	<option value="MH"<? if($country =="MH") echo " selected";?>>Marshall Islands</option>
	<option value="MQ"<? if($country =="MQ") echo " selected";?>>Martinique</option>
	<option value="MR"<? if($country =="MR") echo " selected";?>>Mauritania</option>
	<option value="MU"<? if($country =="MU") echo " selected";?>>Mauritius</option>
	<option value="MX"<? if($country =="MX") echo " selected";?>>Mexico</option>
	<option value="MD"<? if($country =="MD") echo " selected";?>>Moldova, Republic Of</option>
	<option value="MC"<? if($country =="MC") echo " selected";?>>Monaco</option>
	<option value="MA"<? if($country =="MA") echo " selected";?>>Morocco</option>
	<option value="MM"<? if($country =="MM") echo " selected";?>>Myanmar</option>
	<option value="NA"<? if($country =="NA") echo " selected";?>>Namibia</option>
	<option value="NP"<? if($country =="NP") echo " selected";?>>Nepal</option>
	<option value="AN"<? if($country =="AN") echo " selected";?>>Neth. Antilles</option>
	<option value="NC"<? if($country =="NC") echo " selected";?>>New Caledonia</option>
	<option value="PG"<? if($country =="PG") echo " selected";?>>New Guinea</option>
	<option value="NZ"<? if($country =="NZ") echo " selected";?>>New Zealand</option>
	<option value="NI"<? if($country =="NI") echo " selected";?>>Nicaragua</option>
	<option value="NE"<? if($country =="NE") echo " selected";?>>Niger</option>
	<option value="NG"<? if($country =="NG") echo " selected";?>>Nigeria</option>
	<option value="NF"<? if($country =="NF") echo " selected";?>>Norfolk Island</option>
	<option value="KP"<? if($country =="KP") echo " selected";?>>North Korea</option>
	<option value="OM"<? if($country =="OM") echo " selected";?>>Oman</option>
	<option value="PK"<? if($country =="PK") echo " selected";?>>Pakistan</option>
	<option value="PA"<? if($country =="PA") echo " selected";?>>Panama</option>
	<option value="PY"<? if($country =="PY") echo " selected";?>>Paraguay</option>
	<option value="PE"<? if($country =="PE") echo " selected";?>>Peru</option>
	<option value="PH"<? if($country =="PH") echo " selected";?>>Philippines</option>
	<option value="PL"<? if($country =="PL") echo " selected";?>>Poland</option>
	<option value="PT"<? if($country =="PT") echo " selected";?>>Portugal</option>
	<option value="PW"<? if($country =="PW") echo " selected";?>>Palau</option>
	<option value="QA"<? if($country =="QA") echo " selected";?>>Qatar</option>
	<option value="RO"<? if($country =="RO") echo " selected";?>>Romania</option>
	<option value="RU"<? if($country =="RU") echo " selected";?>>Russian Federation</option>
	<option value="KN"<? if($country =="KN") echo " selected";?>>Saint Kitts And Nevis</option>
	<option value="SM"<? if($country =="SM") echo " selected";?>>San Marino</option>
	<option value="SA"<? if($country =="SA") echo " selected";?>>Saudi Arabia</option>
	<option value="SN"<? if($country =="SN") echo " selected";?>>Senegal</option>
	<option value="SL"<? if($country =="SL") echo " selected";?>>Sierra Leone</option>
	<option value="SG"<? if($country =="SG") echo " selected";?>>Singapore</option>
	<option value="SK"<? if($country =="SK") echo " selected";?>>Slovakia</option>
	<option value="SI"<? if($country =="SI") echo " selected";?>>Slovenia</option>
	<option value="SB"<? if($country =="SB") echo " selected";?>>Solomon Islands</option>
	<option value="ZA"<? if($country =="ZA") echo " selected";?>>South Africa</option>
	<option value="KR"<? if($country =="KR") echo " selected";?>>South Korea</option>
	<option value="LK"<? if($country =="LK") echo " selected";?>>Sri Lanka</option>
	<option value="LC"<? if($country =="LC") echo " selected";?>>St. Lucia</option>
	<option value="VC"<? if($country =="VC") echo " selected";?>>St. Vincent/Grenadines</option>
	<option value="SD"<? if($country =="SD") echo " selected";?>>Sudan</option>
	<option value="SR"<? if($country =="SR") echo " selected";?>>Suriname</option>
	<option value="SZ"<? if($country =="SZ") echo " selected";?>>Swaziland</option>
	<option value="CH"<? if($country =="CH") echo " selected";?>>Switzerland</option>
	<option value="SY"<? if($country =="SY") echo " selected";?>>Syria</option>
	<option value="TW"<? if($country =="TW") echo " selected";?>>Taiwan</option>
	<option value="TZ"<? if($country =="TZ") echo " selected";?>>Tanzania</option>
	<option value="TH"<? if($country =="TH") echo " selected";?>>Thailand</option>
	<option value="TT"<? if($country =="TT") echo " selected";?>>Trinidad/Tobago</option>
	<option value="TN"<? if($country =="TN") echo " selected";?>>Tunisia</option>
	<option value="TR"<? if($country =="TR") echo " selected";?>>Turkey</option>
	<option value="TC"<? if($country =="TC") echo " selected";?>>Turks And Caicos Islands</option>
	<option value="SU"<? if($country =="SU") echo " selected";?>>U.S.S.R.</option>
	<option value="UG"<? if($country =="UG") echo " selected";?>>Uganda</option>
	<option value="UA"<? if($country =="UA") echo " selected";?>>Ukraine</option>
	<option value="UY"<? if($country =="UY") echo " selected";?>>Uruguay</option>
	<option value="UZ"<? if($country =="UZ") echo " selected";?>>Uzbekistan</option>
	<option value="VU"<? if($country =="VU") echo " selected";?>>Vanuatu (New Hebrides)</option>
	<option value="VA"<? if($country =="VA") echo " selected";?>>Vatican City State (Holy See)</option>
	<option value="VE"<? if($country =="VE") echo " selected";?>>Venezuela</option>
	<option value="VN"<? if($country =="VN") echo " selected";?>>Viet Nam</option>
	<option value="VG"<? if($country =="VG") echo " selected";?>>Virgin (British) Islands</option>
	<option value="YE"<? if($country =="YE") echo " selected";?>>Yemen</option>
	<option value="YU"<? if($country =="YU") echo " selected";?>>Yugoslavia</option>
	<option value="ZM"<? if($country =="ZM") echo " selected";?>>Zambia</option>
	<option value="ZW"<? if($country =="ZW") echo " selected";?>>Zimbabwe</option>
	<?
}
function addSeat($_draft_id)
{
	$objResponse = new xajaxResponse();
	mysql_query("UPDATE md_draft SET max_players = max_players + 1 WHERE max_players < 8 AND draft_status = 0 AND pk_draft_id = $_draft_id");
	$drafterlist = drafterList($_draft_id);
	$objResponse->assign("drafterlist","innerHTML",$drafterlist);
	return $objResponse;
}
function removeSeat($_draft_id)
{
	$objResponse = new xajaxResponse();
	#how many are in the draft?
	$players = mysql_num_rows(mysql_query("SELECT fk_user_id FROM md_draft2user WHERE fk_draft_id = $_draft_id"));
	mysql_query("UPDATE md_draft SET max_players = max_players - 1 WHERE max_players > $players AND draft_status = 0 AND pk_draft_id = $_draft_id");

	$draft_info = mysql_fetch_array(mysql_query("SELECT * FROM md_draft
		WHERE pk_draft_id = $_draft_id"));
	$drafterlist = drafterList($_draft_id);
	$objResponse->assign("drafterlist","innerHTML",$drafterlist);
	if($players == $draft_info["max_players"])
	{
		$objResponse->redirect("draft.php?id=".$_draft_id);
	}
	return $objResponse;
}
function start_draft($draft_id)
{
	//starta draften
	mysql_query("UPDATE md_draft SET present_pack = 1, draft_status = 1, draft_start = NOW() WHERE pk_draft_id = $draft_id");
	//hämta packnamn
	$pack_names_result = mysql_query("SELECT exp_name FROM md_exp, md_draft WHERE pk_draft_id = $draft_id AND pack_1 = pk_exp_id UNION ALL SELECT exp_name FROM md_exp, md_draft WHERE pk_draft_id = $draft_id AND pack_2 = pk_exp_id  UNION ALL SELECT exp_name FROM md_exp, md_draft WHERE pk_draft_id = $draft_id AND pack_3 = pk_exp_id ");
	for($x=1; $x<=3; $x++) {
		$pack_names_values = mysql_fetch_array($pack_names_result);
		$pack[$x] = $pack_names_values[exp_name];
	}
	
	$players_result = mysql_query("SELECT fk_user_id FROM md_draft2user WHERE fk_draft_id = $draft_id ORDER BY RAND()");
	$x = 1;
	//genomgår spelarlistan och sammanställer query med seating. skapar packs
	while($players = mysql_fetch_array($players_result)) {
		mysql_query("REPLACE INTO md_draft2user(fk_draft_id, fk_user_id, seat_number) VALUES($draft_id, $players[fk_user_id], ".$x.")");
		mysql_query("INSERT INTO md_basicland (fk_user_id, fk_draft_id) VALUES ($players[fk_user_id], $draft_id)");
		add_pack($pack[1],$draft_id, 1, $players[fk_user_id], $x, "draft");
		add_pack($pack[2],$draft_id, 2, $players[fk_user_id], $x, "draft");
		add_pack($pack[3],$draft_id, 3, $players[fk_user_id], $x++, "draft");
	}
	
	//skicka usern till nu startar-draften-om-n√•gra-sekunder-sidan
	//√§r man den sista som joinade? skicka till nu startar draften sidan
	//sista spelarens status måste vara confirmed direkt
}

function printDraftPicks($draft_id, $_sort_order = "time")
{
	if($_sort_order == "color") $sort_order = "find_in_set(card_color, 'L,A,G,R,B,U,W') DESC, card_cmcost DESC";
	elseif($_sort_order == "cost") $sort_order = "card_cmcost";
	elseif($_sort_order == "cardtype") $sort_order = "card_toughness DESC, card_type";
	elseif($_sort_order == "time") $sort_order = "fk_pack_id DESC, pick_number DESC";
	$_SESSION["sort_order"] = $_sort_order;
		
	include_once("../include/kortparm_functions.php");
	$cards = mysql_query($apa = "SELECT card_name, '' as version, fk_card_id, COUNT(fk_card_id) AS nmb, exp_name, packcard_is_foil, IF(CHAR_LENGTH(card_color) > 1,'Multi',card_color) AS card_color, pk_packcard_id, card_rarity, card_cmcost, IF(card_type LIKE 'Creature%','Creature',card_type) AS card_type, card_toughness
		FROM md_packcard, md_pack,md_cards 
		INNER JOIN md_exp ON md_cards.fk_exp_id = pk_exp_id
		WHERE fk_card_id = pk_card_id AND fk_pack_id = pk_pack_id AND md_packcard.fk_user_id = $_SESSION[md_userid] AND md_pack.fk_draft_id = $draft_id
		GROUP BY fk_card_id
		ORDER BY $sort_order");
		
	$nmb_deck = mysql_num_rows($cards);

	$x = 0;
	$output = "<h2>My picks</h2>";
	$output .= "<span class=\"text\" id=\"deckstats\">".deckstats($draft_id)."</span>";
	
	$output .= "<p class=\"mini\">Order by <span class=\"orange pointer\" onclick=\"javascript:setXYposition('indicator',event); javascript:orderDeck('picks','".$draft_id."','time',true);\">time</span> | <span class=\"orange pointer\" onclick=\"javascript:setXYposition('indicator',event); javascript:orderDeck('picks','".$draft_id."','color',true);\">color</span> | <span class=\"orange pointer\" onclick=\"javascript:setXYposition('indicator',event); javascript:orderDeck('picks','".$draft_id."','cost',true);\">cost</span> | <span class=\"orange pointer\" onclick=\"javascript:setXYposition('indicator',event); javascript:orderDeck('picks','".$draft_id."','cardtype',true);\">type</span></p><br />";
	
	while($card = mysql_fetch_array($cards))
	{
		if($_sort_order == "cardtype")
		{
			if(strpos($card[card_type], "Creature") > -1 || $card[card_toughness] > "") { $cardtype = "Creature"; }
			elseif(strpos($card[card_type], "Enchantment") > -1) { $cardtype = "Enchantment"; }
			elseif(strpos($card[card_type], "Artifact") > -1) { $cardtype = "Artifact"; }
			elseif(strpos($card[card_type], "Sorcery") > -1) { $cardtype = "Sorcery"; }
			elseif(strpos($card[card_type], "Instant") > -1 || strrpos($card[card_type], "Interrupt") > -1) { $cardtype = "Instant"; }
			elseif(strpos($card[card_type], "Land") > -1) { $cardtype = "Land"; }
		}
		$bildexp = eregi_replace(' ',"", stripslashes($card[exp_name]));
		$bildexp = eregi_replace("'","", strtolower($bildexp));
		
		if($_sort_order == "cost" && $lastcmcost != $card["card_cmcost"])
		{
			$output .= "<div class=\"breaker grey mini\">".$card["card_cmcost"]."cc</div>";
		}
		elseif($_sort_order == "color" && $lastcolor != $card["card_color"])
		{
			$output .= "<div class=\"breaker grey mini\">".colortag2word($card["card_color"])."</div>";
		}
		elseif($_sort_order == "cardtype" && !strstr($card["card_type"],$lastcardtype))
		{
			$output .= "<div class=\"breaker grey mini\">".$card["card_type"]."</div>";
		}
		$mouseover = "onmouseover=\"viewCard('http://www.svenskamagic.com/kortbilder/".$bildexp."/".cardname2filename($card[card_name],$card[version])."',event);\"
			onclick=\"javascript:increaseZindex('card".$card[pk_packcard_id]."');\"";
	$output .= '<div alt="'.stripslashes($card[card_name]).'" class="mini card" style="" id="card'.$card[pk_packcard_id].'" '.$mouseover.'>'; 
	
	if($card[packcard_is_foil]) $output .= '<div class="foil" '.$mouseover.'></div>';
	if($card[nmb] > 1) $output .= "<div class=\"cardcount\">".$card[nmb]."</div>";
	
	$output .= '<img src="http://www.svenskamagic.com/kortbilder/'.$bildexp.'/'.cardname2filename($card[card_name],$card[version]).'"'; 
	
	if(!$card[packcard_is_foil]) $output .= " ".$mouseover; 
	
	$output .= 'alt="'.stripslashes($card[card_name]).'" class="mini cardpic" /></div>';
	$lastcolor = $card[card_color];
	$lastcmcost = $card[card_cmcost];
	$lastcardtype = $cardtype;
	}
	$output .="<div class=\"breaker\"></div>";
	return $output;
}
function rating2stars($_rating)
{
	$points = 0;
	if($_rating >= 2000) $points = 10;
	elseif($_rating >= 1900) $points = 9;
	elseif($_rating >= 1800) $points = 8;
	elseif($_rating >= 1700) $points = 7;
	elseif($_rating >= 1600) $points = 6;
	elseif($_rating >= 1550) $points = 5;
	elseif($_rating >= 1500) $points = 4;
	elseif($_rating >= 1450) $points = 3;
	elseif($_rating >= 1400) $points = 2;
	elseif($_rating >= 1350) $points = 1;
	$nmb_stars = $points/2;
	
	for ($i=0; $i < floor($nmb_stars); $i++) { 
		$output .= "<img src=\"../images/star.png\" alt=\"\" />";
	}
	if($points == 0)
	{
		$output = "<img src=\"../images/skull.png\" alt=\"\" />";
	}
	if(strlen($nmb_stars) > 1) $half_star = "<img src=\"../images/star_half.png\" alt=\"\" />"; else $half_star = "";
	$output .= $half_star;
	return $output;
}
function is_odd($number) {
   return $number & 1; // 0 = even, 1 = odd
}
function colortag2word($tag)
{
	$tag = strtoupper($tag);
	if($tag == "B") $output = "Black";
	elseif($tag == "MULTI") $output = "Multicolored";
	elseif($tag == "U") $output = "Blue";
	elseif($tag == "G") $output = "Green";
	elseif($tag == "W") $output = "White";
	elseif($tag == "R") $output = "Red";
	elseif($tag == "L") $output = "Land";
	elseif($tag == "A") $output = "Artifact";
	return $output;
}
function filterColors($_draft_id, $_color, $_part, $remove_it = true)
{
	global $_SESSION;
	$objResponse = new xajaxResponse();
	if($remove_it == "false") {array_push($_SESSION["color_array"], $_color); array_unique($_SESSION["color_array"]);}
	elseif($remove_it == "true" && array_search($_color,$_SESSION["color_array"]) !== false) 
	{
		$key = array_search($_color,$_SESSION["color_array"]);
		unset($_SESSION["color_array"][$key]);
	}
	$output = printdeck($_draft_id, $_part, false, false);
	$objResponse->assign("sideboard","innerHTML",$output);
	return $objResponse;
}
function color2class($color_set)
{
	if(count($color_set) > 1) return "gold";
	else
	{
		$color = $color_set[0];
		if($color == "G") return "green";
		elseif($color == "W") return "silver";
		elseif($color == "U") return "blue";
		elseif($color == "R") return "red";
		elseif($color == "B") return "black";
		elseif($color == "L") return "land";
		elseif($color == "A") return "artifact";
		else return "black";
	}
}
?>