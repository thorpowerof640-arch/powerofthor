<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. फॉर्म से डेटा रिसीव करना
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // 2. आपके द्वारा दी गई डॉक्यूमेंटेशन के अनुसार क्रेडेंशियल्स
    $webmasterID = '6';
    $token = 'Shiv@2026';
    $api_url = "https://powerofthorplus.leadvertex.ru/api/webmaster/v2/addOrder.html?webmasterID=" . $webmasterID . "&token=" . $token;

    // ⚠️ अपने ऑफर ओनर/पैनल से कन्फर्म करें कि आपके प्रोडक्ट की असल ID क्या है।
    // अगर प्रोडक्ट ID 1 है तो '1' रहने दें, अन्यथा उसे यहाँ बदलें।
    $goodID = '1'; 

    // 3. डॉक्यूमेंटेशन के हुकूम स्ट्रक्चर के अनुसार पैरामीटर्स (x-www-form-urlencoded के अनुकूल)
    $post_data = [
        'fio' => $name,
        'phone' => $phone,
        'address' => $address,
        'domain' => 'powerofthorplus.leadvertex.info',
        'utm_source' => 'web_form',
        
        // डॉक्यूमेंटेशन के मुताबिक Goods Array स्ट्रक्चर
        'goods[0][goodID]' => $goodID,
        'goods[0][quantity]' => 1,
        'goods[0][price]' => 3000
    ];

    // 4. cURL के ज़रिए POST रिक्वेस्ट भेजना
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    
    // डॉक्यूमेंटेशन की मांग: Content-Type: application/x-www-form-urlencoded
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 5. बैकअप ट्रैकर: यदि रिस्पॉन्स कोड 200 नहीं है या एरर है, तो बैकअप फाइल में डेटा रिकॉर्ड होगा
    if ($http_code != 200 || strpos($response, 'error') !== false) {
        $log_entry = date('Y-m-d H:i:s') . " | Status: $http_code | Response: $response | Name: $name | Phone: $phone \n";
        file_put_contents('leads_backup.txt', $log_entry, FILE_APPEND);
    }

    echo $response;
}
?>
