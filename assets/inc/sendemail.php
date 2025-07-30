<?php
header('Content-Type: application/json');

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
    
    // If no errors, save to file
    if (empty($errors)) {
        
        // Create contact data array
        $contact_data = array(
            'timestamp' => date('Y-m-d H:i:s'),
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
        );
        
        // Prepare data for file
        $file_content = "\n" . str_repeat("=", 50) . "\n";
        $file_content .= "NEW CONTACT FORM SUBMISSION\n";
        $file_content .= "Date: " . $contact_data['timestamp'] . "\n";
        $file_content .= str_repeat("-", 50) . "\n";
        $file_content .= "Name: " . $contact_data['name'] . "\n";
        $file_content .= "Email: " . $contact_data['email'] . "\n";
        
        if (!empty($contact_data['phone'])) {
            $file_content .= "Phone: " . $contact_data['phone'] . "\n";
        }
        
        $file_content .= "Subject: " . $contact_data['subject'] . "\n";
        $file_content .= "IP Address: " . $contact_data['ip_address'] . "\n\n";
        $file_content .= "Message:\n" . $contact_data['message'] . "\n";
        $file_content .= str_repeat("=", 50) . "\n";
        
        // Save to file
        $filename = 'contact_submissions.txt';
        $file_path = __DIR__ . '/' . $filename;
        
        // Try to save to file
        if (file_put_contents($file_path, $file_content, FILE_APPEND | LOCK_EX) !== false) {
            
            // Also save as JSON for easy reading
            $json_filename = 'contact_submissions.json';
            $json_file_path = __DIR__ . '/' . $json_filename;
            
            // Read existing JSON data
            $existing_data = array();
            if (file_exists($json_file_path)) {
                $existing_json = file_get_contents($json_file_path);
                $existing_data = json_decode($existing_json, true) ?: array();
            }
            
            // Add new submission
            $existing_data[] = $contact_data;
            
            // Save updated JSON
            file_put_contents($json_file_path, json_encode($existing_data, JSON_PRETTY_PRINT));
            
            $response['success'] = true;
            $response['message'] = "Thank you " . $name . "! Your message has been received successfully. We will contact you at " . $email . " soon.";
            
        } else {
            $response['success'] = false;
            $response['message'] = "Sorry, there was an error saving your message. Please try again later.";
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
