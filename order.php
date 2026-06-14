<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. फॉर्म से आ रहा डेटा रिसीव करना
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // 2. आपका डायरेक्ट API URL (टोकन और आईडी के साथ फिक्स किया हुआ)
    $api_url = "https://powerofthorplus.leadvertex.ru/api/webmaster/v2/addOrder.html?webmasterID=6&token=Shiv%402026";

    // अपने ऑफर ओनर से कन्फर्म करें कि प्रोडक्ट की असल ID क्या है (डिफ़ॉल्ट 1 रखा है)
    $goodID = '1'; 

    // 3. डॉक्यूमेंटेशन के स्ट्रक्चर के अनुसार पैरामीटर्स (x-www-form-urlencoded)
    $post_data = [
        'fio' => $name,
        'phone' => $phone,
        'address' => $address,
        'domain' => 'powerofthorplus.leadvertex.info',
        'utm_source' => 'web_form',
        
        // Goods Array स्ट्रक्चर जैसा डाक्यूमेंट्स में माँगा गया है
        'goods[0][goodID]' => $goodID,
        'goods[0][quantity]' => 1,
        'goods[0][price]' => 3000
    ];

    // 4. cURL के ज़रिए LeadVertex को सीधा डेटा पोस्ट करना
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 5. सुरक्षित बैकअप: अगर कोड 200 (सक्सेस) नहीं आता है, तो डेटा टेक्स्ट फाइल में सेव हो जाएगा
    if ($http_code != 200 || strpos($response, 'error') !== false) {
        $log_entry = date('Y-m-d H:i:s') . " | Status: $http_code | Response: $response | Name: $name | Phone: $phone \n";
        file_put_contents('leads_backup.txt', $log_entry, FILE_APPEND);
    }

    echo $response;
}
?>
