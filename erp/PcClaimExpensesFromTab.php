<?php

/* $Id: PcClaimExpensesFromTab.php 6310 2013-08-29 10:42:50Z daintree $*/

include('includes/session.inc');
$Title = _('Claim Petty Cash Expenses From Tab');
/* webERP manual links before header.inc */
$ViewTopic= 'PettyCash';
$BookMark = 'ExpenseClaim';
include('includes/header.inc');


if (isset($_POST['SelectedTabs'])){
	$SelectedTabs = mb_strtoupper($_POST['SelectedTabs']);
} elseif (isset($_GET['SelectedTabs'])){
	$SelectedTabs = mb_strtoupper($_GET['SelectedTabs']);
}

if (isset($_POST['SelectedIndex'])){
	$SelectedIndex = $_POST['SelectedIndex'];
} elseif (isset($_GET['SelectedIndex'])){
	$SelectedIndex = $_GET['SelectedIndex'];
}

if (isset($_POST['Days'])){
	$Days = filter_number_format($_POST['Days']);
} elseif (isset($_GET['Days'])){
	$Days = filter_number_format($_GET['Days']);
}

if (isset($_POST['Cancel'])) {
	unset($SelectedTabs);
	unset($SelectedIndex);
	unset($Days);
	unset($_POST['Amount']);
	unset($_POST['Notes']);
	unset($_POST['Receipt']);
}


if (isset($_POST['Process'])) {

	if ($_POST['SelectedTabs']=='') {
		echo prnMsg(_('You have not selected a tab to claim the expenses on'),'error');
		unset($SelectedTabs);
	}
}

if (isset($_POST['Go'])) {
	if ($Days<=0) {
		prnMsg(_('The number of days must be a positive number'),'error');
		$Days=30;
	}
}

if (isset($_POST['submit'])) {
//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible

	if ($_POST['SelectedExpense']=='') {
		$InputError=1;
		prnMsg(_('You have not selected an expense to claim on this tab'),'error');
	} elseif ($_POST['Amount']==0) {
		$InputError = 1;
		prnMsg( _('The amount must be greater than 0'),'error');
	}

	if (isset($SelectedIndex) AND $InputError !=1)  {
		$sql = "UPDATE pcashdetails
			SET date = '".FormatDateForSQL($_POST['Date'])."',
			codeexpense = '" . $_POST['SelectedExpense'] . "',
			amount = '" .-filter_number_format($_POST['Amount']) . "',
			notes = '" . $_POST['Notes'] . "',
			receipt = '" . $_POST['Receipt'] . "'
			WHERE counterindex = '".$SelectedIndex."'";

		$msg = _('The Expense Claim on Tab') . ' ' . $SelectedTabs . ' ' .  _('has been updated');

	} elseif ($InputError !=1 ) {

		// First check the type is not being duplicated
		// Add new record on submit

		$sql = "INSERT INTO pcashdetails (counterindex,
										tabcode,
										date,
										codeexpense,
										amount,
										authorized,
										posted,
										notes,
										receipt)
								VALUES (NULL,
                                        '" . $_POST['SelectedTabs'] . "',
										'".FormatDateForSQL($_POST['Date'])."',
										'" . $_POST['SelectedExpense'] . "',
										'" . -filter_number_format($_POST['Amount']) . "',
										0,
										0,
										'" . $_POST['Notes'] . "',
										'" . $_POST['Receipt'] . "'
										)";

		$msg = _('The Expense Claim on Tab') . ' ' . $_POST['SelectedTabs'] .  ' ' . _('has been created');
	}

	if ( $InputError !=1) {
		//run the SQL from either of the above possibilites
		$result = DB_query($sql,$db);
		prnMsg($msg,'success');

		unset($_POST['SelectedExpense']);
		unset($_POST['Amount']);
		unset($_POST['Date']);
		unset($_POST['Notes']);
		unset($_POST['Receipt']);
	}

} elseif ( isset($_GET['delete']) ) {

	$sql="DELETE FROM pcashdetails
			WHERE counterindex='".$SelectedIndex."'";
	$ErrMsg = _('Petty Cash Expense record could not be deleted because');
	$result = DB_query($sql,$db,$ErrMsg);
	prnMsg(_('Petty cash Expense record') .  ' ' . $SelectedTabs  . ' ' . _('has been deleted') ,'success');

	unset($_GET['delete']);

}//end of get delete

