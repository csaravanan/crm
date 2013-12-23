<?php
/* $Id: SelectProduct.php 6378 2013-10-30 18:10:51Z rchacon $*/

$PricesSecurity = 12;//don't show pricing info unless security token 12 available to user
$SuppliersSecurity = 9; //don't show supplier purchasing info unless security token 9 available to user

include ('includes/session.inc');
$Title = _('Search Inventory Items');
/* webERP manual links before header.inc */
$ViewTopic= 'Inventory';
$BookMark = 'SelectingInventory';

include ('includes/header.inc');

if (isset($_GET['StockID'])) {
	//The page is called with a StockID
	$_GET['StockID'] = trim(mb_strtoupper($_GET['StockID']));
	$_POST['Select'] = trim(mb_strtoupper($_GET['StockID']));
}
echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $Theme . '/images/inventory.png" title="' . _('Inventory Items') . '" alt="" />' . ' ' . _('Inventory Items') . '</p>';
if (isset($_GET['NewSearch']) or isset($_POST['Next']) or isset($_POST['Previous']) or isset($_POST['Go'])) {
	unset($StockID);
	unset($_SESSION['SelectedStockItem']);
	unset($_POST['Select']);
}
if (!isset($_POST['PageOffset'])) {
	$_POST['PageOffset'] = 1;
} else {
	if ($_POST['PageOffset'] == 0) {
		$_POST['PageOffset'] = 1;
	}
}
if (isset($_POST['StockCode'])) {
	$_POST['StockCode'] = trim(mb_strtoupper($_POST['StockCode']));
}
// Always show the search facilities
$SQL = "SELECT categoryid,
				categorydescription
		FROM stockcategory
		ORDER BY categorydescription";
