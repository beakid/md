<?
if(!function_exists('cropImage'))
{
	function cropImage($nw, $nh, $source, $stype, $dest) {
 
    $size = getimagesize($source);
    $w = $size[0];
    $h = $size[1];
 
    switch($stype) {
        case 'gif':
        $simg = imagecreatefromgif($source);
        break;
        case 'jpg':
        $simg = imagecreatefromjpeg($source);
        break;
        case 'png':
        $simg = imagecreatefrompng($source);
        break;
    }
 
    $dimg = imagecreatetruecolor($nw, $nh);
 
    $wm = $w/$nw;
    $hm = $h/$nh;
 
    $h_height = $nh/2;
    $w_height = $nw/2;
 
    if($w> $h) {
 
        $adjusted_width = $w / $hm;
        $half_width = $adjusted_width / 2;
        $int_width = $half_width - $w_height;
 
        imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
 
    } elseif(($w <$h) || ($w == $h)) {
 
        $adjusted_height = $h / $wm;
        $half_height = $adjusted_height / 2;
        $int_height = $half_height - $h_height;
 
        imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
 
    } else {
        imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
    }
 
    imagejpeg($dimg,$dest,100);
}
}

if(!function_exists('cardname2filename'))
{
function cardname2filename($text, $ver) {
	$text = eregi_replace(" ", "", $text);
	if(strpos($text, "tunGrunt"))
	{
		$text = "jotungrunt";
	}
	elseif(strpos($text, "tunOwlKeeper"))
	{
		$text = "jotunowlkeeper";
	}
	$text = eregi_replace(":", "", $text);
	$text = eregi_replace("'", "", $text);
	$text = eregi_replace("\?", "", $text);
	$text = eregi_replace("\"", "", $text);
	$text = eregi_replace("/", "", $text);
	if($ver) $text=$text.$ver;
	$text = strtolower($text).".full.jpg";
	return $text;
}
}

if(!function_exists('cardid2filename'))
{
function cardid2filename($text) {
	$kort = mysql_fetch_array(mysql_query("SELECT name, exp, version FROM cards WHERE id = ".$text));
	$exp = eregi_replace(" ", "", $kort[exp]);
	$exp = eregi_replace("'", "", $exp);
	
	$text = strtolower($exp)."/".cardname2filename($kort[name], $kort[version]);
	return $text;
}
}

if(!function_exists('rest'))
{
function rest($n, $m){ 
	if ($m > $n) {
		$r = $m;
		$m = $n;
		$n = $r;
	}
	while($n > $m) $n = $n - $m;
	if ($n == $m) return 0; 
	else return $n;
}
}

if(!function_exists('contains'))
{
function contains($str1, $str2){ 
	$newstr = eregi_replace($str1, "", $str2);
	$n=1;
	if (strlen($str2)==strlen($newstr)) $n=0;
	return $n;
}
}

if(!function_exists('print_color_img'))
{
function print_color_img($color, $cost)
{
	if(strpos($cost,"{") !== false) { echo "<img src='/bilder/symboler/guild.gif' align='absmiddle'>"; }
	elseif(strpos($color,"Multi") !== false) { echo "<img src='/bilder/symboler/multi.gif' align='absmiddle'>"; }
	elseif(strpos($cost,'W') !== false) { echo "<img src='/bilder/symboler/white_mana.gif' align='absmiddle'>"; }
	elseif(strpos($cost,'U') !== false) { echo "<img src='/bilder/symboler/blue_mana.gif' align='absmiddle'>"; }
	elseif(strpos($cost,'B') !== false) { echo "<img src='/bilder/symboler/black_mana.gif' align='absmiddle'>"; }
	elseif(strpos($cost,'G') !== false) { echo "<img src='/bilder/symboler/green_mana.gif' align='absmiddle'>"; }
	elseif(strpos($cost,'R') !== false) { echo "<img src='/bilder/symboler/red_mana.gif' align='absmiddle'>"; }
	elseif(strpos($color,'Artefakt') !== false) { echo "<img src='/bilder/symboler/".$cost.".gif' align='absmiddle'>"; }
	elseif(strpos($color,'Land') !== false) { echo "<img src='/bilder/symboler/tap.gif' align='absmiddle'>"; }
	else { echo ""; }
}
}

