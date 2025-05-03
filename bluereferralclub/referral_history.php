<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT referral_code, first_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($referral_code, $first_name);
$stmt->fetch();
$stmt->close();

$query = $conn->prepare("
  SELECT 
    referred,
    status,
    commission_amount,
    city,
    DATE_FORMAT(created_at, '%d/%m/%Y') AS created_at
  FROM referrals
  WHERE referral_code = ?
  ORDER BY created_at DESC
");
$query->bind_param("s", $referral_code);
$query->execute();
$res = $query->get_result();

$referrals = [];
while ($row = $res->fetch_assoc()) {
    $referrals[] = $row;
}
$query->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Referral History</title>
  <link rel="stylesheet" href="css/user_style.css">
  <style>
    .filters input, .filters select {
      padding: 5px;
      margin-right: 10px;
      margin-bottom: 10px;
    }
    .filters {
      margin: 20px 0;
    }
    .btn-clear {
      background-color: #999;
      color: white;
      border: none;
      padding: 6px 12px;
      cursor: pointer;
    }
    .btn-clear:hover {
      background-color: #777;
    }
    th {
      cursor: pointer;
    }
  </style>
</head>
<body>
  <header class="main-header">
    <h2 style="padding: 20px;">Hello, <?= htmlspecialchars($first_name) ?> — Referral History</h2>
  </header>

  <div class="bottom">
    <h3>All Your Referrals</h3>

    <!-- Filtros -->
    <div class="filters">
      <input type="text" id="filterName" placeholder="Search by Name">
      <select id="filterStatus">
        <option value="">Status</option>
        <option value="Pending">Pending</option>
        <option value="Successes">Successes</option>
        <option value="Unsuccessful">Unsuccessful</option>
        <option value="Negotiating">Negotiating</option>
        <option value="Paid">Paid</option>
      </select>
      <input type="text" id="filterCity" placeholder="Search by City">
      <button class="btn-clear" id="clearFilters">Clear Filters</button>
    </div>

    <table border="1" style="width: 100%; border-collapse: collapse; color: white;" id="referralsTable">
      <thead>
        <tr>
          <th data-sort="0">Referred</th>
          <th data-sort="1">Status</th>
          <th data-sort="2">Commission</th>
          <th data-sort="3">City</th>
          <th data-sort="4">Created At</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($referrals) === 0): ?>
          <tr><td colspan="5" style="text-align:center;">No referrals found.</td></tr>
        <?php else: ?>
          <?php foreach ($referrals as $row): ?>
            <tr 
              data-name="<?= strtolower(htmlspecialchars($row['referred'])) ?>"
              data-status="<?= strtolower(htmlspecialchars($row['status'])) ?>"
              data-city="<?= strtolower(htmlspecialchars($row['city'])) ?>"
            >
              <td><input type="text" value="<?= htmlspecialchars($row['referred']) ?>" readonly></td>
              <td><input type="text" value="<?= htmlspecialchars($row['status']) ?>" readonly></td>
              <td><input type="text" value="<?= number_format((float)$row['commission_amount'], 2, ',', '.') ?>" readonly></td>
              <td><input type="text" value="<?= htmlspecialchars($row['city']) ?>" readonly></td>
              <td><input type="text" value="<?= htmlspecialchars($row['created_at']) ?>" readonly></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <br>
    <a href="user_dashboard.php" class="btn">← Back to Dashboard</a>
  </div>

  <!-- Script de filtros e ordenação -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const filterName = document.getElementById("filterName");
      const filterStatus = document.getElementById("filterStatus");
      const filterCity = document.getElementById("filterCity");
      const clearBtn = document.getElementById("clearFilters");
      const rows = document.querySelectorAll("#referralsTable tbody tr");

      function applyFilters() {
        const nameVal = filterName.value.toLowerCase();
        const statusVal = filterStatus.value.toLowerCase();
        const cityVal = filterCity.value.toLowerCase();

        rows.forEach(row => {
          const rowName = row.dataset.name || "";
          const rowStatus = row.dataset.status || "";
          const rowCity = row.dataset.city || "";

          const matchName = rowName.includes(nameVal);
          const matchStatus = rowStatus.includes(statusVal);
          const matchCity = rowCity.includes(cityVal);

          row.style.display = (matchName && matchStatus && matchCity) ? "" : "none";
        });
      }

      [filterName, filterStatus, filterCity].forEach(input => {
        input.addEventListener("input", applyFilters);
        input.addEventListener("change", applyFilters);
      });

      clearBtn.addEventListener("click", () => {
        filterName.value = "";
        filterStatus.value = "";
        filterCity.value = "";
        applyFilters();
      });

      const headers = document.querySelectorAll("th[data-sort]");
      let sortDirection = 1;
      let lastSorted = null;

      headers.forEach(header => {
        header.addEventListener("click", () => {
          const index = parseInt(header.dataset.sort);
          const tbody = document.querySelector("#referralsTable tbody");
          const rowsArray = Array.from(tbody.querySelectorAll("tr"));

          if (lastSorted === index) {
            sortDirection *= -1;
          } else {
            sortDirection = 1;
            lastSorted = index;
          }

          rowsArray.sort((a, b) => {
            const aText = a.children[index].innerText.trim().toLowerCase();
            const bText = b.children[index].innerText.trim().toLowerCase();
            return (aText < bText ? -1 : aText > bText ? 1 : 0) * sortDirection;
          });

          rowsArray.forEach(row => tbody.appendChild(row));
        });
      });
    });
  </script>
</body>
</html>