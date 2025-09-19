<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once __DIR__ . '/../Database.php';

// تمكين وضع التصحيح
error_reporting(E_ALL);
ini_set('display_errors', 1);

// بدء الجلسة إذا لم تكن بدأت
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// تعريف الأدوار
define('ROLE_STUDENT', 1);
define('ROLE_TEACHER', 2);
define('ROLE_ADMIN', 3);

// تحميل قاعدة المعرفة
$responsesFile = __DIR__ . '/responses.json';
if (!file_exists($responsesFile)) {
    echo json_encode([
        'reply' => 'عذرًا، النظام غير متاح حاليًا',
        'suggestions' => []
    ]);
    exit;
}

$responses = json_decode(file_get_contents($responsesFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'reply' => 'حدث خطأ في تحميل قاعدة المعرفة',
        'suggestions' => []
    ]);
    exit;
}

// تحديد دور المستخدم
function getUserRole() {
    if (isset($_SESSION['user_id'])) return ROLE_STUDENT;
    if (isset($_SESSION['enseignant_logged_in'])) return ROLE_TEACHER;
    if (isset($_SESSION['admin_id'])) return ROLE_ADMIN;
    return 0;
}

// معالجة المدخلات
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = isset($input['message']) ? strtolower(trim($input['message'])) : '';
$userRole = getUserRole();

// البحث عن أفضل رد
function findBestResponse($message, $role, $responses) {
    // البحث في الأسئلة المباشرة أولاً
    if (isset($responses['direct_answers'])) {
        foreach ($responses['direct_answers'] as $qa) {
            if (isset($qa['questions']) && is_array($qa['questions'])) {
                foreach ($qa['questions'] as $question) {
                    if (strpos($message, strtolower($question)) !== false) {
                        if (!isset($qa['roles']) || empty($qa['roles']) || in_array($role, $qa['roles'])) {
                            return $qa['answer'];
                        }
                    }
                }
            }
        }
    }
    
    // البحث في الأنماط
    if (isset($responses['patterns'])) {
        foreach ($responses['patterns'] as $pattern) {
            if (isset($pattern['regex']) && preg_match($pattern['regex'], $message)) {
                if (!isset($pattern['roles']) || empty($pattern['roles']) || in_array($role, $pattern['roles'])) {
                    return $pattern['answer'];
                }
            }
        }
    }
    
    return $responses['default'];
}

// إنشاء اقتراحات بناءً على دور المستخدم
function getSuggestions($role) {
    $suggestions = [];
    
    if ($role == ROLE_STUDENT) {
        $suggestions = [
            "كيف أقدم تبرير غياب؟",
            "ما هي المدة المسموح بها للغياب؟",
            "كيف أعرف حالة تبريري؟"
        ];
    } elseif ($role == ROLE_TEACHER) {
        $suggestions = [
            "كيف أرى غياب الطلاب؟",
            "كيف أتصفّح التبريرات المقبولة؟",
            "كيف أتلقى إشعارات بالغياب؟"
        ];
    } elseif ($role == ROLE_ADMIN) {
        $suggestions = [
            "كيفة إدارة طلبات الغياب",
            "كيف أغير كلمة مرور الأستاذ",
            "كيف أرشيف التبريرات القديمة"
        ];
    } else {
        $suggestions = [
            "كيف أبلغ عن غياب؟",
            "ما هي سياسة الغياب؟",
            "كيف أتحقق من غياباتي؟"
        ];
    }
    
    return $suggestions;
}

// الحصول على الرد
$response = [
    'reply' => findBestResponse($userMessage, $userRole, $responses),
    'suggestions' => getSuggestions($userRole)
];

echo json_encode($response);
?>