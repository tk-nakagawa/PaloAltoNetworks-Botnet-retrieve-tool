# PaloAltoNetworks-Botnet-retrieve-tool

--- Explanation ---

* This script can retrieve the botnet report from Palo Alto Networks Firewall
  through XML-API and filter it with the Confidence Level you set up, then mail 
  to you the result.

* Use cron(for Linux/Mac) or Time-scheduler(for Windows) to kick this script 
  regularly.

* This script calls PEAR(PHP Extension and Application Repository), so you 
  need to install PEAR in advance.

* Notification email(email subject, email body) can be customized as your 
  favorite format.

* Execution results are logged in "system.log".

* Daily Botnet report is archived, if it exists.





--- Limitation ---

* The device which runs the script needs to communicate to Palo Alto Networks 
  Firewall with HTTPS(or HTTP) directly.
  [Not supported the HTTPS/HTTP communication through Proxy server]

* Email Notification was tested on some SMTP server.
  Supposing that most of SMTP servers are available, but you need to adjust 
  mailout.php script to your SMTP server environment.
