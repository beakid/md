<? 
include("../session_mysql.php");
include("../functions.php");
include("draft_functions.php");

if($_SESSION[md_userid]) $my_current_draft_id = @mysql_result(mysql_query("SELECT fk_draft_id FROM md_draft2user 
	INNER JOIN md_draft ON pk_draft_id = fk_draft_id
	WHERE fk_user_id = $_SESSION[md_userid] AND draft_status < 3"),0);

//om man är i en startad draft, en där man bara ska konfirmera sin närvaro så ska man skickas till den
//$draft_id = @mysql_result(mysql_query("SELECT fk_draft_id FROM md_draft2user WHERE fk_user_id = $_SESSION[md_userid]"),0);
//if($draft_id)
//{
//	header("Location: draft.php?id".$draft_id); die();
//}


?>
<?=printHeader("MagicDraft - Draft","draft")?>
	<div id="content">
		<div id="left">
			<div class="box orange">
				<img src="<?=$path;?>/images/header_newdraft.png" class="headerpic" alt="New draft" />
				<? if(!$_SESSION["md_userid"])
				{
				?>
					<p class="text error">Please login in order to create and join drafts.</p>
				<?
				}
				else
				{
					if($my_current_draft_id) { ?><div class="text">You cannot create or join a draft while
					you're in another one.
					</div>
					<? }
					else
					{
					#default packs
					$pack1 = $pack2 = $pack3 = 87;
					?>
				<form action="index.php" method="post">
				<p class="text">
					<strong>How many players?</strong><br />
					<select name="max_players" size="1">
						<option value="2">2 players</option>
						<option value="3">3 players</option>
						<option value="4">4 players</option>
						<option value="5">5 players</option>
						<option value="6">6 players</option>
						<option value="7">7 players</option>
						<option value="8" selected>8 players</option>
					</select><br /><br />
					<strong>Pack #1</strong><br />
					<select name="pack1" size="1" style="width: 140px;">
						<? 
						$exps = mysql_query("SELECT * FROM md_exp ORDER BY exp_release DESC");
						while($exp = mysql_fetch_array($exps))
						{
							?>
							<option value="<?=$exp[pk_exp_id];?>"<? if($pack1 == $exp[pk_exp_id]) echo " selected";?>><?=$exp[exp_name];?></option>
						<? } ?>
					</select><br />
					<strong>Pack #2</strong><br />
					<select name="pack2" size="1" style="width: 140px;">
						<? 
						$exps = mysql_query("SELECT * FROM md_exp ORDER BY exp_release DESC");
						while($exp = mysql_fetch_array($exps))
						{ 
							?>
							<option value="<?=$exp[pk_exp_id];?>"<? if($pack2 == $exp[pk_exp_id]) echo " selected";?>><?=$exp[exp_name];?></option>
						<? } ?>
					</select><br />
					<strong>Pack #3</strong><br />
					<select name="pack3" size="1" style="width: 140px;">
						<? 
						$exps = mysql_query("SELECT * FROM md_exp ORDER BY exp_release DESC");
						while($exp = mysql_fetch_array($exps))
						{ 
							?>
							<option value="<?=$exp[pk_exp_id];?>"<? if($pack3 == $exp[pk_exp_id]) echo " selected";?>><?=$exp[exp_name];?></option>
						<? } ?>
					</select><br /><br />
					<div class="mini">
					<strong>Tournament after?</strong><br />
					<input type="radio" name="tour_after" value="0" checked="checked" /> No, just draft<br />
					<input type="radio" name="tour_after" value="1"/> Yes<br /><br />
					<strong>Show picks?</strong><br />
					<input type="radio" name="show_picks" value="1" checked="checked" /> Yes<br />
					<input type="radio" name="show_picks" value="0" /> Only between packs<br /><br />
					<strong>Speed?</strong><br />
					<input type="radio" name="speed" value="0" /> Slow<br />
					<input type="radio" name="speed" value="1" checked="checked" /> Normal<br />
					<input type="radio" name="speed" value="2" /> Fast<br /><br />
					<strong>Password (optional)</strong><br />
					<input type="text" name="password" size="16" />
					</div><br />
					<input type="hidden" name="action" value="create_draft">
					<input type="image" src="<?=$path;?>/images/button_create.png" alt="Create" value="submit" />
				</p>
			</form><? } } ?>
			</div>
		</div>
		<div id="middle">
				<h1>Open drafts</h1>
				
				<?
				$drafts = mysql_query("SELECT * FROM md_draft 
				WHERE draft_status = '0' ORDER BY pk_draft_id DESC");
				if(!mysql_num_rows($drafts)) echo "<br /><p class=\"text\">No open drafts right now. Why not start one?</p>";
				while($draft = mysql_fetch_array($drafts))
				{
					$players = mysql_query("SELECT user_name, user_country, fk_user_id FROM md_draft2user 
					INNER JOIN md_user ON pk_user_id = fk_user_id
					WHERE fk_draft_id = $draft[pk_draft_id]");
				?>
					<div class="draftcue">
						<div class="packs"><img src="<?=$path;?>/images/pack_<?=$draft[pack_1];?>.jpg" alt=""/><img src="<?=$path;?>/images/pack_<?=$draft[pack_2];?>.jpg" alt=""/><img src="<?=$path;?>/images/pack_<?=$draft[pack_3];?>.jpg" alt=""/></div>
						<div class="text"><h2><?=$draft[draft_name];?></h2>
						<span class="nmb_players"><?=mysql_num_rows($players)."/".$draft[max_players];?> players</span>
						<? 
						$i_have_joined = false;
						while($player = mysql_fetch_array($players))
						{
							if($player[fk_user_id] == $_SESSION[md_userid]) $i_have_joined = true;
						?>
							<span class="grey"><img class="avatar" src="<?=$path;?>/images/flags/<?=$player["user_country"];?>.png" /> <?=$player["user_name"];?></span>
						<?
						}
						?>
						<br /><span class="grey"><? if(!$draft[draft_is_tournament]) {?>Only draft<? } else {?>Draft + Tournament<? }?></span>
						<? if(!$i_have_joined && $_SESSION[md_userid] && !$my_current_draft_id) { ?><br /><a href="draft.php?action=join_draft&id=<?=$draft[pk_draft_id];?>" class="imglink"><img src="<?=$path;?>/images/button_joindraft.png" onmouseover="this.src='../images/button_joindraft_hover.png';" onmouseout="this.src='../images/button_joindraft.png';"  alt="Join!" /></a><? } ?>
						<? if($i_have_joined) { ?><br />
							<a href="draft.php?id=<?=$draft[pk_draft_id];?>" class="imglink"><img src="<?=$path;?>/images/button_goto.png" onmouseover="this.src='../images/button_goto_hover.png';" onmouseout="this.src='../images/button_goto.png';" alt="Go to!" /></a>
							<a href="draft.php?action=leave_draft&id=<?=$draft[pk_draft_id];?>" class="imglink"><img src="<?=$path;?>/images/button_leavedraft.png" onmouseover="this.src='../images/button_leavedraft_hover.png';" onmouseout="this.src='../images/button_leavedraft.png';" alt="Leave!" /></a><? } ?>		
						<? if($draft[draft_password]) { ?><img src="<?=$path;?>/images/lock.png" alt="" /> <span class="red">Private</span><? } ?>
						</div>
					</div>
					<div class="breaker"></div>
				<?
				}
				?>
				<br />


				<h2>Drafts running right now</h2>
				<?
				$drafts = mysql_query("SELECT *,
				CONCAT(IF(
							hour( timediff(draft_start, now()) ) > 0,
							CONCAT(hour( timediff(draft_start, now()) ),' hour'),
							''
						),
						IF(
							hour( timediff(draft_start, now()) ) > 1,
							's',
							''
						),
						' ',
						IF( (minute( timediff(draft_start, now()) ) > 0 OR hour( timediff(draft_start, now()) ) < 1) AND hour( timediff(draft_start, now()) ) > 0,
						' and ',
						''),
						IF(
							minute( timediff(draft_start, now()) ) > 0 OR hour( timediff(draft_start, now()) ) < 1,
							CONCAT(minute( timediff(draft_start, now()) ),' minute'),
							''
						),
						IF(
							minute( timediff(draft_start, now()) ) > 1 OR minute( timediff(draft_start, now()) ) = 0,
							's',
							''
						)
					)
				AS going_on_for
				FROM md_draft 
				WHERE draft_status != 0 AND draft_status < 3 ORDER BY pk_draft_id DESC");
				if(!mysql_num_rows($drafts)) echo "<br /><p class=\"text\">No drafts are running right now.</p>";
				while($draft = mysql_fetch_array($drafts))
				{
					$players = mysql_query("SELECT user_name, user_country, fk_user_id
					FROM md_draft2user
					INNER JOIN md_user ON pk_user_id = fk_user_id
					WHERE fk_draft_id = $draft[pk_draft_id]");
				?>
					<div class="draftcue">
						<div class="packs"><img src="<?=$path;?>/images/pack_<?=$draft[pack_1];?>.jpg" alt=""/><img src="<?=$path;?>/images/pack_<?=$draft[pack_2];?>.jpg" alt=""/><img src="<?=$path;?>/images/pack_<?=$draft[pack_3];?>.jpg" alt=""/></div>
						<div class="mini"><span class="text"><?=$draft[draft_name];?></span><br />
						
						<? 
						$i_have_joined = false;
						while($player = mysql_fetch_array($players))
						{
							if($player[fk_user_id] == $_SESSION[md_userid]) $i_have_joined = true;
						?>
							<span class="grey"><img class="avatar" src="<?=$path;?>/images/flags/<?=$player["user_country"];?>.png" /> <?=$player["user_name"];?></span>
						<?
						}
						?><br />
						<i class="grey">For <?=$draft["going_on_for"];?></i>
						<? if($i_have_joined) { ?><br />
							<a href="draft.php?id=<?=$draft[pk_draft_id];?>" class="imglink"><img src="<?=$path;?>/images/button_goto.png" onmouseover="this.src='../images/button_goto_hover.png';" onmouseout="this.src='../images/button_goto.png';" alt="Go to!" /></a>
						<? } ?>
						</div>
					</div>
					<div class="breaker"></div>
				<?
				}
				?>
				<br />
				
		</div>
		<div id="right_small">
			<? if($_SESSION[md_userid]) {?>
			<div class="box blue">
				<img src="<?=$path;?>/images/header_yourdrafts.png" class="headerpic" alt="Your drafts" />
				
				<?
				$drafts = mysql_query("SELECT *,
				CONCAT(IF(
							hour( timediff(draft_start, now()) ) > 0,
							CONCAT(hour( timediff(draft_start, now()) ),' hour'),
							''
						),
						IF(
							hour( timediff(draft_start, now()) ) > 1,
							's',
							''
						),
						' ',
						IF( (minute( timediff(draft_start, now()) ) > 0 OR hour( timediff(draft_start, now()) ) < 1) AND hour( timediff(draft_start, now()) ) > 0,
						' and ',
						''),
						IF(
							minute( timediff(draft_start, now()) ) > 0 OR hour( timediff(draft_start, now()) ) < 1,
							CONCAT(minute( timediff(draft_start, now()) ),' minute'),
							''
						),
						IF(
							minute( timediff(draft_start, now()) ) > 1 OR minute( timediff(draft_start, now()) ) = 0,
							's',
							''
						)
					)
				AS going_on_for
				FROM md_draft2user
				INNER JOIN md_draft ON fk_draft_id = pk_draft_id
				WHERE fk_user_id = $_SESSION[md_userid]
				ORDER BY pk_draft_id DESC");
				if(!mysql_num_rows($drafts)) echo "<br /><p class=\"text\">You haven't played any drafts yet.</p>";
				while($draft = mysql_fetch_array($drafts))
				{
					$players = mysql_query("SELECT user_name, user_country, fk_user_id FROM md_draft2user 
					INNER JOIN md_user ON pk_user_id = fk_user_id
					WHERE fk_draft_id = $draft[pk_draft_id]");
				?>
					<div class="yourdrafts">
						<div class="small">
							<img src="<?=$path;?>/images/<? if($draft[draft_is_sealed]) echo "tour"; ?>pack_<?=$draft[pack_1];?>.jpg" alt=""/><? if($draft[draft_is_sealed]) echo " ";?><img src="<?=$path;?>/images/pack_<?=$draft[pack_2];?>.jpg" alt=""/><img src="<?=$path;?>/images/pack_<?=$draft[pack_3];?>.jpg" alt=""/></div>
						<div class="text small"><h2><?=$draft[draft_name];?></h2>
							<? if($draft[draft_status] == 3) {?>
								<span class="gold inverted">Building deck!</span><? }
								elseif($draft[draft_status] != 0 && $draft[draft_status] < 3) {?>
									<span class="green inverted">Drafting!</span><? }
								elseif($draft[draft_status] == 0) {?>
									<span class="red inverted">Waiting to start...</span><? } ?>
								<? if(!$draft[draft_is_sealed]) {?><br />
						<span class="nmb_players"><?=mysql_num_rows($players);?> players</span><? } ?>
						<? if(trim($draft["going_on_for"]) != "") {?><br />
						<i class="white mini">Started <?=$draft["going_on_for"];?> ago</i><? } ?>
						
						<? if(!$draft[draft_is_sealed]) {?><br /><span class="grey mini"><? if(!$draft[draft_is_tournament]) {?>Only draft<? } else {?>Draft + Tournament<? }?></span><? } ?><br />
						<a href="draft.php?id=<?=$draft[pk_draft_id];?>" class="imglink"><img src="<?=$path;?>/images/button_goto.png" onmouseover="this.src='../images/button_goto_hover.png';" style="margin-left: -3px;" onmouseout="this.src='../images/button_goto.png';" alt="Go to!" /></a>
						<hr>
						
						</div>
					</div>
					<div class="breaker"></div>
				<?
				}
				?>
			</div>
			<? } ?>
			<div class="box olive">
				<img src="<?=$path;?>/images/header_sealed.png" alt="Sealed Deck" class="headerpic" />
				<p class="small">Just want to rip a Sealed Deck and make a deck?</p><br />
				<div class="text">
				<form action="index.php" method="post">
				<strong>Tournament pack</strong><br />
				<select name="tourpack" size="1" style="width: 140px;">
					<? 
					$exps = mysql_query("SELECT * FROM md_exp WHERE exp_has_tourpack = 1 ORDER BY exp_release DESC");
					while($exp = mysql_fetch_array($exps))
					{
						?>
						<option value="<?=$exp[pk_exp_id];?>"<? if($tourpack == $exp[pk_exp_id]) echo " selected";?>><?=$exp[exp_name];?></option>
					<? } ?>
				</select><br />
				<strong>Boosters</strong><br />
				<select name="boosterpack1" size="1" style="width: 140px;">
					<? 
					$exps = mysql_query("SELECT * FROM md_exp ORDER BY exp_release DESC");
					while($exp = mysql_fetch_array($exps))
					{
						?>
						<option value="<?=$exp[pk_exp_id];?>"<? if($boosterpack1 == $exp[pk_exp_id]) echo " selected";?>><?=$exp[exp_name];?></option>
					<? } ?>
				</select><br />
				<select name="boosterpack2" size="1" style="width: 140px;">
					<? 
					$exps = mysql_query("SELECT * FROM md_exp ORDER BY exp_release DESC");
					while($exp = mysql_fetch_array($exps))
					{
						?>
						<option value="<?=$exp[pk_exp_id];?>"<? if($boosterpack2 == $exp[pk_exp_id]) echo " selected";?>><?=$exp[exp_name];?></option>
					<? } ?>
				</select><br /><br />
				<input type="hidden" name="action" value="create_sealed">
				<input type="image" src="<?=$path;?>/images/button_create2.png" alt="Create" value="submit" />
				</form>
				</div>
			</div>
		</div>
		
		<div class="breaker"></div>
	</div>
</body>
</html>