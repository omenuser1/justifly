<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مساعد الغياب الذكي</title>
    
    <link rel="stylesheet" href="<?php echo $base_url; ?>chatbot.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="chatbot-container">
        <div class="chatbot-header">
            <h3>مساعد الغياب الذكي</h3>
            <button class="close-btn"><i class="fas fa-times"></i></button>
        </div>
        <div class="chatbot-messages" id="chatbot-messages">
            <div class="bot-message">
                <p>مرحبًا! أنا مساعدك الذكي لتطبيق إدارة الغياب. كيف يمكنني مساعدتك اليوم؟</p>
            </div>
        </div>
        <div class="suggestions" id="suggestions">
            <!-- سيتم ملؤها بالاقتراحات عبر JavaScript -->
        </div>
        <div class="chatbot-input">
            <input type="text" id="user-input" placeholder="اكتب سؤالك هنا...">
            <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <script src="<?php echo $base_url; ?>js/chatbot.js"></script>
</body>
</html>