<div id="left">
	<div class="box orange text">
		<div id="drafting_header" alt="Drafting!" /></div>
		<div id="drafterlist">
			<?=drafterList($draft_id);?>
		</div>
		<?=draftPacks($draft_info);?>
			<form action="draft.php" method="post" name="leavedraft">
				<input type="hidden" name="action" value="leave_draft" />
				<input type="hidden" name="id" value="<?=$ID;?>" />
			<img onclick="javascript:if(confirm('Sure you want to leave this draft?')) document.leavedraft.submit();"  id="leavebutton" src="<?=$path;?>/images/button_leavedraft.png" onmouseover="this.src='../images/button_leavedraft_hover.png';" onmouseout="this.src='../images/button_leavedraft.png';" class="pointer" alt="Leave!" />
		</form>
	</div>
</div>
<div id="middle" class="realmiddle">
	<h1><span class="orange">Draft ready to start!</span></h1>
	<br />
	<p class="white text">Please confirm your presence!</p>
	<br />
	<img onclick="javascript:confirmDraft(<?=$draft_id;?>);" src="../images/imhere.png" alt="I'm here!" class="pointer"/>
</div>

<? 
#if you're already confirmed in this page, make it autopopup
if($mystatus == '1') {?>
	<script type="text/javascript" charset="utf-8">
		confirmDraft(<?=$draft_id;?>);
	</script>
<? } ?>