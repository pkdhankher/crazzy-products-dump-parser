<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

include "simple_html_dom.php";

$base_folder = "Home";
createDirectory($base_folder);
$category_name = "Home";
$page_count = 1;
$continious = true;
$limit = 80;
$offset = 0;

while ($continious == true) {
    $file_name = $category_name . "_" . $offset . ".json";
    $file_path = $base_folder . "/" . $file_name;
    try {
        echo "Writing Category: " .
            $category_name .
            ", page: " .
            $page_count .
            ", offset: " .
            $offset .
            PHP_EOL;

        $url =
            "https://www.shopstyle.com/api/v2/products?abbreviatedCategoryHistogram=true&allowParamScriptScore=true&autodrill=0&cat=living&device=desktop&expandedPriceTitle=true&includeInflencerRecProducts=false&includeInfluencerDetailsInRec=true&includeOtherStoreCount=true&includeProducts=true&includeSavedQueryId=true&includeSponsoredProducts=false&influencerRecProductLimit=20&limit=" .
            $limit .
            "&locales=all&maxNumFilters=1000&newPage=true&offset=" .
            $offset .
            "&onModel=retailer&pca=true&pid=shopstyle&preComputedGrouping=true&prevCat=living&productScore=LessPopularityHighEPC&rankFactors=epc%2Cpop1%2Cclk%2Csrk&rankMultipliers=0.324%2C0.0027%2C9.0%2C1.5&smartSearch=0&sort=Popular&suppressScoring=false&url=%2Fbrowse%2Fliving&view=angular";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);

        if ($response === false) {
            echo "Error fetching data from API. Response: " . $response . "\n";
            break;
        }

        $data = json_decode($response, true);
        if (
            !isset($data["products"]) ||
            !is_array($data["products"]) ||
            empty($data["products"])
        ) {
            echo "No valid products found in the response.\n";
            stopScrapping();
            break;
        }

        $page_count++;
        $offset = $offset + $limit;
        createFile($response, $file_path);

        curl_close($curl);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . PHP_EOL;
        stopScrapping();
    }
}

function stopScrapping()
{
    //writting final paranthisis
    // $file_name = $GLOBALS["category_name"] . ".json";
    // $file_path = $GLOBALS["base_folder"] . "/" . $file_name;
    // $data = "]";
    // createFile($data, $file_path);
    $GLOBALS["continious"] = false;
}

function createFile($data, $file_path)
{
    if (file_put_contents($file_path, $data, FILE_APPEND | LOCK_EX) === false) {
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
