<?php
require './config.php';
$filter = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filter = $_POST['filter'];
    $filterValue = $_POST['filter_value'];
    if ($filter == 'user_id') {
        $sql = "SELECT * FROM bills WHERE user_id='$filterValue'";
    } elseif ($filter == 'customer_name') {
        $sql = "SELECT * FROM bills WHERE customer_name='$filterValue'";
    } elseif ($filter == 'meter_number') {
        $sql = "SELECT * FROM bills WHERE meter_number='$filterValue'";
    }
} else {
    $sql = "SELECT * FROM bills";
}

$result = $conn->query($sql);

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
    <div class="container">
        <h1>Bills</h1>
        <form class="filter-form" method="post" action="view_filter.php">
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
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
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
                                <td>{$row['upload_id']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='13'>No bills found</td></tr>";
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