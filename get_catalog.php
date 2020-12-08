<?php
$auth = array(
    'app_client_id'=>'0241003120070660',
    'app_secret'=>'JDOVJCED0MXTMMKWDELMC5ML',
    'secret_key'=>'k5YgxmpKT7TaHanEImC5RtwJkILNuEhf3mODS91royAkibeVJAMIlcoseVHAZVV2sKIhmPp38Y1gbgRPUu5AR86wNCaUrwke0sURMmlXY9XQ7dNJ3a7qpS'
);
$auth = json_encode($auth);
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL => 'https://online.sbis.ru/oauth/service/',
    CURLOPT_POST => true,
    CURLOPT_HEADER => 0,
    CURLOPT_POSTFIELDS => $auth,
    CURLOPT_HTTPHEADER =>  array(
        'Content-type: charset=utf-8'
        )
));
$response = curl_exec($ch);
curl_close($ch);

$resp = json_decode($response,true);
$sid = ($resp['sid']);
$token= ($resp['token']);

echo ("SID:" . $sid . "<br>");
echo ("Token:" . $token. "<br>");




$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL => 'https://api.sbis.ru/retail/point/list',
    CURLOPT_HEADER => 0,
    CURLOPT_HTTPHEADER =>  array(
        'Content-type: charset=utf-8',
        'X-SBISAccessToken:'. $token
        )
));
$response = curl_exec($ch);
curl_close($ch);

$resp = json_decode($response,true);
$retail_point_id=$resp['salesPoints'][0]['id'];

echo ("retail_point_id:" . $retail_point_id. "<br><br>");


$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL => 'https://api.sbis.ru/retail/nomenclature/price-list?pointId='.strval($retail_point_id).'&actualDate=01.01.2020&page=0&pageSize=5',
    CURLOPT_HEADER => 0,
    CURLOPT_HTTPHEADER =>  array(
        'Content-type: charset=utf-8',
        'X-SBISAccessToken: '. $token
        )
));
$response = curl_exec($ch);
curl_close($ch);
$resp = json_decode($response,true);
$price_list_id=$resp['priceLists'][0]['id'];

echo ("price_list_id:" . $price_list_id. "<br><br>");



$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL => 'https://api.sbis.ru/retail/nomenclature/list?&pointId='.strval($retail_point_id).'&priceListId='.strval($price_list_id).'&withBalance=true&page=0&pageSize=50',
    CURLOPT_HEADER => 0,
    CURLOPT_HTTPHEADER =>  array(
        'Content-type: charset=utf-8',
        'X-SBISAccessToken:'. $token
        )
));
$response = curl_exec($ch);
curl_close($ch);

$resp = json_decode($response,true);

foreach ($resp['nomenclatures'] as $arr) {
    foreach ($arr as $key => $value) {
        
        //print_r ($key);
        if (!is_array($value)) {
        echo ($key.": ".$value."");
        echo ("</br>");
        }
    }
    echo ("</br>");
}



$auth1 = array(
    'event'=>'exit',
    'token'=>$token
);
$auth1 = json_encode($auth1);
$ch1 = curl_init();
curl_setopt_array($ch1, array(
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL => 'https://online.sbis.ru/oauth/service/',
    CURLOPT_POST => true,
    CURLOPT_HEADER => 0,
    CURLOPT_POSTFIELDS => $auth1,
    CURLOPT_HTTPHEADER =>  array(
        'Content-type: charset=utf-8'
        )
));
$response = curl_exec($ch1);
curl_close($ch1);

echo ('Close session: '.$response);

?>