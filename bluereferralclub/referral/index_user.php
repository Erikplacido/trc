<?php
session_start();
require_once '../conexao.php';

if (!isset($_SESSION['user_id'])) {
    exit('Not logged in');
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, referral_code FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $referral_code);
$stmt->fetch();
$stmt->close();
?>

<link rel="stylesheet" href="/bluereferralclub/css/user_style.css">


<h2 class="modal-title">Give a Referral</h2>

<?php if (isset($_GET['success'])): ?>
  <p class="modal-success">Referral submitted successfully!</p>
<?php endif; ?>

<form action="/bluereferralclub/referral/referral_process_user.php" method="POST" class="modal-form">
  
  <div class="form-group">
    <label for="referred_by">Referred by</label><br>
    <input type="text" id="referred_by" name="referred_by" value="<?= htmlspecialchars($name) ?>" readonly>
  </div>

  <div class="form-group">
    <label for="referral_code">Your referral code</label><br>
    <input type="text" id="referral_code_display" value="<?= htmlspecialchars($referral_code) ?>" readonly>
    <input type="hidden" id="referral_code" name="referral_code" value="<?= htmlspecialchars($referral_code) ?>">
  </div>

  <div class="form-group">
    <label for="referred">Name</label><br>
    <input type="text" id="referred" name="referred" required>
  </div>

  <div class="form-group">
    <label for="referred_last_name">Last name</label><br>
    <input type="text" id="referred_last_name" name="referred_last_name">
  </div>
  
  <div class="form-group">
  <label for="client_type">Client Type</label><br>
  <select id="client_type" name="client_type" required>
    <option value="">Select Client Type</option>
    <option value="Home">Home</option>
    <option value="Company">Company</option>
  </select>
</div>

  <div class="form-group">
    <label for="email">Email</label><br>
    <input type="email" id="email" name="email" required>
  </div>

  <div class="form-group">
    <label for="mobile">Mobile</label><br>
    <input type="text" id="mobile" name="mobile" required>
  </div>

  <div class="form-group">
    <label for="postcode">Postcode</label><br>
    <input type="text" id="postcode" name="postcode" required>
  </div>

  <div class="form-group">
    <label for="more_details">More details (optional)</label><br>
    <input type="text" id="more_details" name="more_details">
  </div>

  <div class="form-group form-actions">
    <button type="submit" class="btn-submit">Submit</button>
  </div>
  
</form>