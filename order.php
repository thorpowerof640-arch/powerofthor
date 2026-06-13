<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. HTML फॉर्म से डेटा प्राप्त करना
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // 2. आपकी LeadVertex API क्रेडेंशियल्स
    $lv_domain = 'powerofthorplus.leadvertex.ru';
    $lv_token = 'Shiv@2026';
    $goods_id = '1'; // 👈 यहाँ अपने LeadVertex CRM प्रोडक्ट की सही Goods ID डालें (जैसे 1, 2, या 3)

    // 3. LeadVertex Admin API URL (लीड सबमिशन एंडपॉइंट)
    $api_url = "https://" . $lv_domain . "/api/admin/addOrder.html?token=" . $lv_token;

    // 4. LeadVertex API की जरूरत के अनुसार एरे मैप करना
    $post_data = [
        'fio' => $name,
        'phone' => $phone,
        'address' => $address,
        'goods[' . $goods_id . ']' => '1', // क्वांटिटी 1 सेट की है
        'utm_source' => 'direct_landing_page'
    ];

    // 5. सर्वर साइड से सीधे LeadVertex को सुरक्षित रूप से डेटा भेजना (CORS एरर बाईपास)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $response = curl_exec($ch);
    curl_close($ch);

    // जरूरत पड़ने पर टेस्टिंग के लिए रिस्पॉन्स प्रिंट करना
    echo $response;
}
?>
