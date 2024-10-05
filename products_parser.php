<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

include "simple_html_dom.php";
ini_set("memory_limit", "512M");

$baseDirectory = "parsed_products";
$directories = ["Men", "Women", "Home"];

$allDataFolder = "$baseDirectory/All Data";

if (!file_exists($allDataFolder)) {
    mkdir($allDataFolder, 0777, true);
}

$allBrands = [];
$allProducts = [];
$allRetailers = [];
$allCategories = [];
$allProductCategories = [];

foreach ($directories as $dir) {
    echo "Parsing category -------------$dir-------------\n";

    $brandsFile = "$baseDirectory/$dir/brands.json";
    echo "Parsing file: $brandsFile\n";
    if (file_exists($brandsFile)) {
        $content = file_get_contents($brandsFile);
        $data = json_decode($content, true);

        if ($data === null) {
            echo "Error decoding JSON from file: $brandsFile\n";
            return;
        }

        foreach ($data as $brand) {
            $brandId = $brand["id"] ?? null;
            if ($brandId && !isset($allBrands[$brandId])) {
                $allBrands[] = $brand;
            }
        }
    } else {
        echo "File not found: $brandsFile\n";
    }
    
    $retailersFile = "$baseDirectory/$dir/retailers.json";
    echo "Parsing file: $retailersFile\n";
    if (file_exists($retailersFile)) {
        $content = file_get_contents($retailersFile);
        $data = json_decode($content, true);

        if ($data === null) {
            echo "Error decoding JSON from file: $retailersFile\n";
            return;
        }

        foreach ($data as $retailer) {
            $retailerId = $retailer["id"] ?? null;
            if ($retailerId && !isset($allRetailers[$retailerId])) {
                $allRetailers[] = $retailer;
            }
        }
    } else {
        echo "File not found: $retailersFile\n";
    }

    $productsFile = "$baseDirectory/$dir/products.json";
    echo "Parsing file: $productsFile\n";
    if (file_exists($productsFile)) {
        // Read the JSON content from the file
        $content = file_get_contents($productsFile);
        $data = json_decode($content, true);

        if ($data === null) {
            echo "Error decoding JSON from file: $productsFile\n";
            return;
        }

        foreach ($data as $product) {
            $productId = $product["id"] ?? null;
            if ($productId && !isset($allProducts[$productId])) {
                $allProducts[] = $product;
            }
        }
    } else {
        echo "File not found: $productsFile\n";
    }

    $categoriesFile = "$baseDirectory/$dir/categories.json";
    echo "Parsing file: $categoriesFile\n";
    if (file_exists($categoriesFile)) {
        $content = file_get_contents($categoriesFile);
        $data = json_decode($content, true);

        if ($data === null) {
            echo "Error decoding JSON from file: $categoriesFile\n";
            return;
        }

        foreach ($data as $category) {
            $categoryId = $category["id"] ?? null;
            if ($categoryId && !isset($allCategories[$categoryId])) {
                $allCategories[] = $category;
            }
        }
    } else {
        echo "File not found: $categoriesFile\n";
    }

    $productCategoriesFile = "$baseDirectory/$dir/product_categories.json";
    echo "Parsing file: $productCategoriesFile\n";
    if (file_exists($productCategoriesFile)) {
        $content = file_get_contents($productCategoriesFile);
        $data = json_decode($content, true);

        if ($data === null) {
            echo "Error decoding JSON from file: $productCategoriesFile\n";
            return;
        }

        foreach ($data as $productCategory) {
            $productCategoryId = $productCategory["id"] ?? null;
            if (
                $productCategoryId &&
                !isset($allProductCategories[$productCategoryId])
            ) {
                $allProductCategories[] = $productCategory;
            }
        }
    } else {
        echo "File not found: $productCategoriesFile\n";
    }
}

$mergedBrandsFileName = "$allDataFolder/brands.json";
file_put_contents(
    $mergedBrandsFileName,
    json_encode(array_values($allBrands), JSON_PRETTY_PRINT)
);
echo "Saving Brands: Total: " . count($allBrands) . "\n";

$mergedRetailersFileName = "$allDataFolder/retailers.json";
file_put_contents(
    $mergedRetailersFileName,
    json_encode(array_values($allRetailers), JSON_PRETTY_PRINT)
);
echo "Saving Retailers: Total: " . count($allRetailers) . "\n";

$mergedProductsFileName = "$allDataFolder/products.json";
file_put_contents(
    $mergedProductsFileName,
    json_encode(array_values($allProducts), JSON_PRETTY_PRINT)
);
echo "Saving Products: Total: " . count($allProducts) . "\n";

$mergedCategoriesFileName = "$allDataFolder/categories.json";
file_put_contents(
    $mergedCategoriesFileName,
    json_encode(array_values($allCategories), JSON_PRETTY_PRINT)
);
echo "Saving Categories: Total: " . count($allCategories) . "\n";

$mergedProductCategoriesFileName = "$allDataFolder/product_categories.json";
file_put_contents(
    $mergedProductCategoriesFileName,
    json_encode(array_values($allProductCategories), JSON_PRETTY_PRINT)
);
echo "Saving Product Categories: Total: " . count($allProductCategories) . "\n";

echo "Merging completed. Unique brands, products, retailers, categories and productCategories are saved in the All Data directory.\n";
?>

