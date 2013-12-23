<?php

/* $Id: WorkOrderEntry.php 6310 2013-08-29 10:42:50Z daintree $*/

include('includes/session.inc');
$Title = _('Work Order Entry');
include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

echo '<p class="page_title_text">
		<img src="'.$RootPath.'/css/'.$Theme.'/images/transactions.png" title="' . _('Search') . '" alt="" />' . ' ' . $Title.'
	</p>';


if (isset($_GET['WO'])) {
	$SelectedWO = $_GET['WO'];
} elseif (isset($_POST['WO'])){
	$SelectedWO = $_POST['WO'];
} else {
	unset($SelectedWO);
}

if (isset($_GET['ReqDate'])){
	$ReqDate = ConvertSQLDate($_GET['ReqDate']);
} else {
	$ReqDate=Date('Y-m-d');
}

if (isset($_GET['StartDate'])){
	$StartDate = ConvertSQLDate($_GET['StartDate']);
} else {
	$StartDate=Date('Y-m-d');
}

if (isset($_GET['loccode'])){
	$LocCode = $_GET['loccode'];
} else {
	$LocCode=$_SESSION['UserStockLocation'];
}

foreach ($_POST as $key=>$value) {
	if (substr($key, 0, 9)=='OutputQty' OR substr($key, 0, 7)=='RecdQty') {
		$_POST[$key] = filter_number_format($value);
	}
}

// check for new or modify condition
if (isset($SelectedWO) AND$SelectedWO!=''){
	// modify
	$_POST['WO'] = (int)$SelectedWO;
	$EditingExisting = true;
} else {
	// new
	$_POST['WO'] = GetNextTransNo(40,$db);
	$sql = "INSERT INTO workorders (wo,
									loccode,
									requiredby,
									startdate)
								VALUES (
									'" . $_POST['WO'] . "',
									'" . $LocCode . "',
									'" . $ReqDate . "',
									'" . $StartDate. "')";
	$InsWOResult = DB_query($sql,$db);
}


if (isset($_GET['NewItem'])){
	$NewItem = $_GET['NewItem'];
}
if (isset($_GET['ReqQty'])){
	$ReqQty = $_GET['ReqQty'];
}
if (!isset($_POST['StockLocation'])){
	if (isset($LocCode)){
		$_POST['StockLocation']=$LocCode;
	} elseif (isset($_SESSION['UserStockLocation'])){
		$_POST['StockLocation']=$_SESSION['UserStockLocation'];
	}
}


if (isset($_POST['Search'])){

	If ($_POST['Keywords'] AND $_POST['StockCode']) {
		prnMsg(_('Stock description keywords have been used in preference to the Stock code extract entered'),'warn');
	}
	If (mb_strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
		$_POST['Keywords'] = mb_strtoupper($_POST['Keywords']);
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		if ($_POST['StockCat']=='All'){
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.description " . LIKE . " '$SearchString'
							AND stockmaster.discontinued=0
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.discontinued=0
							AND stockmaster.description " . LIKE . " '" . $SearchString . "'
							AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		}

	} elseif (mb_strlen($_POST['StockCode'])>0){

		$_POST['StockCode'] = mb_strtoupper($_POST['StockCode']);
		$SearchString = '%' . $_POST['StockCode'] . '%';

		/* Only items of stock type F finished goods or M - raw materials can have work orders created - raw materials can include the manufacture of components (as noted by Bob Thomas! */
		if ($_POST['StockCat']=='All'){
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
							AND stockmaster.discontinued=0
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
							AND stockmaster.discontinued=0
							AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		}
	} else {
		if ($_POST['StockCat']=='All'){
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.discontinued=0
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.discontinued=0
							AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		  }
	}

	$SQL = $SQL . ' LIMIT ' . $_SESSION['DisplayRecordsMax'];

	$ErrMsg = _('There is a problem selecting the part records to display because');
	$DbgMsg = _('The SQL used to get the part selection was');
	$SearchResult = DB_query($SQL,$db,$ErrMsg, $DbgMsg);

	if (DB_num_rows($SearchResult)==0 ){
		prnMsg (_('There are no products available meeting the criteria specified'),'info');

		if ($debug==1){
			prnMsg(_('The SQL statement used was') . ':<br />' . $SQL,'info');
		}
	}
	if (DB_num_rows($SearchResult)==1){
		$myrow=DB_fetch_array($SearchResult);
		$NewItem = $myrow['stockid'];
		DB_data_seek($SearchResult,0);
	}

} //end of if search

