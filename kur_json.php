<?php
// filepath: c:\wamp64\www\Döviz_İşlemleri\kur_json.php
header("Content-Type: application/json; charset=utf-8");

$xml = @file_get_contents("https://www.tcmb.gov.tr/kurlar/today.xml");
if ($xml === false) {
    echo json_encode(["error" => "TCMB verisi alınamadı!"]);
    exit;
}

$doc = simplexml_load_string($xml);
$result = [
    "labels" => [],
    "data" => []
];
foreach ($doc->Currency as $currency) {
    $code = (string)$currency['Kod'];
    $forexSelling = str_replace(",", ".", (string)$currency->ForexSelling);
    if ($code && $forexSelling) {
        $result["labels"][] = $code;
        $result["data"][] = floatval($forexSelling);
    }
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>