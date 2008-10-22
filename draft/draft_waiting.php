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
	
	<form action="index.php" method="post">
	<input type="hidden" name="action" value="draft_pack_of_the_day">
	<div id="pack">
		<div class="card shadow" style="z-index: 1;" id="card1"><img id="cardimg_1" src="http://www.svenskamagic.com/kortbilder/eventide/merrowlevitator.full.jpg" onmouseover="viewCard('http://www.svenskamagic.com/kortbilder/eventide/merrowlevitator.full.jpg');"
				onclick="javascript:selectCard('card1', '21084'); javascript:increaseZindex('card1');" alt="Merrow Levitator" class="cardpic" />
		</div>
		<div class="card shadow" style="z-index: 1;" id="card2"><img id="cardimg_2" src="http://www.svenskamagic.com/kortbilder/eventide/oonasgrace.full.jpg" onmouseover="viewCard('http://www.svenskamagic.com/kortbilder/eventide/oonasgrace.full.jpg');"
				onclick="javascript:selectCard('card2', '21085'); javascript:increaseZindex('card2');" alt="Oona's Grace" class="cardpic" />
		</div>
		<div class="card shadow" style="z-index: 1;" id="card3"><img id="cardimg_3" src="http://www.svenskamagic.com/kortbilder/eventide/wildernesshypnotist.full.jpg" onmouseover="viewCard('http://www.svenskamagic.com/kortbilder/eventide/wildernesshypnotist.full.jpg');"
				onclick="javascript:selectCard('card3', '21090'); javascript:increaseZindex('card3');" alt="Wilderness Hypnotist" class="cardpic" />
		</div>
	</div>
	<div class="breaker"></div>
		<img src="<?=$path;?>/images/button_pick_grey.png" id="pickbutton" class="pointer" alt="Pick!" onclick="if($('chosen_card').value == '') alert('Select a card first!'); else {playSound('pick.wav'); document.draftform.submit();}" style="float:none; margin-top: 10px;"/>
	<input type="hidden" name="chosen_card" value="" id="chosen_card" />
	</form>
	<?
	}
	?>
	
	
</div>
<div id="right">
	<?
	$firstcard_src = "http://www.svenskamagic.com/kortbilder/eventide/merrowlevitator.full.jpg";
	?>
	<span class="small" id="show_cardviewer"><img src="<?=$path;?>/images/zoom.png" alt="" style="vertical-align: middle;"> <span class="blue pointer" onclick="toggleCardViewer('show');">Show cardviewer</span></span>
	<div id="cardviewer" class="mini"><div onclick="toggleCardViewer('hide');" id="closecard"><img src="<?=$path;?>/images/close.png"></div><div id="cardcloseup"><img src="<?=$firstcard_src;?>"></div></div>
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