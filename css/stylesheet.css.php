<? 
header('Content-type: text/css');
include_once("../session_mysql.php");
?>
body
{
	margin: 0; 
	padding: 0; 
	background-color: #000; 
	background-image: url('../images/topback.jpg'); 
	background-repeat: repeat-x; 
	background-position: -3px 0px;
	width: 1000px;
	height: 100%;
}
html
{
	height: 100%;
}
p, h1, h2, h3, form
{
	margin: 0;
	padding:0;
}
ul, li
{
	margin: 0; padding: 0;
}
img
{
	border: 0;
}
#logotype
{
	margin-top: 35px;
	float: left;
}
a
{
	color: #000;
}
#top
{
	height: 114px;
}
#top .mini a
{
	color: #fff;
}
#top .mini a:hover
{
	color: #1977aa;
	border-bottom: 1px dotted #1977aa;
	text-decoration: none;
}
#menu
{
	width: 100%;
	margin-left: 1px;
	height: 27px;
	background-repeat: no-repeat;
	background-position: top right;
}
#menu.user
{
	background-image: url('../images/menu_leftover2.png');
}
#menu.visitor
{
	background-image: url('../images/menu_leftover1.png');
}

#menu img
{
	margin-right: -1px;
	float: left;
}
#content
{
	background-color: #fff;
	margin-left: 2px;
	width: 997px;
}
.box
{
	padding-left: 10px;
	padding-right: 10px;
	min-height: 100%;
}
.box .headerpic
{
	margin-left: -10px;
}
.box.olive
{
	background-color: #d5de97;	
}
.box.greyback, #left.greyback
{
	background-color: #dbdbdb;
}
.box.orange
{
	background-color: #e6dc9b;	
}
.box.blue
{
	background-color: #a2c4d6;	
}
.box p
{
	padding-top: 3px;
}
.box
{
	padding-bottom: 50px;
}
#left
{
	min-height: 600px;
	background-color: #e6dc9b;
	width: 157px;
	float: left;
}
#middle
{
	width: 589px;
	float: left;
	padding-left: 15px;
	padding-top: 20px;
}
#right
{
	padding-top: 20px;
	width: 220px;
	float: left;
}
#right_small
{
	width: 173px;
	float: right;
}
.text
{
	font-family: "Lucida Grande", Helvetica;
	font-size: 13px;
}
.breaker
{
	clear: both;
}
img.left
{
	float: left;
	margin-right: 2px;
}
.small
{
	font-family: "Lucida Grande", Helvetica;
	font-size: 12px;
}
.mini
{
	font-family: "Lucida Grande", Helvetica;
	font-size: 11px;
}
h1
{
	font-family: "Helvetica";
	font-size: 22px;
	letter-spacing: -0.05em;
	font-weight: normal;
}
h2
{
	font-family: "Helvetica";
	font-size: 18px;
	letter-spacing: -0.05em;
	font-weight: normal;
}
h3
{
	font-family: "Helvetica";
	font-size: 11px;
	font-weight: bold;
	color: #747474;
}
span.orange
{
	color: #e48116;
}
.card, .basicland
{
	background-image: url('../images/cardback.png');
	background-repeat: no-repeat;
	padding-left: 7px;
	padding-top: 7px;
	width: 112px;
	height: 150px;
	margin-left: -6px;
	float: left;
	margin-right: -9px;
	margin-bottom: -10px;
	z-index: 1;
}
.card.mini
{
	padding-left: 4px;
	padding-top: 5px;
	margin-left: -3px;
	margin-right: -4px;
	margin-bottom: -5px;
	width: 56px;
	height: 75px;
	background-image: url('../images/cardback_mini.png');
}
.card img:hover, .card .foil:hover
{
	cursor: pointer;
}
.card.shadow, .basicland
{
	background-image: url('../images/cardback_shadow_small.png');
}
.card.selected, .cardstats .card:hover
{
	background-image: url('../images/cardback_selected_small.png');
	background-position: -1px 1px;
	padding-left: 10px;
	padding-top: 13px;
	margin-top: -6px;
	margin-left: -9px;
}
.cardstats .card
{
	color: #747474;
	height: auto;
	margin-bottom: 20px;
}
.cardstats .card:hover
{
	color: #000;
}
.cardstats .card:hover h3
{
	color: #e48116;
}
.cardstats .card img:hover
{
	cursor: default;
}
#pack
{
	margin-top: 10px;
}
#sideboard .card, #deck .card, #sideboard .card img:hover, #sideboard .card .foil:hover, #deck .card img:hover, #deck .card .foil:hover
{
	cursor: move;
}
#deck, #sideboard
{
	width: 95%; height: 1000px;
}
#deck .card:hover, #sideboard .card:hover
{
	background-image: url('../images/cardback_selected_small.png');
	background-position: -1px -5px;
	padding-left: 10px;
	padding-top: 7px;
	margin-left: -9px;
}
#deck
{
	border-right: 1px dotted grey;
}
#cardviewer
{
	position: absolute;
	z-index: 999;
	display: none;
	width: 200px;
	height: 285px;
	padding: 5px;
	top: 0; left: 0;
	background-color: #000;
	cursor: move;
}
#welcome_box
{
	height: 191px;
}

