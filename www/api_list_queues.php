<?php
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/vendor/autoload.php';

    use Aws\Sqs\SqsClient;


        # List Queues
        # 
        # http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#listqueues


        // set up credentials from config
        $sqs_credentials = unserialize(AWS_CREDENTIALS);

        // Instantiate the client
        $sqs_client = new SqsClient($sqs_credentials);

        // set up request options
        $request_options = array( );

        // make the request
        $result = $sqs_client->listQueues($request_options);

        // dump out results
        print_r(json_encode($result->toArray()));
