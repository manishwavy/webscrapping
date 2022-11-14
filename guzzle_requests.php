<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sample.csv"');
fputcsv(
    fopen('php://output', 'w+'),
    array(
        'S.No.',
        'City',
        'Country',
        'Email',
        'Street Address',
        'Mailing Address',
        'Population',
        'Fax Number',
        'Phone Number',
        'Website',
        'Year Incorporated'
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
    $country =  $title->getAttribute('data-county');
    $email =  $title->getAttribute('data-email');
    $address =  $title->getAttribute('data-address');
    $population =  $title->getAttribute('data-population');
    $fax =  $title->getAttribute('data-fax');
    $phone =  $title->getAttribute('data-phone');
    $mailingaddress =  $title->getAttribute('data-mailingaddress');
    $website =  $title->getAttribute('data-website');
    $years =  $title->getAttribute('data-years');
    fputcsv(
        fopen('php://output', 'w+'),
        array(
            $no,
            $city,
            $country,
            $email,
            $address,
            $mailingaddress,
            $population,
            $fax,
            $phone,
            $website,
            $years
        )
    );
    $i++;
}

?>