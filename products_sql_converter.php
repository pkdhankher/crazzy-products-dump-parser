<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

include "simple_html_dom.php";
ini_set("memory_limit", "512M");

$jsonFilePath = 'parsed_products/All Data/products.json';
$sqlFilePath = 'parsed_products/All Data/insert_products.sql'; 
$jsonData = file_get_contents($jsonFilePath);
$products = json_decode($jsonData, true);

$sqlFile = fopen($sqlFilePath, 'w');

$createTableSQL = <<<SQL
CREATE TABLE products (
    id BIGINT PRIMARY KEY,
    name TEXT NOT NULL,
    brandedName TEXT,
    unbrandedName TEXT,
    currency TEXT,
    price DECIMAL(10, 2),
    priceLabel TEXT,
    salePrice DECIMAL(10, 2),
    salePriceLabel TEXT,
    inStock BOOLEAN,
    image JSONB,
    alternateImages JSONB,
    discount INT,
    productUrl TEXT,
    crazzyTodayUrl TEXT,
    brand TEXT,
    category TEXT,
    productCategory JSONB,
    description TEXT,
    aiDescription TEXT,
    retailer TEXT
);

SQL;
fwrite($sqlFile, $createTableSQL);

foreach ($products as $product) {
    $id = $product['id'];
    $name = addslashes($product['name']);
    $brandedName = addslashes($product['brandedName']);
    $unbrandedName = addslashes($product['unbrandedName']);
    $currency = $product['currency'];
    $price = $product['price'];
    $priceLabel = addslashes($product['priceLabel']);
    $salePrice = $product['salePrice'];
    $salePriceLabel = addslashes($product['salePriceLabel']);
    $inStock = $product['inStock'] ? 'TRUE' : 'FALSE';
    $image = addslashes(json_encode($product['image']));
    $alternateImages = addslashes(json_encode($product['alternateImages']));
    $discount = $product['discount'];
    $productUrl = addslashes($product['productUrl']);
    $crazzyTodayUrl = addslashes($product['crazzyTodayUrl']);
    $brand = addslashes($product['brand']);
    $category = addslashes($product['category']);
    $productCategory = addslashes(json_encode($product['productCategory']));
    $description = addslashes($product['description']);
    $aiDescription = addslashes($product['aiDescription']);
    $retailer = addslashes($product['retailer']);

    $insertSQL = "INSERT INTO products (id, name, brandedName, unbrandedName, currency, price, priceLabel, salePrice, salePriceLabel, inStock, image, alternateImages, discount, productUrl, crazzyTodayUrl, brand, category, productCategory, description, aiDescription, retailer) VALUES ($id, '$name', '$brandedName', '$unbrandedName', '$currency', $price, '$priceLabel', $salePrice, '$salePriceLabel', $inStock, '$image', '$alternateImages', $discount, '$productUrl', '$crazzyTodayUrl', '$brand', '$category', '$productCategory', '$description', '$aiDescription', '$retailer');\n";

    fwrite($sqlFile, $insertSQL);
}

// Close the SQL file
fclose($sqlFile);

echo "SQL file created successfully: $sqlFilePath\n";
?>
