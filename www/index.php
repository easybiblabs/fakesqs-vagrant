<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Aws\Sqs\SqsClient;

$sqs_credentials = array(
    'region' => 'no-boop-1',
    'version' => 'latest',
    'endpoint' => 'http://localhost:4568',
    'credentials' => array(
        'key'    => 'accessid',
        'secret' => 'accesskey',
    )
);

echo '<pre>';


    // Instantiate the client
    $sqs_client = new SqsClient($sqs_credentials);
    // Get the queue URL from the queue name.
    $queue_options = array(
        'QueueName' => 'poo_queue'
    );

    $result = $sqs_client->createQueue($queue_options);
    print_r($result);
    echo '<hr/>';

    $result = $sqs_client->getQueueUrl($queue_options);
    print_r($result);
    echo '<hr/>';

    $queue_url = $result->get('QueueUrl');
    print_r($queue_url);
    echo '<hr/>';

    // The message we will be sending
    $our_message = array('foo' => 'blah', 'bar' => 'blah blah', 'date' => time());

    // Send the message
    /*
    $sqs_client->sendMessage(array(
        'QueueUrl' => $queue_url,
        'MessageBody' => json_encode($our_message)
    ));
    $sqs_client->sendMessage(array(
        'QueueUrl' => $queue_url,
        'MessageBody' => json_encode($our_message)
    ));
    $sqs_client->sendMessage(array(
        'QueueUrl' => $queue_url,
        'MessageBody' => json_encode($our_message)
    ));
	*/

    // Receive a message from the queue
    $result = $sqs_client->receiveMessage(array(
        'QueueUrl' => $queue_url
    ));
    print_r($result);
    echo '<hr/>';

    if ($result['Messages'] == null) {
        // No message to process
        echo "no message";
        exit;
    }

    // Get the message information
    $result_message = array_pop($result['Messages']);
    $queue_handle = $result_message['ReceiptHandle'];
    $message_json = $result_message['Body'];

    // process

    // delete message
    $result = $sqs_client->deleteMessage(array(
    	'QueueUrl' => $queue_url,
    	'ReceiptHandle' => $queue_handle
    ));
   
    print_r($result);
    print_r($queue_handle);
    echo '<hr/>';


