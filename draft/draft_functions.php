<?
#denna fil har tillgång till $draft_info som är en select * from md_draft where pk_draft_id = $draft_id (ställs i draft.php);

include("../generatepack.php");
include_once("../functions.php");


if($_POST[action] == "create_draft") {
	//verify data - yawn


	//make draft_name
	$draft_name_result = mysql_query("SELECT exp_shortname FROM md_exp WHERE pk_exp_id = $pack1 UNION ALL SELECT exp_shortname FROM md_exp WHERE pk_exp_id = $pack2 UNION ALL SELECT exp_shortname FROM md_exp WHERE pk_exp_id = $pack3");
	while($draft_name_values = mysql_fetch_array($draft_name_result)) {
		$draft_name = $draft_name . " " . $draft_name_values[exp_shortname];
	ltrim($draft_name);
	
	
	//insert data for now
	mysql_query("INSERT INTO md_draft(draft_password, draft_is_tournament, draft_name, max_players, show_picks, draft_speed, pack_1, pack_2, pack_3) VALUES('$password', $tour_after, '$draft_name', $max_players, $show_picks, $speed, $pack1, $pack2, $pack3)");
	$draft_id = mysql_insert_id();
	
	//join player (as seat #1, "the Creator")
	mysql_query("INSERT INTO md_draft2user(fk_draft_id, fk_user_id, seat_number) VALUES(".$draft_id.", $_SESSION[md_userid], 1)");

	//ett welcome-chat-message från mdboten
	mysql_query("INSERT INTO md_chat (fk_draft_id, fk_user_id, chat_message, chat_date)
	VALUES ('$draft_id','1','Welcome to draft #$draft_id. Good luck and have fun!', NOW())");

	}
	header("Location: draft.php?id=".$draft_id);
	die();
}
elseif($_POST["action"] == "create_sealed")
{
		//insert data for now
		$sealed_name = mysql_result(mysql_query("SELECT exp_shortname FROM md_exp WHERE pk_exp_id = $tourpack"),0);
		mysql_query("INSERT INTO md_draft(draft_name, max_players, draft_status, pack_1, pack_2, pack_3, draft_start, draft_is_sealed) VALUES('Sealed $sealed_name', 1, 3, '$tourpack', '$boosterpack1', '$boosterpack2', NOW(), 1)");
		$draft_id = mysql_insert_id();

		//join player
		mysql_query("INSERT INTO md_basicland (fk_user_id, fk_draft_id) VALUES ($_SESSION[md_userid], $draft_id)");
		mysql_query("INSERT INTO md_draft2user(fk_draft_id, fk_user_id) VALUES('$draft_id', $_SESSION[md_userid])");
		add_tourpack($draft_id,$tourpack,$boosterpack1,$boosterpack2);	
		header("Location: draft.php?id=".$draft_id);
		die();
}

if($action == "join_draft") {
	//stoppa in spelaren i md_draft2user om man inte redan ≈†r med
	if(!mysql_num_rows(mysql_query("SELECT fk_user_id FROM md_draft2user WHERE fk_user_id = '$_SESSION[md_userid]' AND fk_draft_id = '$draft_id'")))
	{	
		$highestseatnumber = mysql_result(mysql_query("SELECT seat_number FROM md_draft2user ORDER BY seat_number DESC LIMIT 0,1"),0);
		mysql_query("INSERT INTO md_draft2user(fk_draft_id, fk_user_id, seat_number) VALUES($draft_id,$_SESSION[md_userid], '".($highestseatnumber+1)."')");
	}
}

if($action == "leave_draft") {
	#finns det inte några andra förutom mig själv (och ev. bots, id 1)
	if(!mysql_num_rows(mysql_query("SELECT * FROM md_draft2user WHERE fk_draft_id = $draft_id AND fk_user_id != '1' AND fk_user_id != $_SESSION[md_userid]"))) {
		mysql_query("DELETE FROM md_draft WHERE pk_draft_id = $draft_id");
		mysql_query("DELETE FROM md_chat WHERE fk_draft_id = $draft_id");
		mysql_query("DELETE FROM md_basicland WHERE fk_draft_id = $draft_id");
		$packs = mysql_query("SELECT * FROM md_pack WHERE fk_draft_id = $draft_id");
		while($pack = mysql_fetch_array($packs))
		{
			mysql_query("DELETE FROM md_packcard WHERE fk_pack_id = $pack[pk_pack_id]");
		}
		mysql_query("DELETE FROM md_pack WHERE fk_draft_id = $draft_id");
		mysql_query("DELETE FROM md_draft2user WHERE fk_draft_id = $draft_id AND fk_user_id = $_SESSION[md_userid]");
		header("Location: index.php");
		die();
	}

	$myseatnumber = @mysql_result(mysql_query("SELECT seat_number FROM md_draft2user WHERE fk_user_id = $_SESSION[md_userid] AND fk_draft_id = $draft_id"),0);
	#if draft is not running, decrease higher seats with 1
	if($draft_info["draft_status"] == 0)
	{
		mysql_query("UPDATE md_draft2user SET seat_number = seat_number - 1 WHERE seat_number > $myseatnumber AND fk_draft_id = $draft_id");
		mysql_query("DELETE FROM md_draft2user WHERE fk_draft_id = $draft_id AND fk_user_id = $_SESSION[md_userid]");
	}
	elseif($draft_info["draft_status"] == 1)
	{
		mysql_query("DELETE FROM md_draft2user WHERE fk_draft_id = $draft_id AND fk_user_id = $_SESSION[md_userid]");
		mysql_query("UPDATE md_draft SET draft_status = 0 WHERE pk_draft_id = $draft_id");
	}
	else
	#annars, i en aktiv draft, plockar vi en bot (ID 1)
	{
		mysql_query("UPDATE md_draft2user SET fk_user_id = 1 WHERE fk_user_id = $_SESSION[md_userid] AND fk_draft_id = $draft_id");
	}

	header("Location: index.php");
	die();
}

if($_POST["action"] == "draft") {
	//h√§mtar diverse info om draften o kontrollerar sƒ∫ man inte fuskar
	$pack_id = mysql_fetch_row(mysql_query("SELECT fk_pack_id, fk_card_id FROM md_packcard WHERE pk_packcard_id = $_POST[chosen_card] LIMIT 1"));
		//fundera ut vad fusk √§r och vad som ska kollas
		
	//draftar kortet man valt
	$pick_number = mysql_fetch_row(mysql_query("SELECT count(*) FROM md_packcard WHERE fk_user_id = 0 AND fk_pack_id = $pack_id[0] GROUP BY fk_pack_id"));
	mysql_query("UPDATE md_packcard SET fk_user_id = $_SESSION[md_userid], pick_number = 16 - $pick_number[0] WHERE pk_packcard_id = $_POST[chosen_card]");
	$_SESSION["draft_start"] = false;

	//här fixar vi med statsen - labbvarning
	//$ignore_rating true -> ignore... annars tom
	
	//antal kort i packet - kortet man väljer i slutet bör påverka mkt lite
	//man skickar in en klump med kort-id o ratings, inkl ett pick - antalet påverkar k-värdet, likaså bör userns rating göra
	//lagar vi flera olika ratings eller bara en? tveksamt men jag tror nej till att börja med
	//hur viktar vi userns rating? som kvoten mot 1600? det dubbla? +12,5% resp +25% för en spelare med 1800
	//jag tror på minimal påverkan av 1600-users... 
	//ska varje pick gå in i statsen eller slumpat t.ex. 10%? för att minska last
	//1800-1550 -> 250
	//1600-1550 -> 50
	//1600 -> 50/100 -> x0,5
	//1800 -> 250/100 -> x2,5
	//2100 -> 550/100 ->x5,5
	//allt detta måste gås igenom o viktas om - utan tvekan
	//lägg till analys av vad man pickat så att färger är en del i analysen
	//t.ex:
	//man har gått grönt -> när man pickar grönt - det gröna kortet går upp, de andra gröna går ner,artifakt går ner, gröna länder går ner(eller färglösa) MEN
	//allt som vi taggat som inte grönt påverkas bara ytterst lite(eller ens alls?)
	//omvänt, gick man grönt och plötsligt pickar ett blått kort påverkar det alla kort som vanligt. detta innebär sammantaget att ju längre in i draften man
	//kommer, ju färre kort påverkar man genom sina val om man håller sig i sin färg... hmm
	//grundvärde på kort kontro aktuellt värde för spelaren
	//dessutom tänker jag mig att hur långt man kommit in i ett pack reflekterar påverkar kvärdet. detta gäller också efterkommande pack men frågan är: ska man
	//börja helt från början eller ska man liksom gå i tre vågor nedåt i k-värdet där starten på varje pack är större än där man slutade förra packet men botten sen blir
	//lägre än förra packet osv...
	//hur fan väger alla dessa faktorer ihop... reflekterar det vad vi tror det gör...
	//allt pickande o räknande syftar att räkna ut ett basvärde på kortet - bäst info får man när spelaren inte har pickat en massa ELLER om den pickat en massa o plötsligt byter färg

	//beräkna spelarens pickpool
	//just nu siktar vi bara på färger man man kan tänka sig att väga in critters/etc oxå
	//rad1-5 = färgerna WUBRG
	//rad6 antal kort totalt
	$color_chk = mysql_query("SELECT count(*) as color FROM md_packcard, md_cards WHERE fk_user_id = $_SESSION[md_userid] AND fk_card_id = pk_card_id AND find_in_set('W',card_colortag)
	UNION ALL SELECT count(*) as color FROM md_packcard, md_cards WHERE fk_user_id = $_SESSION[md_userid] AND fk_card_id = pk_card_id AND find_in_set('U',card_colortag)
	UNION ALL SELECT count(*) as color FROM md_packcard, md_cards WHERE fk_user_id = $_SESSION[md_userid] AND fk_card_id = pk_card_id AND find_in_set('R',card_colortag)
	UNION ALL SELECT count(*) as color FROM md_packcard, md_cards WHERE fk_user_id = $_SESSION[md_userid] AND fk_card_id = pk_card_id AND find_in_set('B',card_colortag)
	UNION ALL SELECT count(*) as color FROM md_packcard, md_cards WHERE fk_user_id = $_SESSION[md_userid] AND fk_card_id = pk_card_id AND find_in_set('G',card_colortag)
	UNION ALL SELECT count(*) as color FROM md_packcard  WHERE fk_user_id = $_SESSION[md_userid]");
	for($x = 1; $x<=6; $x++) {
		$color_values = mysql_fetch_array($color_chk);
		$color[$x] = $color_values[color];
	}
	
	$pick_id = $pack_id[1];
	$pick = $cardarray[$pick_id];
	
	//plocka ut picket från alla kort
	unset($cardarray[$pick_id]);
	
	$x = count($cardarray);
	$query = "REPLACE into md_stats(fk_card_id, rating) VALUES";
	
	foreach ($cardarray as $fk_card_id => $trash) {
			$k_value = $x*($_SESSION[md_user_rating]-1550)*0.0025;
			$pick_rating_change=round($k_value*(1-(1/(pow(10, (($trash-$pick) / 400)) + 1))));
			$total_rating_change = $total_rating_change + $pick_rating_change;
			$query = $query."($fk_card_id, $trash-$pick_rating_change),";
	}
	$query = $query."($pick_id, $pick+$total_rating_change)";
	//echo $query;
	mysql_query($query);
	
	//labbvarning slut - inga mer stats



	//skickar packet till n√§sta spelare och g√∂mmer det tills alla spelare skickat sina packs
	//pack 2 g√•r andra h√•llet	
	if($draft_info[present_pack] == 2) {
		mysql_query("UPDATE md_pack SET pack_status = 1, seat_number = if(seat_number > 1, seat_number - 1, $draft_info[max_players]) WHERE pk_pack_id = $pack_id[0]");
	} else {
		mysql_query("UPDATE md_pack SET pack_status = 1, seat_number = if(seat_number < $draft_info[max_players], seat_number + 1, 1) WHERE pk_pack_id = $pack_id[0]");		
	}
	//uppdaterer status p√• spelaren - lite overkill men det funkar
	mysql_query("UPDATE md_draft2user SET seat_status = 3 WHERE fk_draft_id = $draft_id AND fk_user_id = $_SESSION[md_userid]");
	
	//har alla spelare pickat? visa d√• n√§sta stack, eventuellt n√§sta pack
	$packs_status = mysql_fetch_row(mysql_query("SELECT count(*) FROM md_pack, md_draft WHERE pack_status = 1 AND fk_draft_id = $draft_id AND md_draft.present_pack = md_pack.pack_number AND md_draft.pk_draft_id = md_pack.fk_draft_id"));
	if($packs_status[0] == $draft_info[max_players]) {
		//om man just tog 15e kortet och alla andra ox√• plockat sina kort: √∂ppna n√§sta pack, s√§tt nuvarande som f√§rdigt (status 2)
		if($pick_number[0] == 1) {
			mysql_query("UPDATE md_draft SET draft_status = IF(present_pack = 3, 3, 2), present_pack = IF(present_pack < 3, present_pack + 1, 0) WHERE pk_draft_id = $draft_id");
			mysql_query("UPDATE md_pack SET pack_status = 2 WHERE fk_draft_id = $draft_id AND pack_status = 1");
			//tog vi sista packet
		} else {
			mysql_query("UPDATE md_pack SET pack_status = 0 WHERE fk_draft_id = $draft_id AND pack_status = 1");
		}
		mysql_query("UPDATE md_draft2user SET seat_status = 2 WHERE fk_draft_id = $draft_id");
	}	
	header("Location: draft.php?id=".$draft_id);
	die();
}


#check som körs vid reload av ostartad draft
if($draft_info["draft_status"] == "0" && $_SESSION["md_userid"] && $draft_id)
{
	//kontrollera antalet spelare i draften - ≈†r vi max_players: slumpa ut seats, aktivera draften, skapa packs
	//man ska nog se till att st≈†da i md_draft2user ofta, typ vi kanske i samband med inlogg ska skilja p≈ö offline, online (men inte aktiv sista 5)
	//samt online(aktiv)

	//spelaren som joinar sist beh√∂ver obv inte konfirma att han √§r d√§r utan kommer bara till sidan "waiting for other players to confirm"
	$present_players = mysql_fetch_row(mysql_query("SELECT count(*) FROM md_draft2user WHERE fk_draft_id = $draft_id"));
	if($present_players[0] >= $draft_info["max_players"]) 
	{
		start_draft($draft_id);
		header("Location: draft.php?id=".$draft_id);
	}
}

?>