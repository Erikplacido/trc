<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

header('Content-Type: application/json');
require_once __DIR__ . '/../conexao.php';

// 1. Verifica conexão
if ($conn->connect_error) {
    error_log("DB Connection Error: " . $conn->connect_error);
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
    exit;
}

// 2. Captura o payload (JSON ou POST)
$raw = file_get_contents('php://input');
error_log('Raw JSON recebido: ' . $raw);
$payload = json_decode($raw, true) ?? $_POST;
error_log("Received Payload: " . print_r($payload, true));

// 3. Validação dos campos obrigatórios
$requiredFields = [
    'referred' => 'referred',
    'referred_last_name' => 'referred_last_name',
    'email' => 'Email',
    'mobile' => 'Mobile',
    'postcode' => 'Postcode',
    'client_type' => 'Client Type',
    'service_name' => 'Service'
];

$errors = [];
foreach ($requiredFields as $field => $label) {
    if (empty(trim($payload[$field] ?? ''))) {
        $errors[] = "Missing field: $label";
    }
}
if (!filter_var($payload['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}
$postcode = preg_replace('/\D/', '', $payload['postcode'] ?? '');
if (!$postcode) {
    $errors[] = "Invalid postcode";
}
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['error' => implode(" | ", $errors)]);
    exit;
}

// 4. Prepara os dados
$referral_code = $payload['referral_code'] ?? '';
$referred = trim($payload['referred']);
$referred_last_name = trim($payload['referred_last_name']);
$email = trim($payload['email']);
$mobile = trim($payload['mobile']);
$client_type = trim($payload['client_type']);
$service_name = trim($payload['service_name']);
$more_details = trim($payload['more_details'] ?? '');

// 4.1 Sanitização
$mobile = preg_replace('/[^\d+]/', '', $mobile);
$referred = htmlspecialchars($referred, ENT_QUOTES);
$referred_last_name = htmlspecialchars($referred_last_name, ENT_QUOTES);

// 4.2 Verifica duplicidade nos últimos 2 minutos
try {
    $checkStmt = $conn->prepare("
        SELECT id FROM quote_admin 
        WHERE email = ? AND service_name = ? AND created_at >= NOW() - INTERVAL 2 MINUTE
    ");
    $checkStmt->bind_param("ss", $email, $service_name);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        error_log("Duplicate submission blocked for $email - $service_name");
        http_response_code(429);
        echo json_encode(['error' => 'Duplicate submission detected. Please wait a bit and try again.']);
        $checkStmt->close();
        $conn->close();
        exit;
    }

    $checkStmt->close();
} catch (Exception $e) {
    error_log("Duplication check failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal error']);
    exit;
}

// 5. Insere na tabela quote_admin
try {
    $stmt = $conn->prepare("
        INSERT INTO quote_admin (
            referral_code, referred, referred_last_name, email, mobile, postcode,
            client_type, service_name, more_details, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

    $stmt->bind_param(
        "sssssssss",
        $referral_code,
        $referred,
        $referred_last_name,
        $email,
        $mobile,
        $postcode,
        $client_type,
        $service_name,
        $more_details
    );

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    error_log("DB Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to process request']);
    exit;
}

// 6. E-mail para admin e usuário
$sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

$companySubject = "New Quote Request: $service_name";
$companyMessage = "
Name: $referred  $referred_last_name
Service: $service_name
Client Type: $client_type
Email: $sanitizedEmail
Mobile: $mobile
Postcode: $postcode
Details: " . ($more_details ?: 'None') . "
Referral Code: " . ($referral_code ?: 'None');

@mail(
    'office@bluefacilityservices.com.au',
    $companySubject,
    $companyMessage,
    "From: no-reply@bluefacilityservices.com.au\r\nReply-To: $sanitizedEmail"
);

$userMessage = "
Hi $referred,

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

// 7. Sucesso 🎉
echo json_encode(['success' => true]);