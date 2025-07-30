<?php
// Email Configuration
// IMPORTANT: Update these settings with your actual email information

// The email address where contact form messages will be sent
define('TO_EMAIL', 'realgamers654@gmail.com');

// The "from" email address (should be from your domain)
define('FROM_EMAIL', 'noreply@yourdomain.com');

// Subject prefix for contact form emails
define('SUBJECT_PREFIX', 'Contact Form: ');

// Website name (optional)
define('WEBSITE_NAME', 'Your Website');

/*
SETUP INSTRUCTIONS:

1. Change TO_EMAIL to your actual email address where you want to receive messages
2. Change FROM_EMAIL to an email address from your domain (e.g., norepyourdomain.comly@)
3. Make sure your web server has mail() function enabled
4. For better email delivery, consider using SMTP instead of mail() function

For Gmail or other email providers, you might need to:
- Enable "Less secure app access" or use App Passwords
- Consider using PHPMailer with SMTP for better reliability

SMTP Setup (Advanced):
If mail() function doesn't work, you can use PHPMailer with SMTP.
Download PHPMailer and configure it with your email provider's SMTP settings.
*/
?>
