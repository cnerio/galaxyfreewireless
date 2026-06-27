<?php

/**
 * Write a log message to the log file 'check_logs' in the folder 'app_log'
 * located at the same level as the '.secrets' directory.
 *
 * @param mixed $message The message to log.
 * @return void
 */
function write_app_log($message) {
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) : '';
    
    $baseDir = null;

    if ($documentRoot !== '') {
        // Check if .secrets exists at dirname($documentRoot)
        if (is_dir(dirname($documentRoot) . DIRECTORY_SEPARATOR . '.secrets')) {
            $baseDir = dirname($documentRoot);
        }
        // Check if .secrets exists at dirname(dirname($documentRoot)) (nested public_html)
        elseif (is_dir(dirname(dirname($documentRoot)) . DIRECTORY_SEPARATOR . '.secrets')) {
            $baseDir = dirname(dirname($documentRoot));
        }
    }

    // Fallback logic
    if ($baseDir === null) {
        if ($documentRoot !== '') {
            $baseDir = dirname($documentRoot);
        } else {
            // Fallback for CLI/local development (one level above APPROOT)
            $baseDir = dirname(APPROOT);
        }
    }

    $logDir = $baseDir . DIRECTORY_SEPARATOR . 'app_log';
    
    // Ensure the log directory exists
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . DIRECTORY_SEPARATOR . 'check_logs';
    
    // Format the log message with a timestamp
    $timestamp = date('Y-m-d H:i:s');
    
    // Convert arrays or objects to human-readable string
    if (is_array($message) || is_object($message)) {
        $message = print_r($message, true);
    }
    
    $formattedMessage = "[{$timestamp}] " . trim($message) . PHP_EOL;

    // Append to the log file
    file_put_contents($logFile, $formattedMessage, FILE_APPEND | LOCK_EX);

    // Also write to the public stepLog.txt file in the current way
    @error_log($formattedMessage, 3, APPROOT . '/../public/stepLog.txt');
}
