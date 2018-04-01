<?php
/*
# PHP amqp(RabbitMQ)  Demo - Consumer
*/

$conn_args = array(
    'host' => 'localhost',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest',
    'vhost' => '/'
);
$e_name = 'e_demo'; //交换机名
$q_name = 'q_demo'; //队列名称，
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


//创建交换机
$ex = new AMQPExchange($channel);

$ex->setName($e_name);
//$ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型

//$ex->setFlags(AMQP_DURABLE); //持久化

/* echo "Exchange Status:" . $ex->declare() . "\n"; */

//创建队列


$q = new AMQPQueue($channel);
$q->setName($q_name);
//$q->setFlags(AMQP_DURABLE);
//echo "Message  Total: " . $q->declare() . "\n";

//绑定交换机与队列
/* echo  "Queue Bind: " . $q->bind($e_name, $k_route) . "\n"; */

//阻塞模式接收消息

/* echo "Message: " . "\n"; */
$counter = 0;
while(true)
{
    $envelope = $q->get();
    /* $q->consume('processMessage'); */
    /* echo "Send Message" . $ex->publish($message,$k_route . "\n"); */
    //$q->consume('processMessage',AQP_AUTOACK); //自动ack应答
    $message =$envelope->getBody();
    if ($message) {
        echo $message .  "\n";
        $q->ack($envelope->getDeliveryTag());
    } else {
        $q->nack($envelope->getDeliveryTag(),AMQP_REQUEUE);
    }
    echo $counter . " Consuming...";
    $counter++;
    sleep(1);
}


if ($counter) {
    echo "Consuming...";
} else {
    echo "No messages to Consume";
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

//$conn->disconnect();



/*
 * 消息处理函数
 * 处理消息
 */

function processMessage($envelop, $queue)
{
    $msg = $envelopoe->getBody();
    echo $msg . "\n";
    $queue->ack($envelop->getDeliverytag());  // 手动发送SCK应答

}
