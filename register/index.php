<?
include("../session_mysql.php");
include("../functions.php");
$registered = false;
$activated = false;
if($_POST["action"] == "register_user")
{
	if(!$_POST["user_name"])
	{
		$error["user_name"] = errormess("Choose a username");
	}
	else
	{
		if(mysql_num_rows(mysql_query("SELECT user_name FROM md_user WHERE user_name = '$_POST[user_name]'")))
		{
			$error["user_name"] = errormess("Username already exists");
		}
	}
	if(!$_POST["user_city"])
	{
		$error["user_city"] = errormess("Enter your city");
	}
	if(!$_POST["user_email"])
	{
		$error["user_email"] = errormess("Enter your email");
	}
	elseif(!preg_match('/^[_a-z0-9-]+(?:\.[_a-z0-9-]+)*@(?:[-a-z0-9-]+\.)+(?:[a-z]{2,7})$/i', $_POST["user_email"]))
	{
		$error["user_email"] = errormess("Wrong email format");
	}
	if(!$_POST["user_password"])
	{
		$error["user_password"] = errormess("Choose a password");
	}
	elseif(!$_POST["user_password2"])
	{
		$error["user_password"] = errormess("Confirm your password");
	}
	elseif($_POST["user_password2"] != $_POST["user_password"])
	{
		$error["user_password"] = errormess("Passwords does not match");
	}
	if($_POST["user_dci"] && !preg_match ("/^[0-9]+$/", $_POST["user_dci"]))
	{
		$error["user_dci"] = errormess("Only numbers");
	}
	
	if(!count($error))
	{
		$code = "asdfijv".rand(1,100);
		mysql_query("INSERT INTO md_user (user_name, user_password, user_email, user_dci, user_country, user_city, user_code)
		VALUES ('".addslashes($_POST[user_name])."','$_POST[user_password]','$_POST[user_email]','$_POST[user_dci]','".strtolower($_POST[user_country])."','".addslashes($_POST[user_city])."','$code')");
		$id = mysql_insert_id();
		$message = "Welcome to MagicDraft!
		
Your account has been created. To be able to login and start drafting you first need to activate the account by clicking on this link:
$path/register/?activate=".$id."&code=".$code."

Welcome!";
		mail($_POST["user_email"], "MagicDraft - Your account has been created", $message, "From: MagicDraft <mail@magicdraft.net>\n"."Reply-To: mail@magicdraft.net\n"."X-Sender: mail@magicdraft.net\n"."Return-Path: mail@magicdraft.net");
		$registered = true;
	}
}
elseif($_GET["activate"] && $_GET["code"])
{
	mysql_query("UPDATE md_user SET user_active = 1 WHERE pk_user_id = '$_GET[username]' AND code = '$_GET[code]'");
	$activated = true;
}
?>
<?=printHeader("MagicDraft - Register","register")?>
	<div id="content">
		<div id="left">
			<div class="box olive welcome_box">
				<img src="<?=$path;?>/images/header_welcome.png" class="headerpic" alt="Welcome!" />
				<p class="text">Can you make the right picks? Improve your skills in drafting Magic the Gathering here with us!
					Start ripping those packs right now.
				</p>
			</div>
		</div>
		<div id="middle">
			<?
			if($activated)
			{
				?>
					<h1><span class="orange">Account activated!</span></h1>
					<p class="text"><br />Your account is now active. Login with your username and password in the right top corner.</p>
				<?
			}
			elseif($registered)
			{
				?>
					<h1><span class="orange">Congratulations!</span> Your account has been created</h1>
					<p class="text"><br />An email with an activation link has been sent to <?=$_POST["user_email"];?>.
						Click the link to start using MagicDraft.net. Welcome!</p>
				<?
			}
			else
			{
				if(!$_POST["user_country"]) $user_country = "BE"; else $user_country = $_POST["user_country"];
			?>
				<h1><span class="orange">Registration</span> Create your account</h1>
			
				<div id="registerform">
					<form action="index.php" method="post">
					<input type="hidden" name="action" value="register_user">
						<table>
							<tr>
								<td>Username</td>
								<td><input type="text" class="textfield" name="user_name" value="<?=$user_name;?>" /><?=$error["user_name"];?></td>
							</tr>
							<tr>
								<td>Email</td>
								<td><input type="text" class="textfield" name="user_email" value="<?=$user_email;?>" /><?=$error["user_email"];?></td>
							</tr>
							<tr>
								<td>Password</td>
								<td><input type="password" class="textfield" name="user_password" value="<?=$user_password;?>" /><?=$error["user_password"];?></td>
							</tr>
							<tr>
								<td>Confirm</td>
								<td><input type="password" class="textfield" name="user_password2" value="<?=$user_password2;?>" /></td>
							</tr>
							<tr>
								<td>DCI#</td>
								<td><input type="text" class="textfield" name="user_dci" value="<?=$user_dci;?>" /><?=$error["user_dci"];?></td>
							</tr>
							<tr>
								<td>Country</td>
								<td><img id="user_country_image" style="vertical-align: middle; margin-left: 12px;" src="../images/flags/<?=strtolower($user_country);?>.png" alt="" />
									<select name="user_country" onchange="$('user_country_image').src = '../images/flags/' + this.value + '.png';">
										<?=countrylist($user_country);?>
									</select>
							<tr>
								<td>City</td>
								<td><input type="text" class="textfield" name="user_city" value="<?=$user_city;?>" /><?=$error["user_city"];?></td>
							</tr>
							<tr>
								<td></td>
								<td><br /><input type="image" src="<?=$path;?>/images/button_register.png" value="submit" /></td>
							</tr>
						</table>
					</form>
				</div>
			<?
			}
			?>
		</div>
		<div id="right">

		</div>
		<div class="breaker"></div>
	</div>
</body>
</html>