#pickbutton
{
	float: right;
	margin-right: 75px;
}
#pick_result
{
	margin-top: 40px;
}
.red
{
	color: #c62529;
}
.grey
{
	color: #747474;
}
.blue
{
	color: #1977aa;
}
.black
{
	color: #000;
	font-weight: bold;
}
.gold
{
	color: #D4AF37;
}
.silver
{
	color: #2a2a2a;
}
.land
{
	color: #9F9F5F;
}
.artifact
{
	color: #5E2605;
}
.white
{
	color: #fff;
}
.error
{
	color: #800000;
	font-weight: bold;
}
.errorpic
{
	vertical-align: middle;
	margin-right: 3px;
	margin-left: 3px;
}
.foil
{
	position: absolute;
	z-index: 2;
	background-image: url('../images/foil_small.png'); 
	background-repeat: no-repeat;
	width: 120px;
	height: 170px; 
}
.cardcount
{
	position: absolute;
	z-index: 3;
	margin-left: 16px;
	margin-top: 14px;
	font-family: "Helvetica";
	font-size: 14px;
	font-weight: normal;
	background-color: #b67200;
	padding: 3px;
	padding-bottom: 0px;
	padding-top: 0px;
	color: #fff;
}
img.cardpic
{
	width: 90px;
	height: 127px;
}
img.cardpic.mini
{
	width: 45px;
	height: 63px;
}
.mini.card .foil
{
	background-image: url('../images/foil_mini.png'); 
	width: 60px;
	height: 85px;
}
#next_to_logo
{
	margin-top: 35px;
	float: left;
	width: 700px;
}
#login
{
	font-family: "Lucida Grande", Helvetica;
	font-size: 13px;
	color: #fff;
	margin-top: 10px;
	margin-left: 50px;
}
#loggedin_info
{
	width: 170px;
}
#login .textfield
{
	width: 120px;
	font-family: "Lucida Grande", Helvetica;
	font-size: 13px;
}
#login_fields
{
	margin-left: 20px;
}
#login_fields div
{
	margin-right: 10px;
}
#login div
{
	float: left;
}
#login .loginbutton
{
	margin-top: -1px;
}
#registerform
{
	margin-top: 20px;
	font-size: 15px;
	font-family: "Lucida Grande", Helvetica;
}
#registerform .textfield
{
	width: 150px;
	font-size: 15px;
	font-family: "Lucida Grande", Helvetica;
}
#registerform td
{
	padding-right: 10px;
}
#registerform input
{
	margin-left: 10px;
}
a
{
	font-weight: bold;
	color: #8B8614;
}
.box.orange a
{
	color: #b67200;
}
a:hover
{
	color: #000;
	text-decoration: none;
	border-bottom: 1px dotted #000;
}
a.imglink:hover
{
	border-bottom: 0;
}
.avatar
{
	vertical-align: middle;
	padding-bottom: 2px;
}
.pointer
{
	cursor: pointer;
}
span.pointer:hover
{
	border-bottom: 1px dotted #1977aa;
}
#show_cardviewer
{
	display: none;
}
#closecard
{
	position: absolute;
	top: -8px;
	left: -8px;
	z-index: 2;
}
#closecard:hover
{
	cursor: pointer;
}
.draftcue
{
	padding-top: 15px;
}
.packs
{
	float: left;
	margin-right: 5px;
	width: 90px;
	text-align: center;
}
.draftcue img
{
	vertical-align: middle;
}
.draftcue div
{
	float: left;
}
.draftcue h2
{
	font-family: "Lucida Grande";
	font-size: 14px;
	font-weight: bold;
}
.nmb_players
{
	margin-right: 10px;
}
.vertmiddle
{
	vertical-align: middle;
}
.chatdiv
{
	width: 476px;
	border: 2px solid #747474;
	vertical-align: bottom;
	background-color: #fff9f2;
}
.chatdiv.smallchat
{
	width: 300px;
}
.chatdiv ul
{
	height: 60px;
}
.chatdiv input
{
	width: 473px;
	border: 0px;
	border-top: 2px solid #747474;
}
.chatdiv.smallchat input
{
	width: 297px;
}
.chatdiv ul, .chatdiv li
{
	margin: 0; padding: 0;
}
.chatdiv li
{
	list-style-type: none;
	padding: 2px;
}
.chatdiv .even, .chatdiv .odd
{
	background-color: #fff;
	color: #000;
	border-bottom: 1px dotted #000;
}
.chatdiv .odd
{
	background-color: #fff9f2;
}
.draftchat
{
	width: 382px;
	float: left;
	margin-top: -16px;
	margin-left: 17px;
}
.draftchat.small
{
	width: 300px;
}
#drafter_list
{
	padding-top: 45px;
	margin-left: -10px;
	margin-right: -10px;
	padding-bottom: 5px;
}
#drafter_list li
{
	list-style-type: none;
	color: #fff;
	padding-left: 12px;
	padding-bottom: 2px;
}
#drafter_list .done
{
	background-color: #3faf1d;
	border-top: 2px solid #46bd23;
}
#drafter_list .waiting
{
	background-color: #b33522;
	border-top: 2px solid #cb2b13;
}
#drafter_list .open
{
	background-color: #cecece;
	color: #000;
	border-top: 1px solid #bdbdbd;
	border-bottom: 1px solid #bdbdbd;
}
#drafter_list .inactive
{
	background-color: #b3b3b3;
	border-top: 2px solid #cbcbcb;
	color: #000;
}
#drafting_these_packs
{
	margin-top: 10px;
	margin-left: -10px;
	margin-right: -10px;
}
#drafting_these_packs .packpic
{
	margin-bottom: 2px;
	padding-right: 5px;
	vertical-align: middle;
}
#drafting_these_packs img
{
	float: left;
}
.arrow
{
	padding-top: 20px;
	margin-left: -3px;
	padding-right: 3px;
}
#drafting_these_packs div
{
	font-family: "Helvetica";
	letter-spacing: -0.05em;
	font-size: 12px;
}
#drafting_these_packs .currently_drafting
{
	font-weight: bold;
	padding-top: 10px;
	padding-bottom: 10px;
	background-color: #f3ecc1;
	border-top: 1px dotted #a9a272;
	border-bottom: 1px dotted #a9a272;
}
#drafting_these_packs .pack
{
	padding-top: 4px;
	padding-bottom: 4px;
	padding-left: 10px;
	padding-right: 10px;
}
#drafting_these_packs .packname
{
	margin-top: 20px;
}
#drafting_these_packs .currently_drafting .pack_number
{
	padding-bottom: 10px;
}
#drafting_header
{
	height: 60px; 
	width: 157px; 
	position: absolute; 
	margin-left: -10px;
	z-index: 9; 
	background-image: url('../images/header_drafting.png'); 
	background-repeat: no-repeat;
}
.blackback
{
	background-color: #000;
}
#middle.realmiddle
{
	width: 820px;
	text-align: center;
	padding-top: 100px;
}
.clock
{
	width: 30px;
	height: 30px;
	vertical-align: middle;
}
.clock_green
{
	font-size: 11px;
	color: #000;
}
.clock_yellow
{
	font-size: 11px;
	color: #000;
	font-weight: bold;
}
.clock_red
{
	font-size: 11px;
	color: #c62529;
	font-weight: bold;
}
.clock_ringing
{
	font-size: 11px;
	background-color: #c62529;
	color: #fff;
	font-weight: bold;
}
#sound { width: 0; height: 0; position: absolute; top: 0; left: 0; }

