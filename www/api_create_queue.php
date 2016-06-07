<?php
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/vendor/autoload.php';

    use Aws\Sqs\SqsClient;

    if(isset($_GET['queue_name'])) {

        # http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#createqueue

        # parse out the GETs
        $queue_name = $_GET['queue_name'];

        if(isset($_GET['delay_seconds'])) $delay_seconds = intval($_GET['delay_seconds']);
        else $delay_seconds = 0;

        if(isset($_GET['visibility_timeout'])) $visibility_timeout = intval($_GET['visibility_timeout']);
        else $visibility_timeout = 30;

        // set up credentials from config
        $sqs_credentials = unserialize(AWS_CREDENTIALS);

        // Instantiate the client
        $sqs_client = new SqsClient($sqs_credentials);

        // set up request options
        $request_options = array( 
            'QueueName' => $queue_name,
            'DelaySeconds' => $delay_seconds,
            'VisibilityTimeout' => $visibility_timeout
            );

        // make the request
        $result = $sqs_client->createQueue($request_options);

        // dump out results
        print_r(json_encode($result->toArray()));

    } else {
        // prolly should error here but eh.
        print_r('{}');
    }