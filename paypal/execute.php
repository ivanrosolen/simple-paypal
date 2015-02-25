<?php
require __DIR__ . '/bootstrap.php';
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

if (isset($_GET['success']) && $_GET['success'] == 'true') {

    $paymentId = $_GET['paymentId'];
    $payment = Payment::get($paymentId, $apiContext);

    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);

    try {

        $result = $payment->execute($execution, $apiContext);

        try {
            $payment = Payment::get($paymentId, $apiContext);
        } catch (Exception $ex) {
            // @todo: Falha ao recuperar dados do pagamento
            exit(1);
        }
    } catch (Exception $ex) {
        // @todo: Falha ao pagar
        exit(1);
    }

    // @todo: Tudo ok, salvar no banco e redirecionar
    $payload = json_encode($payment);

    header('Location: http://site.com.br/xxx.php');


} else {
    echo 'Falhou, olhar log :(';
    exit;
}