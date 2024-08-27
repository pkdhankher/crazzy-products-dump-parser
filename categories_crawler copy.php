







<?php

// The API endpoint with query parameters
$url = "https://www.shopstyle.com/api/v2/products?abbreviatedCategoryHistogram=true&allowParamScriptScore=true&autodrill=0&cat=women&device=mobile&expandedPriceTitle=true&includeInflencerRecProducts=false&includeInfluencerDetailsInRec=true&includeOtherStoreCount=true&includeProducts=true&includeSavedQueryId=true&includeSponsoredProducts=true&influencerRecProductLimit=20&limit=40&locales=all&maxNumFilters=1000&offset=40&onModel=retailer&pca=true&pid=shopstyle&preComputedGrouping=true&prevCat=women&productScore=LessPopularityHighEPC&rankFactors=epc%2Cpop1%2Cclk%2Csrk&rankMultipliers=0.324%2C0.0027%2C9.0%2C1.5&smartSearch=0&sort=Popular&suppressScoring=false&url=%2Fbrowse%2Fwomen&view=angular";

// Initialize cURL session
$ch = curl_init($url);

// Set options to return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
// curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/CAcerts/BuiltinObjectToken-EquifaxSecureCA.crt");

// Set headers
// curl_setopt($ch, CURLOPT_HTTPHEADER, [
//     'Host: www.shopstyle.com',
//     'User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Mobile Safari/537.36',
//     'Accept: */*',
//     'Accept-Encoding: gzip',
//     'Connection: keep-alive',
//     'content-type: application/json;charset=UTF-8',
//     'server: Apache/2.4.58 ()',
// ]);

        // $data = json_decode($response);
        // echo "Response: " . $data . PHP_EOL;
        // if (empty($data["products"])) {
        //     echo "No more products to fetch." . PHP_EOL;
        //     stopScrapping();
        //     break;
        // }

// Execute the cURL request
$response = curl_exec($ch);
echo "Data :". $response ."\n";

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    // Save response to a file
    file_put_contents('products_offset_40.json', $response);
    echo "Data saved successfully.\n";
}

// Close cURL session
curl_close($ch);

?>

