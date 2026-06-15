<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 

    $api_url = "https://powerofthorplus.leadvertex.ru/api/webmaster/v2/addOrder.html?webmasterID=6&token=Shiv%402026";

    $productList = array(
        0 => array(
            'goodID' => 15,
            'quantity' => 1,
            'price' => 500
        )
    );

    $data = array(
        'fio' => $name,
        'phone' => $phone,
        'goods' => $productList,
        'domain' => 'powerofthorplus.leadvertex.info',
        'utm_source' => 'web_form'
    );

    $myCurl = curl_init();
    curl_setopt_array($myCurl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_POSTFIELDS => http_build_query($data),
    ));

    $response = curl_exec($myCurl);
    $http_code = curl_getinfo($myCurl, CURLINFO_HTTP_CODE);
    curl_close($myCurl);

    if ($http_code != 200 || strpos($response, 'error') !== false) {
        $log_entry = date('Y-m-d H:i:s') . " | Code: $http_code | Response: $response \n";
        file_put_contents('leads_backup.txt', $log_entry, FILE_APPEND);
    }

    echo $response;
}
?>
