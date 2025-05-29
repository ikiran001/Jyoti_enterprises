<?php
session_start();

if (!isset($_POST['password']) && !isset($_SESSION['loggedin'])) {
  echo '<form method="POST" style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;padding: 0 20px;box-sizing:border-box;">
          <h2 style="font-size: 32px; margin-bottom: 20px;">Admin Login</h2>
          <input type="password" name="password" placeholder="Admin Password" style="padding:15px;margin-bottom:15px;width:100%;max-width:400px;font-size:18px;box-sizing:border-box;">
          <button type="submit" style="padding:15px 20px;background:#007bff;color:white;border:none;border-radius:5px;cursor:pointer;width:100%;max-width:400px;font-size:18px;">Login</button>
        </form>';
  exit();
}

if (isset($_POST['password']) && $_POST['password'] === 'K@9833514014j') {
  $_SESSION['loggedin'] = true;
} elseif (!isset($_SESSION['loggedin'])) {
  die("Wrong password!");
}

$conn = new mysqli("localhost", "root", "", "jyoti_enterprises");
if ($conn->connect_error) die("DB error");

$result = $conn->query("SELECT * FROM enquiries ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #343a40;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 {
      margin: 0;
      font-size: 24px;
    }
    .container {
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: white;
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      padding: 12px 15px;
      text-align: left;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    tr:hover {
      background-color: #e0f0ff;
    }
    .search-bar {
      width: 100%;
      max-width: 400px;
      padding: 10px;
      font-size: 16px;
      margin-top: 20px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    @media screen and (max-width: 600px) {
      header h1 {
        font-size: 18px;
      }
      .search-bar {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>Admin Dashboard</h1>
    <a href="logout.php" style="color:white;text-decoration:none;font-size:16px;">Logout</a>
  </header>
  <div class="container">
    <input type="text" id="searchInput" class="search-bar" onkeyup="filterTable()" placeholder="Search enquiries...">
    <table id="enquiryTable">
      <thead>
        <tr><th>ID</th><th>Product</th><th>Name</th><th>Contact</th><th>Email</th><th>Date</th></tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['product']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['contact']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script>
    function filterTable() {
      const input = document.getElementById("searchInput").value.toLowerCase();
      const rows = document.querySelectorAll("#enquiryTable tbody tr");
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
      });
    }
  </script>
</body>
</html>
