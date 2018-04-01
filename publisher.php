<?php
/*
# PHP amqp(RabbitMQ)  Demo - Publisher
*/

$conn_args = array(
    'host' => 'localhost',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest',
    'vhost' => '/'
);
$e_name = 'e_demo'; //交换机名
$q_name = 'q_demo'; //无需队列名称，
$k_route = 'key_1'; //路由key
try {
/* $conn = new AMQPConnection($conn_args); */
$conn = new AMQPConnection();
$conn->connect();
if (!$conn->isConnected()) {
    die('Conexiune esuata');
}
/* if (!$conn->connect()) { */
/*     die("Cannot connect to the broker!\n"); */
/* } */
$channel = new AMQPChannel($conn);
if (!$channel->isConnected()) {
    die('Connection through channel failed');
}


$message = 'this is a test message';

$ex = new AMQPExchange($channel);

$ex->setName($e_name);

//$channel->startTransaction();

$q = new AMQPQueue($channel);
$q->setName($q_name);

for($i =0; $i < 500000000; ++ $i)
{
    //echo "Send Message" . $ex->publish($message,$k_route) . "\n";
    if ($ex->publish($message, $k_route)) {
        echo $i . " Published!\n";
    }
sleep(1);
}
} catch (AMQPExcetpion $e) {
    echo 'AMQP Exception - '.$e->getMessage();

}catch(AMQPConnectionException $e){
    echo 'AMQP Connection Exception - '.$e->getMessage();

}catch(AMQPExchangeException $e){
   echo 'AMQP Exchange Exception - '.$e->getMessajge();

}catch(AMQPQueueException  $e){
   echo 'AMQP Queue Exception - '.$e->getMessage();
}
//$conn->disconnect();

