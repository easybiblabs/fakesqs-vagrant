<?php
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/vendor/autoload.php';

    use Aws\Sqs\SqsClient;

    if(isset($_GET['queue_name'])) {

        # http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#receivemessage
        # http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#getqueueurl

        # parse out the GETs
        $queue_name = $_GET['queue_name'];

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

        // set up request options
        $request_options = array( 
            'QueueUrl' => $queue_url
            );
        // LONG POLLING can be used here with the parameter "WaitTimeSeconds"
        // with a maximum wait time of 20 seconds.

        // purge the queue
        $result = $sqs_client->receiveMessage($request_options);

        // dump out results
        print_r(json_encode($result->toArray()));

    } else {
        // prolly should error here but eh.
        print_r('{}');
    }