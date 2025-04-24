<?php
$url = "http://localhost/food_app/save_cart.php"; // corrected URL

$data = [
    "restaurant" => "The Food Spot",
    "cart" => [
        "Pizza" => ["qty" => 2],
        "Burger" => ["qty" => 1]
    ]
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json",
        "method"  => "POST",
        "content" => json_encode($data)
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "Error sending request.\n";
    print_r(error_get_last());
} else {
    echo $result;
}
?>
