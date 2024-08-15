<?php
    
    $fileName = 'bills_01.csv';
    
    // Construct the file path
    $getpwd = "./uploads/$fileName";
    $getbucketname = "s3://electricity-billing-system";

    // Execute the AWS S3 copy command
    $command = escapeshellcmd("aws s3 cp $getpwd $getbucketname");
    $output = shell_exec("$command");
    echo "<h6>$output</h6>";
    
 ?>