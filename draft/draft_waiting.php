<div id="left">
	<div class="box orange text">
		<div id="drafting_header" alt="Drafting!" /></div>
		<div id="drafterlist">
			<?=drafterList($draft_id);?>
		</div>
		<?=draftPacks($draft_info);?>
			<form action="draft.php" method="post" name="leavedraft">
				<input type="hidden" name="action" value="leave_draft" />
				<input type="hidden" name="id" value="<?=$ID;?>" />
			<img onclick="javascript:if(confirm('Sure you want to leave this draft?')) document.leavedraft.submit();"  id="leavebutton" src="<?=$path;?>/images/button_leavedraft.png" onmouseover="this.src='../images/button_leavedraft_hover.png';" onmouseout="this.src='../images/button_leavedraft.png';" class="pointer" alt="Leave!" />
		</form>
	</div>
</div>
<div id="middle">
	<? if(!$_SESSION["md_userid"])
	{
		?>
		<h1>This draft is still open</h1>
		<h2><span class="orange">Login in order to join this draft!</span></h2>
		<?
	}
	elseif($mystatus === false)
	{
	?>
		<h1>This draft is still open</h1>
		<br />
		<h2><span class="orange">Want to join? <? if($my_current_draft_id) echo "Then please leave your <a href=\"?id=".$my_current_draft_id."\">current draft</a> first."; else echo "Click the button!";?></span></h2>
		<? if(!$my_current_draft_id) {?>
		<br /><a href="draft.php?action=join_draft&id=<?=$draft_id;?>" class="imglink"><img src="<?=$path;?>/images/button_joindraft.png" onmouseover="this.src='../images/button_joindraft_hover.png';" onmouseout="this.src='../images/button_joindraft.png';"  alt="Join!" /></a><? }?>
	<?
	}
	else
	{?>
	<h1><span class="orange">Waiting to start...</span></h1>
	<br />
	<h2>Meanwhile you wait - which is the best pick?</h2>

	<form action="draft.php?id=<?=$id;?>" method="post" name="voteform">
	<input type="hidden" name="action" value="vote_1_of_3">
	<div id="pack">
	<?
	#här ska vi slumpa fram 3 kort som vi vill testa användaren på

	#slumpar fram raritet och expansion typ
	if(rand(1,2) == 1) $rand_rarity = "U"; else $rand_rarity = "C";
	$exp_name = "Eventide";

	#skapar packet
	mysql_query("INSERT INTO md_pack(fk_draft_id, fk_exp_id, pack_number, fk_user_id, seat_number, pack_type) SELECT '', pk_exp_id, '1', '$_SESSION[md_userid]', '1', 'best_of_three' FROM md_exp WHERE exp_name = '$exp_name'");
	$pk_pack_id = mysql_insert_id();
	#sparar in 3 kort
	mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) 
	SELECT '$pk_pack_id', pk_card_id FROM md_cards INNER JOIN md_exp ON fk_exp_id = pk_exp_id WHERE exp_name = '$exp_name' AND card_rarity = '$rand_rarity' ORDER BY rand() LIMIT 3");
	#listar dem
	$list_them = mysql_query("SELECT * FROM md_packcard
	INNER JOIN md_cards ON fk_card_id = pk_card_id
	INNER JOIN md_exp ON fk_exp_id = pk_exp_id
	WHERE fk_pack_id = '$pk_pack_id'");
	$xet = 0;
	while($card = mysql_fetch_array($list_them))
	{
		$xet++;
		if(!$bildexp)
		{
			$bildexp = eregi_replace(' ',"", stripslashes($card[exp_name]));
			$bildexp = eregi_replace("'","", strtolower($bildexp));
			$firstcard_src = "http://www.svenskamagic.com/kortbilder/".$bildexp."/".cardname2filename($card[card_name],$card[card_version]);
		}
	?>
		<div class="card shadow" style="z-index: 1;" id="card<?=$xet;?>"><img id="cardimg_<?=$xet;?>" src="http://www.svenskamagic.com/kortbilder/<?=$bildexp;?>/<?=cardname2filename($card[card_name],$card[card_version]);?>" onmouseover="viewCard('http://www.svenskamagic.com/kortbilder/<?=$bildexp;?>/<?=cardname2filename($card[card_name],$card[card_version]);?>');"
				onclick="javascript:selectCard('card<?=$xet;?>', '<?=$card[pk_card_id];?>'); javascript:increaseZindex('card<?=$xet;?>');" alt="<?=$card[card_name];?>" class="cardpic" />
		</div>
	<?
	}
	?>
	</div>
	<div class="breaker"></div>
		<img src="<?=$path;?>/images/button_pick_grey.png" id="pickbutton" class="pointer" alt="Pick!" onclick="if($('chosen_card').value == '') alert('Select a card first!'); else {playSound('pick.wav'); document.voteform.submit();}" style="float:none; margin-top: 10px;"/>
	<input type="hidden" name="chosen_card" value="" id="chosen_card" />
	<input type="hidden" name="pack_id" value="<?=$pk_pack_id;?>" />
	</form>
	<?
	}
	?>
	
	
