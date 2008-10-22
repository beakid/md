<? 
include("../session_mysql.php");
include("../functions.php");
include("../include/kortparm_functions.php");
$c = $_REQUEST["c"]; $e = $_REQUEST["e"]; $r = $_REQUEST["r"];
if(!$c) $c = "all";
if(!$r) $r = "all";
if(!$e) $e = "86";
$rarity["c"] = "commons"; $rarity["u"] = "uncommons"; $rarity["r"] = "rares"; $rarity["all"] = "cards";
$color["w"] = "White"; $color["u"] = "Blue"; $color["b"] = "Black"; $color["r"] = "Red"; $color["g"] = "Green"; $color["l"] = "Land"; $color["a"] = "Artifact";
$color["all"] = "All";
$exp_info = mysql_fetch_array(mysql_query("SELECT * FROM md_exp WHERE pk_exp_id = $e"));
?>
<?=printHeader("MagicDraft - Draft","stats")?>
	<div id="content">
		<div id="left" class="greyback">
			<div class="box greyback">
				<img src="<?=$path;?>/images/header_stats.png" class="headerpic" alt="Statistics" />
				<p class="text">Show stats for:</p>
				<select name="e" size="1" style="width: 140px;" onchange="location.href='?e='+this.value+'&amp;c=<?=$c;?>&amp;r=<?=$r;?>';">
					<? 
					$exps = mysql_query("SELECT * FROM md_exp ORDER BY exp_release DESC");
					while($exp = mysql_fetch_array($exps))
					{
						?>
						<option value="<?=$exp[pk_exp_id];?>"<? if($e == $exp[pk_exp_id]) echo " selected";?>><?=$exp[exp_name];?></option>
					<? } ?>
				</select><br />
				<p class="text">Colors:</p>
				<div class="stats_buttons">
					<a href="?e=<?=$e;?>&c=all&r=<?=$r;?>"><img src="../images/button_all.png"<? if($c=="all") echo " class=\"active\"";?> alt="" /></a>
					<a href="?e=<?=$e;?>&c=w&r=<?=$r;?>"><img src="../images/button_white.png"<? if($c=="w") echo " class=\"active\"";?> alt="" /></a>
					<a href="?e=<?=$e;?>&c=u&r=<?=$r;?>"><img src="../images/button_blue.png"<? if($c=="u") echo " class=\"active\"";?> alt="" /></a>
					<a href="?e=<?=$e;?>&c=b&r=<?=$r;?>"><img src="../images/button_black.png"<? if($c=="b") echo " class=\"active\"";?> alt="" /></a>
					<a href="?e=<?=$e;?>&c=r&r=<?=$r;?>"><img src="../images/button_red.png"<? if($c=="r") echo " class=\"active\"";?> alt="" /></a>
					<a href="?e=<?=$e;?>&c=g&r=<?=$r;?>"><img src="../images/button_green.png"<? if($c=="g") echo " class=\"active\"";?> alt="" /></a>
					<a href="?e=<?=$e;?>&c=a&r=<?=$r;?>"><img src="../images/button_artifacts.png"<? if($c=="a") echo " class=\"active\"";?> alt="" /></a>
					<a href="?e=<?=$e;?>&c=l&r=<?=$r;?>"><img src="../images/button_lands.png"<? if($c=="l") echo " class=\"active\"";?> alt="" /></a>
				</div>
				<p class="text">Rarity:</p>
				<div class="stats_buttons">
					<a href="?e=<?=$e;?>&c=<?=$c;?>&r=all"><img src="../images/button_all.png"<? if($r=="all") echo " class=\"active\"";?> alt="" /></a>
					<a href="?e=<?=$e;?>&c=<?=$c;?>&r=c"><img src="../images/button_common.png"<? if($r=="c") echo " class=\"active\"";?> alt="" /></a>
					<a href="?e=<?=$e;?>&c=<?=$c;?>&r=u"><img src="../images/button_uncommon.png"<? if($r=="u") echo " class=\"active\"";?> alt="" /></a>
					<a href="?e=<?=$e;?>&c=<?=$c;?>&r=r"><img src="../images/button_rare.png"<? if($r=="r") echo " class=\"active\"";?> alt="" /></a>
				</div>
			</div>
		</div>
		<div id="middle">

				<h1>Toplist <span class="orange"><?=$color[$c];?> <?=$rarity[$r];?>, <?=$exp_info["exp_name"];?></span></h1>
				<div class="cardstats text">
					<?
					$cards = mysql_query($query = "SELECT *, '' AS version FROM md_cards
					WHERE
					IF('$r'='all',true,card_rarity = '$r') AND IF('$c'='all',true,FIND_IN_SET('$c',card_color))
					AND fk_exp_id = '$e'
					ORDER BY card_current_rating DESC");
					$bildexp = eregi_replace(' ',"", stripslashes($exp_info[exp_name]));
					$bildexp = eregi_replace("'","", strtolower($bildexp));
					$xet = $col = 0;
					while($card = mysql_fetch_array($cards))
					{
						$xet++; $col++;
						$bildurl = "http://www.svenskamagic.com/kortbilder/".$bildexp."/".cardname2filename($card[card_name], $card[version]);
						if(!$firstcard_src) $firstcard_src = $bildurl;
						
					?>
					<div class="card shadow"><img src="<?=$bildurl;?>" onmouseover="viewCard('<?=$bildurl;?>');" alt="" class="cardpic" />
							<div class="starbox">
								<h3><?=$xet;?>. <?=$card[card_name]?></h3>
								<? if($card[card_current_rating]) {?>
								<?=rating2stars($card[card_current_rating]);?>
								<div class="rating"><?=$card[card_current_rating];?> p<br />
									55% 1st pick<br />
									33% 2nd pick<br />
									10% 3rd pick
								</div><? } ?>
							</div>
					</div>
					<?
					if($col == 5) { echo '<div class="breaker"></div>'; $col = 0; }
					}
					?>
				</div>
				<div class="breaker"></div>
		</div>
		<div id="right">
			<span class="small" id="show_cardviewer"><img src="<?=$path;?>/images/zoom.png" alt="" style="vertical-align: middle;"> <span class="blue pointer" onclick="toggleCardViewer('show');">Show cardviewer</span></span>
			<div id="cardviewer" class="mini"><div onclick="toggleCardViewer('hide');" id="closecard"><img src="<?=$path;?>/images/close.png"></div><div id="cardcloseup"><img src="<?=$firstcard_src;?>"></div></div>
			<div class="roundbox grey">
				<h2>Trends</h2><br />
				<div class="upcoming text">
					<ul>
						<li>+13p <a href="#" onmouseover="viewCard('/kortbilder/shadowmoor/silkbindfaerie.full.jpg');">Silkbind Faerie</a></li>
						<li>+12p <a href="#" onmouseover="viewCard('/kortbilder/shadowmoor/safeholdelite.full.jpg');">Safehold Elite</a></li>
						<li>+10p <a href="#" onmouseover="viewCard('/kortbilder/shadowmoor/elvishhexhunter.full.jpg');">Elvish Hexhunter</a></li>
					</ul>
				</div>
				<div class="downgoing text">
					<ul>
						<li>-67p <a href="#" onmouseover="viewCard('/kortbilder/shadowmoor/mineexcavation.full.jpg');">Mine Excavation</a></li>
						<li>-50p <a href="#" onmouseover="viewCard('/kortbilder/shadowmoor/goldenglowmoth.full.jpg');">Goldenglow Moth</a></li>
						<li>-48p <a href="#" onmouseover="viewCard('/kortbilder/shadowmoor/kithkinshielddare.full.jpg');">Kithkin Shielddare</a></li>
					</ul>
				</div>
			</div>
			<div class="roundbox grey bottom"></div>
			<div class="breaker"></div>
		</div>
		<div class="breaker"></div>
	</div>
	<script type="text/javascript">
	if(readCookie("cardposition_top"))
	{
		document.getElementById('cardviewer').style.top = readCookie("cardposition_top");
	}
	if(readCookie("cardposition_left"))
	{
		document.getElementById('cardviewer').style.left = readCookie("cardposition_left");
	}
	new Draggable('cardviewer',{onEnd: function (dragObj, event) 
	{
		createCookie("cardposition_top",document.getElementById('cardviewer').style.top,"7");
		createCookie("cardposition_left",document.getElementById('cardviewer').style.left,"7"); 
	}
	});
	if(readCookie("hidecard"))
	{
		document.getElementById('show_cardviewer').style.display = "inline";
	}
	</script>
	<div class="breaker"></div>
</body>
</html>