if (isset($NewItem) AND isset($_POST['WO'])){
	$InputError=false;
	$CheckItemResult = DB_query("SELECT mbflag,
										eoq,
										controlled
									FROM stockmaster
									WHERE stockid='" . $NewItem . "'",
								$db);
	if (DB_num_rows($CheckItemResult)==1){
		$CheckItemRow = DB_fetch_array($CheckItemResult);
		if ($CheckItemRow['controlled']==1 AND $_SESSION['DefineControlledOnWOEntry']==1){ //need to add serial nos or batches to determine quantity
			$EOQ = 0;
		} else {
			if (!isset($ReqQty)) {
				$ReqQty=$CheckItemRow['eoq'];
			}
			$EOQ = $ReqQty;
		}
		if ($CheckItemRow['mbflag']!='M'){
			prnMsg(_('The item selected cannot be added to a work order because it is not a manufactured item'),'warn');
			$InputError=true;
		}
	} else {
		prnMsg(_('The item selected cannot be found in the database'),'error');
		$InputError = true;
	}
	$CheckItemResult = DB_query("SELECT stockid
									FROM woitems
									WHERE stockid='" . $NewItem . "'
										AND wo='" .$_POST['WO'] . "'",
									$db);
	if (DB_num_rows($CheckItemResult)==1){
		prnMsg(_('This item is already on the work order and cannot be added again'),'warn');
		$InputError=true;
	}


	if ($InputError==false){
		$CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*bom.quantity) AS cost
									FROM stockmaster
									INNER JOIN bom
										ON stockmaster.stockid=bom.component
									WHERE bom.parent='" . $NewItem . "'
										AND bom.loccode='" . $_POST['StockLocation'] . "'",
							 $db);
		$CostRow = DB_fetch_array($CostResult);
		if (is_null($CostRow['cost']) OR $CostRow['cost']==0){
				$Cost =0;
				prnMsg(_('The cost of this item as accumulated from the sum of the component costs is nil. This could be because there is no bill of material set up ... you may wish to double check this'),'warn');
		} else {
				$Cost = $CostRow['cost'];
		}
		if (!isset($EOQ)){
			$EOQ=1;
		}

		$Result = DB_Txn_Begin($db);

		// insert parent item info
		$sql = "INSERT INTO woitems (wo,
									 stockid,
									 qtyreqd,
									 stdcost)
								VALUES (
									 '" . $_POST['WO'] . "',
									 '" . $NewItem . "',
									 '" . $EOQ . "',
									 '" . $Cost . "'
								)";
		$ErrMsg = _('The work order item could not be added');
		$result = DB_query($sql,$db,$ErrMsg);

		//Recursively insert real component requirements - see includes/SQL_CommonFunctions.in for function WoRealRequirements
		WoRealRequirements($db, $_POST['WO'], $_POST['StockLocation'], $NewItem);

		$result = DB_Txn_Commit($db);

		unset($NewItem);
	} //end if there were no input errors
} //adding a new item to the work order


