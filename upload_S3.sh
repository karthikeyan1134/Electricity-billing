#!/bin/bash

set -e

# Function to handle errors
error_handler() {
    echo "Script execution failed."
    exit 1
}

# Trap any error and call the error_handler function
trap 'error_handler' ERR

fileName=$1
getpwd=$(pwd)"/uploads/$fileName"
getbucketname="s3://electricity-billing-system"

# Execute the AWS S3 copy command
aws s3 cp "$getpwd" "$getbucketname"