$result1 = DB_query($SQL, $db);
if (DB_num_rows($result1) == 0) {
	echo '<p class="bad">' . _('Problem Report') . ':<br />' . _('There are no stock categories currently defined please use the link below to set them up') . '</p>';
	echo '<br /><a href="' . $RootPath . '/StockCategories.php">' . _('Define Stock Categories') . '</a>';
	exit;
}
// end of showing search facilities
/* displays item options if there is one and only one selected */
if (!isset($_POST['Search']) AND (isset($_POST['Select']) OR isset($_SESSION['SelectedStockItem']))) {
	if (isset($_POST['Select'])) {
		$_SESSION['SelectedStockItem'] = $_POST['Select'];
		$StockID = $_POST['Select'];
		unset($_POST['Select']);
	} else {
		$StockID = $_SESSION['SelectedStockItem'];
	}
	$result = DB_query("SELECT stockmaster.description,
								stockmaster.longdescription,
								stockmaster.mbflag,
								stockcategory.stocktype,
								stockmaster.units,
								stockmaster.decimalplaces,
								stockmaster.controlled,
								stockmaster.serialised,
								stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS cost,
								stockmaster.discontinued,
								stockmaster.eoq,
								stockmaster.volume,
								stockmaster.grossweight,
								stockcategory.categorydescription,
								stockmaster.categoryid
						FROM stockmaster INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
						WHERE stockid='" . $StockID . "'", $db);
	$myrow = DB_fetch_array($result);
	$Its_A_Kitset_Assembly_Or_Dummy = false;
	$Its_A_Dummy = false;
	$Its_A_Kitset = false;
	$Its_A_Labour_Item = false;
	if ($myrow['discontinued']==1){
		$ItemStatus = '<p class="bad">' ._('Obsolete') . '</p>';
	} else {
		$ItemStatus = '';
	}
	echo '<table width="90%">
			<tr>
				<th colspan="3"><img src="' . $RootPath . '/css/' . $Theme . '/images/inventory.png" title="' . _('Inventory') . '" alt="" /><b title="' . $myrow['longdescription'] . '">' . ' ' . $StockID . ' - ' . $myrow['description'] . '</b> ' . $ItemStatus . '</th>
			</tr>';


	echo '<tr>
			<td style="width:40%" valign="top">
			<table>'; //nested table
	echo '<tr><th class="number">' . _('Category:') . '</th> <td colspan="2" class="select">' . $myrow['categorydescription'] , '</td></tr>';
	echo '<tr><th class="number">' . _('Item Type:') . '</th>
			<td colspan="2" class="select">';
	switch ($myrow['mbflag']) {
		case 'A':
			echo _('Assembly Item');
			$Its_A_Kitset_Assembly_Or_Dummy = True;
		break;
		case 'K':
			echo _('Kitset Item');
			$Its_A_Kitset_Assembly_Or_Dummy = True;
			$Its_A_Kitset = True;
		break;
		case 'D':
			echo _('Service/Labour Item');
			$Its_A_Kitset_Assembly_Or_Dummy = True;
			$Its_A_Dummy = True;
			if ($myrow['stocktype'] == 'L') {
				$Its_A_Labour_Item = True;
			}
		break;
		case 'B':
			echo _('Purchased Item');
		break;
		default:
			echo _('Manufactured Item');
		break;
	}
	echo '</td><th class="number">' . _('Control Level:') . '</th><td class="select">';
	if ($myrow['serialised'] == 1) {
		echo _('serialised');
	} elseif ($myrow['controlled'] == 1) {
		echo _('Batchs/Lots');
	} else {
		echo _('N/A');
	}
	echo '</td><th class="number">' . _('Units') . ':</th>
			<td class="select">' . $myrow['units'] . '</td></tr>';
	echo '<tr><th class="number">' . _('Volume') . ':</th>
			<td class="select" colspan="2">' . locale_number_format($myrow['volume'], 3) . '</td>
			<th class="number">' . _('Weight') . ':</th>
			<td class="select">' . locale_number_format($myrow['grossweight'], 3) . '</td>
			<th class="number">' . _('EOQ') . ':</th>
			<td class="select">' . locale_number_format($myrow['eoq'], $myrow['decimalplaces']) . '</td></tr>';
	if (in_array($PricesSecurity, $_SESSION['AllowedPageSecurityTokens']) OR !isset($PricesSecurity)) {
		echo '<tr><th colspan="2">' . _('Sell Price') . ':</th>
				<td class="select">';
		$PriceResult = DB_query("SELECT typeabbrev,
										price
								FROM prices
								WHERE currabrev ='" . $_SESSION['CompanyRecord']['currencydefault'] . "'
								AND typeabbrev = '" . $_SESSION['DefaultPriceList'] . "'
								AND debtorno=''
								AND branchcode=''
								AND startdate <= '". Date('Y-m-d') ."' AND ( enddate >= '" . Date('Y-m-d') . "' OR enddate = '0000-00-00')
								AND stockid='" . $StockID . "'", $db);
		if ($myrow['mbflag'] == 'K' OR $myrow['mbflag'] == 'A') {
			$CostResult = DB_query("SELECT SUM(bom.quantity * (stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost)) AS cost
									FROM bom INNER JOIN stockmaster
									ON bom.component=stockmaster.stockid
									WHERE bom.parent='" . $StockID . "'
									AND bom.effectiveto > '" . Date('Y-m-d') . "'
									AND bom.effectiveafter < '" . Date('Y-m-d') . "'", $db);
			$CostRow = DB_fetch_row($CostResult);
			$Cost = $CostRow[0];
		} else {
			$Cost = $myrow['cost'];
		}
		if (DB_num_rows($PriceResult) == 0) {
			echo _('No Default Price Set in Home Currency') . '</td></tr>';
			$Price = 0;
		} else {
			$PriceRow = DB_fetch_row($PriceResult);
			$Price = $PriceRow[1];
			echo $PriceRow[0] . '</td>
				<td class="select">' . locale_number_format($Price, $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
				<th class="number">' . _('Gross Profit') . '</th>
				<td class="select">';
			if ($Price > 0) {
				$GP = locale_number_format(($Price - $Cost) * 100 / $Price, 1);
			} else {
				$GP = _('N/A');
			}
			echo $GP . '%' . '</td>
				</tr>';
		}
		if ($myrow['mbflag'] == 'K' OR $myrow['mbflag'] == 'A') {
			$CostResult = DB_query("SELECT SUM(bom.quantity * (stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost)) AS cost
									FROM bom INNER JOIN
										stockmaster
									ON bom.component=stockmaster.stockid
									WHERE bom.parent = '" . $StockID . "'
									AND bom.effectiveafter < '" . Date('Y-m-d') . "'
									AND (bom.effectiveto > '" . Date('Y-m-d') . "'
									OR bom.effectiveto='0000-00-00')",
									 $db);
			$CostRow = DB_fetch_row($CostResult);
			$Cost = $CostRow[0];
		} else {
			$Cost = $myrow['cost'];
		}
		echo '<tr>
				<th class="number">' . _('Cost') . '</th>
				<td class="select">' . locale_number_format($Cost, $_SESSION['StandardCostDecimalPlaces']) . '</td>
			</tr>';
	} //end of if PricesSecuirty allows viewing of prices
	echo '</table>'; //end of first nested table
	// Item Category Property mod: display the item properties
	echo '<table>';

	$sql = "SELECT stkcatpropid,
					label,
					controltype,
					defaultvalue
				FROM stockcatproperties
				WHERE categoryid ='" . $myrow['categoryid'] . "'
				AND reqatsalesorder =0
				ORDER BY stkcatpropid";
	$PropertiesResult = DB_query($sql, $db);
	$PropertyCounter = 0;
	$PropertyWidth = array();
	while ($PropertyRow = DB_fetch_array($PropertiesResult)) {
		$PropValResult = DB_query("SELECT value
									FROM stockitemproperties
									WHERE stockid='" . $StockID . "'
									AND stkcatpropid ='" . $PropertyRow['stkcatpropid']."'", $db);
		$PropValRow = DB_fetch_row($PropValResult);
		if (DB_num_rows($PropValResult)==0){
			$PropertyValue = _('Not Set');
		} else {
			$PropertyValue = $PropValRow[0];
		}
		echo '<tr>
				<th align="right">' . $PropertyRow['label'] . ':</th>';
		switch ($PropertyRow['controltype']) {
			case 0:
			case 1:
				echo '<td class="select" style="width:60px">' . $PropertyValue;
			break;
			case 2; //checkbox
				echo '<td class="select" style="width:60px">';
				if ($PropertyValue == _('Not Set')){
					echo _('Not Set');
				} elseif ($PropertyValue == 1){
					echo _('Yes');
				} else {
					echo _('No');
				}
			break;
		} //end switch
	echo '</td></tr>';
	$PropertyCounter++;
} //end loop round properties for the item category
echo '</table></td>'; //end of Item Category Property mod
echo '<td style="width:15%; vertical-align:top">
			<table>'; //nested table to show QOH/orders
$QOH = 0;
switch ($myrow['mbflag']) {
	case 'A':
	case 'D':
	case 'K':
		$QOH = _('N/A');
		$QOO = _('N/A');
	break;
	case 'M':
	case 'B':
		$QOHResult = DB_query("SELECT sum(quantity)
						FROM locstock
						WHERE stockid = '" . $StockID . "'", $db);
		$QOHRow = DB_fetch_row($QOHResult);
		$QOH = locale_number_format($QOHRow[0], $myrow['decimalplaces']);
		$QOOSQL="SELECT SUM(purchorderdetails.quantityord -purchorderdetails.quantityrecd) AS QtyOnOrder
					FROM purchorders INNER JOIN purchorderdetails
					ON purchorders.orderno=purchorderdetails.orderno
					WHERE purchorderdetails.itemcode='" . $StockID . "'
					AND purchorderdetails.completed =0
					AND purchorders.status<>'Cancelled'
					AND purchorders.status<>'Pending'
					AND purchorders.status<>'Rejected'";
		$QOOResult = DB_query($QOOSQL, $db);
		if (DB_num_rows($QOOResult) == 0) {
			$QOO = 0;
		} else {
			$QOORow = DB_fetch_row($QOOResult);
			$QOO = $QOORow[0];
		}
		//Also the on work order quantities
		$sql = "SELECT SUM(woitems.qtyreqd-woitems.qtyrecd) AS qtywo
				FROM woitems INNER JOIN workorders
				ON woitems.wo=workorders.wo
				WHERE workorders.closed=0
				AND woitems.stockid='" . $StockID . "'";
		$ErrMsg = _('The quantity on work orders for this product cannot be retrieved because');
		$QOOResult = DB_query($sql, $db, $ErrMsg);
		if (DB_num_rows($QOOResult) == 1) {
			$QOORow = DB_fetch_row($QOOResult);
			$QOO+= $QOORow[0];
		}
		$QOO = locale_number_format($QOO, $myrow['decimalplaces']);
	break;
}
$Demand = 0;
$DemResult = DB_query("SELECT SUM(salesorderdetails.quantity-salesorderdetails.qtyinvoiced) AS dem
						FROM salesorderdetails INNER JOIN salesorders
						ON salesorders.orderno = salesorderdetails.orderno
						WHERE salesorderdetails.completed=0
						AND salesorders.quotation=0
						AND salesorderdetails.stkcode='" . $StockID . "'", $db);
$DemRow = DB_fetch_row($DemResult);
$Demand = $DemRow[0];
$DemAsComponentResult = DB_query("SELECT  SUM((salesorderdetails.quantity-salesorderdetails.qtyinvoiced)*bom.quantity) AS dem
									FROM salesorderdetails INNER JOIN salesorders
									ON salesorders.orderno = salesorderdetails.orderno
									INNER JOIN bom ON salesorderdetails.stkcode=bom.parent
									INNER JOIN stockmaster ON stockmaster.stockid=bom.parent
									WHERE salesorderdetails.quantity-salesorderdetails.qtyinvoiced > 0
									AND bom.component='" . $StockID . "'
									AND stockmaster.mbflag='A'
									AND salesorders.quotation=0", $db);
$DemAsComponentRow = DB_fetch_row($DemAsComponentResult);
$Demand+= $DemAsComponentRow[0];
//Also the demand for the item as a component of works orders
$sql = "SELECT SUM(qtypu*(woitems.qtyreqd - woitems.qtyrecd)) AS woqtydemo
		FROM woitems INNER JOIN worequirements
		ON woitems.stockid=worequirements.parentstockid
		INNER JOIN workorders
		ON woitems.wo=workorders.wo
		AND woitems.wo=worequirements.wo
		WHERE  worequirements.stockid='" . $StockID . "'
		AND workorders.closed=0";
$ErrMsg = _('The workorder component demand for this product cannot be retrieved because');
$DemandResult = DB_query($sql, $db, $ErrMsg);
if (DB_num_rows($DemandResult) == 1) {
	$DemandRow = DB_fetch_row($DemandResult);
	$Demand+= $DemandRow[0];
}
echo '<tr>
		<th class="number" style="width:15%">' . _('Quantity On Hand') . ':</th>
		<td style="width:17%" class="select">' . $QOH . '</td>
	</tr>';
echo '<tr>
		<th class="number" style="width:15%">' . _('Quantity Demand') . ':</th>
		<td style="width:17%" class="select">' . locale_number_format($Demand, $myrow['decimalplaces']) . '</td>
	</tr>';
echo '<tr>
		<th class="number" style="width:15%">' . _('Quantity On Order') . ':</th>
		<td style="width:17%" class="select">' . $QOO . '</td>
	</tr>
	</table>'; //end of nested table
echo '</td>'; //end cell of master table

if (($myrow['mbflag'] == 'B' OR ($myrow['mbflag'] == 'M'))
	AND (in_array($SuppliersSecurity, $_SESSION['AllowedPageSecurityTokens']))){

	echo '<td style="width:50%" valign="top"><table>
			<tr><th style="width:50%">' . _('Supplier') . '</th>
				<th style="width:15%">' . _('Cost') . '</th>
				<th style="width:5%">' . _('Curr') . '</th>
				<th style="width:15%">' . _('Eff Date') . '</th>
				<th style="width:10%">' . _('Lead Time') . '</th>
				<th style="width:10%">' . _('Min Order Qty') . '</th>
				<th style="width:5%">' . _('Prefer') . '</th></tr>';
	$SuppResult = DB_query("SELECT suppliers.suppname,
									suppliers.currcode,
									suppliers.supplierid,
									purchdata.price,
									purchdata.effectivefrom,
									purchdata.leadtime,
									purchdata.conversionfactor,
									purchdata.minorderqty,
									purchdata.preferred,
									currencies.decimalplaces
								FROM purchdata INNER JOIN suppliers
								ON purchdata.supplierno=suppliers.supplierid
								INNER JOIN currencies
								ON suppliers.currcode=currencies.currabrev
								WHERE purchdata.stockid = '" . $StockID . "'
							ORDER BY purchdata.preferred DESC, purchdata.effectivefrom DESC", $db);
	while ($SuppRow = DB_fetch_array($SuppResult)) {
		echo '<tr><td class="select">' . $SuppRow['suppname'] . '</td>
					<td class="select">' . locale_number_format($SuppRow['price'] / $SuppRow['conversionfactor'], $SuppRow['decimalplaces']) . '</td>
					<td class="select">' . $SuppRow['currcode'] . '</td>
					<td class="select">' . ConvertSQLDate($SuppRow['effectivefrom']) . '</td>
					<td class="select">' . $SuppRow['leadtime'] . '</td>
					<td class="select">' . $SuppRow['minorderqty'] . '</td>';

		if ($SuppRow['preferred']==1) { //then this is the preferred supplier
			echo '<td class="select">' . _('Yes') . '</td>';
		} else {
			echo '<td class="select">' . _('No') . '</td>';
		}
		echo '<td class="select"><a href="' . $RootPath . '/PO_Header.php?NewOrder=Yes&amp;SelectedSupplier=' .
			$SuppRow['supplierid'] . '&amp;StockID=' . $StockID . '&amp;Quantity='.$SuppRow['minorderqty'].'&amp;LeadTime='.$SuppRow['leadtime'] . '">' . _('Order') . ' </a></td>';
		echo '</tr>';
	}
	echo '</table>';
	DB_data_seek($result, 0);
}
echo '</td></tr></table><br />'; // end first item details table
echo '<table width="90%"><tr>
		<th style="width:33%">' . _('Item Inquiries') . '</th>
		<th style="width:33%">' . _('Item Transactions') . '</th>
		<th style="width:33%">' . _('Item Maintenance') . '</th>
	</tr>';
echo '<tr><td valign="top" class="select">';
/*Stock Inquiry Options */
echo '<a href="' . $RootPath . '/StockMovements.php?StockID=' . $StockID . '">' . _('Show Stock Movements') . '</a><br />';
if ($Its_A_Kitset_Assembly_Or_Dummy == False) {
	echo '<a href="' . $RootPath . '/StockStatus.php?StockID=' . $StockID . '">' . _('Show Stock Status') . '</a><br />';
	echo '<a href="' . $RootPath . '/StockUsage.php?StockID=' . $StockID . '">' . _('Show Stock Usage') . '</a><br />';
}
echo '<a href="' . $RootPath . '/SelectSalesOrder.php?SelectedStockItem=' . $StockID . '">' . _('Search Outstanding Sales Orders') . '</a><br />';
echo '<a href="' . $RootPath . '/SelectCompletedOrder.php?SelectedStockItem=' . $StockID . '">' . _('Search Completed Sales Orders') . '</a><br />';
if ($Its_A_Kitset_Assembly_Or_Dummy == False) {
	echo '<a href="' . $RootPath . '/PO_SelectOSPurchOrder.php?SelectedStockItem=' . $StockID . '">' . _('Search Outstanding Purchase Orders') . '</a><br />';
	echo '<a href="' . $RootPath . '/PO_SelectPurchOrder.php?SelectedStockItem=' . $StockID . '">' . _('Search All Purchase Orders') . '</a><br />';
	echo '<a href="' . $RootPath . '/' . $_SESSION['part_pics_dir'] . '/' . $StockID . '.jpg">' . _('Show Part Picture (if available)') . '</a><br />';
}
if ($Its_A_Dummy == False) {
	echo '<a href="' . $RootPath . '/BOMInquiry.php?StockID=' . $StockID . '">' . _('View Costed Bill Of Material') . '</a><br />';
	echo '<a href="' . $RootPath . '/WhereUsedInquiry.php?StockID=' . $StockID . '">' . _('Where This Item Is Used') . '</a><br />';
}
if ($Its_A_Labour_Item == True) {
	echo '<a href="' . $RootPath . '/WhereUsedInquiry.php?StockID=' . $StockID . '">' . _('Where This Labour Item Is Used') . '</a><br />';
}
wikiLink('Product', $StockID);
echo '</td><td valign="top" class="select">';
/* Stock Transactions */
if ($Its_A_Kitset_Assembly_Or_Dummy == false) {
	echo '<a href="' . $RootPath . '/StockAdjustments.php?StockID=' . $StockID . '">' . _('Quantity Adjustments') . '</a><br />';
	echo '<a href="' . $RootPath . '/StockTransfers.php?StockID=' . $StockID . '&amp;NewTransfer=true">' . _('Location Transfers') . '</a><br />';
	//show the item image if it has been uploaded
	 if (function_exists('imagecreatefromjpg')){
		if ($_SESSION['ShowStockidOnImages'] == '0'){
			$StockImgLink = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC'.
								'&amp;StockID='.urlencode($StockID).
								'&amp;text='.
								'&amp;width=100'.
								'&amp;height=100'.
								'" alt="" />';
		} else {
			$StockImgLink = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC'.
								'&amp;StockID='.urlencode($StockID).
								'&amp;text='. $StockID .
								'&amp;width=100'.
								'&amp;height=100'.
								'" alt="" />';
		}
	} else {
		if( isset($StockID) AND file_exists($_SESSION['part_pics_dir'] . '/' .$StockID.'.jpg') ) {
			$StockImgLink = '<img src="' . $_SESSION['part_pics_dir'] . '/' . $StockID . '.jpg" height="100" width="100" />';
		} else {
			$StockImgLink = _('No Image');
		}
	}

	echo '<div class="centre">' . $StockImgLink . '</div>';


	if (($myrow['mbflag'] == 'B')
		AND (in_array($SuppliersSecurity, $_SESSION['AllowedPageSecurityTokens']))
		AND $myrow['discontinued']==0){
		echo '<br />';
		$SuppResult = DB_query("SELECT suppliers.suppname,
										suppliers.supplierid,
										purchdata.preferred,
										purchdata.minorderqty,
										purchdata.leadtime
									FROM purchdata INNER JOIN suppliers
									ON purchdata.supplierno=suppliers.supplierid
									WHERE purchdata.stockid='" . $StockID . "'
									ORDER BY purchdata.effectivefrom DESC", $db);
		$LastSupplierShown = "";
		while ($SuppRow = DB_fetch_array($SuppResult)) {
			if ($LastSupplierShown != $SuppRow['supplierid']){
				if (($myrow['eoq'] < $SuppRow['minorderqty'])) {
					$EOQ = $SuppRow['minorderqty'];
				} else {
					$EOQ = $myrow['eoq'];
				}
				echo '<a href="' . $RootPath . '/PO_Header.php?NewOrder=Yes' . '&amp;SelectedSupplier=' . $SuppRow['supplierid'] . '&amp;StockID=' . $StockID . '&amp;Quantity='.$EOQ.'&amp;LeadTime='.$SuppRow['leadtime'].'">' .  _('Purchase this Item from') . ' ' . $SuppRow['suppname'] . '</a>
				<br />';
				$LastSupplierShown = $SuppRow['supplierid'];
			}
			/**/
		} /* end of while */
	} /* end of $myrow['mbflag'] == 'B' */
} /* end of ($Its_A_Kitset_Assembly_Or_Dummy == False) */
echo '</td><td valign="top" class="select">';
/* Stock Maintenance Options */
echo '<a href="' . $RootPath . '/Stocks.php?">' . _('Insert New Item') . '</a><br />';
echo '<a href="' . $RootPath . '/Stocks.php?StockID=' . $StockID . '">' . _('Modify Item Details') . '</a><br />';
if ($Its_A_Kitset_Assembly_Or_Dummy == False) {
	echo '<a href="' . $RootPath . '/StockReorderLevel.php?StockID=' . $StockID . '">' . _('Maintain Reorder Levels') . '</a><br />';
	echo '<a href="' . $RootPath . '/StockCostUpdate.php?StockID=' . $StockID . '">' . _('Maintain Standard Cost') . '</a><br />';
	echo '<a href="' . $RootPath . '/PurchData.php?StockID=' . $StockID . '">' . _('Maintain Purchasing Data') . '</a><br />';
}
if ($Its_A_Labour_Item == True) {
	echo '<a href="' . $RootPath . '/StockCostUpdate.php?StockID=' . $StockID . '">' . _('Maintain Standard Cost') . '</a><br />';
}
if (!$Its_A_Kitset) {
	echo '<a href="' . $RootPath . '/Prices.php?Item=' . $StockID . '">' . _('Maintain Pricing') . '</a><br />';
	if (isset($_SESSION['CustomerID'])
		AND $_SESSION['CustomerID'] != ''
		AND mb_strlen($_SESSION['CustomerID']) > 0) {
		echo '<a href="' . $RootPath . '/Prices_Customer.php?Item=' . $StockID . '">' . _('Special Prices for customer') . ' - ' . $_SESSION['CustomerID'] . '</a><br />';
	}
	echo '<a href="' . $RootPath . '/DiscountCategories.php?StockID=' . $StockID . '">' . _('Maintain Discount Category') . '</a><br />';
    echo '<a href="' . $RootPath . '/StockClone.php?OldStockID=' . $StockID . '">' . _('Clone This Item') . '</a><br />';
}
echo '</td></tr></table>';
} else {
	// options (links) to pages. This requires stock id also to be passed.
	echo '<table width="90%" cellpadding="4">';
	echo '<tr>
		<th style="width:33%">' . _('Item Inquiries') . '</th>
		<th style="width:33%">' . _('Item Transactions') . '</th>
		<th style="width:33%">' . _('Item Maintenance') . '</th>
	</tr>';
	echo '<tr><td class="select">';
	/*Stock Inquiry Options */
	echo '</td><td class="select">';
	/* Stock Transactions */
	echo '</td><td class="select">';
	/*Stock Maintenance Options */
	echo '<a href="' . $RootPath . '/Stocks.php?">' . _('Insert New Item') . '</a><br />';
	echo '</td></tr></table>';
} // end displaying item options if there is one and only one record
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $Theme . '/images/magnifier.png" title="' . _('Search') . '" alt="" />' . ' ' . _('Search for Inventory Items'). '</p>';
echo '<table class="selection"><tr>';
echo '<td>' . _('In Stock Category') . ':';
echo '<select name="StockCat">';
if (!isset($_POST['StockCat'])) {
	$_POST['StockCat'] ='';
}
if ($_POST['StockCat'] == 'All') {
	echo '<option selected="selected" value="All">' . _('All') . '</option>';
} else {
	echo '<option value="All">' . _('All') . '</option>';
}
while ($myrow1 = DB_fetch_array($result1)) {
	if ($myrow1['categoryid'] == $_POST['StockCat']) {
		echo '<option selected="selected" value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
	} else {
		echo '<option value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
	}
}
echo '</select></td>';
echo '<td>' . _('Enter partial') . '<b> ' . _('Description') . '</b>:</td><td>';
if (isset($_POST['Keywords'])) {
	echo '<input type="text" autofocus="autofocus" name="Keywords" value="' . $_POST['Keywords'] . '" title="' . _('Enter text that you wish to search for in the item description') . '" size="20" maxlength="25" />';
} else {
	echo '<input type="text" autofocus="autofocus" name="Keywords" title="' . _('Enter text that you wish to search for in the item description') . '" size="20" maxlength="25" />';
}
echo '</td></tr><tr><td></td>';
echo '<td><b>' . _('OR') . ' ' . '</b>' . _('Enter partial') . ' <b>' . _('Stock Code') . '</b>:</td>';
echo '<td>';
if (isset($_POST['StockCode'])) {
	echo '<input type="text" name="StockCode" value="' . $_POST['StockCode'] . '" title="' . _('Enter text that you wish to search for in the item code') . '" size="15" maxlength="18" />';
} else {
	echo '<input type="text" name="StockCode" title="' . _('Enter text that you wish to search for in the item code') . '" size="15" maxlength="18" />';
}
echo '</td></tr></table><br />';
echo '<div class="centre"><input type="submit" name="Search" value="' . _('Search Now') . '" /></div><br />';
echo '</div>
      </form>';
// query for list of record(s)
if(isset($_POST['Go']) OR isset($_POST['Next']) OR isset($_POST['Previous'])) {
	$_POST['Search']='Search';
}
if (isset($_POST['Search']) OR isset($_POST['Go']) OR isset($_POST['Next']) OR isset($_POST['Previous'])) {
	if (!isset($_POST['Go']) AND !isset($_POST['Next']) AND !isset($_POST['Previous'])) {
		// if Search then set to first page
		$_POST['PageOffset'] = 1;
	}
	if ($_POST['Keywords'] AND $_POST['StockCode']) {
		prnMsg (_('Stock description keywords have been used in preference to the Stock code extract entered'), 'info');
	}
	if ($_POST['Keywords']) {
		//insert wildcard characters in spaces
		$_POST['Keywords'] = mb_strtoupper($_POST['Keywords']);
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';
		if ($_POST['StockCat'] == 'All') {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.longdescription,
							SUM(locstock.quantity) AS qoh,
							stockmaster.units,
							stockmaster.mbflag,
							stockmaster.discontinued,
							stockmaster.decimalplaces
						FROM stockmaster LEFT JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid,
							locstock
						WHERE stockmaster.stockid=locstock.stockid
						AND stockmaster.description " . LIKE . " '$SearchString'
						GROUP BY stockmaster.stockid,
							stockmaster.description,
							stockmaster.longdescription,
							stockmaster.units,
							stockmaster.mbflag,
							stockmaster.discontinued,
							stockmaster.decimalplaces
						ORDER BY stockmaster.discontinued, stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.longdescription,
							SUM(locstock.quantity) AS qoh,
							stockmaster.units,
							stockmaster.mbflag,
							stockmaster.discontinued,
							stockmaster.decimalplaces
						FROM stockmaster INNER JOIN locstock
						ON stockmaster.stockid=locstock.stockid
						WHERE description " . LIKE . " '$SearchString'
						AND categoryid='" . $_POST['StockCat'] . "'
						GROUP BY stockmaster.stockid,
							stockmaster.description,
							stockmaster.longdescription,
							stockmaster.units,
							stockmaster.mbflag,
							stockmaster.discontinued,
							stockmaster.decimalplaces
						ORDER BY stockmaster.discontinued, stockmaster.stockid";
		}
	} elseif (isset($_POST['StockCode'])) {
		$_POST['StockCode'] = mb_strtoupper($_POST['StockCode']);
		if ($_POST['StockCat'] == 'All') {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.longdescription,
							stockmaster.mbflag,
							stockmaster.discontinued,
							SUM(locstock.quantity) AS qoh,
							stockmaster.units,
							stockmaster.decimalplaces
						FROM stockmaster
						INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
						INNER JOIN locstock ON stockmaster.stockid=locstock.stockid
						WHERE stockmaster.stockid " . LIKE . " '%" . $_POST['StockCode'] . "%'
						GROUP BY stockmaster.stockid,
							stockmaster.description,
							stockmaster.longdescription,
							stockmaster.units,
							stockmaster.mbflag,
							stockmaster.discontinued,
							stockmaster.decimalplaces
						ORDER BY stockmaster.discontinued, stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.mbflag,
						stockmaster.discontinued,
						sum(locstock.quantity) as qoh,
						stockmaster.units,
						stockmaster.decimalplaces
					FROM stockmaster INNER JOIN locstock
					ON stockmaster.stockid=locstock.stockid
					WHERE stockmaster.stockid " . LIKE . " '%" . $_POST['StockCode'] . "%'
					AND categoryid='" . $_POST['StockCat'] . "'
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.units,
						stockmaster.mbflag,
						stockmaster.discontinued,
						stockmaster.decimalplaces
					ORDER BY stockmaster.discontinued, stockmaster.stockid";
		}
	} elseif (!isset($_POST['StockCode']) AND !isset($_POST['Keywords'])) {
		if ($_POST['StockCat'] == 'All') {
			$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.mbflag,
						stockmaster.discontinued,
						SUM(locstock.quantity) AS qoh,
						stockmaster.units,
						stockmaster.decimalplaces
					FROM stockmaster
					LEFT JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid,
						locstock
					WHERE stockmaster.stockid=locstock.stockid
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.units,
						stockmaster.mbflag,
						stockmaster.discontinued,
						stockmaster.decimalplaces
					ORDER BY stockmaster.discontinued, stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.mbflag,
						stockmaster.discontinued,
						SUM(locstock.quantity) AS qoh,
						stockmaster.units,
						stockmaster.decimalplaces
					FROM stockmaster INNER JOIN locstock
					ON stockmaster.stockid=locstock.stockid
					WHERE categoryid='" . $_POST['StockCat'] . "'
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.units,
						stockmaster.mbflag,
						stockmaster.discontinued,
						stockmaster.decimalplaces
					ORDER BY stockmaster.discontinued, stockmaster.stockid";
		}
	}
	$ErrMsg = _('No stock items were returned by the SQL because');
	$DbgMsg = _('The SQL that returned an error was');
	$SearchResult = DB_query($SQL, $db, $ErrMsg, $DbgMsg);
	if (DB_num_rows($SearchResult) == 0) {
		prnMsg(_('No stock items were returned by this search please re-enter alternative criteria to try again'), 'info');
	}
	unset($_POST['Search']);
}
/* end query for list of records */
/* display list if there is more than one record */
if (isset($SearchResult) AND !isset($_POST['Select'])) {
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
    echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	$ListCount = DB_num_rows($SearchResult);
	if ($ListCount > 0) {
		// If the user hit the search button and there is more than one item to show
		$ListPageMax = ceil($ListCount / $_SESSION['DisplayRecordsMax']);
		if (isset($_POST['Next'])) {
			if ($_POST['PageOffset'] < $ListPageMax) {
				$_POST['PageOffset'] = $_POST['PageOffset'] + 1;
			}
		}
		if (isset($_POST['Previous'])) {
			if ($_POST['PageOffset'] > 1) {
				$_POST['PageOffset'] = $_POST['PageOffset'] - 1;
			}
		}
		if ($_POST['PageOffset'] > $ListPageMax) {
			$_POST['PageOffset'] = $ListPageMax;
		}
		if ($ListPageMax > 1) {
			echo '<div class="centre"><br />&nbsp;&nbsp;' . $_POST['PageOffset'] . ' ' . _('of') . ' ' . $ListPageMax . ' ' . _('pages') . '. ' . _('Go to Page') . ': ';
			echo '<select name="PageOffset">';
			$ListPage = 1;
			while ($ListPage <= $ListPageMax) {
				if ($ListPage == $_POST['PageOffset']) {
					echo '<option value="' . $ListPage . '" selected="selected">' . $ListPage . '</option>';
				} else {
					echo '<option value="' . $ListPage . '">' . $ListPage . '</option>';
				}
				$ListPage++;
			}
			echo '</select>
				<input type="submit" name="Go" value="' . _('Go') . '" />
				<input type="submit" name="Previous" value="' . _('Previous') . '" />
				<input type="submit" name="Next" value="' . _('Next') . '" />
				<input type="hidden" name="Keywords" value="'.$_POST['Keywords'].'" />
				<input type="hidden" name="StockCat" value="'.$_POST['StockCat'].'" />
				<input type="hidden" name="StockCode" value="'.$_POST['StockCode'].'" />
				<br />
				</div>';
		}
		echo '<table id="ItemSearchTable" class="selection">';
		$TableHeader = '<tr>
							<th>' . _('Stock Status') . '</th>
							<th class="ascending">' . _('Code') . '</th>
							<th class="ascending">' . _('Description') . '</th>
							<th>' . _('Total Qty On Hand') . '</th>
							<th>' . _('Units') . '</th>
						</tr>';
		echo $TableHeader;
		$j = 1;
		$k = 0; //row counter to determine background colour
		$RowIndex = 0;
		if (DB_num_rows($SearchResult) <> 0) {
			DB_data_seek($SearchResult, ($_POST['PageOffset'] - 1) * $_SESSION['DisplayRecordsMax']);
		}
		while (($myrow = DB_fetch_array($SearchResult)) AND ($RowIndex <> $_SESSION['DisplayRecordsMax'])) {
			if ($k == 1) {
				echo '<tr class="EvenTableRows">';
				$k = 0;
			} else {
				echo '<tr class="OddTableRows">';
				$k++;
			}
			if ($myrow['mbflag'] == 'D') {
				$qoh = _('N/A');
			} else {
				$qoh = locale_number_format($myrow['qoh'], $myrow['decimalplaces']);
			}
			if ($myrow['discontinued']==1){
				$ItemStatus = '<p class="bad">' . _('Obsolete') . '</p>';
			} else {
				$ItemStatus ='';
			}

			echo '<td>' . $ItemStatus . '</td>
				<td><input type="submit" name="Select" value="' . $myrow['stockid'] . '" /></td>
				<td title="'. $myrow['longdescription'] . '">' . $myrow['description'] . '</td>
				<td class="number">' . $qoh . '</td>
				<td>' . $myrow['units'] . '</td>
				<td><a target="_blank" href="' . $RootPath . '/StockStatus.php?StockID=' . $myrow['stockid'].'">' . _('View') . '</a></td>
				</tr>';
/*
			$j++;

			if ($j == 20 AND ($RowIndex + 1 != $_SESSION['DisplayRecordsMax'])) {
				$j = 1;
				echo $TableHeader;
			}
			*/
			$RowIndex = $RowIndex + 1;
			//end of page full new headings if
		}
		//end of while loop
		echo '</table>
              </div>
              </form>
              <br />';
	}
}
/* end display list if there is more than one record */

include ('includes/footer.inc');
?>