if (isset($_POST['submit']) OR isset($_POST['Search'])) { //The update button has been clicked

	echo '<div class="centre"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') .'">' . _('Enter a new work order') . '</a>';
	echo '<br /><a href="' . $RootPath . '/SelectWorkOrder.php">' . _('Select an existing work order') . '</a>';
	echo '<br /><a href="'. $RootPath . '/WorkOrderCosting.php?WO=' .  $SelectedWO . '">' . _('Go to Costing'). '</a></div>';

	$Input_Error = false; //hope for the best
	 for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++){
	   	if (!is_numeric($_POST['OutputQty'.$i])){
		   	prnMsg(_('The quantity entered must be numeric'),'error');
			$Input_Error = true;
		} elseif ($_POST['OutputQty'.$i]<=0){
			prnMsg(_('The quantity entered must be a positive number greater than zero'),'error');
			$Input_Error = true;
		}
	 }
	 if (!Is_Date($_POST['RequiredBy'])){
		prnMsg(_('The required by date entered is in an invalid format'),'error');
		$Input_Error = true;
	 }

	if ($Input_Error == false) {

		$SQL_ReqDate = FormatDateForSQL($_POST['RequiredBy']);
		$QtyRecd=0;

		for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++){
				$QtyRecd+=$_POST['RecdQty'.$i];
		}
		unset($sql);

		if ($QtyRecd==0){ //can only change factory location if Qty Recd is 0
				$sql[] = "UPDATE workorders SET requiredby='" . $SQL_ReqDate . "',
												startdate='" . FormatDateForSQL($_POST['StartDate']) . "',
												loccode='" . $_POST['StockLocation'] . "'
											WHERE wo='" . $_POST['WO'] . "'";
		} else {
				prnMsg(_('The factory where this work order is made can only be updated if the quantity received on all output items is 0'),'warn');
				$sql[] = "UPDATE workorders SET requiredby='" . $SQL_ReqDate . "',
												startdate='" . FormatDateForSQL($_POST['StartDate']) . "'
											WHERE wo='" . $_POST['WO'] . "'";
		}

		for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++){
			if (!isset($_POST['NextLotSNRef'.$i])) {
				$_POST['NextLotSNRef'.$i]='';
			}
			if (isset($_POST['QtyRecd'.$i]) AND $_POST['QtyRecd'.$i]>$_POST['OutputQty'.$i]){
				$_POST['OutputQty'.$i]=$_POST['QtyRecd'.$i]; //OutputQty must be >= Qty already reced
			}
			if ($_POST['RecdQty'.$i]==0 AND (!isset($_POST['HasWOSerialNos'.$i]) OR $_POST['HasWOSerialNos'.$i]==false)){
				/* can only change location cost if QtyRecd=0 */
				$CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*bom.quantity) AS cost
												FROM stockmaster
												INNER JOIN bom
													ON stockmaster.stockid=bom.component
												WHERE bom.parent='" . $_POST['OutputItem'.$i] . "'
													AND bom.loccode='" . $_POST['StockLocation'] . "'",
									 $db);
				$CostRow = DB_fetch_array($CostResult);
				if (is_null($CostRow['cost'])){
					$Cost =0;
					prnMsg(_('The cost of this item as accumulated from the sum of the component costs is nil. This could be because there is no bill of material set up ... you may wish to double check this'),'warn');
				} else {
					$Cost = $CostRow['cost'];
				}
				$sql[] = "UPDATE woitems SET qtyreqd =  '". $_POST['OutputQty' . $i] . "',
											 nextlotsnref = '". $_POST['NextLotSNRef'.$i] ."',
											 stdcost ='" . $Cost . "'
										WHERE wo='" . $_POST['WO'] . "'
										AND stockid='" . $_POST['OutputItem'.$i] . "'";
  			} elseif (isset($_POST['HasWOSerialNos'.$i]) AND $_POST['HasWOSerialNos'.$i]==false) {
				$sql[] = "UPDATE woitems SET qtyreqd =  '". $_POST['OutputQty' . $i] . "',
											 nextlotsnref = '". $_POST['NextLotSNRef'.$i] ."'
										WHERE wo='" . $_POST['WO'] . "'
										AND stockid='" . $_POST['OutputItem'.$i] . "'";
			}
		}

		//run the SQL from either of the above possibilites
		$ErrMsg = _('The work order could not be added/updated');
		foreach ($sql as $sql_stmt){
		//	echo '<br />' . $sql_stmt;
			$result = DB_query($sql_stmt,$db,$ErrMsg);

		}
		if (!isset($_POST['Search'])) {
			prnMsg(_('The work order has been updated'),'success');
		}

		for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++){
		  		 unset($_POST['OutputItem'.$i]);
				 unset($_POST['OutputQty'.$i]);
				 unset($_POST['QtyRecd'.$i]);
				 unset($_POST['NetLotSNRef'.$i]);
				 unset($_POST['HasWOSerialNos'.$i]);
		}
	}
} elseif (isset($_POST['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

	$CancelDelete=false; //always assume the best

	// can't delete it there are open work issues
	$HasTransResult = DB_query("SELECT transno
									FROM stockmoves
								WHERE (stockmoves.type= 26 OR stockmoves.type=28)
								AND reference " . LIKE  . " '%" . $_POST['WO'] . "%'",$db);
	if (DB_num_rows($HasTransResult)>0){
		prnMsg(_('This work order cannot be deleted because it has issues or receipts related to it'),'error');
		$CancelDelete=true;
	}

	if ($CancelDelete==false) { //ie all tests proved ok to delete
		DB_Txn_Begin($db);
		$ErrMsg = _('The work order could not be deleted');
		$DbgMsg = _('The SQL used to delete the work order was');
		//delete the worequirements
		$sql = "DELETE FROM worequirements WHERE wo='" . $_POST['WO'] . "'";
		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);
		//delete the items on the work order
		$sql = "DELETE FROM woitems WHERE wo='" . $_POST['WO'] . "'";
		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);
		//delete the controlled items defined in wip
		$sql="DELETE FROM woserialnos WHERE wo='" . $_POST['WO'] . "'";
		$ErrMsg=_('The work order serial numbers could not be deleted');
		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);
		// delete the actual work order
		$sql="DELETE FROM workorders WHERE wo='" . $_POST['WO'] . "'";
		$ErrMsg=_('The work order could not be deleted');
		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);

		DB_Txn_Commit($db);
		prnMsg(_('The work order has been cancelled'),'success');


		echo '<p><a href="' . $RootPath . '/SelectWorkOrder.php">' . _('Select an existing outstanding work order') . '</a></p>';
		unset($_POST['WO']);
		for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++){
			unset($_POST['OutputItem'.$i]);
			unset($_POST['OutputQty'.$i]);
			unset($_POST['QtyRecd'.$i]);
			unset($_POST['NetLotSNRef'.$i]);
			unset($_POST['HasWOSerialNos'.$i]);
		}
		include('includes/footer.inc');
		exit;
	}
}

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" name="form1">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<br /><table class="selection">';

