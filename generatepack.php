<?php
function add_pack($exp,$draft_id, $pack_number, $user_id, $seat_number, $pack_type) {
global $_REQUEST;
if(!intval($exp)) $exp = mysql_result(mysql_query("SELECT pk_exp_id FROM md_exp WHERE exp_name = '".addslashes($exp)."'"),0);

	//create packrow in md_pack and generate pk_pack_id
	mysql_query($apa = "INSERT INTO md_pack(fk_draft_id, fk_exp_id, pack_number, fk_user_id, seat_number, pack_type) SELECT '$draft_id', pk_exp_id, '$pack_number', '$user_id', '$seat_number', '$pack_type' FROM md_exp WHERE pk_exp_id = '$exp'");
#	echo $apa;
	$pk_pack_id = mysql_insert_id();

	//foilpack or not?
	//foil
	if(rand(1,5) == 5) {
		//reg cards
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) 
		(SELECT '$pk_pack_id', pk_card_id FROM md_cards
			WHERE fk_exp_id = '$exp' AND card_rarity = 'R' ORDER BY rand() LIMIT 1) UNION ALL 
		(SELECT '$pk_pack_id', pk_card_id FROM md_cards
			 WHERE fk_exp_id = '$exp' AND card_rarity = 'U' ORDER BY rand() LIMIT 3)");
		
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND card_color = 'B' AND card_type NOT LIKE 'Basic Land%' ORDER BY rand() LIMIT 1");
		$cq_result = mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = ".mysql_insert_id());
		$cq_values = mysql_fetch_array($cq_result);
		$c1 = $cq_values[fk_card_id];
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND card_color = 'R' AND card_type NOT LIKE 'Basic Land%' ORDER BY rand() LIMIT 1");
		$cq_result = mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = ".mysql_insert_id());
		$cq_values = mysql_fetch_array($cq_result);
		$c2 = $cq_values[fk_card_id];
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND card_color = 'U' AND card_type NOT LIKE 'Basic Land%' ORDER BY rand() LIMIT 1");
		$cq_result = mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = ".mysql_insert_id());
		$cq_values = mysql_fetch_array($cq_result);
		$c3 = $cq_values[fk_card_id];
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND card_color = 'W' AND card_type NOT LIKE 'Basic Land%' ORDER BY rand() LIMIT 1");
		$cq_result = mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = ".mysql_insert_id());
		$cq_values = mysql_fetch_array($cq_result);
		$c4 = $cq_values[fk_card_id];
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND card_color = 'G' AND card_type NOT LIKE 'Basic Land%' ORDER BY rand() LIMIT 1");
		$cq_result = mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = ".mysql_insert_id());
		$cq_values = mysql_fetch_array($cq_result);
		$c5 = $cq_values[fk_card_id];

		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT DISTINCT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND card_type NOT LIKE 'Basic Land%' AND pk_card_id != $c1 AND pk_card_id != $c2 AND pk_card_id != $c3 AND pk_card_id != $c4 AND pk_card_id != $c5 ORDER BY rand() LIMIT 5");
		
		//foil
		$foil = rand(1,15);
		if($foil == 1) {
			mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id, packcard_is_foil) SELECT '$pk_pack_id', pk_card_id, '1' FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'R' ORDER BY rand() LIMIT 1");
		} elseif($foil > 1 && $foil < 5) {
			mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id, packcard_is_foil) SELECT '$pk_pack_id', pk_card_id, '1' FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'U' ORDER BY rand() LIMIT 1");		
		} elseif($foil > 4) { 
			mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id, packcard_is_foil) SELECT '$pk_pack_id', pk_card_id, '1' FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' ORDER BY rand() LIMIT 1");
		}
		
	//usual pack
	} else {
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) 
		(SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'R' ORDER BY rand() LIMIT 1) UNION ALL 
		(SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'U' ORDER BY rand() LIMIT 3)");
		
		mysql_query($apa = "INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND FIND_IN_SET('B',card_color) AND card_type NOT LIKE 'Basic Land%' ORDER BY rand() LIMIT 1");
		$cq_result = mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = ".mysql_insert_id());
		$cq_values = mysql_fetch_array($cq_result);
		$c1 = $cq_values[fk_card_id];
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND FIND_IN_SET('R',card_color) AND card_type NOT LIKE 'Basic Land%' ORDER BY rand() LIMIT 1");
		$cq_result = mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = ".mysql_insert_id());
		$cq_values = mysql_fetch_array($cq_result);
		$c2 = $cq_values[fk_card_id];
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND FIND_IN_SET('U',card_color) AND card_type NOT LIKE 'Basic Land%' ORDER BY rand() LIMIT 1");
		$cq_result = mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = ".mysql_insert_id());
		$cq_values = mysql_fetch_array($cq_result);
		$c3 = $cq_values[fk_card_id];
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND FIND_IN_SET('W',card_color) AND card_type NOT LIKE 'Basic Land%' ORDER BY rand() LIMIT 1");
		$cq_result = mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = ".mysql_insert_id());
		$cq_values = mysql_fetch_array($cq_result);
		$c4 = $cq_values[fk_card_id];
		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND FIND_IN_SET('G',card_color) AND card_type NOT LIKE 'Basic Land%' ORDER BY rand() LIMIT 1");
		$cq_result = mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = ".mysql_insert_id());
		$cq_values = mysql_fetch_array($cq_result);
		$c5 = $cq_values[fk_card_id];

		mysql_query("INSERT INTO md_packcard(fk_pack_id, fk_card_id) SELECT DISTINCT '$pk_pack_id', pk_card_id FROM md_cards WHERE fk_exp_id = '$exp' AND card_rarity = 'C' AND card_type NOT LIKE 'Basic Land%' AND pk_card_id != $c1 AND pk_card_id != $c2 AND pk_card_id != $c3 AND pk_card_id != $c4 AND pk_card_id != $c5 ORDER BY rand() LIMIT 6");
	}
	#om det är sealed, ge usern alla korten i packsen direkt
	if($pack_type == "sealed")
	{
		mysql_query("UPDATE md_packcard SET fk_user_id = $_SESSION[md_userid] WHERE fk_pack_id = $pk_pack_id");
	}
}

function add_tourpack($draft_id,$tourpack,$booster1,$booster2){
	add_pack($tourpack,$draft_id, "1", $_SESSION["md_userid"], 1, "sealed");
	add_pack($tourpack,$draft_id, "2", $_SESSION["md_userid"], 1, "sealed");
	add_pack($tourpack,$draft_id, "3", $_SESSION["md_userid"], 1, "sealed");
	add_pack($booster1,$draft_id, "4", $_SESSION["md_userid"], 1, "sealed");
	add_pack($booster2,$draft_id, "5", $_SESSION["md_userid"], 1, "sealed");
}
?>