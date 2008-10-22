function selectCard(_id, _card_id)
{
	var selected = true;
	if(document.getElementById(_id).className == 'card selected')
	{
		document.getElementById(_id).className = 'card shadow';
		document.getElementById("chosen_card").value = '';
		document.getElementById("pickbutton").src = 'http://localhost/~beakid/magicdraft/images/button_pick_grey.png';
		selected = false;
	}

	var all = document.getElementById("pack").getElementsByTagName("div");
 	var countElements = all.length;
 	for(var i = 0; i < countElements; i++){
		if(all[i].id != _id)
		{
	 		strNamn = all[i].className;
	 		if(strNamn == "card selected") {
	 			all[i].className = "card shadow";
		 		strNamn = all[i].className;
			}
			if(strNamn == "card shadow") {
				if(selected == false) { all[i].style.opacity = 1; }
				else { all[i].style.opacity = 0.9; } 
			}
		}
	}
	if(selected)
	{
		document.getElementById(_id).className='card selected';
		document.getElementById(_id).style.opacity = 1;
		document.getElementById("chosen_card").value = _card_id;
		document.getElementById("pickbutton").src = 'http://localhost/~beakid/magicdraft/images/button_pick.png';
	}
}
function viewCard(_card_src)
{
	document.getElementById('cardcloseup').innerHTML = '<img src="'+_card_src+'" width="200" />';
	if(!readCookie("hidecard"))
	{
		if(_card_src == "false")
		{
			document.getElementById('cardviewer').style.display = 'none';
		}
		else
		{
			document.getElementById('cardviewer').style.display = 'block';
		}
	}
}
function toggleCardViewer(_toggle)
{
	if(_toggle == "hide")
	{
		createCookie("hidecard","true","2");
		document.getElementById('cardviewer').style.display = 'none';
		document.getElementById('show_cardviewer').style.display = 'inline';
	}
	else
	{
		eraseCookie("hidecard");
		document.getElementById('show_cardviewer').style.display = 'none';
		document.getElementById('cardviewer').style.display = 'block';
	}
}


function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}
var highest_zindex = 150;
function increaseZindex(_element)
{
	highest_zindex++;
	document.getElementById(_element).style.zIndex = highest_zindex;
}
function makedraggables(_part)
{
	var all = document.getElementById(_part).getElementsByTagName("div");
 	var countElements = all.length;
 	for(var i = 0; i < countElements; i++){
		if(all[i].id) new Draggable(all[i].id,{starteffect: false, endeffect: false});
	}
}
function playSound(soundfile) 
{
	document.getElementById('sound').innerHTML = "<embed src=\"http://www.svenskamagic.com/magicdraft/pick.wav\" hidden=\"true\" autostart=\"true\" loop=\"false\" />";
}
/*
	setXYposition()
	Positions a div regarding to mouse position
	Param: element id of div; event
	Returns: none
*/
var dont_show = false;
function setXYposition(divid, e)
{
	var posx = 0;
	var posy = 0;
	if (!e) var e = window.event;
	if (e.pageX || e.pageY)
	{
		posx = e.pageX;
		posy = e.pageY;
	}
	else if (e.clientX || e.clientY)
	{
		posx = e.clientX + document.body.scrollLeft;
		posy = e.clientY + document.body.scrollTop;
	}
	document.getElementById(divid).style.top = (posy + 5) + 'px';
	document.getElementById(divid).style.left = (posx + 10) + 'px';
	if(dont_show == false)
	{
		document.getElementById(divid).style.display = 'block';
	}
}
function draftCard()
{
	playSound('pick.wav'); 
	xajax.$('blackscreen_title').innerHTML = 'Picking card ...';
	xajax.$('blackscreen').style.display = 'block';	
	document.draftform.submit();
}