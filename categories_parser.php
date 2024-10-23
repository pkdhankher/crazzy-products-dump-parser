<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

include "simple_html_dom.php";
ini_set("memory_limit", "512M");

$directory = "Home"; //Home, Men, Women
$categoryFilePath = "Home_";      //Men Fashion_, Women Fashion_, Home_
$productsDirectory = "parsed_products";
$brands = [];
$retailers = [];
$categories = [];
$allProducts = [];
$productCategories = [];
$count = 1;
$limit = 80;
$offset = 0;
$lastOffset = 9840;

$brandsFilePath = "$productsDirectory/$directory/brands.json";
$productsFilePath = "$productsDirectory/$directory/products.json";
$retailersFilePath = "$productsDirectory/$directory/retailers.json";
$categoriesFilePath = "$productsDirectory/$directory/categories.json";
$productCategoriesFilePath = "$productsDirectory/$directory/product_categories.json";

if (!is_dir(dirname($brandsFilePath))) {
    mkdir(dirname($brandsFilePath), 0777, true);
}

if (!is_dir(dirname($productsFilePath))) {
    mkdir(dirname($productsFilePath), 0777, true);
}

if (!is_dir(dirname($retailersFilePath))) {
    mkdir(dirname($retailersFilePath), 0777, true);
}


if (!is_dir(dirname($categoriesFilePath))) {
    mkdir(dirname($categoriesFilePath), 0777, true);
}

if (!is_dir(dirname($productCategoriesFilePath))) {
    mkdir(dirname($productCategoriesFilePath), 0777, true);
}

echo "Parsing Category: $directory\n";
for ($i = $offset; $i <= $lastOffset; $i += $limit) {
    $filename = "$directory/$categoryFilePath$i.json";
    echo "Parsing File: $filename\n";

    if (file_exists($filename)) {
        $jsonData = file_get_contents($filename);
        $data = json_decode($jsonData, true);

        $metadata = $data["metadata"];
        $products = $data["products"];

        $categoryName = $metadata["category"]["name"] ?? "";
        if (
            $categoryName &&
            !in_array($metadata["category"], $categories, true)
        ) {
            $categories[] = $metadata["category"];
        }

        foreach ($products as $product) {
            echo "Parsing Product: " . $product["id"] . ", Count: $count \n";
            $formattedProduct = [
                "id" => $product["id"] ?? "",
                "name" => $product["name"] ?? "",
                "brandedName" => $product["brandedName"] ?? "",
                "unbrandedName" => $product["unbrandedName"] ?? "",
                "currency" => $product["currency"] ?? "",
                "price" => $product["price"] ?? "",
                "priceLabel" => $product["priceLabel"] ?? "",
                "salePrice" => $product["salePrice"] ?? "",
                "salePriceLabel" => $product["salePriceLabel"] ?? "",
                "inStock" => $product['inStock'] ?: "",
                "image" => $product["image"] ?? "",
                "alternateImages" => $product["alternateImages"] ?? "",
                "discount" => $product["discount"] ?? "",
                "productUrl" => $product["stock"][0]["productUrl"] ?? "",
                "crazzyTodayUrl" =>
                    "http://www.crazzytoday.com/product/" . $product["id"] ??
                    "",
                "brand" => $product["brand"]["name"] ?? "",
                "category" => $metadata["category"]["id"] ?? "",
                "productCategory" => array_map(function ($category) {
                    return $category["name"] ?? "";
                }, $product["categories"] ?? []),
                "description" => $product["description"] ?? "",
                "aiDescription" => $product["aiDescription"] ?? "",
                "retailer" => $product["retailer"]["name"] ?? "",
            ];
            $allProducts[] = $formattedProduct;
            $count++;

            $brandName = $product["brand"]["name"] ?? "";
            if ($brandName && !in_array($product["brand"], $brands, true)) {
                $brands[] = $product["brand"];
            }
            
            $retailerName = $product["retailer"]["name"] ?? "";
            if ($retailerName && !in_array($product["retailer"], $retailers, true)) {
                $retailers[] = $product["retailer"];
            }

            foreach ($product["categories"] as $productCategory) {
                $productCategoryId = $productCategory["id"] ?? "";
                if (
                    $productCategoryId &&
                    !in_array($productCategory, $productCategories, true)
                ) {
                    $productCategories[] = $productCategory;
                }
            }
        }
        // Clear memory after processing the file
        unset($data);
        unset($products);
        unset($jsonData);
        gc_collect_cycles(); // Force garbage collection
    }
}

$brandsJson = json_encode($brands, JSON_PRETTY_PRINT);
$retailerJson = json_encode($retailers, JSON_PRETTY_PRINT);
$productsJson = json_encode($allProducts, JSON_PRETTY_PRINT);
$categoriesJson = json_encode($categories, JSON_PRETTY_PRINT);
$productCategoriesJson = json_encode($productCategories, JSON_PRETTY_PRINT);

file_put_contents($brandsFilePath, $brandsJson);
file_put_contents($productsFilePath, $productsJson);
file_put_contents($retailersFilePath, $retailerJson);
file_put_contents($categoriesFilePath, $categoriesJson);
file_put_contents($productCategoriesFilePath, $productCategoriesJson);

echo "Products have been successfully merged into .$productsFilePath.\n";

function stopScrapping()
{
    //writting final paranthisis
    $file_name = $GLOBALS["category_name"] . ".json";
    $file_path = $GLOBALS["base_folder"] . "/" . $file_name;
    $data = "]";
    createFile($data, $file_path);
    $GLOBALS["continious"] = false;
}

function createFile($data, $file_path)
{
    if (
        file_put_contents(
            $file_path,
            $data . PHP_EOL,
            FILE_APPEND | LOCK_EX
        ) === false
    ) {
        die("Failed to write file");
    }
}

function createDirectory($path)
{
    if (file_exists($path)) {
        return;
    }

    mkdir($path, 0777, false);
}
?>


