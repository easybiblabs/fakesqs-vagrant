<?php
/**
 * The name of the SQS queue
 */
define('QUEUE_NAME', 'poo_queue');

/**
 * AWS Credentials array for accessing the API
 *
 * It is a serialised array, which is then unserialised when used.
 */
define('AWS_CREDENTIALS', serialize(array(
        'region' => 'no-boop-1',
        'version' => 'latest',
        'endpi]oint' => 'http://localhost:4568',
        'credentials' => array(
            'key'    => '[[YOUR_AWS_ACCESS_KEY_ID]]',
            'secret' => '[[YOUR_AWS_SECRET_ACCESS_KEY]]',
        )
    )));
