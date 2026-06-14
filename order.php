<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. फॉर्म से आ रहा डेटा रिसीव करना
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : ''; // यहाँ 'phonc' ठीक कर दिया है
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // 2. आपके द्वारा दिए गए Leadvertex क्रेडेंशियल्स
    $webmasterID = '6';
    $token = 'Shiv@2026';
    
    // यहाँ webmasterTD को ठीक करके webmasterID कर दिया गया है
    $api_url = "https://powerofthorplus.leadvertex.ru/api/webmaster/v2/addOrder.html?webmasterID=" . $webmasterID . "&token=" . $token;
    // 3. डॉक्यूमेंटेशन के अनुसार केवल ज़रूरी पैरामीटर्स को मैप करना
    $post_data = [
        'fio' => $name,
        'phone' => $phone,
        'address' => $address,
        'domain' => 'powerofthorplus.leadvertex.info',
        'utm_source' => 'web_form'
    ];

    // 4. cURL के ज़रिए बैकएंड से सीधे LeadVertex को डेटा पोस्ट करना (CORS ब्लॉकिंग से बचने के लिए)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 5. 🛠️ सुरक्षित बैकअप: अगर ओनर ने API इम्पोर्ट बंद किया हुआ है, तो लीड इस टेक्स्ट फाइल में सेव हो जाएगी
    if ($http_code != 200) {
        $log_entry = date('Y-m-d H:i:s') . " | Name: $name | Phone: $phone | Address: $address \n";
        file_put_contents('leads_backup.txt', $log_entry, FILE_APPEND);
    }

    echo $response;
}
?>
