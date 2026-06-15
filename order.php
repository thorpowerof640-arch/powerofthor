<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. फॉर्म से आ रहा डेटा रिसीव करना
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // 2. आपका डायरेक्ट API URL (टोकन और आईडी के साथ)
    $api_url = "https://powerofthorplus.leadvertex.ru/api/webmaster/v2/addOrder.html?webmasterID=6&token=Shiv%402026";

    // 3. मैनेजर की इमेज (image_473f61.jpg) के अनुसार प्रोडक्ट लिस्ट तैयार करना
    $productList = array(
        0 => array(
            'goodID' => 15,    // मैनेजर के स्क्रीनशॉट के मुताबिक ID 15 है
            'quantity' => 1,   // क्वांटिटी 1
            'price' => 500     // प्राइस 500
        )
    );

    // 4. मुख्य डेटा एरे तैयार करना (जैसा मैनेजर के उदाहरण में है)
    $data = array(
        'fio' => $name,
        'phone' => $phone,
        'address' => $address, // आपका कस्टम एड्रेस फ़ील्ड
        'goods' => $productList, // यहाँ प्रोडक्ट लिस्ट एरे जा रहा है
        'domain' => 'powerofthorplus.leadvertex.info',
        'utm_source' => 'web_form'
    );

    // 5. cURL सेटअप (हूबहू मैनेजर के कोड के नियमों पर आधारित)
    $myCurl = curl_init();
    curl_setopt_array($myCurl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_POSTFIELDS => http_build_query($data), // डेटा को सही से कनवर्ट करेगा
    ));

    // रिक्वेस्ट भेजना
    $response = curl_exec($myCurl);
    $http_code = curl_getinfo($myCurl, CURLINFO_HTTP_CODE);
    curl_close($myCurl);

    // 6. बैकअप सिस्टम: अगर ओनर की तरफ से परमिशन का कोई इशू होगा तो डेटा मिस नहीं होगा
    if ($http_code != 200 || strpos($response, 'error') !== false) {
        $log_entry = date('Y-m-d H:i:s') . " | Code: $http_code | Response: $response | Name: $name | Phone: $phone \n";
        file_put_contents('leads_backup.txt', $log_entry, FILE_APPEND);
    }

    // रिस्पॉन्स स्क्रीन पर दिखाना
    echo $response;
}
?>
