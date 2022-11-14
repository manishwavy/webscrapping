<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sample.csv"');
fputcsv(
    fopen('php://output', 'w+'),
    array(
        'S.No.',
        'City',
        // 'Email',
        // 'Address'
    )
);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
$httpClient = new \GuzzleHttp\Client();
$response = $httpClient->get('https://www.floridaleagueofcities.com/research-resources/municipal-directory/');
$htmlString = (string) $response->getBody();

libxml_use_internal_errors(true);
$doc = new DOMDocument();
$doc->loadHTML($htmlString);
$xpath = new DOMXPath($doc);

$titles = $xpath->evaluate('//div[@class="accordion-body"]//ul//li/a');

$extractedTitles = [];
$i = 1;
foreach ($titles as $title) {
    $no = $i;
    $city = $title->textContent;
    $email =  $title->getAttribute('data-email');
    $address =  $title->getAttribute('data-address');
    fputcsv(
        fopen('php://output', 'w+'),
        array(
            $no,
            $city,
            // $email,
            // $address
        )
    );
    $i++;
}

?>