<?
//till för att låsa ute en viss ip
#if($REMOTE_ADDR == '127.0.0.1') die;
$base = "http://localhost/~beakid";

$SESS_DBH = "";
$SESS_LIFE = 3600;

if (!$SESS_DBH = @mysql_connect('localhost', 'root', '')) {
		echo "<center><img src=\"/Photo-102.jpg\" /><br />- Prrrrrrrrrrr.";
		echo "<br /><br />PS. - <script type=\"text/javascript\" src=\"http://rotation.affiliator.com/ad_rotation.php?id=2983&w=755\" /></script>
		</center>";
		die;
}		

if (! mysql_select_db('magicdraft', $SESS_DBH)) {
	echo "<li>Unable to select database magicdraft";
	die;
}

session_start();


function sess_open($save_path, $session_name) {
	global $SESS_DBH;



	return true;
}

function sess_close() {
	return true;
}

function sess_read($key) {
	global $SESS_DBH, $SESS_LIFE;

	$qry = "SELECT value FROM md_sessions WHERE sesskey = '$key'";
	$qid = @mysql_query($qry, $SESS_DBH);

	list($value) = @mysql_fetch_row($qid);
	return (string)$value;
}

function sess_write($key, $val) {
	global $SESS_DBH, $SESS_LIFE;

	$value = addslashes($val);

	if(strstr($val, "uid|s:"))
	{
		$uids = strpos($val, "uid|s:");
		$ffnutt = strpos($val, "\"", $uids + 6);
		$lfnutt = strpos($val, "\"", $ffnutt + 1);
		$uidl = $lfnutt - $ffnutt - 1;
		$suid = substr($val, $ffnutt + 1, $uidl);
		$expiry = time() + $SESS_LIFE;
	}
	else
	{
		$suid = 0;
		$expiry = time() + 300;
	}

	$qry = "INSERT INTO md_sessions VALUES ('$key', '$expiry', '$value', '$suid')";
	$qid = @mysql_query($qry, $SESS_DBH);

	if (!$qid) {
		$qry = "UPDATE md_sessions SET suid = $suid, expiry = $expiry, value = '$value' WHERE sesskey = '$key'";
		$qid = @mysql_query($qry, $SESS_DBH);
	}

	return $qid;
}

//när man loggar ut tas ligan_villspela bort oxå OCH form_keys, alltså antidubbelpostskyddet
function sess_destroy($key) {
	global $SESS_DBH;

	$qry = "DELETE * FROM md_sessions WHERE sesskey ='$key'";
	$qid = mysql_query($qry, $SESS_DBH);

	return $qid;
}

//städar numera även bort gc:ade ligan_villspela tillsammans med session_städningen - dock inte enbart för gamla ligan_villspela
function sess_gc($maxlifetime) {
	global $SESS_DBH;

	$qry = "DELETE * FROM md_sessions WHERE expiry < UNIX_TIMESTAMP()";
	$qid = mysql_query($qry, $SESS_DBH);

	return mysql_affected_rows($SESS_DBH);
}

session_set_save_handler(
	"sess_open",
	"sess_close",
	"sess_read",
	"sess_write",
	"sess_destroy",
	"sess_gc");
	

$robots = array("","Mozilla/5.0 (compatible; Yahoo! Slurp/3.0; http://help.yahoo.com/help/us/ysearch/slurp)","Hatena Antenna/0.5 (http://a.hatena.ne.jp/help)","WebAlta Crawler/2.0 (http://www.webalta.net/ru/about_webmaster.html) (Windows; U; Windows NT 5.1; ru-RU)","Gigabot/3.0 (http://www.gigablast.com/spider.html)","Apache/2.2.4 (FreeBSD) mod_ssl/2.2.4 OpenSSL/0.9.7e-p1 DAV/2 PHP/4.4.7 with Suhosin-Patch (internal dummy connection)","psbot/0.1 (+http://www.picsearch.com/bot.html)","MJ12bot/v1.0.7 (http://majestic12.co.uk/bot.php?+)","Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)","msnbot/0.9 (+http://search.msn.com/msnbot.htm)","msnbot/1.0 (+http://search.msn.com/msnbot.htm)","Googlebot/2.1 (+http://www.google.com/bot.html)","Mediapartners-Google/2.1","msnbot/0.3 (+http://search.msn.com/msnbot.htm)", "Apache/2.2.0 (FreeBSD) mod_ssl/2.2.0 OpenSSL/0.9.7e-p1 DAV/2 PHP/4.4.2 (internal dummy connection)","Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)","Gigabot/2.0/gigablast.com/spider.html","Hatena Antenna/0.4 (http://a.hatena.ne.jp/help)","Mozilla/5.0 (Twiceler-0.9 http://www.cuill.com/twiceler/robot.html)","Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)","msnbot-media/1.0 (+http://search.msn.com/msnbot.htm)","msnbot/1.1 (+http://search.msn.com/msnbot.htm)","Mozilla/5.0 (compatible; MJ12bot/v1.2.1; http://www.majestic12.co.uk/bot.php?+)","Mozilla/5.0 (X11; U; Linux x86_64; sv-SE; rv:1.9b5) Gecko/2008041515 Firefox/3.0b5");
session_set_cookie_params(0);
if(!in_array($_SERVER['HTTP_USER_AGENT'], $robots)) {
	$apa = $_SERVER['HTTP_USER_AGENT'];
	session_register("apa");
	session_register($REMOTE_ADDR);
}
if(!session_is_registered("uid")) unset($uid);