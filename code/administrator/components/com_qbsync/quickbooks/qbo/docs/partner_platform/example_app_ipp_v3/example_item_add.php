<?php

require_once dirname(__FILE__) . '/config.php';

require_once dirname(__FILE__) . '/views/header.tpl.php';

?>

<pre>

<?php

$ItemService = new QuickBooks_IPP_Service_Item();

$Item = new QuickBooks_IPP_Object_Item();

$Item->setName('Magic Mirror');
$Item->setType('Inventory');
$Item->setTrackQtyOnHand(true);
$Item->setQtyOnHand(10);
$Item->setIncomeAccountRef('125');
$Item->setExpenseAccountRef('126');
$Item->setAssetAccountRef('124');
$Item->setInvStartDate(date('Y-m-d'));

if ($resp = $ItemService->add($Context, $realm, $Item))
{
	print('Our new Item ID is: [' . $resp . ']');
}
else
{
	print($ItemService->lastError($Context));
}


print('<br><br><br><br>');
print("\n\n\n\n\n\n\n\n");
print('Request [' . $IPP->lastRequest() . ']');
print("\n\n\n\n");
print('Response [' . $IPP->lastResponse() . ']');
print("\n\n\n\n\n\n\n\n\n");

?>

</pre>

<?php

require_once dirname(__FILE__) . '/views/footer.tpl.php';
