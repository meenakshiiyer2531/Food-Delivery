<?php
// CORS headers
header("Access-Control-Allow-Origin: *"); // Allow all origins during development
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Enable error reporting during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set header to return JSON
header('Content-Type: application/json');

try {
    // Read raw POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!$data || !isset($data['restaurant']) || !isset($data['cart'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid input format. 'restaurant' and 'cart' required."
        ]);
        exit;
    }

    $restaurant = $data['restaurant'];
    $cart = $data['cart'];

    // Optional: Ensure the carts/ directory exists
    if (!file_exists('carts')) {
        mkdir('carts', 0777, true);
    }

    // Create a sanitized filename
    $filename = 'carts/' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $restaurant) . '_cart.json';

    // Save cart to file
    file_put_contents($filename, json_encode($cart, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    // Success response
    echo json_encode([
        "status" => "success",
        "message" => "Cart saved successfully."
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?>
