<?php
require_once __DIR__ . '/bootstrap.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

$p = ( isset($_GET['p']) && !empty($_GET['p']) ) ? trim($_GET['p']) : 'hash1';

$payer = new Payer();
$payer->setPaymentMethod('paypal');

$item = new Item();


// Não usar isso !!!!
switch ($p) {
    case 'hash1':
        $item->setName('Produto 1')
             ->setCurrency('BRL')
             ->setQuantity(1)
             ->setPrice(0.99);
    break;
    case 'hash2':
        $item->setName('Produto 2')
             ->setCurrency('BRL')
             ->setQuantity(1)
             ->setPrice(1.99);
    break;
    case 'hash3':
        $item->setName('Produto 3')
             ->setCurrency('BRL')
             ->setQuantity(1)
             ->setPrice(4.99);
    break;
    case 'hash4':
        $item->setName('Produto 4')
             ->setCurrency('BRL')
             ->setQuantity(1)
             ->setPrice(9.99);
    break;
    default:
        $item->setName('Produto 1')
             ->setCurrency('BRL')
             ->setQuantity(1)
             ->setPrice(0.99);
    break;
}

$itemList = new ItemList();
$itemList->setItems(array($item));

// Cálculos 'ninjas'
$shipping = 1.2;
$tax      = 0.65;
$total    = $item->getPrice() + $shipping + $tax;

$details = new Details();
$details->setShipping($shipping)
        ->setTax($tax)
        ->setSubtotal($item->getPrice());

$amount = new Amount();
$amount->setCurrency('BRL')
       ->setTotal($total)
       ->setDetails($details);

$transaction = new Transaction();
$transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Descrição do Pagamento')
            ->setInvoiceNumber(uniqid());

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl(PAYPAL_TRUE)
             ->setCancelUrl(PAYPAL_FALSE);

$payment = new Payment();
$payment->setIntent('order')
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction))
        // para mudar o logo e infos criar um Profile
        ->setExperienceProfileId(PAYPAL_PROFILE);

$request = clone $payment;

try {
    $payment->create($apiContext);
} catch (Exception $ex) {
    // @todo: Deu zica ao criar a order
    exit(1);
}

$approvalUrl = $payment->getApprovalLink();

// @todo: Salvar esse retorno no banco?
error_log(json_encode($payment));

header('Location: '.$approvalUrl);