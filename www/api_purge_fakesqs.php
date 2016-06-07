<?php
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