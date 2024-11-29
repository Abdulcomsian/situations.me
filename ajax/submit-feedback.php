<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recommendationsUseful = isset($_POST['recommendations_useful']) ? $_POST['recommendations_useful'] : '';
    $learnedAnythingNew = isset($_POST['learned_anything_new']) ? $_POST['learned_anything_new'] : '';
    $productsAndServicesRelevant = isset($_POST['products_and_services_relevant']) ? $_POST['products_and_services_relevant'] : '';
    $yourSuggestion = isset($_POST['your_suggestion']) ? trim($_POST['your_suggestion']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
        exit;
    }

    if (empty($recommendationsUseful) || empty($learnedAnythingNew) || empty($productsAndServicesRelevant)) {
        echo json_encode(['success' => false, 'message' => 'Please answer all the questions.']);
        exit;
    }

    $subject = "New Feedback Received!";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset=\"UTF-8\">
        <title>New Feedback Received</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                color: #333;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
            }
            .container {
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
                max-width: 600px;
                margin: auto;
            }
            .header {
                background-color: #E89439;
                color: #ffffff;
                padding: 10px 0;
                text-align: center;
                border-radius: 8px 8px 0 0;
            }
            .content {
                padding: 20px;
                line-height: 1.6;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                color: #888;
                font-size: 0.9em;
            }
            .response-label {
                font-weight: bold;
                color: #E89439;
            }
            .response-value {
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class=\"container\">
            <div class=\"header\">
                <h2>New Feedback Received</h2>
            </div>
            <div class=\"content\">
                <p>A user has submitted feedback through your website. Below are the details:</p>
                <div class=\"response-value\">
                    <span class=\"response-label\">User Email:</span> " . htmlspecialchars($email) . "
                </div>
                <div class=\"response-value\">
                    <span class=\"response-label\">Recommendations Useful:</span> " . htmlspecialchars($recommendationsUseful) . "
                </div>
                <div class=\"response-value\">
                    <span class=\"response-label\">Learned Anything New:</span> " . htmlspecialchars($learnedAnythingNew) . "
                </div>
                <div class=\"response-value\">
                    <span class=\"response-label\">Products and Services Relevant:</span> " . htmlspecialchars($productsAndServicesRelevant) . "
                </div>
                <div class=\"response-value\">
                    <span class=\"response-label\">Your Suggestion:</span> " . nl2br(htmlspecialchars($yourSuggestion)) . "
                </div>
            </div>
            <div class=\"footer\">
                <p>Thank you for your attention to this feedback.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Set the email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@yourdomain.com" . "\r\n";

    // Send the email
    if (mail($email, $subject, $message, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Feedback submitted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send the email.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
