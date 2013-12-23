<?php

include('includes/session.inc');
$Title = _('Customer Purchases');
include('includes/header.inc');

if (isset($_GET['DebtorNo'])) {
	$DebtorNo = $_GET['DebtorNo'];
} //isset($_GET['DebtorNo'])
else if (isset($_POST['DebtorNo'])) {
	$DebtorNo = $_POST['DebtorNo'];
} //isset($_POST['DebtorNo'])
else {
	prnMsg(_('This script must be called with a customer code.'), 'info');
	include('includes/footer.inc');
	exit;
}

$SQL = "SELECT debtorsmaster.name,
				custbranch.brname
		FROM debtorsmaster
		INNER JOIN custbranch
			ON debtorsmaster.debtorno=custbranch.debtorno
		WHERE debtorsmaster.debtorno = '" . $DebtorNo . "'";

$ErrMsg = _('The customer details could not be retrieved by the SQL because');
$CustomerResult = DB_query($SQL, $db, $ErrMsg);
$CustomerRecord = DB_fetch_array($CustomerResult);

echo '<p class="page_title_text">
		<img src="' . $RootPath . '/css/' . $Theme . '/images/customer.png" title="' . _('Customer') . '" alt="" /> ' . _('Items Purchased by Customer') . ' : ' . $CustomerRecord['name'] . '
	</p>';

$SQL = "SELECT stockmoves.stockid,
				stockmaster.description,
				systypes.typename,
				transno,
				locations.locationname,
				trandate,
				branchcode,
				price,
				reference,
				qty,
				narrative
			FROM stockmoves
			INNER JOIN stockmaster
				ON stockmaster.stockid=stockmoves.stockid
			INNER JOIN systypes
				ON stockmoves.type=systypes.typeid
			INNER JOIN locations
				ON stockmoves.loccode=locations.loccode
			WHERE debtorno='" . $DebtorNo . "'
			ORDER BY trandate DESC";
$ErrMsg = _('The stock movement details could not be retrieved by the SQL because');
$StockMovesResult = DB_query($SQL, $db, $ErrMsg);

if (DB_num_rows($StockMovesResult) == 0) {
	echo '<br />';
	prnMsg(_('There are no items for this customer'), 'notice');
	echo '<br />';
} //DB_num_rows($StockMovesResult) == 0
else {
	echo '<table class="selection">
			<tr>
				<th>' . _('Transaction Date') . '</th>
				<th>' . _('Stock ID') . '</th>
				<th>' . _('Description') . '</th>
				<th>' . _('Type') . '</th>
				<th>' . _('Transaction No.') . '</th>
				<th>' . _('From Location') . '</th>
				<th>' . _('Branch Code') . '</th>
				<th>' . _('Price') . '</th>
				<th>' . _('Quantity') . '</th>
				<th>' . _('Amount of Sale') . '</th>
				<th>' . _('Reference') . '</th>
				<th>' . _('Narrative') . '</th>
			</tr>';

	while ($StockMovesRow = DB_fetch_array($StockMovesResult)) {
		echo '<tr>
				<td>' . ConvertSQLDate($StockMovesRow['trandate']) . '</td>
				<td>' . $StockMovesRow['stockid'] . '</td>
				<td>' . $StockMovesRow['description'] . '</td>
				<td>' . $StockMovesRow['typename'] . '</td>
				<td>' . $StockMovesRow['transno'] . '</td>
				<td>' . $StockMovesRow['locationname'] . '</td>
				<td>' . $StockMovesRow['branchcode'] . '</td>
				<td class="number">' . locale_number_format($StockMovesRow['price'], $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
				<td class="number">' . locale_number_format(-$StockMovesRow['qty'], $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
				<td class="number">' . locale_number_format((-$StockMovesRow['qty'] * $StockMovesRow['price']), $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
				<td>' . $StockMovesRow['reference'] . '</td>
				<td>' . $StockMovesRow['narrative'] . '</td>
			</tr>';

	} //$StockMovesRow = DB_fetch_array($StockMovesResult)

	echo '</table>';
}

echo '<br /><div class="centre"><a href="SelectCustomer.php">' . _('Return to customer selection screen') . '</a></div><br />';

include('includes/footer.inc');
?>