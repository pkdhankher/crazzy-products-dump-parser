<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

include "simple_html_dom.php";
ini_set('memory_limit', '512M');

$directory = "womens";
$productsDirectory = "parsed_products";
$allProducts = [];
$count = 1;
$limit = 80;
$offset = 0;
$lastOffset = 9840;

echo "Parsing Category: $directory\n";
for ($i = $offset; $i <= $lastOffset; $i += $limit) {
    $filename = "$directory/Women Fashion_$i.json";
    echo "Parsing File: $filename\n";

    if (file_exists($filename)) {
        $jsonData = file_get_contents($filename);
        $data = json_decode($jsonData, true);

        $metadata = $data["metadata"];
        $products = $data["products"];
 
        foreach ($products as $product) {
            echo "Parsing Product: " . $product["id"] . ", Count: $count \n";
            $formattedProduct = [
                "id" => $product["id"]?? '',
                "name" => $product["name"]?? '',
                "brandedName" => $product["brandedName"]?? '',
                "unbrandedName" => $product["unbrandedName"]?? '',
                "currency" => $product["currency"]?? '',
                "price" => $product["price"]?? '',
                "priceLabel" => $product["priceLabel"]?? '',
                "salePrice" => $product["salePrice"]?? '',
                "salePriceLabel" => $product["salePriceLabel"]?? '',
                "image" => $product["image"]?? '',
                "alternateImages" => $product["alternateImages"]?? '',
                "discount" => $product["discount"]?? '',
                "productUrl" => $product["stock"][0]["productUrl"]?? '',
                "crazzyTodayUrl" => "http://www.crazzytoday.com/product/" . $product["id"]?? '',
                "brand" => $product["brand"]["name"]?? '',
                "category" => $metadata["category"]?? '',
                "majorCategory" => $metadata["majorCategory"]?? '',
                "productCategory" => $product["categories"]?? '',
                "description" => $product["description"]?? '',
                "aiDescription" => $product["aiDescription"]?? '',
                "retailer" => $product["retailer"]["name"]?? '',
            ];
            $allProducts[] = $formattedProduct;
            $count++;
        }
        // Clear memory after processing the file
        unset($data);
        unset($products);
        unset($jsonData);
        gc_collect_cycles(); // Force garbage collection
    }
}

$finalJson = json_encode($allProducts, JSON_PRETTY_PRINT);

$filePath = "$productsDirectory/$directory/products.json";

if (!is_dir(dirname($filePath))) {
    mkdir(dirname($filePath), 0777, true);
}

file_put_contents($filePath, $finalJson);

echo "Products have been successfully merged into .$filePath.\n";

function stopScrapping(){
		//writting final paranthisis
	$file_name = $GLOBALS['category_name'].'.json';
	$file_path = $GLOBALS['base_folder'].'/'.$file_name;
	$data = ']';
	createFile($data,$file_path);
	$GLOBALS['continious'] = false;
}


function createFile($data,$file_path){
	if (file_put_contents($file_path, $data.PHP_EOL , FILE_APPEND | LOCK_EX) === false) {
		die("Failed to write file");
	}
}


function createDirectory($path){
	if (file_exists($path))
		return;

	mkdir($path, 0777, false);
}



?>


