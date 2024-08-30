<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

include "simple_html_dom.php";
ini_set("memory_limit", "512M");

$jsonFilePath = 'parsed_products/All Data/products.json';
$csvFilePath = 'parsed_products/All Data/insert_products.csv'; 

$jsonData = file_get_contents($jsonFilePath);
$products = json_decode($jsonData, true);

$csvFile = fopen($csvFilePath, 'w');

// Write the header row
$header = [
    'id', 'name', 'brandedName', 'unbrandedName', 'currency', 'price', 'priceLabel', 
    'salePrice', 'salePriceLabel', 'inStock', 'image', 'alternateImages', 
    'discount', 'productUrl', 'crazzyTodayUrl', 'brand', 'category', 
    'productCategory', 'description', 'aiDescription', 'retailer'
];
fputcsv($csvFile, $header);

foreach ($products as $product) {
    $row = [
        $product['id'],
        $product['name'],
        $product['brandedName'],
        $product['unbrandedName'],
        $product['currency'],
        $product['price'],
        $product['priceLabel'],
        $product['salePrice'],
        $product['salePriceLabel'],
        $product['inStock'] ? 'TRUE' : 'FALSE',
        json_encode($product['image']),
        json_encode($product['alternateImages']),
        $product['discount'],
        $product['productUrl'],
        $product['crazzyTodayUrl'],
        $product['brand'],
        $product['category'],
        json_encode($product['productCategory']),
        $product['description'],
        $product['aiDescription'],
        $product['retailer']
    ];
    fputcsv($csvFile, $row);
}

fclose($csvFile);

echo "CSV file created successfully: $csvFilePath\n";
?>
