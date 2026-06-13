<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. फॉर्म से आ रहा डेटा रिसीव करना
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // 2. आपकी LeadVertex API कॉन्फ़िगरेशन
    $lv_domain = 'powerofthorplus.leadvertex.ru';
    $lv_token = 'Shiv@2026';
    $goods_id = '1'; // 👈 यहाँ अपने LeadVertex प्रोडक्ट की सही Goods ID डाल दें (जैसे 1, 2, या 3)

    // 3. LeadVertex Admin API URL (लीड ऐड करने के लिए)
    $api_url = "https://" . $lv_domain . "/api/admin/addOrder.html?token=" . $lv_token;

    // 4. API के लिए डेटा का एरे तैयार करना (जैसा डॉक्यूमेंटेशन में चाहिए)
    $post_data = [
        'fio' => $name,
        'phone' => $phone,
        'address' => $address,
        'goods[' . $goods_id . ']' => '1', // Quantity: 1
        'utm_source' => 'direct_lp'
    ];

    // 5. cURL के ज़रिए बैकएंड से सीधे LeadVertex को डेटा पोस्ट करना
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL सर्टिफिकेट एरर से बचने के लिए

    $response = curl_exec($ch);
    curl_close($ch);

    // रिस्पॉन्स को आप चाहें तो लॉग कर सकते हैं
    echo $response;
}
?>
