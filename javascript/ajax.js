function submitLogin()
{
	xajax.$('blackscreen_title').innerHTML = 'Logging in ...';
	xajax.$('blackscreen').style.display = 'block';	
	xajax.$('login_fields').style.display='none';
	xajax.$('login_extras').style.display='none';
	xajax_login(xajax.getFormValues("loginform"),xajax.config.requestURI);
	return false;
}
function addSeat(_draft_id)
{
	xajax_addSeat(_draft_id);
	setTimeout("xajax.$('indicator').style.display = 'none';",500);
	return false;
}
function removeSeat(_draft_id)
{
	xajax_removeSeat(_draft_id);
	setTimeout("xajax.$('indicator').style.display = 'none';",500);
	return false;
}
function submitChat(_draft_id)
{
	xajax_chat(xajax.getFormValues("chatform_"+_draft_id));
	return false;
}
function reloadChat(_draft_id)
{
	xajax_reloadchat(_draft_id);
	setTimeout("reloadChat('"+_draft_id+"')",5000);
	return false;
}
function reloadDrafterlist(_draft_id)
{
	var picking = false;
	if($('picking')) picking = true;
	//time to goofgrab?
	setTimeout("reloadDrafterlist("+_draft_id+")",3000);
	if($('autograb') && $('pack'))
	{
		// om ett kort inte redan är markerat?
		if($('chosen_card').value == "")
		{
			var all = document.getElementById("pack").getElementsByTagName("div");
		 	var countElements = all.length;
			//hitta random kort
			var randomnumber=Math.floor(Math.random()*countElements);
		 	for(var i = 0; i < countElements; i++){
				//är detta kortet vi ska randompicka?
				if(randomnumber == i)
				{
					$('chosen_card').value = all[i].id;
					//markera kortet
					selectCard(all[i].id, all[i].id.replace(/card/, ""));
					increaseZindex(all[i].id);
				}
			}
		}
		//ignorea rating
		$('ignore_rating').value = "true";
		$("pickbutton").src = 'http://localhost/~beakid/magicdraft/images/button_pick.png';
		document.draftform.submit();

	}
	else
	{
		var draft_status = $('draft_status').innerHTML;
		xajax_reloadDrafterlist(_draft_id, picking, draft_status);
	}
	return false;
}
function addCard2Deck(_card_id, _part, _draft_id)
{
	xajax_addCard2Deck(_card_id, _part)
	otherpart = "sideboard";
	if(_part == "sideboard")
	{
		otherpart = "deck";
		xajax.$('nmb_cards_in_' + otherpart).innerHTML = parseFloat(xajax.$('nmb_cards_in_' + otherpart).innerHTML)-1;
		xajax.$('nmb_cards_in_' + _part).innerHTML = parseFloat(xajax.$('nmb_cards_in_' + _part).innerHTML)+1;
	}
	else
	{
		xajax.$('nmb_cards_in_' + _part).innerHTML = parseFloat(xajax.$('nmb_cards_in_' + _part).innerHTML)+1;
		xajax.$('nmb_cards_in_' + otherpart).innerHTML = parseFloat(xajax.$('nmb_cards_in_' + otherpart).innerHTML)-1;
	}
	xajax.$(_card_id).className = "card shadow "+_part+"_card";
	setTimeout("xajax_printStats('"+_draft_id+"')",500);
	setTimeout("makedraggables('"+_part+"')",500);
	return false;
}
function orderDeck(_part, _draft_id, _order_by, _only_one_side)
{
	xajax_sortDeck(_part, _draft_id, _order_by);
	setTimeout("makedraggables('"+_part+"')",500);
	if(!_only_one_side)
	{
		otherpart = "sideboard";
		if(_part == "sideboard")
		{
			otherpart = "deck";
		}
		xajax_sortDeck(otherpart, _draft_id, _order_by);
		setTimeout("makedraggables('"+otherpart+"')",500);
	}
	setTimeout("xajax.$('indicator').style.display = 'none';",500);
	return false;
}
function showBasicLands(_land, _nmb, _draft_id)
{
	xajax_updateBasics(_land, _nmb, _draft_id);
	var all = document.getElementById("basiclands_"+_land).getElementsByTagName("div");
 	var countElements = all.length;
 	for(var i = 0; i < countElements; i++){
		if(all[i].className == "basicland")
		{
			if(i < _nmb) all[i].style.display = "block";
			else all[i].style.display = "none";
		}
	}
	return false;
}
function printStats(_draft_id)
{
	xajax_printStats(_draft_id);
	return false;
}
function confirmDraft(_draft_id)
{
	xajax_confirmDraft(_draft_id);
	document.getElementById('blackscreen_title').innerHTML = 'Waiting for all players ...';
	document.getElementById('blackscreen').style.display = 'block';	
	return false;
}
function filterColors(_color,_draft_id,_part,_order_by)
{
	if(xajax.$('color_'+_color).className == 'active')
	{
		var remove_it = true;
		xajax.$('color_'+_color).className = '';
	}
	else
	{
		var remove_it = false;
		xajax.$('color_'+_color).className = 'active';
	}
	xajax_filterColors(_draft_id, _color, _part, remove_it);
	setTimeout("makedraggables('"+_part+"')",500);
	setTimeout("xajax.$('indicator').style.display = 'none';",500);
	return false;
}