<?php
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) : '';
    $httpHost = strtolower(trim($_SERVER['HTTP_HOST'] ?? ''));
    $serverName = strtolower(trim($_SERVER['SERVER_NAME'] ?? ''));
    $isLocalhost = in_array($httpHost, ['localhost', '127.0.0.1', '::1'], true)
        || in_array($serverName, ['localhost', '127.0.0.1', '::1'], true);

    $configCandidates = [];

    if ($documentRoot !== '') {
        // 1) First attempt from document root.
        $configCandidates[] = $documentRoot . DIRECTORY_SEPARATOR . 'config_fw.ini';

        // 2) Try .secrets one level above the document root (typical hosted setup).
        $configCandidates[] = dirname($documentRoot) . DIRECTORY_SEPARATOR . '.secrets' . DIRECTORY_SEPARATOR . 'config.ini';

        // 3) If running under public_html, also try two levels up to cover nested doc roots.
        if (stripos($documentRoot, 'public_html') !== false) {
            $configCandidates[] = dirname(dirname($documentRoot)) . DIRECTORY_SEPARATOR . '.secrets' . DIRECTORY_SEPARATOR . 'config.ini';
        }
    }

    // Local fallback from project root for development environments without DOCUMENT_ROOT mapping.
    $configCandidates[] = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'config.ini';

    $configPath = null;
    foreach ($configCandidates as $candidate) {
        if ($candidate && is_readable($candidate)) {
            $configPath = $candidate;
            break;
        }
    }

    if ($configPath === null) {
        $environment = $isLocalhost ? 'localhost' : 'hosted';
        throw new RuntimeException('config.ini not found for ' . $environment . ' environment. Checked: ' . implode(' | ', array_unique($configCandidates)));
    }

    $config = parse_ini_file($configPath);
    if ($config === false) {
        throw new RuntimeException('Unable to parse config.ini at: ' . $configPath);
    }

    //print_r($config);
  // DB Params
  define('DB_HOST', $config["dbhost"]);
  define('DB_USER', $config["dbuser"]);
  define('DB_PASS', $config["dbpass"]);
  define('DB_NAME', $config["dbname"]);

    // Security and external-service settings (from config.ini only)
    define('RECAPTCHA_SECRET', $config['recaptcha_secret'] ?? '');
    define('SMTP_HOST', $config['smtp_host'] ?? '');
    define('SMTP_USERNAME', $config['smtp_username'] ?? '');
    define('SMTP_PASSWORD', $config['smtp_password'] ?? '');
    define('SMTP_ENCRYPTION', $config['smtp_encryption'] ?? '');
    define('SMTP_PORT', isset($config['smtp_port']) ? (int)$config['smtp_port'] : 587);
    define('MAIL_FROM_ADDRESS', $config['mail_from_address'] ?? '');
    define('MAIL_FROM_NAME_DOCS', $config['mail_from_name_docs'] ?? 'Lifeline');
    define('MAIL_FROM_NAME_ORDERS', $config['mail_from_name_orders'] ?? 'Lifeline Orders');
    define('MAIL_DOCS_TO', $config['mail_docs_to'] ?? '');
    define('MAIL_DOCS_BCC', $config['mail_docs_bcc'] ?? '');
    define('MAIL_ORDERS_TO', $config['mail_orders_to'] ?? '');
    define('MAIL_NOTIFY_DOCS_BCC', $config['mail_notify_docs_bcc'] ?? '');
    define('AMBT_ADD_SUBSCRIBER_URL', $config['ambt_add_subscriber_url'] ?? '');
    define('AMBT_UPLOAD_DOCUMENT_URL', $config['ambt_upload_document_url'] ?? '');
    define('IS_LOCALHOST', $isLocalhost);
  //echo $_SERVER['DOCUMENT_ROOT'];
  
  // App Root
  define('APPROOT', dirname(dirname(__FILE__)));

 
  $GLOBALS["urlroot"] = $config["urlroot"];
  // URL Root
  define('URLROOT', $GLOBALS["urlroot"]);
  // Site Name
  define('SITENAME', $config["sitename"]);
  // App Version
  define('APPVERSION', '1.0.0');