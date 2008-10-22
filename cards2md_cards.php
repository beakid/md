<? 
include("../session_mysql.php");
$cards = mysql_query("SELECT * FROM cards WHERE exp = 'Shadowmoor' OR exp = 'Morningtide' OR exp = 'Lorwyn' OR exp = 'Eventide'");
while($card = mysql_fetch_array($cards))
{
	$colors = explode(",",$card["color"]);
	$tags = "";
	foreach($colors as $color)
	{
#		echo $color;
		if(substr($color,0,1) == "R") $tags .= "R,";
		if(substr($color,0,1) == "B") $tags .= "U,";
		if(substr($color,0,1) == "G") $tags .= "G,";
		if($color == "Vit") $tags .= "W,";
		if($color == "Svart") $tags .= "B,";
		if($color == "Land") $tags .= "L,";
		if($color == "Artefakt") $tags .= "A,";
	}
	$cmcost = $card[cmcost];
	$toughness = $card[toughness];
	$card_type = $card[cardtype];
	if($tags == "") $tags = ""; else $tags = substr($tags,0,-1);
	mysql_query($query = "UPDATE md_cards SET card_colortag = '".$tags."', card_rarity = '".strtoupper($card[rarity])."',
	card_cmcost = '$cmcost', card_toughness = '$toughness', card_type = '$card_type' WHERE card_name = '".addslashes($card["name"])."'");
	echo $query;
}
?>