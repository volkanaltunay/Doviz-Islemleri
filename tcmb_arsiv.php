<?php
header("Content-Type: application/json; charset=utf-8");

function tcmbArsivUrl($date) {
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    $yyyy = $dt->format('Y');
    $MM = $dt->format('m');
    $dd = $dt->format('d');
    $MMM = strtoupper(date('M', $dt->getTimestamp())); // İngilizce ay kısaltması
    return "https://www.tcmb.gov.tr/kurlar/{$yyyy}{$MM}/{$dd}{$MMM}{$yyyy}.xml";
}

function getKurByDate($date) {
    $url = tcmbArsivUrl($date);
    $xml = @file_get_contents($url);
    if ($xml === false) return false;
    $doc = simplexml_load_string($xml);
    $result = [];
    foreach ($doc->Currency as $currency) {
        $code = (string)$currency['Kod'];
        $forexSelling = str_replace(",", ".", (string)$currency->ForexSelling);
        if ($code && $forexSelling) {
            $result[$code] = floatval($forexSelling);
        }
    }
    return $result;
}

$dates = [
    '2024-08-01',
    '2024-08-02',
    '2024-08-05'
];

$output = [];
foreach ($dates as $date) {
    $kur = getKurByDate($date);
    if ($kur !== false) {
        $output[$date] = $kur;
    } else {
        $output[$date] = "Veri yok";
    }
}

echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>