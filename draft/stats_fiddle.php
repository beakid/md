<?
//antal kort i packet - kortet man vŠljer i slutet bšr pŒverka mkt lite
//man skickar in en klump med kort-id o ratings, inkl ett pick - antalet pŒverkar k-vŠrdet, likasŒ bšr userns rating gšra
//lagar vi flera olika ratings eller bara en? tveksamt men jag tror nej till att bšrja med
//hur viktar vi userns rating? som kvoten mot 1600? det dubbla? +12,5% resp +25% fšr en spelare med 1800
//jag tror pŒ minimal pŒverkan av 1600-users... 
//ska varje pick gŒ in i statsen eller slumpat t.ex. 10%? fšr att minska last
//1800-1550 -> 250
//1600-1550 -> 50
//1600 -> 50/100 -> x0,5
//1800 -> 250/100 -> x2,5
//2100 -> 550/100 ->x5,5 

$player_rating = 1800;

//$getting_pick_card_id = mysql_fetch_array(mysql_query("SELECT fk_card_id FROM md_packcard WHERE pk_packcard_id = $chosen_card LIMIT 1"));
//$pick_id = $getting_pick_card_id[fk_card_id];

$cardarray = array(1=>1555,2=>1784,3=>1545);
$pick_id = 2;
$pick = $cardarray[$pick_id];


//plocka ut picket frŒn alla kort
unset($cardarray[$pick_id]);

$x = count($cardarray);
$query = "REPLACE into md_stats(fk_card_id, rating, stats_date) VALUES";

foreach ($cardarray as $fk_card_id => $trash) {

		$k_value = $x*($player_rating-1550)*0.005;

		$pick_rating_change=round($k_value*(1-(1/(pow(10, (($trash-$pick) / 400)) + 1))));
		$total_rating_change = $total_rating_change + $pick_rating_change;
		$query = $query."($fk_card_id, $trash-$pick_rating_change,NOW()),";
		
}
$query = $query."($pick_id, $pick+$total_rating_change,NOW())";
echo $query;
//mysql_query($query);



?>