#indicator
{
	display: none;
	position: absolute;
}
#blackscreen
{
	z-index: 1000; 
	position: absolute; 
	left: 0; 
	top: 0; 
	width: 100%; 
	height: 100%; 
	background-color: #000; 
	opacity: 0.8; 
	text-align: center;
	display: none;
}
#blackscreen_title
{
	color: #fff;
}
#leavebutton
{
	margin-top: 40px;
}
.remove_user
{
	vertical-align: middle;
	margin-left: 23px;
}
.green, a.green
{
	color: #45c81d;
}
.box.orange a.adduser
{
	color: #000;
}
.box.orange a.adduser:hover
{
	background-color: #45c81d;
	color: #fff;
	border-bottom: 1px dotted #fff;
}
#draft_status
{
	display: none;
}
.starbox
{
	margin-top: 5px;
	width: 90px;
}
.rating
{
	font-size: 10px;
}
.upcoming, .downgoing
{
	width: 200px;
	height: 100px;
	padding-top: 20px;
	background-image: url('../images/arrow_up.png');
	background-repeat: no-repeat;
}
.downgoing
{
	padding-top: 0px;
	background-image: url('../images/arrow_down.png');
}
.upcoming ul, .downgoing ul
{
	font-size: 11px;
	list-style-type: none;
	padding-left: 70px;
}
.upcoming a
{
	color: #299c29;
}
.downgoing a
{
	color: #b93926;
}
.stats_buttons img
{
	display: block;
	opacity: .5;
	filter: alpha(opacity=50);
}
.stats_buttons img.active, .stats_buttons img:hover
{
	opacity: 1;
	filter: alpha(opacity=100);
	cursor: pointer;
}
.filter_buttons img
{
	opacity: .5;
	cursor: pointer;
	filter: alpha(opacity=50);
}
.filter_buttons img.active
{
	opacity: 1;
	filter: alpha(opacity=100);
}
.roundbox.grey
{
	width: 221px;
	padding: 10px;
	padding-top: 15px;
	color: #000;
	background-image: url('../images/roundbox_grey.png');
	background-repeat: no-repeat;
}
.roundbox.grey.bottom
{
	width: 231px;
	height: 20px;
	margin-top: -24px;
	background-image: url('../images/roundbox_grey_bottom.png');
	background-repeat: no-repeat;
}