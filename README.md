# Electricity-billing
This Webpage is bulit to understand the services of AWS Here I have 
S3 Bucket to store the user Billing Details
AWS Elastic BeanStalk for store the Webpage code so that it can scale based on the traffic on the webpage 

Use the billing.sql file to Create the Database Infrastructure like. Tables and Views

Modify the config.php file With your RDS Details

upload_S3.sh which is used to send the [Billing Details.csv] file into the S3 bucket
Modify the below line in the upload_S3.sh with your bucket name
getbucketname="s3://electricity-billing-system"


# The 'View Bills' navigation item will not be displayed until there is a database connection.
