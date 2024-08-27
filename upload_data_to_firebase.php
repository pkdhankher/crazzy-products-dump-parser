<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$caregories = json_decode(file_get_contents('wall_categories.json'),true);

$base_folder = "images_urls";

foreach ($caregories as $category) {

  $json_file = $base_folder.'/'.$category.'.json';
  $json_data = file_get_contents($json_file);
  $database_url = 'https://spectra-9745d.firebaseio.com/'.$category.'_wall.json';

echo "uploading category: ".$category.", database url: ".$database_url.PHP_EOL;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $database_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "PUT",
  CURLOPT_POSTFIELDS => $json_data,
  CURLOPT_HTTPHEADER => array("content-type: application/json"),
));


$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err.PHP_EOL;
} else {
  // echo $response.PHP_EOL;
}


}

?>