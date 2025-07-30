<?php
// Include configuration
require_once 'config.php';

header('Content-Type: application/json');

// Get configuration values
$to_email = TO_EMAIL;
$from_email = FROM_EMAIL;
$subject_prefix = SUBJECT_PREFIX;

// Response array
$response = array();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get and sanitize form data
    $name = isset($_POST['name']) ? strip_tags(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? strip_tags(trim($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? strip_tags(trim($_POST['phone'])) : '';
    $subject = isset($_POST['subject']) ? strip_tags(trim($_POST['subject'])) : 'Contact Form Message';
    $message = isset($_POST['message']) ? strip_tags(trim($_POST['message'])) : '';
    
    // Validation
    $errors = array();
    
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required.";
    }
    
    // If no errors, send email
    if (empty($errors)) {
        
        // Create email content
        $email_subject = $subject_prefix . $subject;
        
        $email_body = "You have received a new message from your website contact form.\n\n";
        $email_body .= "Here are the details:\n\n";
        $email_body .= "Name: " . $name . "\n";
        $email_body .= "Email: " . $email . "\n";
        
        if (!empty($phone)) {
            $email_body .= "Phone: " . $phone . "\n";
        }
        
        $email_body .= "Subject: " . $subject . "\n\n";
        $email_body .= "Message:\n" . $message . "\n\n";
        $email_body .= "---\n";
        $email_body .= "This email was sent from your website contact form.";
        
        // Email headers
        $headers = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-type: text/plain; charset=UTF-8";
        $headers[] = "From: " . $name . " <" . $from_email . ">";
        $headers[] = "Reply-To: " . $email;
        $headers[] = "X-Mailer: PHP/" . phpversion();
        
        // Send email
        if (mail($to_email, $email_subject, $email_body, implode("\r\n", $headers))) {
            $response['success'] = true;
            $response['message'] = "Thank you! Your message has been sent successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Sorry, there was an error sending your message. Please try again later.";
        }
        
    } else {
        $response['success'] = false;
        $response['message'] = "Please fix the following errors: " . implode(", ", $errors);
    }
    
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

// Return JSON response
echo json_encode($response);
?>