if(!function_exists('showlegal'))
{
function showlegal($kort)
{
	global $langarray;
	include_once("langfunctions.php");
	$langarray = run_lang($_SERVER['DOCUMENT_ROOT'].'/kortparmen/cardcollection');
	
	if (substr($kort[cardtype], 0, 5) == "Basic" && substr($kort[name], 0, 4) != "Snow") $legalablock = $kort[name]." ".getTxt("is legal in all formats");
	else
	{
		$typ2leg = "<span class='text_gron'>".getTxt("Legal")."</span>";
		if ($kort[standard] == 'illegal') $typ2leg = "<span class='text_rod'>".getTxt("Not legal")."</span>";
		if ($kort[standard] == 'banned') $typ2leg = "<span class='text_rod'>".getTxt("Banned")."</span>";
		$extleg = "<span class='text_gron'>".getTxt("Legal")."</span>";
		if ($kort[extended] == 'illegal') $extleg = "<span class='text_rod'>".getTxt("Not legal")."</span>";
		if ($kort[extended] == 'banned') $extleg = "<span class='text_rod'>".getTxt("Banned")."</span>";
		$typ1leg = "<span class='text_gron'>".getTxt("Legal")."</span>";
		if ($kort[vintage] == 'illegal') $typ1leg = "<span class='text_rod'>".getTxt("Not legal")."</span>";
		if ($kort[vintage] == 'banned') $typ1leg = "<span class='text_rod'>".getTxt("Banned")."</span>";
		if ($kort[vintage] == 'restricted') $typ1leg = "<span class='text_gra'>".getTxt("Restricted")."</span>";
		$typ15leg = "<span class='text_gron'>".getTxt("Legal")."</span>";
		if ($kort[typ15] == 'illegal') $typ15leg = "<span class='text_rod'>".getTxt("Not legal")."</span>";
		if ($kort[typ15] == 'banned') $typ15leg = "<span class='text_rod'>".getTxt("Banned")."</span>";
		$block = explode(",", $kort[block]);
		$legalablock = $kort[name]." ".getTxt("is")." <span class='text_gron' style='text-transform: lowercase;'>".getTxt("Legal")."</span> ".getTxt("in these blocks").": ";
		foreach ($block as $a_block) $legalablock = $legalablock.$a_block.", ";
		$legalablock = substr($legalablock, 0, -2);  
		if (!$kort[block] && !$kort[blockban]) $legalablock = $kort[name]." ".getTxt("is not legal in any block").".";
		if ($kort[blockban]){
			if (!$kort[block]) $legalablock = ""; else $legalablock = $legalablock."<p>";
			$block = explode(",", $kort[blockban]);
			$legalablock = $legalablock.$kort[name]." ".getTxt("is")." <span class='text_rod' style='text-transform: lowercase;'>".getTxt("Banned")."</span> ".getTxt("in these blocks").": ";
			foreach ($block as $a_block) $legalablock = $legalablock.$a_block.", ";
			$legalablock = substr($legalablock, 0, -2);
		}
		if ($kort[exp] == 'Oversize Cards') {
			echo getTxt("Cards from Oversize Cards are not legal in any format");
		} elseif ($kort[color] == 'Token'){
			echo getTxt("You can play with tokens in any formats");
		} elseif ($kort[exp] == 'Vanguard'){
			echo getTxt("The Vanguard-characters are not legal in any format");
		} else {
			?>
			Standard: <?=$typ2leg;?><br>
			Extended: <?=$extleg;?><br>
			Vintage: <?=$typ1leg;?><br>
			Legacy: <?=$typ15leg;?>
			<?
		}
	}
	?>
	<p><?=$legalablock;?></p>
	<?
}
}

