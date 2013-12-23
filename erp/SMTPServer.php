<?php
/* $Id: SMTPServer.php 4469 2011-01-15 02:28:37Z daintree $*/
include('includes/session.inc');

$Title = _('SMTP Server details');

include('includes/header.inc');

echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $Theme . '/images/email.gif" title="' . _('SMTP Server') . '" alt="" />' . ' ' . _('SMTP Server Settings') . '</p>';
// First check if there are smtp server data or not


if (isset($_POST['submit']) AND $_POST['MailServerSetting']==1) {//If there are already data setup, Update the table
	$sql="UPDATE emailsettings SET
				host='".$_POST['Host']."',
				port='".$_POST['Port']."',
				heloaddress='".$_POST['HeloAddress']."',
				username='".$_POST['UserName']."',
				password='".$_POST['Password']."',
				auth='".$_POST['Auth']."'";
	$ErrMsg = _('The email setting information failed to update');
	$DbgMsg = _('The SQL failed to update is ');
	$result1=DB_query($sql, $db, $ErrMsg, $DbgMsg);
	unset($_POST['MailServerSetting']);
	prnMsg(_('The settings for the SMTP server have been successfully updated'), 'success');
	echo '<br />';

}elseif(isset($_POST['submit']) and $_POST['MailServerSetting']==0){//There is no data setup yet
	$sql = "INSERT INTO emailsettings(host,
		 				port,
						heloaddress,
						username,
						password,
						auth)
				VALUES (
					'".$_POST['Host']."',
					'".$_POST['Port']."',
					'".$_POST['HeloAddress']."',
					'".$_POST['UserName']."',
					'".$_POST['Password']."',
					'".$_POST['Auth']."')";
	$ErrMsg = _('The email settings failed to be inserted');
	$DbgMsg = _('The SQL failed to insert the email information is');
	$result2 = DB_query($sql,$db);
	unset($_POST['MailServerSetting']);
	prnMsg(_('The settings for the SMTP server have been sucessfully inserted'),'success');
	echo '<br/>';
}

  // Check the mail server setting status

		$sql="SELECT id,
				host,
				port,
				heloaddress,
				username,
				password,
				timeout,
				auth
			FROM emailsettings";
		$ErrMsg = _('The email settings information cannot be retrieved');
		$DbgMsg = _('The SQL that failed was');

		$result=DB_query($sql, $db,$ErrMsg,$DbgMsg);
		if(DB_num_rows($result)!=0){
			$MailServerSetting = 1;
			$myrow=DB_fetch_array($result);
		}else{
			DB_free_result($result);
			$MailServerSetting = 0;
			$myrow['host']='';
			$myrow['port']='';
			$myrow['heloaddress']='';
			$myrow['username']='';
			$myrow['password']='';
			$myrow['timeout']=5;
		}


echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<input type="hidden" name="MailServerSetting" value="' . $MailServerSetting . '" />';
echo '<table class="selection">';
echo '<tr><td>' . _('Server Host Name') . '</td>
		<td><input type="text" name="Host" value="'.$myrow['host'].'" /></td></tr>';
echo '<tr><td>' . _('SMTP port') . '</td>
		<td><input type="text" name="Port" size="4" class="number" value="'.$myrow['port'].'" /></td></tr>';
echo '<tr><td>' . _('Helo Command') . '</td>
		<td><input type="text" name="HeloAddress" value="'.$myrow['heloaddress'].'" /></td></tr>';
echo '<tr><td>' . _('Authorisation Required') . '</td><td>';
echo '<select name="Auth">';
if ($myrow['auth']==1) {
	echo '<option selected="selected" value="1">' . _('True') . '</option>';
	echo '<option value="0">' . _('False') . '</option>';
} else {
	echo '<option value="1">' . _('True') . '</option>';
	echo '<option selected="selected" value="0">' . _('False') . '</option>';
}
echo '</select></td></tr>';
echo '<tr><td>' . _('User Name') . '</td>
	<td><input type="text" name="UserName" value="'.$myrow['username'].'" /></td></tr>';
echo '<tr><td>' . _('Password') . '</td>
	<td><input type="password" name="Password" value="'.$myrow['password'].'" /></td></tr>';
echo '<tr><td>' . _('Timeout (seconds)') . '</td>
	<td><input type="text" size="5" name="Timeout" class="number" value="'.$myrow['timeout'].'" /></td></tr>';
echo '<tr><td colspan="2"><div class="centre"><input type="submit" name="submit" value="' . _('Update') . '" /></div></td></tr>';
echo '</table>
      </div>
      </form>';

include('includes/footer.inc');

?>
