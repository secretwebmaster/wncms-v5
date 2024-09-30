<?php

session_start(); // Start the session

header('Content-Type: application/json'); // Set JSON response header

if (file_exists(__DIR__ . '/../../.env') && file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    $response = [
        'status' => 'success',
        'message' => 'Already installed.',
    ];
    echo json_encode($response);
    exit;
}

$response = [
    'status' => 'running',
    'message' => 'Starting the Composer merge and installation process...',
];

// Verify the CSRF token from the request
$csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

if (empty($csrfToken) || $csrfToken !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode($response);
    exit;
}

$response['status'] = 'running';
$response['message'] = 'Starting the Composer merge and installation process...';

// Check if exec function exists
if (!function_exists('exec')) {
    http_response_code(500);
    $response['status'] = 'error';
    $response['message'] = 'The exec function is disabled on this server.';
    echo json_encode($response);
    exit;
}

$coreFile = __DIR__ . '/../../composer.json';
$customFile = __DIR__ . '/../../composer.custom.json';
$pluginFile = __DIR__ . '/../../composer.plugin.json';

// Merging custom and plugin Composer files if they exist

try {
    if (file_exists($customFile)) {
        $core = json_decode(file_get_contents($coreFile), true);
        $custom = json_decode(file_get_contents($customFile), true);

        if (isset($custom['require'])) {
            $core['require'] = array_merge($core['require'] ?? [], $custom['require']);
        }

        $result = @file_put_contents($coreFile, json_encode($core, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        if ($result === false) {
            throw new Exception("Failed to write to $coreFile", 1001);
        }
    }

    if (file_exists($pluginFile)) {
        $core = json_decode(file_get_contents($coreFile), true);
        $plugin = json_decode(file_get_contents($pluginFile), true);

        if (isset($plugin['require'])) {
            $core['require'] = array_merge($core['require'] ?? [], $plugin['require']);
        }

        $result = @file_put_contents($coreFile, json_encode($core, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        if ($result === false) {
            throw new Exception("Failed to write to $coreFile", 1001);
        }
    }

    // Run composer install to update dependencies
    exec('cd ../.. && composer install --ignore-platform-reqs 2>&1', $output, $returnVar);

    if ($returnVar !== 0) {
        // Capture the output from Composer and return it in the response
        $response['status'] = 'error';
        $response['message'] = 'Composer install failed. Please check the server logs for details.';
        $response['output'] = implode("\n", $output); // Include output in the response
    } else {
        $response['status'] = 'success';
        $response['message'] = 'Composer install completed successfully.';

        try {
            // Copy the .env.example file to .env
            $base_path = realpath(__DIR__ . '/../../');
            copy($base_path . '/.env.example', $base_path . '/.env');
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
            exit;
        }
    }
     
} catch (Exception $e) {

    // Handle any exceptions and return an error message
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
    $errorCode = $e->getCode();

    switch ($errorCode) {
        case 1001:
            $response['suggestion'] = 'fix_file_permission_for_composer_json';
            break;
    }


} finally {
    // Restore the original error handler
    restore_error_handler();
}

echo json_encode($response); // Return JSON response
