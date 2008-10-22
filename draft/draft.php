<? 
include("../session_mysql.php");
include("../functions.php");
include("../include/kortparm_functions.php");
//man utgår från draft-id som kontrolleras mot usern och sen visar man var man är i draften eller visas kortpoolen för lekbygge ->
if($_SESSION[md_userid]) $my_current_draft_id = @mysql_result(mysql_query("SELECT fk_draft_id FROM md_draft2user 
	INNER JOIN md_draft ON pk_draft_id = fk_draft_id
	WHERE fk_user_id = $_SESSION[md_userid]"),0);

if(!$_GET[id])
{
	$draft_id = $my_current_draft_id;
}
else { $draft_id = $_GET[id]; }

if(!$draft_id && !$_GET[id])
{
	header("Location: index.php?"); die();
}

$draft_info = mysql_query("SELECT * FROM md_draft
	WHERE pk_draft_id = $draft_id");
if(!mysql_num_rows($draft_info))
{
	header("Location: index.php"); die();
} else $draft_info = mysql_fetch_array($draft_info);

include("draft_functions.php");

if($_SESSION[md_userid]) @$mystatus = mysql_result(mysql_query("SELECT seat_status FROM md_draft2user WHERE fk_user_id = $_SESSION[md_userid] AND fk_draft_id = $draft_id"),0);
#kolla om man har tillgång till draften
$allowed_id = $draft_id;
if($draft_info[draft_status] > 0 && $my_current_draft_id != $draft_id) { $allowed_id = false;}
?>
<?=printHeader("MagicDraft","draft",$allowed_id)?>
	<div id="content"<? if($draft_info[draft_status] == 1) echo " class=\"blackback\"";?>>
		<? 
		if($draft_info[draft_status] == 0) {
		include("draft_waiting.php");
			//visa vilka som är med, eventuell join-knapp för den user som inte är med, eventuell login combo join för den oinloggade
		} elseif($draft_info[draft_status] == 1 && $allowed_id) {
			include("draft_confirm.php");
		} elseif($draft_info[draft_status] == 2 && $allowed_id) {
			include("draft_active.php");
		} elseif($draft_info[draft_status] == 3 && $allowed_id) {
			include("draft_deckbuilding.php");
		}
		else echo "<div id=\"left\"></div><div id=\"middle\"><h1>You're not allowed in here.</h1>
		<br />
		<div class=\"text\"><img src=\"../images/arrow_left.png\" alt=\"\" class=\"avatar\" /> <a href=\"./\">Ok, thanks, bye.</a></div></div>";
		?>
		<div class="breaker"></div>
	</div>
</body>
</html>