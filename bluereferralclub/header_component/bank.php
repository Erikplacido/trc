<?php
session_start();
require_once __DIR__ . '/../conexao.php';

$user_id = $_SESSION['user_id'] ?? 1;

// Update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bank = $_POST['bankName'] ?? '';
    $agency = $_POST['agency'] ?? '';
    $bsb = $_POST['bsb'] ?? '';
    $account = $_POST['accountNumber'] ?? '';
    $abn = $_POST['abnNumber'] ?? '';

    $stmt = $conn->prepare("UPDATE users SET bankName = ?, agency = ?, bsb = ?, accountNumber = ?, abnNumber = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("sssssi", $bank, $agency, $bsb, $account, $abn, $user_id);

        if ($stmt->execute()) {
            echo "<p style='color:green; font-weight:bold;'>✅ Bank details updated successfully!</p>";
        } else {
            echo "<p style='color:red; font-weight:bold;'>❌ Failed to update bank details: {$stmt->error}</p>";
        }

        $stmt->close();
    } else {
        die("❌ Error preparing the update statement: " . $conn->error);
    }

    exit; // ⚡ Prevents loading the rest of the page
}

// Fetch current data
$stmt = $conn->prepare("SELECT bankName, agency, bsb, accountNumber, abnNumber FROM users WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} else {
    die("❌ Error preparing the select statement: " . $conn->error);
}
?>

<link rel="stylesheet" href="/bluereferralclub/css/user_style.css">

<div class="modal-form">
  <form method="POST" id="bankForm" action="/bluereferralclub/header_component/bank.php">
    <div class="form-group">
      <label>Bank Name</label>
      <input type="text" name="bankName" id="bankName" value="<?= htmlspecialchars($data['bankName'] ?? '') ?>" disabled>
    </div>
<br>
    <div class="form-group">
      <label>Agency</label>
      <input type="text" name="agency" id="agency" value="<?= htmlspecialchars($data['agency'] ?? '') ?>" disabled>
    </div>
<br>
    <div class="form-group">
      <label>BSB</label>
      <input type="text" name="bsb" id="bsb" value="<?= htmlspecialchars($data['bsb'] ?? '') ?>" disabled>
    </div>
<br>
    <div class="form-group">
      <label>Account Number</label>
      <input type="text" name="accountNumber" id="accountNumber" value="<?= htmlspecialchars($data['accountNumber'] ?? '') ?>" disabled>
    </div>
<br>
    <div class="form-group">
      <label>ABN Number</label>
      <input type="text" name="abnNumber" id="abnNumber" value="<?= htmlspecialchars($data['abnNumber'] ?? '') ?>" disabled>
    </div>
<br>
    <button type="button" id="editBtn" class="btn-gold">Edit</button>
    <button type="submit" id="saveBtn" class="btn-gold" style="display:none;">Save</button>

    <div id="responseMessage" style="margin-top: 10px;"></div>
  </form>
</div>