if (!isset($SelectedTabs)){

	/* It could still be the first time the page has been run and a record has been selected for modification - SelectedTabs will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
	then none of the above are true and the list of sales types will be displayed with
	links to delete or edit each. These will call the same page again and allow update/input
	or deletion of the records*/
	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/money_add.png" title="' . _('Payment Entry')	. '" alt="" />' . ' ' . $Title . '</p>';

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
    echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<br /><table class="selection">'; //Main table

	echo '<tr><td>' . _('Petty Cash Tabs for User ') . $_SESSION['UserID'] . ':</td>
				<td><select name="SelectedTabs">';

	$SQL = "SELECT tabcode
		FROM pctabs
		WHERE usercode='" . $_SESSION['UserID'] . "'";

	$result = DB_query($SQL,$db);
	echo '<option value="">' . _('Not Yet Selected') . '</option>';
	while ($myrow = DB_fetch_array($result)) {
		if (isset($_POST['SelectTabs']) and $myrow['tabcode']==$_POST['SelectTabs']) {
			echo '<option selected="selected" value="';
		} else {
			echo '<option value="';
		}
		echo $myrow['tabcode'] . '">' . $myrow['tabcode'] . '</option>';

	} //end while loop

	echo '</select></td></tr>';
   	echo '</table>'; // close main table
    DB_free_result($result);

	echo '<br />
			<div class="centre">
				<input type="submit" name="Process" value="' . _('Accept') . '" />
				<input type="submit" name="Cancel" value="' . _('Cancel') . '" />
			</div>
		</div>
	</form>';

} else { // isset($SelectedTabs)

	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/money_add.png" title="' . _('Petty Cash Claim Entry') . '" alt="" />
         ' . ' ' . $Title . '</p>';

	echo '<br /><div class="centre"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' . _('Select another tab') . '</a></div>';

	if (! isset($_GET['edit']) OR isset ($_POST['GO'])){
		echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
			<div>
				<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
				<br />
				<table class="selection">
				<tr>
					<th colspan="8"><h3>' . _('Petty Cash Tab') . ' ' .$SelectedTabs. '</h3></th>
				</tr>
				<tr>
					<th colspan="8">' . _('Detail Of Movements For Last ') .': ';


		if(!isset ($Days)){
			$Days=30;
		}

		/* Retrieve decimal places to display */
		$SqlDecimalPlaces="SELECT decimalplaces
					FROM currencies,pctabs
					WHERE currencies.currabrev = pctabs.currency
						AND tabcode='" . $SelectedTabs . "'";
		$result = DB_query($SqlDecimalPlaces,$db);
		$myrow=DB_fetch_array($result);
		$CurrDecimalPlaces = $myrow['decimalplaces'];

		echo '<input type="hidden" name="SelectedTabs" value="' . $SelectedTabs . '" />
			<input type="text" class="integer" name="Days" value="' . $Days . '" maxlength="3" size="4" /> ' . _('Days');
		echo '<input type="submit" name="Go" value="' . _('Go') . '" />';
		echo '</th></tr>';

		if (isset($_POST['Cancel'])) {
			unset($_POST['SelectedExpense']);
			unset($_POST['Amount']);
			unset($_POST['Date']);
			unset($_POST['Notes']);
			unset($_POST['Receipt']);
		}

		$sql = "SELECT * FROM pcashdetails
				WHERE tabcode='".$SelectedTabs."'
					AND date >=DATE_SUB(CURDATE(), INTERVAL ".$Days." DAY)
				ORDER BY date, counterindex ASC";

		$result = DB_query($sql,$db);

		echo '<tr>
				<th>' . _('Date Of Expense') . '</th>
				<th>' . _('Expense Description') . '</th>
				<th>' . _('Amount') . '</th>
				<th>' . _('Authorized') . '</th>
				<th>' . _('Notes') . '</th>
				<th>' . _('Receipt') . '</th>
			</tr>';

		$k=0; //row colour counter

		while ($myrow = DB_fetch_row($result)) {
			if ($k==1){
				echo '<tr class="EvenTableRows">';
				$k=0;
			} else {
				echo '<tr class="OddTableRows">';
				$k=1;
			}

			$sqldes="SELECT description
						FROM pcexpenses
						WHERE codeexpense='". $myrow['3'] . "'";

			$ResultDes = DB_query($sqldes,$db);
			$Description=DB_fetch_array($ResultDes);

			if (!isset($Description['0'])){
				$Description['0']='ASSIGNCASH';
			}
			if ($myrow['5']=='0000-00-00') {
				$AuthorisedDate=_('Unauthorised');
			} else {
				$AuthorisedDate=ConvertSQLDate($myrow['5']);
			}
			if (($myrow['5'] == '0000-00-00') and ($Description['0'] != 'ASSIGNCASH')){
				// only movements NOT authorized can be modified or deleted
				printf('<td>%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td><a href="%sSelectedIndex=%s&amp;SelectedTabs=' . $SelectedTabs . '&amp;Days=' . $Days . '&amp;edit=yes">' . _('Edit') . '</a></td>
					<td><a href="%sSelectedIndex=%s&amp;SelectedTabs=' . $SelectedTabs . '&amp;Days=' . $Days . '&amp;delete=yes" onclick=\'return confirm("' . _('Are you sure you wish to delete this code and the expenses it may have set up?') . '");\'>' . _('Delete') . '</a></td>
					</tr>',
					ConvertSQLDate($myrow['2']),
					$Description['0'],
					locale_number_format($myrow['4'],$CurrDecimalPlaces),
					$AuthorisedDate,
					$myrow['7'],
					$myrow['8'],
					htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?', $myrow['0'],
					htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?', $myrow['0']);
			} else {
				printf('<td>%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					</tr>',
					ConvertSQLDate($myrow['2']),
					$Description['0'],
					locale_number_format($myrow['4'],$CurrDecimalPlaces),
					$AuthorisedDate,
					$myrow['7'],
					$myrow['8']);

			}

		}
		//END WHILE LIST LOOP

		$sqlAmount="SELECT sum(amount)
					FROM pcashdetails
					WHERE tabcode='".$SelectedTabs."'";

		$ResultAmount = DB_query($sqlAmount,$db);
		$Amount=DB_fetch_array($ResultAmount);

		if (!isset($Amount['0'])) {
			$Amount['0']=0;
		}

		echo '<tr>
				<td colspan="2" style="text-align:right" >' . _('Current balance') . ':</td>
				<td class="number">' . locale_number_format($Amount['0'],$CurrDecimalPlaces) . '</td>
			</tr>
			</table>
			</div>
		</form>';
		}

	if (! isset($_GET['delete'])) {

		echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
			<div>
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

		if ( isset($_GET['edit'])) {
			$sql = "SELECT *
				FROM pcashdetails
				WHERE counterindex='".$SelectedIndex."'";

			$result = DB_query($sql, $db);
			$myrow = DB_fetch_array($result);

			$_POST['Date'] = ConvertSQLDate($myrow['date']);
			$_POST['SelectedExpense'] = $myrow['codeexpense'];
			$_POST['Amount']  =  -$myrow['amount'];
			$_POST['Notes']  = $myrow['notes'];
			$_POST['Receipt']  = $myrow['receipt'];

			echo '<input type="hidden" name="SelectedTabs" value="' . $SelectedTabs . '" />
				<input type="hidden" name="SelectedIndex" value="' . $SelectedIndex. '" />
				<input type="hidden" name="Days" value="' . $Days . '" />';

		}//end of Get Edit

		if (!isset($_POST['Date'])) {
			$_POST['Date']=Date($_SESSION['DefaultDateFormat']);
		}

        echo '<br /><table class="selection">'; //Main table
		echo '<tr>
				<td>' . _('Date Of Expense') . ':</td>
				<td><input type="text" class="date" alt="' . $_SESSION['DefaultDateFormat'] . '" name="Date" size="10" required="required" autofocus="autofocus" maxlength="10" value="' . $_POST['Date']. '" /></td>
			</tr>
			<tr>
				<td>' . _('Code Of Expense') . ':</td>
				<td><select required="required" name="SelectedExpense">';

		DB_free_result($result);

		$SQL = "SELECT pcexpenses.codeexpense,
					pcexpenses.description
			FROM pctabexpenses, pcexpenses, pctabs
			WHERE pctabexpenses.codeexpense = pcexpenses.codeexpense
				AND pctabexpenses.typetabcode = pctabs.typetabcode
				AND pctabs.tabcode = '".$SelectedTabs."'
			ORDER BY pcexpenses.codeexpense ASC";

		$result = DB_query($SQL,$db);
		echo '<option value="">' . _('Not Yet Selected') . '</option>';
		while ($myrow = DB_fetch_array($result)) {
			if (isset($_POST['SelectedExpense']) and $myrow['codeexpense']==$_POST['SelectedExpense']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $myrow['codeexpense'] . '">' . $myrow['codeexpense'] . ' - ' . $myrow['description'] . '</option>';

		} //end while loop

		echo '</select></td>
			</tr>';

		if (!isset($_POST['Amount'])) {
			$_POST['Amount']=0;
		}

		echo '<tr>
				<td>' . _('Amount') . ':</td>
				<td><input type="text" class="number" required="required" name="Amount" size="12" maxlength="11" value="' . $_POST['Amount'] . '" /></td>
			</tr>';

		if (!isset($_POST['Notes'])) {
			$_POST['Notes']='';
		}

		echo '<tr>
				<td>' . _('Notes') . ':</td>
				<td><input type="text" name="Notes" size="50" maxlength="49" value="' . $_POST['Notes'] . '" /></td>
			</tr>';

		if (!isset($_POST['Receipt'])) {
			$_POST['Receipt']='';
		}

		echo '<tr>
				<td>' . _('Receipt') . ':</td>
				<td><input type="text" name="Receipt" size="50" maxlength="49" value="' . $_POST['Receipt'] . '" /></td>
			</tr>
			</table>
			<input type="hidden" name="SelectedTabs" value="' . $SelectedTabs . '" />
			<input type="hidden" name="Days" value="' .$Days. '" />
			<br />
			<div class="centre">
				<input type="submit" name="submit" value="' . _('Accept') . '" />
				<input type="submit" name="Cancel" value="' . _('Cancel') . '" />
			</div>
			</div>
		</form>';

	} // end if user wish to delete

}

include('includes/footer.inc');
?>