<?php
$path = __DIR__ . '/terms_and_conditions.pdf';
if (!is_file($path) || !is_readable($path)) {
  http_response_code(404);
  exit('File not found');
}
if (function_exists('ob_get_length') && ob_get_length()) { ob_end_clean(); }

header('Content-Type: application/pdf');
header('X-Content-Type-Options: nosniff');
header('Content-Disposition: inline; filename="terms_and_conditions.pdf"');
header('Cache-Control: public, max-age=86400');
header('Content-Length: ' . filesize($path));
readfile($path);
