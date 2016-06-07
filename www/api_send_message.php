<?php
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/vendor/autoload.php';

    use Aws\Sqs\SqsClient;

    if(isset($_GET['queue_name']) && isset($_GET['body'])) {

        # Create Queue
        # 
        # http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#sendmessage
        # http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#getqueueurl

        # parse out the GETs
        $queue_name = $_GET['queue_name'];
        $message_body = $_GET['body'];

        if(isset($_GET['delay_seconds'])) $delay_seconds = intval($_GET['delay_seconds']);
        else $delay_seconds = 0;

        // set up credentials from config
        $sqs_credentials = unserialize(AWS_CREDENTIALS);

        // Instantiate the client
        $sqs_client = new SqsClient($sqs_credentials);

        // set up request options
        $request_options = array( 
            'QueueName' => $queue_name
            );

        // make the request
        $result = $sqs_client->getQueueUrl($request_options);

        // get the queue_url
        $queue_url = $result['QueueUrl'];

        $date = date('m/d/Y h:i:s a', time());
        $message_body = '('.$date.') '.$message_body;

        // set up request options
        $request_options = array( 
            'DelaySeconds' => $delay_seconds,
            'MessageBody' => $message_body,
            'QueueUrl' => $queue_url
            );
        
        // purge the queue
        $result = $sqs_client->sendMessage($request_options);

        // dump out results
        print_r(json_encode($result->toArray()));

    } else {
        // prolly should error here but eh.
        print_r('{}');
    }