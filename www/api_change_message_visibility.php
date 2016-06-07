<?php
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/vendor/autoload.php';

    use Aws\Sqs\SqsClient;

    $sqs_credentials = unserialize(AWS_CREDENTIALS);

    // Instantiate the client
    $sqs_client = new SqsClient($sqs_credentials);
	$result = $sqs_client->changeMessageVisibility(array(
		// QueueUrl is required
		'QueueUrl' => 'string',
		// ReceiptHandle is required
		'ReceiptHandle' => 'string',
		// VisibilityTimeout is required
		'VisibilityTimeout' => integer,
	));
    print_r(json_encode($result->toArray()));


/* changing a batch of messages is similar:
	$result = $client->changeMessageVisibilityBatch(array(
	    // QueueUrl is required
	    'QueueUrl' => 'string',
	    // Entries is required
	    'Entries' => array(
	        array(
	            // Id is required
	            'Id' => 'string',
	            // ReceiptHandle is required
	            'ReceiptHandle' => 'string',
	            'VisibilityTimeout' => integer,
	        ),
	        // ... repeated
	    ),
	));
*/
