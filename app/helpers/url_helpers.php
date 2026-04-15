<?php
function redirect($page){
    header('location: '. URLROOT . '/'.$page );
}

function saveBase64File($base64_string,$customer_id,$doctype) {
    $folder = APPROOT . "/public/uploads/" . $customer_id . "/";
    // Extract the file type and base64 data
    if (preg_match('/^data:(.*?);base64,/', $base64_string, $matches)) {
        $mimeType = $matches[1]; // like "application/pdf", "image/png", etc.
        $base64_string = substr($base64_string, strpos($base64_string, ',') + 1);
        $extension = '';

        // Map MIME types to file extensions
        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        ];

        if (!isset($mimeMap[$mimeType])) {
            throw new Exception('Unsupported file type: ' . $mimeType);
        }

        $extension = $mimeMap[$mimeType];

        $decodedData = base64_decode($base64_string);
        if ($decodedData === false) {
            throw new Exception('Base64 decode failed');
        }

        // Create upload folder if it doesn't exist
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        try {
            $randomSuffix = bin2hex(random_bytes(3));
        } catch (Exception $e) {
            $randomSuffix = (string)mt_rand(100000, 999999);
        }

        $filename = $doctype . "_" . $customer_id . "_" . date('YmdHis') . "_" . $randomSuffix . '.' . $extension;
        $filepath = $folder . $filename;

        file_put_contents($filepath, $decodedData);

        return URLROOT."/public/uploads/".$customer_id."/".$filename;
    } else {
        throw new Exception('Invalid base64 format');
    }
}
