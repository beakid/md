<?
include("../session_mysql.php");
if($draft_id && $_SESSION[md_userid])
{
	#uppdatera att man nu är klar med draften -> seat_status = 4
	mysql_query("UPDATE md_draft2user SET seat_status = 4 WHERE fk_user_id = $_SESSION[md_userid] AND fk_draft_id = $draft_id");
	
	$lek_main = mysql_query($apa = "SELECT card_name AS cardname, exp_shortname AS shortname, COUNT(card_name) AS nmb FROM md_packcard
	INNER JOIN md_pack ON pk_pack_id = fk_pack_id
	LEFT OUTER JOIN md_exp ON fk_exp_id = pk_exp_id
	INNER JOIN md_cards ON pk_card_id = fk_card_id 
	WHERE fk_draft_id = '$draft_id' AND in_deck = 1 AND md_packcard.fk_user_id = $_SESSION[md_userid] AND exp_shortname != ''
	GROUP BY card_name");
	
	$output = "";
	while($main = mysql_fetch_array($lek_main))
	{
		$output .= "\r        ".$main[nmb]." [".$main[shortname]."] ".$main[cardname];
	}

	$basiclands = mysql_fetch_array(mysql_query("SELECT * FROM md_basicland WHERE fk_user_id = ".$_SESSION["md_userid"]." AND fk_draft_id = ".$draft_id));
	for ($i=0; $i < 5; $i++) 
	{ 
		if($i == 0) $land = "Island"; elseif($i == 1) $land = "Plains"; elseif($i == 2) $land = "Forest";
		elseif($i == 3) $land = "Mountain"; elseif($i == 4) $land = "Swamp";
		if($basiclands["nmb_".strtolower($land)] > 0) $output .= "\r        ".$basiclands["nmb_".strtolower($land)]." [10E] ".$land;
	}
	$lek_side = mysql_query("SELECT card_name AS cardname, exp_shortname, COUNT(card_name) AS nmb FROM md_packcard
	INNER JOIN md_pack ON pk_pack_id = fk_pack_id
	LEFT OUTER JOIN md_exp ON fk_exp_id = pk_exp_id
	INNER JOIN md_cards ON pk_card_id = fk_card_id 
	WHERE fk_draft_id = '$draft_id' AND in_deck = 0 AND md_packcard.fk_user_id = $_SESSION[md_userid] AND exp_shortname != ''
	GROUP BY card_name");
	if(mysql_num_rows($lek_side)) $output .= "\r\r// Sideboard:";
	while($side = mysql_fetch_array($lek_side))
	{
		$side[cardname] = eregi_replace(" // ","/",$side[cardname]);
		$output .= "\rSB: ".$side[nmb]." [".$side[shortname]."] ".$side[cardname];
	}

	if($output != "")
	{
		header('Content-type: application/mwdec');
		header('Content-Disposition: attachment; filename="draft_'.$draft_id.'_'.date("Hi").'.mwdeck"');
		echo $output;
	}
}
?>