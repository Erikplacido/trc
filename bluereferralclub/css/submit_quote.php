<?php
/* submit_quote.php — SECURE & DEBUGGED VERSION */
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
header('Content-Type: application/json'); // Must be FIRST line

// ==============================================
// 1. Database Connection
// ==============================================
require_once __DIR__ . '/conexao.php';

// Check DB connection
if ($conn->connect_error) {
    error_log("DB Connection Error: " . $conn->connect_error);
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
    exit;
}

// ==============================================
// 2. Capture Payload (JSON or Form Data)
// ==============================================
$raw = file_get_contents('php://input');
$payload = json_decode($raw, true); // Try JSON

// Fallback to form data if JSON fails
if (!$payload) {
    parse_str($raw, $payload);
}

// Debug (check error.log)
error_log("Received Payload: " . print_r($payload, true));

// ==============================================
// 3. Validate Required Fields (IN ENGLISH)
// ==============================================
$requiredFields = [
    'first_name' => 'First Name',
    'last_name' => 'Last Name',
    'email' => 'Email',
    'mobile' => 'Mobile',
    'postcode' => 'Postcode',
    'service_name' => 'Service'
];

$errors = [];
foreach ($requiredFields as $field => $name) {
    if (empty(trim($payload[$field] ?? ''))) {
        $errors[] = "Missing field: $name";
    }
}

// Email validation
if (!filter_var($payload['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

// Postcode cleanup (digits only)
$postcode = preg_replace('/\D/', '', $payload['postcode'] ?? '');
if (empty($postcode)) {
    $errors[] = "Invalid postcode";
}

// Return errors if any
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['error' => implode(" | ", $errors)]);
    exit;
}

// ==============================================
// 4. Save to Database
// ==============================================
try {
    $stmt = $conn->prepare(
        "INSERT INTO referrals (
            referral_code, first_name, last_name, 
            email, mobile, postcode, 
            service_name, more_details, created_at
        ) VALUES (?,?,?,?,?,?,?,?,NOW())"
    );

    $stmt->bind_param(
        "ssssssss",
        $payload['referral_code'] ?? '',
        $payload['first_name'],
        $payload['last_name'],
        $payload['email'],
        $payload['mobile'],
        $postcode, // Use cleaned postcode
        $payload['service_name'],
        $payload['more_details'] ?? ''
    );

    if (!$stmt->execute()) {
        throw new Exception("Database insert failed");
    }
} catch (Exception $e) {
    error_log("DB Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to process request']);
    exit;
}

// ==============================================
// 5. Send Emails (Securely)
// ==============================================
$sanitizedEmail = filter_var($payload['email'], FILTER_SANITIZE_EMAIL);

// Email to company
$companySubject = "New Quote Request: " . $payload['service_name'];
$companyMessage = "
Name: {$payload['first_name']} {$payload['last_name']}
Service: {$payload['service_name']}
Email: $sanitizedEmail
Mobile: {$payload['mobile']}
Postcode: $postcode
Details: " . ($payload['more_details'] ?? 'None') . "
Referral Code: " . ($payload['referral_code'] ?? 'None')
;

@mail(
    'office@bluefacilityservices.com.au', // Update this email
    $companySubject,
    $companyMessage,
    "From: no-reply@bluefacilityservices.com.au\r\nReply-To: $sanitizedEmail"
);

// Confirmation email to user
$userMessage = "
Hi {$payload['first_name']},

Thank you for your quote request!
Our team will contact you shortly.

Regards,
Blue Facility Services
";

@mail(
    $sanitizedEmail,
    "Thank you for your request",
    $userMessage,
    "From: no-reply@bluefacilityservices.com.au"
);

// ==============================================
// 6. Final Response
// ==============================================
echo json_encode(['success' => true]);