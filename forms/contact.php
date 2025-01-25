<?php
// Replace this with your receiving email address
$receiving_email_address = 'ykarlosh@gmail.com';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form inputs
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

    // Check if any required field is empty
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    // Compose the email
    $email_body = "You have received a new message from the contact form:\n\n";
    $email_body .= "Name: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Subject: $subject\n";
    $email_body .= "Message:\n$message\n";

    // Email headers
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    $mail_sent = mail($receiving_email_address, $subject, $email_body, $headers);

    // Return response
    if ($mail_sent) {
        echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
    }
} else {
    // If accessed directly, return an error
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