if(!function_exists('show_card_in_torget'))
{
function show_card_in_torget($cardid, $inpopup = false, $exp_kvittar = false)
{
	global $langarray;
	global $uid;
	include_once("langfunctions.php");
	$langarray = run_lang($_SERVER['DOCUMENT_ROOT'].'/kortparmen/cardcollection');
	
	$namnet = mysql_result(mysql_query("SELECT name FROM cards WHERE id = $cardid"),0);
	$checken = "kortid = '$cardid'";
	$checken_want = "(kortid = '$cardid' OR IF(exp_kvittar = 'on',cards.name ='".addslashes($namnet)."',NULL))";
	
	if($exp_kvittar) 
	{
		$checken = "cards.name = '".addslashes($namnet)."'";
	}
	if($inpopup) $blanklink = " target=\"_blank\""; else $blanklink = "";
		$text='';
		$query=mysql_query("SELECT username, medlemmar.id AS hansid, suid, SUM(antal) AS antal, MAX(expiry), foil, sign FROM medlemmar, torget_have 
		INNER JOIN cards ON kortid = cards.id
		LEFT OUTER JOIN sessions ON medlemmar.ID = sessions.suid 
		WHERE $checken AND uid = medlemmar.id AND dold != 'on' AND byte = '1' AND biz = '0' AND DATEDIFF(CURDATE( ) , lastlogin) < 30 
		AND FIND_IN_SET(SUBSTRING(language_settings,1,1), '$_SESSION[lang_string]') GROUP BY hansid ORDER BY lastlogin DESC");
		$antal=mysql_num_rows($query);
		?>
		<div class="box bla"><h1><?=getTxt("ON HAVE");?></h1>
		<div>
		<?
		if($antal) {
			while($res=mysql_fetch_array($query)){
				if($res[foil] == 'Ja')
				{
					$foil = "<img src=\"../bilder/hw_foil.gif\" align=\"absmiddle\"> ";
				} else $foil = "";
				if($res[sign] == 'Ja')
				{
					$sign = "<img src=\"../bilder/hw_signerad.gif\" align=\"absmiddle\"> ";
				} else $sign = "";
				if($res[suid]) $text .= "<b>";
				$text .= '<a href="../medlem/kort.php?ID='.$res[hansid].'"'.$blanklink.'>'.$res[username].'</a>';
				if($res[suid]) $text .= "</b>";
				$text .= ' '.$foil.$sign.'('.$res[antal].'), ';
			}
			echo substr($text, 0, -2);
		} 
		else echo '- '.getTxt("none").' -';
		?>
		</div>
		</div>
<!-- vi har kortet p&aring; v&aring;r want!!! -->
		
		<?
		$text='';
		$query=mysql_query("SELECT username, medlemmar.id AS hansid, suid, SUM(antal) AS antal, foil, MAX(expiry), sign FROM medlemmar, torget_want
		INNER JOIN cards ON kortid = cards.id
		LEFT OUTER JOIN sessions ON medlemmar.ID = sessions.suid
		WHERE $checken_want AND uid = medlemmar.id AND dold != 'on' AND (byte = '1' OR kopes = '1') AND biz = '0' AND DATEDIFF(CURDATE( ) , lastlogin) < 30 
		AND FIND_IN_SET(SUBSTRING(language_settings,1,1), '$_SESSION[lang_string]') GROUP BY hansid ORDER BY lastlogin DESC");
		$antal=mysql_num_rows($query);
		?>
		
		<div class="box gron"><h1><?=getTxt("ON WANT");?></h1>
		<div>
		<?
		if($antal) {
			while($res=mysql_fetch_array($query)){
				if($res[foil] == 'Ja')
				{
					$foil = "<img src=\"../bilder/hw_foil.gif\" align=\"absmiddle\"> ";
				} else $foil = "";
				if($res[sign] == 'Ja')
				{
					$sign = "<img src=\"../bilder/hw_signerad.gif\" align=\"absmiddle\"> ";
				} else $sign = "";
				if($res[suid]) $text .= "<b>";
				$text .= '<a href="../medlem/kort.php?ID='.$res[hansid].'&h_or_w=want"'.$blanklink.'>'.$res[username].'</a>';
				if($res[suid]) $text .= "</b>";
				$text .= ' '.$foil.$sign.'('.$res[antal].getTxt("st").'), ';
			}
			echo substr($text, 0, -2);
		} 
		else echo '- '.getTxt("none").' -';
		?>
		</div>
		</div>
<!-- Till salu  -->
			<?
			$text='';
			$query = mysql_query($kennet = "SELECT username, medlemmar.id AS hansid, torget_have.lang_id, IF(antal > 1,ROUND(pris/antal),pris) AS pris, MAX(expiry), SUM(antal) AS antal, foil, sign, suid, cards.name FROM medlemmar, torget_have 
			INNER JOIN cards ON kortid = cards.id
			LEFT OUTER JOIN sessions ON medlemmar.ID = sessions.suid
			LEFT OUTER JOIN torget_auctions ON torget_auctions.aID = torget_have.auktion
			WHERE $checken AND uid = medlemmar.id AND dold != 'on' AND biz = '0' AND saljes = '1' AND pris > 0 
			AND IF(auktion > 0,torget_auctions.status = 'active',true)
			AND currentbid != pris AND DATEDIFF(CURDATE( ) , lastlogin) < 30 
			AND FIND_IN_SET(SUBSTRING(language_settings,1,1), '$_SESSION[lang_string]') GROUP BY hansid ORDER BY pris");
#			if($uid == 1) echo $kennet;
			$antal=mysql_num_rows($query);
			?>
			<div class="box rod"><h1><?=getTxt("FOR SALE");?></h1><div>
			<?
			if($antal){
				while($res=mysql_fetch_array($query)){
				if($res[foil] == 'Ja')
				{
					$foil = "<img src=\"../bilder/hw_foil.gif\" align=\"absmiddle\"> ";
				} else $foil = "";
				if($res[sign] == 'Ja')
				{
					$sign = "<img src=\"../bilder/hw_signerad.gif\" align=\"absmiddle\"> ";
				} else $sign = "";
				if($res[suid]) $text .= "<b>";
				$text .= '<a href="../medlem/kort.php?ID='.$res[hansid].'&h_or_w=have&kortnamn='.$res[name].'&saljes=1"'.$blanklink.'>'.$res[username].'</a>';
				if($res[suid]) $text .= "</b>";
				$text .= ' '.$res[pris].valuta($res[lang_id],2).' '.$foil.$sign.'('.$res[antal].getTxt("st").')<br>';
				}
				echo substr($text, 0, -4);
			} else echo "- ".getTxt("none")." -";
			?>		
			</div></div>

<!-- K&ouml;pes  -->
			<div class="box gra"><h1><?=getTxt("WANTS TO BUY");?></h1><div>
			<?
			$text='';
			$query = mysql_query("SELECT username, SUM(antal) AS antal, lang_id, medlemmar.id AS hansid, IF(antal > 1,ROUND(pris/antal),pris) AS pris, MAX(expiry), foil, suid, cards.name FROM medlemmar, torget_want
			INNER JOIN cards ON kortid = cards.id
			LEFT OUTER JOIN sessions ON medlemmar.ID = sessions.suid
			WHERE $checken AND uid=medlemmar.id AND dold != 'on' AND biz = '0' AND kopes = '1' AND pris > 0 AND DATEDIFF(CURDATE( ) , lastlogin) < 30 
			AND FIND_IN_SET(SUBSTRING(language_settings,1,1), '$_SESSION[lang_string]') GROUP BY hansid ORDER BY pris DESC");
			if(mysql_num_rows($query)){
				while($res=mysql_fetch_array($query)){
					if($res[foil] == 'Ja')
					{
						$foil = "<img src=\"../bilder/hw_foil.gif\" align=\"absmiddle\"> ";
					} else $foil = "";
					if($res[sign] == 'Ja')
					{
						$sign = "<img src=\"../bilder/hw_signerad.gif\" align=\"absmiddle\"> ";
					} else $sign = "";
					if($res[suid]) $text .= "<b>";
					$text .= '<a href="../medlem/kort.php?ID='.$res[hansid].'&h_or_w=want&kortnamn='.$res[name].'&kopes=1"'.$blanklink.'>'.$res[username].'</a>';
					if($res[suid]) $text .= "</b>";
					$text .= ' '.$res[pris].valuta($res[lang_id],2).' '.$foil.$sign.'('.$res[antal].getTxt("st").')<br>';
				}
				echo substr($text, 0, -4);
			} else echo "- ".getTxt("none")." -";
			?>		
			</div></div>	

			<? inline_popup("click", "100", " <b class='text_gra brodtext'>".getTxt("Info about the lists")."</b>", getTxt("THE LISTS"), "../bilder/help_gray.gif", getTxt("Lists_info"), "gra", "layer1",""); ?>

	<div class="prickrad_x">&nbsp;</div>
		
<!-- S‰ljauktion  -->
			<?
			$query = mysql_query("SELECT COUNT(torget_auctions_autobid.ID) AS antal_bud, cards.name AS kortnamn, torget_have.lang_id, torget_auctions.topic, IF( enddate > now( ) , IF( HOUR( timediff( enddate, now( ) ) ) >24, concat( DATEDIFF( enddate, now( ) ) , ' ".getTxt("days left")."' ) , concat( HOUR( timediff( enddate, now( ) ) ) , 'h ', minute( timediff( enddate, now( ) ) ) , '".getTxt("min left")."' ) ) , '".getTxt("overtime")."' ) AS slutdatum, torget_auctions.aID, username, medlemmar.id, currentbid, auktion, foil, sign, count(torget_auctions.aID) AS antal FROM medlemmar, torget_have, torget_auctions
			INNER JOIN cards ON kortid = cards.id
			LEFT OUTER JOIN torget_auctions_autobid ON torget_auctions_autobid.hID = torget_have.hID
			WHERE $checken AND uid=medlemmar.id AND auktion > 0 AND currentbid != pris AND status = 'active' AND torget_auctions.aID = auktion 
			AND FIND_IN_SET(SUBSTRING(language_settings,1,1), '$_SESSION[lang_string]') GROUP BY torget_auctions.aID ORDER BY enddate");
			$antal=mysql_num_rows($query);
			?>
			<div class="box gron"><h1><?=getTxt("ON AUCTION");?> (<?=$antal;?>)</h1><div>
			<?
			if($antal){
				
				while($res=mysql_fetch_array($query)){
					$topic=stripslashes($res[topic]);
					if(strlen($topic) > 33) $topic=substr($topic, 0, 30).'...'; 
					if($res[foil] == 'Ja')
					{
						$foil = "<img src=\"../bilder/hw_foil.gif\" align=\"absmiddle\"> ";
					} else $foil = "";
					if($res[sign] == 'Ja')
					{
						$sign = "<img src=\"../bilder/hw_signerad.gif\" align=\"absmiddle\"> ";
					} else $sign = "";
					echo "- <a href='../torget/index.php?what=auktionen&ID=".$res[aID]."&action=sok&kortnamn=".$res[kortnamn]."&show_filterbox=1'.$blanklink.''>".$topic."</a> ".$res[currentbid].valuta($res[lang_id],2)." ".$foil." ".$sign." (".$res[antal].getTxt("st").", $res[antal_bud] bud), ".$res[slutdatum]."<br>";
				}
			} else echo "- ".getTxt("none")." -";
			?>		
			</div></div>
<?
}
function keywordhelp($text)
{
	global $uid;
	$keywords = array("flying","vigilance","deathtouch","reach","first strike","double strike","trample","phasing","wither","persist","madness","flashback","conspire","reinforce","kinship","evoke","protection","shroud","flash","fear","lifelink",
	"banding","flanking","indestructible","provoke","haste","morph","equip","suspend","bushido","ninjutsu","soulshift","regenerate","islandwalk","forestwalk","plainswalk","mountainwalk","swampwalk","absorb","affinity","amplify","aura swap","bloothirst",
	"champion", "channel","clash","convoke","cumulative upkeep","deathtouch","defender","delve","dredge","echo","entwine","epic","fading","fateseal","forecast","fortify","frenzy","graft","grandeur","gravestorm","haunt","hellbent","hideaway","horsemanship","imprint",
	"kicker","modular","offering","poisonous","prowl","radiance","rampage","recover","replicate","ripple","scry","shadow","splice","split second","storm","substance","sunburst","sweep","treshold","transfigure","transmute","vanishing",
	"swampcycling","islandcycling","plainscycling","forestcycling","mountaincycling","cycling");
	$divar = "";
	$ignore_cyling = false;
	foreach($keywords as $keyword)
	{
		if((($keyword == "cycling" && $ignore_cyling == false) || $keyword != "cycling") && strstr(strtolower($text),strtolower($keyword)))
		{
			$keywordet = $keyword;
			if(strstr($keyword,"walk")) $keywordet = "landwalk";
			if(strstr($keyword,"cycling") && $keyword != "cycling") { $ignore_cyling = true; $keywordet = "typecycling"; }
			$length_1 = strlen($text);
			$text = str_replace(" ".$keyword," <span onclick=\"toggle_showhide('rule_".$keyword."');\" style=\"border-bottom: 1px dotted #CAB386;\" class=\"jslink\">".$keyword."</span>",$text);
			$text = str_replace(ucfirst($keyword),"<span onclick=\"toggle_showhide('rule_".$keyword."');\" style=\"border-bottom: 1px dotted #cecece;\" class=\"jslink\">".ucfirst($keyword)."</span>",$text);
			if(strlen($text) > $length_1) $divar .= "<div style=\"display: none; background-color: #fff; z-index: 5; width: 200px; position: absolute;\" id=\"rule_".$keyword."\"><img src='../bilder/close.gif' class='jslink' style='margin-bottom: -10px;' onclick=\"toggle_showhide('rule_".$keyword."');\" border='0'><br />".str_replace("<br />","",svm2html("[rule]".$keywordet."[/rule]"))."</div>";
		}
	}
	return $text . $divar;
  	
}
}
?>