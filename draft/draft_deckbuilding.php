		<div id="middle">
			<h1><span class="orange">Build your deck</span></h1>
			
			<form action="draft.php" method="post">
			<input type="hidden" name="action" value="build_deck">
			<br />
			<div id="deck">
				<?=printDeck($draft_id,"deck",$_SESSION["sort_order"]);?>
			</div>
			</form>
			
		</div>
		<?
		$first_card = mysql_fetch_array(mysql_query($apa = "SELECT card_name AS name, '' AS version, exp_name AS exp
		FROM md_packcard, md_pack, md_cards, md_exp
		WHERE fk_card_id = pk_card_id AND fk_pack_id = pk_pack_id AND md_cards.fk_exp_id = pk_exp_id
		AND md_packcard.fk_user_id = $_SESSION[md_userid] AND md_pack.fk_draft_id = $draft_id AND in_deck = '0'
		LIMIT 0,1"));
	
		$bildexp = eregi_replace(' ',"", stripslashes($first_card[exp]));
		$bildexp = eregi_replace("'","", strtolower($bildexp));
		$firstcard_src = "http://www.svenskamagic.com/kortbilder/".$bildexp."/".cardname2filename($first_card[name],$first_card[version]);
		?>
		<div id="right" style="width: 350px;">
			<div style="float: right;" class="small" id="show_cardviewer"><img src="<?=$path;?>/images/zoom.png" alt="" style="vertical-align: middle;"> <span class="text blue pointer" onclick="toggleCardViewer('show');">Show cardviewer</span></div>
			
			<div id="cardviewer" class="mini"><div onclick="toggleCardViewer('hide');" id="closecard"><img src="<?=$path;?>/images/close.png"></div><div id="cardcloseup"><img src="<?=$firstcard_src;?>"></div></div>
		<br />
		<br />
		<br />

		<div id="sideboard">
			<?=printDeck($draft_id,"sideboard",$_SESSION["sort_order"])?>
		</div>
			
		<script type="text/javascript">
		if(readCookie("cardposition_top")) {
			document.getElementById('cardviewer').style.top = readCookie("cardposition_top");
		}
		if(readCookie("cardposition_left")) {
			document.getElementById('cardviewer').style.left = readCookie("cardposition_left");
		}
		new Draggable('cardviewer',{onEnd: function (dragObj, event) {
			createCookie("cardposition_top",document.getElementById('cardviewer').style.top,"7");
			createCookie("cardposition_left",document.getElementById('cardviewer').style.left,"7"); }
		});
		if(readCookie("hidecard")) {
			document.getElementById('show_cardviewer').style.display = "inline"; }
		else { 
			viewCard('<?=$firstcard_src;?>'); }

		Droppables.add('deck', {
			accept: 'sideboard_card',
			onDrop: function(element) 
			{ addCard2Deck(element.id, 'deck','<?=$draft_id;?>'); }});
		Droppables.add('sideboard', {
			accept: 'deck_card',
			onDrop: function(element) 
			{ addCard2Deck(element.id, 'sideboard','<?=$draft_id;?>'); }});
		</script>
	 
		
		</div>
