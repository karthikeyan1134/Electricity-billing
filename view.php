<?php
require './config.php';

$filter = '';
$filterValue = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filter = $_POST['filter'];
    $filterValue = $_POST['filter_value'];
    if ($filter == 'user_id') {
        $stmt = $conn->prepare("CALL get_bills_by_user_id(?)");
        $stmt->bind_param("i", $filterValue);
    } elseif ($filter == 'customer_name') {
        $stmt = $conn->prepare("CALL get_bills_by_customer_name(?)");
        $stmt->bind_param("s", $filterValue);
    } elseif ($filter == 'meter_number') {
        $stmt = $conn->prepare("CALL get_bills_by_meter_number(?)");
        $stmt->bind_param("s", $filterValue);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM bills_by_user_id");
}

// Fetch total bill for each user
$totalBillResult = $conn->query("CALL calculate_total_bill()");
$totalBills = [];
while ($row = $totalBillResult->fetch_assoc()) {
    $totalBills[$row['user_id']] = $row['total_bill'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bills</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #333;
            color: #fff;
            padding: 15px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 15px;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        .filter-form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="upload.html">Upload Bill</a>
    </nav>
    if this page filters are not working use this
    <a href="view_filter.php">link</a>
    <div class="container">
        <h1>Bills</h1>
        <form class="filter-form" method="post" action="view.php">
            <label for="filter">Filter by:</label>
            <select id="filter" name="filter">
                <option value="user_id">User ID</option>
                <option value="customer_name">Customer Name</option>
                <option value="meter_number">Meter Number</option>
            </select>
            <input type="text" id="filter_value" name="filter_value" required>
            <button type="submit">Filter</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Meter Number</th>
                    <th>Billing Period</th>
                    <th>Billing Date</th>
                    <th>Previous Reading</th>
                    <th>Current Reading</th>
                    <th>Units Consumed</th>
                    <th>Rate per Unit</th>
                    <th>Bill Amount</th>
                    <th>Upload ID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['bill_id']}</td>
                                <td>{$row['user_id']}</td>
                                <td>{$row['customer_name']}</td>
                                <td>{$row['address']}</td>
                                <td>{$row['meter_number']}</td>
                                <td>{$row['billing_period']}</td>
                                <td>{$row['billing_date']}</td>
                                <td>{$row['previous_reading']}</td>
                                <td>{$row['current_reading']}</td>
                                <td>{$row['units_consumed']}</td>
                                <td>{$row['rate_per_unit']}</td>
                                <td>{$row['bill_amount']}</td>
                                <td>{$row['uploaded_file']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='13'>No bills found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <h2>Total Bill for Each User</h2>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Total Bill Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($totalBills as $userId => $totalBill) {
                    echo "<tr>
                            <td>{$userId}</td>
                            <td>{$totalBill}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