$sql="SELECT workorders.loccode,
			 requiredby,
			 startdate,
			 costissued,
			 closed
		FROM workorders	INNER JOIN locations
		ON workorders.loccode=locations.loccode
		WHERE workorders.wo='" . $_POST['WO'] . "'";

$WOResult = DB_query($sql,$db);
if (DB_num_rows($WOResult)==1){

	$myrow = DB_fetch_array($WOResult);
	$_POST['StartDate'] = ConvertSQLDate($myrow['startdate']);
	$_POST['CostIssued'] = $myrow['costissued'];
	$_POST['Closed'] = $myrow['closed'];
	$_POST['RequiredBy'] = ConvertSQLDate($myrow['requiredby']);
	$_POST['StockLocation'] = $myrow['loccode'];
	$ErrMsg =_('Could not get the work order items');
	$WOItemsResult = DB_query("SELECT   woitems.stockid,
										stockmaster.description,
										qtyreqd,
										qtyrecd,
										stdcost,
										nextlotsnref,
										controlled,
										serialised,
										stockmaster.decimalplaces,
										nextserialno
								FROM woitems INNER JOIN stockmaster
								ON woitems.stockid=stockmaster.stockid
								WHERE wo='" .$_POST['WO'] . "'",$db,$ErrMsg);

	$NumberOfOutputs=DB_num_rows($WOItemsResult);
	$i=1;
	while ($WOItem=DB_fetch_array($WOItemsResult)){
				$_POST['OutputItem' . $i]=$WOItem['stockid'];
				$_POST['OutputItemDesc'.$i]=$WOItem['description'];
				$_POST['OutputQty' . $i]= $WOItem['qtyreqd'];
		  		$_POST['RecdQty' .$i] =$WOItem['qtyrecd'];
		  		$_POST['DecimalPlaces' . $i] = $WOItem['decimalplaces'];
		  		if ($WOItem['serialised']==1 AND $WOItem['nextserialno']>0){
		  		   $_POST['NextLotSNRef' .$i]=$WOItem['nextserialno'];
		  		} else {
				   $_POST['NextLotSNRef' .$i]=$WOItem['nextlotsnref'];
				}
		  		$_POST['Controlled'.$i] =$WOItem['controlled'];
		  		$_POST['Serialised'.$i] =$WOItem['serialised'];
		  		$HasWOSerialNosResult = DB_query("SELECT wo FROM woserialnos WHERE wo='" . $_POST['WO'] . "'",$db);
		  		if (DB_num_rows($HasWOSerialNosResult)>0){
		  		   $_POST['HasWOSerialNos']=true;
		  		} else {
				   $_POST['HasWOSerialNos']=false;
				}
		  		$i++;
	}
}

echo '<input type="hidden" name="WO" value="' .$_POST['WO'] . '" />';
echo '<tr><td class="label">' . _('Work Order Reference') . ':</td><td>' . $_POST['WO'] . '</td></tr>';
echo '<tr><td class="label">' . _('Factory Location') .':</td>
	<td><select name="StockLocation" onChange="ReloadForm(form1.submit)">';
$LocResult = DB_query("SELECT loccode,locationname FROM locations",$db);
while ($LocRow = DB_fetch_array($LocResult)){
	if ($_POST['StockLocation']==$LocRow['loccode']){
		echo '<option selected="True" value="' . $LocRow['loccode'] .'">' . $LocRow['locationname'] . '</option>';
	} else {
		echo '<option value="' . $LocRow['loccode'] .'">' . $LocRow['locationname'] . '</option>';
	}
}
echo '</select></td></tr>';
if (!isset($_POST['StartDate'])){
	$_POST['StartDate'] = Date($_SESSION['DefaultDateFormat']);
}

echo '<tr>
		<td class="label">' . _('Start Date') . ':</td>
		<td><input type="text" name="StartDate" size="12" maxlength="12" value="' . $_POST['StartDate'] .'" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" /></td>
	</tr>';

if (!isset($_POST['RequiredBy'])){
	$_POST['RequiredBy'] = Date($_SESSION['DefaultDateFormat']);
}

echo '<tr>
		<td class="label">' . _('Required By') . ':</td>
		<td><input type="text" name="RequiredBy" size="12" maxlength="12" value="' . $_POST['RequiredBy'] .'" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" /></td>
	</tr>';

if (isset($WOResult)){
	echo '<tr><td class="label">' . _('Accumulated Costs') . ':</td>
			  <td class="number">' . locale_number_format($myrow['costissued'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td></tr>';
}
echo '</table>
		<br /><table class="selection">';
echo '<tr><th>' . _('Output Item') . '</th>
		  <th>' . _('Qty Required') . '</th>
		  <th>' . _('Qty Received') . '</th>
		  <th>' . _('Balance Remaining') . '</th>
		  <th>' . _('Next Lot/SN Ref') . '</th>
		  </tr>';
$j=0;
if (isset($NumberOfOutputs)){
	for ($i=1;$i<=$NumberOfOutputs;$i++){
		if ($j==1) {
			echo '<tr class="OddTableRows">';
			$j=0;
		} else {
			echo '<tr class="EvenTableRows">';
			$j++;
		}
		echo '<td><input type="hidden" name="OutputItem' . $i . '" value="' . $_POST['OutputItem' .$i] . '" />' .
			$_POST['OutputItem' . $i] . ' - ' . $_POST['OutputItemDesc' .$i] . '</td>';
		if ($_POST['Controlled'.$i]==1 AND $_SESSION['DefineControlledOnWOEntry']==1){
			echo '<td class="number">' . locale_number_format($_POST['OutputQty' . $i], $_POST['DecimalPlaces' . $i]) . '</td>';
			echo '<input type="hidden" name="OutputQty' . $i .'" value="' . locale_number_format($_POST['OutputQty' . $i]-$_POST['RecdQty' .$i], $_POST['DecimalPlaces' . $i]) . '" />';
		} else {
		  	echo'<td><input type="text" required="required" class="number" name="OutputQty' . $i . '" value="' . locale_number_format($_POST['OutputQty' . $i]-$_POST['RecdQty' .$i], $_POST['DecimalPlaces' . $i]) . '" size="10" maxlength="10" title="'._('The input format must be positive numeric').'" /></td>';
		}
		 echo '<td class="number"><input type="hidden" name="RecdQty' . $i . '" value="' . locale_number_format($_POST['RecdQty' .$i], $_POST['DecimalPlaces' . $i]) . '" />' . locale_number_format($_POST['RecdQty' .$i], $_POST['DecimalPlaces' . $i])  . '</td>
		  		<td class="number">' . locale_number_format(($_POST['OutputQty' . $i] - $_POST['RecdQty' .$i]), $_POST['DecimalPlaces' . $i]) . '</td>';
		if ($_POST['Controlled'.$i]==1){
			echo '<td><input type="text" name="NextLotSNRef' .$i . '" value="' . $_POST['NextLotSNRef'.$i] . '" /></td>';
			if ($_SESSION['DefineControlledOnWOEntry']==1){
				if ($_POST['Serialised' . $i]==1){
					$LotOrSN = _('S/Ns');
				} else {
					$LotOrSN = _('Batches');
				}
				echo '<td><a href="' . $RootPath . '/WOSerialNos.php?WO=' . $_POST['WO'] . '&StockID=' . $_POST['OutputItem' .$i] . '&Description=' . $_POST['OutputItemDesc' .$i] . '&Serialised=' . $_POST['Serialised' .$i] . '&NextSerialNo=' . $_POST['NextLotSNRef' .$i] . '">' . $LotOrSN . '</a></td>';
			}
		}
		echo '<td>';
		wikiLink('WorkOrder', $_POST['WO'] . $_POST['OutputItem' .$i]);
		echo '</td>';
		echo '</tr>';
		if (isset($_POST['Controlled' . $i])) {
			echo '<input type="hidden" name="Controlled' . $i .'" value="' . $_POST['Controlled' . $i] . '" />';
		}
		if (isset( $_POST['Serialised' . $i])) {
			echo '<input type="hidden" name="Serialised' . $i .'" value="' . $_POST['Serialised' . $i] . '" />';
		}
		if (isset($_POST['HasWOSerialNos' . $i])) {
			echo '<input type="hidden" name="HasWOSerialNos' . $i .'" value="' . $_POST['HasWOSerialNos' . $i] . '" />';
		}
	}
	echo '<input type="hidden" name="NumberOfOutputs" value="' . ($i -1).'" />';
}
echo '</table>';

echo '<br /><div class="centre"><button type="submit" name="submit">' . _('Update') . '</button></div>';

echo '<br /><div class="centre"><button type="submit" name="delete" onclick="return confirm(\'' . _('Are You Sure?') . '\');">' . _('Cancel This Work Order') . '</button>';

echo '</div><br />';

$SQL="SELECT categoryid,
			categorydescription
		FROM stockcategory
		WHERE stocktype='F' OR stocktype='M'
		ORDER BY categorydescription";
	$result1 = DB_query($SQL,$db);

echo '<table class="selection"><tr><td>' . _('Select a stock category') . ':<select name="StockCat">';

if (!isset($_POST['StockCat'])){
	echo '<option selected="True" value="All">' . _('All') . '</option>';
	$_POST['StockCat'] ='All';
} else {
	echo '<option value="All">' . _('All') . '</option>';
}

while ($myrow1 = DB_fetch_array($result1)) {

	if ($_POST['StockCat']==$myrow1['categoryid']){
		echo '<option selected="True" value=' . $myrow1['categoryid'] . '>' . $myrow1['categorydescription'] . '</option>';
	} else {
		echo '<option value='. $myrow1['categoryid'] . '>' . $myrow1['categorydescription'] . '</option>';
	}
}

if (!isset($_POST['Keywords'])) {
    $_POST['Keywords']='';
}

if (!isset($_POST['StockCode'])) {
    $_POST['StockCode']='';
}

echo '</select>
		<td>' . _('Enter text extracts in the') . ' <b>' . _('description') . '</b>:</td>
		<td><input type="text" name="Keywords" size="20" maxlength="25" value="' . $_POST['Keywords'] . '" /></td>
	</tr>
    <tr>
		<td>&nbsp;</td>
		<td><font size="3"><b>' . _('OR') . ' </b></font>' . _('Enter extract of the') . ' <b>' . _('Stock Code') . '</b>:</td>
		<td><input type="text" name="StockCode" autofocus="autofocus" size="15" maxlength="18" value="' . $_POST['StockCode'] . '" /></td>
	</tr>
	</table>
	<br />
	<div class="centre">
		<button type="submit" name="Search">' . _('Search Now') . '</button>
	</div>';

if (isset($SearchResult)) {

	if (DB_num_rows($SearchResult)>1){

		echo '<br /><table cellpadding="2" class="selection">
			<tr>
				<th class="ascending">' . _('Code') . '</th>
	   			<th class="ascending">' . _('Description') . '</th>
	   			<th>' . _('Units') . '</th></tr>';
		$j = 1;
		$k=0; //row colour counter
		$ItemCodes = array();
		for ($i=1;$i<=$NumberOfOutputs;$i++){
			$ItemCodes[] =$_POST['OutputItem'.$i];
		}

		while ($myrow=DB_fetch_array($SearchResult)) {

			if (!in_array($myrow['stockid'],$ItemCodes)){
				if (function_exists('imagecreatefrompng') ){
					$ImageSource = '<img src="GetStockImage.php?automake=1&textcolor=FFFFFF&bgcolor=CCCCCC&StockID=' . urlencode($myrow['stockid']). '&text=&width=64&height=64" />';
				} else {
					if(file_exists($_SERVER['DOCUMENT_ROOT'] . $RootPath . '/' . $_SESSION['part_pics_dir'] . '/' . $myrow['stockid'] . '.jpg')) {
						$ImageSource = '<img src="' .$_SERVER['DOCUMENT_ROOT'] . $RootPath .  '/' . $_SESSION['part_pics_dir'] . '/' . $myrow['stockid'] . '.jpg" />';
					} else {
						$ImageSource = _('No Image');
					}
				}

				if ($k==1){
					echo '<tr class="EvenTableRows">';
					$k=0;
				} else {
					echo '<tr class="OddTableRows">';
					$k=1;
				}

				printf('<td><font size="1">%s</font></td>
						<td><font size="1">%s</font></td>
						<td><font size="1">%s</font></td>
						<td>%s</td>
						<td><font size="1"><a href="%s">'
						. _('Add to Work Order') . '</a></font></td>
						</tr>',
						$myrow['stockid'],
						$myrow['description'],
						$myrow['units'],
						$ImageSource,
						htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?WO=' . $_POST['WO'] . '&NewItem=' . $myrow['stockid'].'&Line='.$i);

				$j++;
			} //end if not already on work order
		}//end of while loop
	} //end if more than 1 row to show
	echo '</table>';

}#end if SearchResults to show

echo '</form>';
include('includes/footer.inc');
?>
