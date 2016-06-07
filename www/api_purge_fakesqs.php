<?php

    // this is a special script that forces fakeSQS to reset itself
    // this does NOT exist in amazon sqs!

    $result = file_get_contents(
        'http://localhost:4568/', 
        false, 
        stream_context_create(array(
            'http' => array(
                'method' => 'DELETE' 
            )
        ))
    );
    print_r('{}');