</div>
<div id="right">
	<span class="small" id="show_cardviewer"><img src="<?=$path;?>/images/zoom.png" alt="" style="vertical-align: middle;"> <span class="blue pointer" onclick="toggleCardViewer('show');">Show cardviewer</span></span>
	<div id="cardviewer" class="mini"><div onclick="toggleCardViewer('hide');" id="closecard"><img src="<?=$path;?>/images/close.png"></div><div id="cardcloseup"><img src="<?=$firstcard_src;?>"></div></div>
	<? if($_POST["action"] == "vote_1_of_3" && $_POST["chosen_card"])
	{
		?><br /><br />
			<? 
			$inthepack = mysql_query($snutt = "SELECT card_name, stats_rating, card_color, exp_name, pk_card_id FROM md_packcard
			LEFT OUTER JOIN md_stats ON md_stats.fk_card_id = md_packcard.fk_card_id
			INNER JOIN md_cards ON md_packcard.fk_card_id = pk_card_id
			INNER JOIN md_exp ON fk_exp_id = pk_exp_id
			WHERE fk_pack_id = '$_POST[pack_id]'
			ORDER BY stats_rating DESC");
			$xet = 0;
			while($pick = mysql_fetch_array($inthepack))
			{
				$xet++;
				if(!$bildexp)
				{
					$bildexp = eregi_replace(' ',"", stripslashes($pick[exp_name]));
					$bildexp = eregi_replace("'","", strtolower($bildexp));
				}

				if($pick[pk_card_id] == $_POST[chosen_card])
				{
					$kortnamn = "<span class=\"".color2class($pick[card_color])."\">".$pick["card_name"]."</span>";
					if($xet == 1) {$title = "Sweet!"; $grade = "We think you made the right pick since $kortnamn has higher points than the rest.";}
					elseif($xet == 2) {$title = "OK!"; $grade = "We think you made an alright pick since $kortnamn has the second best points of the three."; }
					elseif($xet == 3) {$title = "Ouch..."; $grade = "Nooo... bad choice. At least does the community think so since $kortnamn is rated as the worst card of the three."; }
				}
				
				$list_output .= "
				<li><span onmouseover=\"viewCard('http://www.svenskamagic.com/kortbilder/".$bildexp."/".cardname2filename($pick[card_name],$pick[version])."')\" class=\"pointer ".color2class($pick[card_color])."\">".$pick[card_name]."</span>
				    <br /><span class=\"grey\">".$pick[stats_rating]." points</span></li>";
			}
			?>
			<h1><?=$title;?></h1><br />
			<p class="text"><?=$grade;?></p>

			<ol class="small">
				<?=$list_output?>
			</ol>
		
	<? } ?>
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