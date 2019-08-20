<?php

use BeBound\Sdk;
use BeBound\BeBoundException;

require __DIR__ . '/../vendor/autoload.php';

// Create Be-Bound SDK with the proper values for your be-app
$beBound = new Sdk(array(
    'id'      => '22f2deec-26cb-4cfe-a00b-a749958aa91a',
    'secret'  => '123456',
    'version' => 1,
));

try {
    // Initialize the sdk (retrieves data and checks if everything is correct)
    $beBound->init();
    // If it's a Be-Bound request and authentication or version is wrong, it will throw a BeBoundException
} catch (BeBoundException $exception) {
    \error_log($exception->getMessage());

    return;
}

// Default response if no be-bound request
$response = array('not be-bound');

// If you're in a Be-Bound request (can be useful if other kind of requests go through the same endpoint)
if ($beBound->isBeBound()) {
    $message = '';
    // Check the message (even though we only have 1 in our example)
    switch ($beBound->getMessage()) {
        case 'test_message':
            // This will display the content of your json in your log
            \error_log(\json_encode($beBound->getData()));

            // Set the response message
            $message = 'test_response';

            // Set the data for your response
            $response = array(
                'test_string' => 'blabla',
            );
            break;
    }

    // Send the beapp-message in the header
    \header("beapp-message: $message");
}

// Send back your response as a json
\header('Content-Type: application/json');
echo \json_encode($response);