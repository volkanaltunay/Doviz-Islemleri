<?php
header("Content-Type: text/html; charset=utf-8");

$xml = @file_get_contents("https://www.tcmb.gov.tr/kurlar/today.xml");
if ($xml === false) {
    echo "<p>TCMB verisi alınamadı!</p>";
    exit;
}

$doc = simplexml_load_string($xml);
echo '<table border="1" cellpadding="5" cellspacing="0">';
echo '<tr>
        <th>Döviz Kodu</th>
        <th>Döviz Adı</th>
        <th>Birim</th>
        <th>Alış (TL)</th>
        <th>Satış (TL)</th>
        <th>Efektif Alış</th>
        <th>Efektif Satış</th>
      </tr>';

foreach ($doc->Currency as $currency) {
    $code = (string)$currency['Kod'];
    $name = (string)$currency->Isim;
    $unit = (string)$currency->Unit;
    $forexBuying = str_replace(",", ".", (string)$currency->ForexBuying);
    $forexSelling = str_replace(",", ".", (string)$currency->ForexSelling);
    $banknoteBuying = str_replace(",", ".", (string)$currency->BanknoteBuying);
    $banknoteSelling = str_replace(",", ".", (string)$currency->BanknoteSelling);

    echo "<tr>
            <td>{$code}</td>
            <td>{$name}</td>
            <td>{$unit}</td>
            <td>{$forexBuying}</td>
            <td>{$forexSelling}</td>
            <td>{$banknoteBuying}</td>
            <td>{$banknoteSelling}</td>
          </tr>";
}
echo '</table>';
?>