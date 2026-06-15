<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. फॉर्म से आ रहा नाम और नंबर रिसीव करना
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 

    // 2. आपका डायरेक्ट API URL 
    $api_url = "https://powerofthorplus.leadvertex.ru/api/webmaster/v2/addOrder.html?webmasterID=6&token=Shiv%402026";

    // 3. मैनेजर की इमेज (image_473f61.jpg) के अनुसार प्रोडक्ट एरे
    $productList = array(
        0 => array(
            'goodID' => 15,    // मैनेजर की इमेज के मुताबिक प्रोडक्ट आईडी 15 है
            'quantity' => 1,   // क्वांटिटी 1
            'price' => 500     // प्राइस 500
        )
    );

    // 4. मुख्य डेटा एरे (सिर्फ वही पैरामीटर्स जो मैनेजर के कोड में थे)
    $data = array(
        'fio' => $name,          // ग्राहक का नाम
        'phone' => $phone,        // ग्राहक का फोन नंबर
        'goods' => $productList,  // मैनेजर की प्रोडक्ट लिस्ट
        'domain' => 'powerofthorplus.leadvertex.info',
        'utm_source' => 'web_form'
    );

    // 5. मैनेजर के कोड के अनुसार cURL
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

    // 6. ट्रैकर: रिजल्ट देखने के लिए इसे लोकल फाइल में रिकॉर्ड कर रहे हैं
    $log_entry = date('Y-m-d H:i:s') . " | HTTP Code: $http_code | Server Response: $response \n";
    file_put_contents('leads_backup.txt', $log_entry, FILE_APPEND);

    echo $response;
}
?>
