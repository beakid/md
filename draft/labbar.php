<?
$cardarray = array(1=>1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600);

//picka packet hur många gånger: 10
for($rounds = 1; $rounds < 101; $rounds++) {

//picka igenom ett helt pack
for($pick = 1; $pick < 16; $pick++) {
	$present_pick = $cardarray[$pick];
	unset($cardarray[$pick]);
	$k_value = round((16-$pick)/2);
	$total_rating_change = 0;
	foreach ($cardarray as $fk_card_id => $trash) {
		$pick_rating_change=round($k_value*(1-(1/(pow(10, (($trash-$present_pick) / 400)) + 1))));
		$cardarray[$fk_card_id] = $cardarray[$fk_card_id] - $pick_rating_change;
		if($rounds == 1 || $rounds == 10 || $rounds == 100 || $rounds == 999 || $rounds == 1000) {
		if($fk_card_id == 15) echo "K: $k_value ->".$pick_rating_change."\n"; }
		$total_rating_change = $total_rating_change + $pick_rating_change;
	}
	
	$backuparray[$pick] = $present_pick + $total_rating_change;
}
//pack genompickat
//fyller cardarray igen med de nya ratingarna
$cardarray = $backuparray;
if($rounds == 1 || $rounds == 10 || $rounds == 100 || $rounds == 999 || $rounds == 1000) { echo "round $rounds"; print_r($cardarray); }
}



?>	