<?
include("generatepack.php");
include("session_mysql.php");
include("functions.php");
include("include/kortparm_functions.php");

if($_POST["action"] == "draft_pack_of_the_day")
{
	if(!$_POST["chosen_card"])
	{
		echo errormess("Choose a card");
	}
	else
	{
		mysql_query("INSERT INTO md_vote (fk_card_id, fk_pack_id, vote_ip, fk_user_id) VALUES ('$_POST[chosen_card]','$_POST[pack_id]','$_SERVER[REMOTE_ADDR]','$_SESSION[md_userid]')");
	}
}
?>
<?=printHeader("MagicDraft","home")?>
	<div id="content">
		<div id="left">
			<? if(!$_SESSION[md_userid]) {?>
			<div class="box olive" id="welcome_box">
				<img src="<?=$path;?>/images/header_welcome.png" alt="Welcome!" class="headerpic" />
				<p class="text">Can you make the right picks? Improve your skills in drafting Magic the Gathering here with us!
					Start ripping those packs right now.
				<br /><br />
				<img src="<?=$path;?>/images/user_add.png" class="avatar" alt="" /> <a href="register/">Register for free!</a>
				</p>
			</div><? } ?>


			<div class="box orange">
				<img src="<?=$path;?>/images/header_opendrafts.png" alt="Open drafts!" class="headerpic" />

				<?
				$drafts = mysql_query("SELECT * FROM md_draft 
				WHERE draft_status = '0' ORDER BY pk_draft_id DESC LIMIT 0,3");
				while($draft = mysql_fetch_array($drafts))
				{
					$xet++;
					$players = mysql_query("SELECT * FROM md_draft2user 
					WHERE fk_draft_id = $draft[pk_draft_id]");
				?>
					<div class="draftcue">
						<p class="packs"><img src="<?=$path;?>/images/pack_<?=$draft[pack_1];?>.jpg" alt=""/><img src="<?=$path;?>/images/pack_<?=$draft[pack_2];?>.jpg" alt=""/><img src="<?=$path;?>/images/pack_<?=$draft[pack_3];?>.jpg" alt=""/></p>
						<div class="text"><h2><?=$draft[draft_name];?></h2>
						<span class="nmb_players"><?=mysql_num_rows($players)."/".$draft[max_players];?> players</span>

						<br /><span class="grey"><? if(!$draft[draft_is_tournament]) {?>Only draft<? } else {?>Draft + Tournament<? }?></span>
						<? if($draft[draft_password]) { ?><img src="<?=$path;?>/images/lock.png" alt="" /> <span class="red">Private</span><? } ?>
						</div>
					</div>
					<div class="breaker"></div>
				<?
				}
				?><br />
				<img src="<?=$path;?>/images/minidraft.png" class="avatar" alt="" /> <a class="text" href="draft/">View all drafts</a>
				
			</div>
		</div>
		<div id="middle">
			<?
			$latest_pack_id = mysql_result(mysql_query("SELECT pk_pack_id FROM md_pack WHERE pack_type = 'firstpage' ORDER BY pk_pack_id DESC LIMIT 0,1"),0);
			if($_SESSION["md_userid"]) $chosen_card = @mysql_result(mysql_query("SELECT fk_card_id FROM md_vote WHERE fk_pack_id = $latest_pack_id AND fk_user_id = '$_SESSION[md_userid]'"),0);
			else $chosen_card = @mysql_result(mysql_query("SELECT fk_card_id FROM md_vote WHERE fk_pack_id = $latest_pack_id AND vote_ip = '$_SERVER[REMOTE_ADDR]'"),0);
			?>
			<h1><span class="orange">Pack of the day</span> <? if($chosen_card) { ?>Thanks for your pick!<? } else { ?>What would you pick?<? } ?></h1>
			
			<form action="index.php" method="post">
			<input type="hidden" name="action" value="draft_pack_of_the_day">
			<div id="pack">
				<?
				$cards = mysql_query($apan = "SELECT card_name AS name, fk_card_id, exp_name AS exp, packcard_is_foil, pk_packcard_id FROM md_packcard 
					INNER JOIN md_cards ON fk_card_id = pk_card_id 
					INNER JOIN md_exp ON fk_exp_id = pk_exp_id 
					WHERE fk_pack_id = $latest_pack_id
					ORDER BY packcard_is_foil DESC, card_rarity = 'R' DESC, card_rarity = 'U' DESC, card_rarity = 'C' DESC");
#					echo $apan;
				$x = 0;
				while($card = mysql_fetch_array($cards))
				{
					if($card["fk_card_id"] == $chosen_card) $i_picked_pack_card_id = $card["pk_packcard_id"];
					if(!$bildexp)
					{
						$bildexp = eregi_replace(' ',"", stripslashes($card[exp]));
						$bildexp = eregi_replace("'","", strtolower($bildexp));
						$firstcard_src = "http://www.svenskamagic.com/kortbilder/".$bildexp."/".cardname2filename($card[name],$card[version]);
					}
					$x++;
					$mouseover = "onmouseover=\"viewCard('http://www.svenskamagic.com/kortbilder/".$bildexp."/".cardname2filename($card[name],$card[version])."');\"";
					if(!$chosen_card) $mouseover .= " onclick=\"javascript:selectCard('card".$card[pk_packcard_id]."', '".$card[fk_card_id]."'); javascript:increaseZindex('card".$card[pk_packcard_id]."');\""
				?>
				<div class="card shadow" style="z-index: <?=$x;?>;" id="card<?=$card[pk_packcard_id];?>"><? if($card[packcard_is_foil]) {?><div class="foil" <?=$mouseover;?>></div><? } ?><img id="cardimg_<?=$x;?>" src="http://www.svenskamagic.com/kortbilder/<?=$bildexp;?>/<?=cardname2filename($card[name],$card[version]);?>"<? if(!$card[packcard_is_foil]) echo " ".$mouseover;?> alt="<?=stripslashes($card[name]);?>" class="cardpic" />
				</div>
					<script type="text/javascript">
					var highest_zindex = 150;
					new Draggable('card<?=$card[pk_packcard_id];?>',{starteffect: false, endeffect: false});
					</script>
				<?
					if($x == 5 or $x == 10 or $x == 15)
					{
					?>
						<div class="breaker"></div>
					<?
					}
				}
				if($chosen_card)
				{
					?>
					<script type="text/javascript" charset="utf-8">
						selectCard('card<?=$i_picked_pack_card_id;?>', '<?=$chosen_card;?>');
					</script>
					<?
				}
				?>
			</div>
			<? if(!$chosen_card) { ?>
			<div id="pickbutton">
				<input type="image" src="<?=$path;?>/images/button_pick.png" alt="Pick!" value="submit" />
			</div>
			<? } ?>
			<input type="hidden" name="chosen_card" value="" id="chosen_card" />
			<input type="hidden" name="pack_id" value="<?=$latest_pack_id;?>" />
			<input type="hidden" name="exp" value="<?=$_REQUEST[exp];?>" />
			</form>
			<div class="breaker"></div>
		</div>
		<div id="right">
			<span class="small" id="show_cardviewer"><img src="<?=$path;?>/images/zoom.png" alt="" style="vertical-align: middle;"> <span class="blue pointer" onclick="toggleCardViewer('show');">Show cardviewer</span></span>
			<div id="cardviewer" class="mini"><div onclick="toggleCardViewer('hide');" id="closecard"><img src="<?=$path;?>/images/close.png"></div><div id="cardcloseup"><img src="<?=$firstcard_src;?>"></div></div>
			
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
			<? if($chosen_card)
			{
					$info = mysql_fetch_array(mysql_query("SELECT card_name, count(fk_card_id) AS nmb, card_color FROM md_vote
					INNER JOIN md_cards ON fk_card_id = pk_card_id
					WHERE fk_pack_id = '$latest_pack_id' AND pk_card_id = '$chosen_card'
					GROUP BY fk_card_id"));
					$nmb_voters = mysql_num_rows(mysql_query("SELECT * FROM md_vote WHERE fk_pack_id = '$latest_pack_id'"));
					$picks = mysql_query("SELECT card_name, count(fk_card_id) AS nmb, card_color, exp_name FROM md_vote
					INNER JOIN md_cards ON fk_card_id = pk_card_id
					INNER JOIN md_exp ON fk_exp_id = pk_exp_id
					WHERE fk_pack_id = '$latest_pack_id'
					GROUP BY fk_card_id
					ORDER BY nmb DESC");
			?>
				<div id="pick_result">
					<h2>You picked<br />
					<span class="<?=color2class($info[card_color]);?>"><?=$info["card_name"]?></span><br />
					<span class="grey"><? if($info[nmb] == 1) { ?>You were the first to pick that card!<? } elseif($nmb_voters > 1) { ?><?=round(($info[nmb]/$nmb_voters)*100)?>% chose
					the same card<? } else { ?>You were the first to pick today! :)<? } ?></span></h2>
					<ol class="small">
						<? while($pick = mysql_fetch_array($picks))
						{
							$bildexp = eregi_replace(' ',"", stripslashes($pick[exp_name]));
							$bildexp = eregi_replace("'","", strtolower($bildexp));
							
							?>
							<li><span onmouseover="viewCard('http://www.svenskamagic.com/kortbilder/<?=$bildexp;?>/<?=cardname2filename($pick[card_name],$pick[version]);?>')" class="pointer <?=color2class($pick[card_color]);?>"><?=$pick[card_name];?></span>
							    <br /><span class="grey"><?=round($pick[nmb]/$nmb_voters*100)?>% (<?=$pick[nmb];?>)</span></li>
							<?
						}
						?>
					</ol>
					</p>
					<? if(!$_SESSION["md_userid"]) {?>
					<p class="grey small">Not you who picked this card? Login to pick your own card!</p><? } ?>
				</div>
			<?
			}?>
			<div class="breaker"></div>
		</div>
		<div class="breaker"></div>
	</div>
</body>
</html>
<?
#add_pack("Eventide",0,0,0,0,"firstpage");
?>