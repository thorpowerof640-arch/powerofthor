<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. फॉर्म से आ रहा डेटा रिसीव करना
    $fio = isset($_POST['fio']) ? $_POST['fio'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 

    // 2. डॉक्यूमेंटेशन के अनुसार अपडेटेड API URL (webmasterID=7)
    $api_url = "https://powerofthorplus.leadvertex.ru/api/webmaster/v2/addOrder.html?webmasterID=7&token=Shiv%402026";

    // 3. डॉक्यूमेंटेशन के नियम के मुताबिक सामान (Goods) का एरे
    $goods = array(
        0 => array(
            'goodID' => 15,    // मैनेजर की इमेज के अनुसार आपकी प्रोडक्ट आईडी 15 है
            'quantity' => 1,   // मात्रा 1
            'price' => 500     // कीमत 500
        )
    );

    // 4. मुख्य पोस्ट डेटा बॉडी (Content-Type: application/x-www-form-urlencoded के लिए)
    $post_fields = array(
        'fio' => $fio,
        'phone' => $phone,
        'goods' => $goods,
        'domain' => 'powerofthorplus.leadvertex.info',
        'utm_source' => 'web_form'
    );

    // 5. cURL के ज़रिए LeadVertex को डेटा पोस्ट करना
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    // http_build_query एरे को 'application/x-www-form-urlencoded' फॉर्मेट में बदल देता है
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields)); 

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 6. लॉग ट्रैकर: टेस्टिंग का रिजल्ट देखने के लिए इसे 'leads_backup.txt' में रिकॉर्ड कर रहे हैं
    $log_entry = date('Y-m-d H:i:s') . " | HTTP Code: $http_code | Response: $response | Name: $fio | Phone: $phone \n";
    file_put_contents('leads_backup.txt', $log_entry, FILE_APPEND);

    // रिस्पॉन्स को ब्राउज़र पर दिखाना
    echo $response;
}
?>
