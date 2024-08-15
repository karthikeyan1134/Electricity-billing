<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Bill Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="upload.php">Upload Bill</a>
        <a href="view.php">View Bills</a>
    </nav>
</body>
</html> 

<?php
require './config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $file = $_FILES['file'];

    // File upload path
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create directory if it does not exist
    }
    $fileName = basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Upload file to server
    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        // Insert file info into database
        $sql = "INSERT INTO files (user_id, file_name) VALUES ('$user_id', '$fileName')";
        if ($conn->query($sql) === TRUE) {
            $upload_id = $conn->insert_id;

            // Parse CSV file and insert data into database
            if (($handle = fopen($targetFilePath, "r")) !== FALSE) {
                fgetcsv($handle); // Skip the first row (headers)
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $id = $data[0];
                    $user_id = $data[1];
                    $customer_name = $data[2];
                    $address = $data[3];
                    $meter_number = $data[4];
                    $billing_period = $data[5];
                    $billing_date = $data[6];
                    $previous_reading = $data[7];
                    $current_reading = $data[8];
                    $units_consumed = $data[9];
                    $rate_per_unit = $data[10];
                    $bill_amount = $data[11];
                    $sql = "INSERT INTO bills (id, user_id, customer_name, address, meter_number, billing_period, billing_date, previous_reading, current_reading, units_consumed, rate_per_unit, bill_amount, upload_id) 
                            VALUES ('$id', '$user_id', '$customer_name', '$address', '$meter_number', '$billing_period', '$billing_date', '$previous_reading', '$current_reading', '$units_consumed', '$rate_per_unit', '$bill_amount', '$upload_id')";
                    if (!$conn->query($sql)) {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
                fclose($handle);
                echo "File uploaded and data inserted successfully.";
    
                // Construct the file path
                $getpwd = realpath($targetFilePath); // Get absolute path
                $getbucketname = "s3://electricity-billing-system/$fileName";

                // Execute the AWS S3 copy command
                $command = escapeshellcmd("aws s3 cp $getpwd $getbucketname");
                $output = shell_exec("$command 2>&1");

                if (strpos($output, 'upload:') !== false) {
                    echo "<h6>File uploaded to S3 successfully.</h6>";
                } else {
                    echo "<h6>Error uploading file to S3: $output</h6>";
                }
            } else {
                echo "Error opening the file.";
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$conn->close();
?>