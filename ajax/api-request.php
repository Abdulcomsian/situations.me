<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$openAISecretKey = // key here;

$openAIEndpoint = 'https://api.openai.com/v1/chat/completions';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_input'])) {
        $user_input = $_POST['user_input'];

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $user_input
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $openAIEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $openAISecretKey
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            exit;
        }

        $responseData = json_decode($response, true);

        error_log('API Response: ' . print_r($responseData, true));

        if (isset($responseData['choices'][0]['message']['content'])) {
            echo json_encode([
                'choices' => [
                    [
                        'message' => [
                            'content' => $responseData['choices'][0]['message']['content']
                        ]
                    ]
                ]
            ]);
        } else {
            echo json_encode(['error' => 'Invalid response from OpenAI']);
        }

        curl_close($ch);
    } else {
        echo json_encode(['error' => 'No user input received']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
