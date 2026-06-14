<?php
// एरर देखने के लिए इसे ऑन कर रहे हैं
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. फॉर्म से आ रहा डेटा रिसीव करना
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // 2. LeadVertex क्रेडेंशियल्स 
    $webmasterID = '6';
    $token = 'Shiv@2026';
    
    // स्पेलिंग एकदम सही (webmasterID):
    $api_url = "https://powerofthorplus.leadvertex.ru/api/webmaster/v2/addOrder.html?webmasterID=" . $webmasterID . "&token=" . $token;

    // 3. पैरामीटर्स मैप करना
    $post_data = [
        'fio' => $name,
        'phone' => $phone,
        'address' => $address,
        'domain' => 'powerofthorplus.leadvertex.info',
        'utm_source' => 'web_form'
    ];

    // 4. cURL रिक्वेस्ट
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 5. बैकअप सिस्टम और एरर लॉगिंग
    // अगर लीडवर्टेक्स एरर देगा तो वो इस फाइल में रिकॉर्ड हो जाएगा ताकि आप देख सकें क्या दिक्कत है
    $log_entry = date('Y-m-d H:i:s') . " | HTTP: $http_code | Response: $response | Data: [Name: $name, Phone: $phone] \n";
    file_put_contents('leads_debug_log.txt', $log_entry, FILE_APPEND);

    echo $response;
}
?>
