<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. फॉर्म से आ रहा डेटा रिसीव करना
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // 2. LeadVertex क्रेडेंशियल्स
    $webmasterID = '6';
    $token = 'Shiv@2026';
    
    // ⚠️ ज़रूरी काम: अपने LeadVertex पैनल में जाकर देखें कि आपके प्रोडक्ट की ID क्या है।
    // अगर प्रोडक्ट ID 1 है तो '1' रहने दें, अगर कुछ और है (जैसे 24, 105) तो यहाँ बदल दें।
    $goodsID = '1'; 

    $api_url = "https://powerofthorplus.leadvertex.ru/api/webmaster/v2/addOrder.html?webmasterID=" . $webmasterID . "&token=" . $token;

    // 3. पैरामीटर्स मैप करना (डॉक्यूमेंटेशन के अनुसार Goods Array के साथ)
    $post_data = [
        'fio' => $name,
        'phone' => $phone,
        'address' => $address,
        'domain' => 'powerofthorplus.leadvertex.info',
        'utm_source' => 'web_form',
        
        // यह लाइन LeadVertex को बताती है कि कौन सा प्रोडक्ट कितनी मात्रा में आ रहा है
        'goods' => [
            0 => [
                'goodID' => $goodsID,
                'quantity' => 1,
                'price' => 3000 // आपकी डॉक्यूमेंटेशन के अनुसार प्रोडक्ट की प्राइस
            ]
        ]
    ];

    // 4. cURL रिक्वेस्ट भेजना
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    
    // सरणी (Array) डेटा भेजने के लिए http_build_query का सही इस्तेमाल
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 5. बैकअप सिस्टम: अगर अभी भी API रिजेक्ट होती है, तो डेटा इस फाइल में मिलेगा
    // इससे आपकी एक भी लीड का नुकसान नहीं होगा!
    if ($http_code != 200 || strpos($response, 'error') !== false) {
        $log_entry = date('Y-m-d H:i:s') . " | Response: $response | Name: $name | Phone: $phone | Address: $address \n";
        file_put_contents('leads_backup.txt', $log_entry, FILE_APPEND);
    }

    echo $response;
}
?>
