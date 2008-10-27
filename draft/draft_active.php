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
				<img onclick="javascript:if(confirm('Sure you want to leave this draft? A bot will take your seat.')) document.leavedraft.submit();"  id="leavebutton" src="<?=$path;?>/images/button_leavedraft.png" onmouseover="this.src='../images/button_leavedraft_hover.png';" onmouseout="this.src='../images/button_leavedraft.png';" class="pointer" alt="Leave!" />
			</form>
			</div>
		</div>
		<div id="middle">
				<?
				$pack_id_result = mysql_query("SELECT a1.pk_pack_id, a3.exp_name FROM md_pack as a1, md_draft2user as a2, md_exp as a3 WHERE 
					a2.fk_draft_id = $draft_id aND a2.fk_user_id = $_SESSION[md_userid] AND 
					a2.seat_number = a1.seat_number AND a1.fk_draft_id = $draft_id AND 
					a1.pack_number = $draft_info[present_pack] AND a1.pack_status = 0
					AND a3.pk_exp_id = a1.fk_exp_id");
				$rows_returned = mysql_num_rows($pack_id_result);
				if($rows_returned > 0) {
					?>
					<img src="<?=$path;?>/images/button_pick_grey.png" id="pickbutton" class="pointer" alt="Pick!" onclick="if($('chosen_card').value == '') alert('Select a card first!'); else {draftCard();}" />
					<?
					$pack_info = mysql_fetch_row($pack_id_result);
					//denna måste skrivas om: cards ska väck, vi ska köra våra egna slipade tabeller
					$cards = mysql_query("SELECT md_cards.card_name AS name, card_colortag, '' AS version, md_packcard.fk_card_id, exp_name AS exp, packcard_is_foil, pk_packcard_id, card_rarity, stats_rating FROM md_packcard
						INNER JOIN md_cards ON md_packcard.fk_card_id = pk_card_id
						INNER JOIN md_exp ON fk_exp_id = pk_exp_id
						LEFT OUTER JOIN md_stats ON md_packcard.fk_card_id = md_stats.fk_card_id
						WHERE fk_pack_id = $pack_info[0] AND fk_user_id = 0
						ORDER BY packcard_is_foil DESC, card_rarity = 'R' DESC, card_rarity = 'U' DESC, card_rarity = 'C' DESC");
					?>
					<h1>#<?=$draft_info[present_pack];?> <?=$pack_info[1];?> <span class="orange"><?=numeric(16-mysql_num_rows($cards));?> pick</span></h1>

					<form action="draft.php" method="post" name="draftform">
					<input type="hidden" name="action" value="draft">
					<div id="pack">
					<?
				$x = 0;
				while($card = mysql_fetch_array($cards))
				{
					if(!$bildexp)
					{
						$bildexp = eregi_replace(' ',"", stripslashes($card[exp]));
						$bildexp = eregi_replace("'","", strtolower($bildexp));
						$firstcard_src = "../cardpics/".$bildexp."/".cardname2filename($card[name],$card[version]);
					}
					$x++;
					$mouseover = "onmouseover=\"viewCard('../cardpics/".$bildexp."/".cardname2filename($card[name],$card[version])."');\"
						onclick=\"javascript:selectCard('card".$card[pk_packcard_id]."', '".$card[pk_packcard_id]."'); javascript:increaseZindex('card".$card[pk_packcard_id]."');\" "
				?>
				<div class="card shadow" style="z-index: <?=$x;?>;" id="card<?=$card[pk_packcard_id];?>"><? if($card[packcard_is_foil]) {?><div class="foil" <?=$mouseover;?>></div><? } ?><img id="cardimg_<?=$x;?>" src="../cardpics/<?=$bildexp;?>/<?=cardname2filename($card[name],$card[version]);?>"<? if(!$card[packcard_is_foil]) echo " ".$mouseover;?> alt="<?=stripslashes($card[name]);?>" class="cardpic" />
				</div>
				<input type="hidden" name="cardarray[<?=$card[fk_card_id];?>]" value="<?=$card[stats_rating];?>" />
				<input type="hidden" name="colorarray[<?=$card[fk_card_id];?>]" value="<?=$card[colortag];?>" />
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
				?>
			</div>
			<div class="breaker"></div>
			<input type="hidden" name="chosen_card" value="" id="chosen_card" />
			<input type="hidden" name="ignore_rating" value="" id="ignore_rating" />
			</form>
			<?
			//om man inte fick ett pack redo
			} else {
				?>
					<div id="waiting"><h1>Waiting for other players...</h1></div>
				<?
			}
			?>
			<div class="breaker"></div>
		</div>
		<div id="right"><span class="small" id="show_cardviewer"><img src="<?=$path;?>/images/zoom.png" alt="" style="vertical-align: middle;"> <span class="blue pointer" onclick="toggleCardViewer('show');">Show cardviewer</span></span>
			<div id="cardviewer" class="mini"><div onclick="toggleCardViewer('hide');" id="closecard"><img src="<?=$path;?>/images/close.png"></div><div id="cardcloseup"><img src="<?=$firstcard_src;?>"></div></div>
			
			<?
			$cards_drafted = mysql_num_rows(mysql_query($apa = "SELECT fk_pack_id FROM md_packcard 
				INNER JOIN md_pack ON pk_pack_id = fk_pack_id
				WHERE md_packcard.fk_user_id = $_SESSION[md_userid] AND md_pack.fk_draft_id = $draft_id"));
			if($draft_info["show_picks"] == 1 || ($cards_drafted == 15 || $cards_drafted == 30))
			{
			?>
				<div class="roundbox grey">
				<div id="picks">
					<?=printDraftPicks($draft_id, $_SESSION["sort_order"])?>
					<div class="breaker"></div>
				</div>
				</div>
				<div class="roundbox grey bottom"></div>
			<? } ?>
			
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
		</div>