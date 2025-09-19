<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - جامعة بلحاج بوشعيب</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
        }
        
        .header {
            background: #6a11cb;
            color: white;
            text-align: center;
            padding: 20px;
        }
        
        .header h2 {
            font-weight: 700;
            font-size: 24px;
        }
        
        .university-name {
            font-size: 14px;
            margin-top: 5px;
            opacity: 0.9;
        }
        
        .form-container {
            padding: 25px;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        .input-group input:focus {
            border-color: #6a11cb;
            outline: none;
        }
        
        .btn {
            background: #6a11cb;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #2575fc;
        }
        
        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        
        .links a {
            color: #6a11cb;
            text-decoration: none;
            font-size: 14px;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
            display: none;
        }
        
        .error {
            background: #ffebee;
            color: #d32f2f;
            border: 1px solid #f44336;
        }
        
        .success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }
        
        .user-type {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .user-type span {
            font-weight: 500;
            color: #6a11cb;
        }
        
        @media (max-width: 480px) {
            .container {
                width: 95%;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>تسجيل الدخول</h2>
            <div class="university-name">جامعة بلحاج بوشعيب - عين تموشنت</div>
        </div>
        
        <div class="form-container">
            <div id="errorMessage" class="message error">
                اسم المستخدم أو كلمة المرور غير صحيحة
            </div>
            
            <form id="loginForm" method="POST" action="">
                <div class="input-group">
                    <label for="username">اسم المستخدم</label>
                    <input type="text" id="username" name="username" required placeholder="أدخل اسم المستخدم">
                </div>
                
                <div class="input-group">
                    <label for="password">كلمة المرور</label>
                    <input type="password" id="password" name="password" required placeholder="أدخل كلمة المرور">
                </div>
                
                <button type="submit" class="btn">تسجيل الدخول</button>
                
                <div class="links">
                    <a href="#">نسيت كلمة المرور؟</a>
                    <a href="#">إنشاء حساب جديد</a>
                </div>
            </form>
        </div>
    </div>

    <?php
    // كود PHP للتحقق من صحة بيانات الدخول
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // اتصال بقاعدة البيانات
        $servername = "localhost";
        $username = "root"; // افتراضي في XAMPP
        $password = ""; // افتراضي في XAMPP
        $dbname = "university_belhadjebouchib_db"; // اسم قاعدة البيانات
        
        // إنشاء الاتصال
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // التحقق من الاتصال
        if ($conn->connect_error) {
            die("<script>document.getElementById('errorMessage').innerHTML = 'فشل الاتصال بقاعدة البيانات: " . $conn->connect_error . "'; document.getElementById('errorMessage').style.display = 'block';</script>");
        }
        
        // الحصول على البيانات من النموذج وتنظيفها
        $user = $conn->real_escape_string($_POST['username']);
        $pass = $_POST['password'];
        
        // استعلام للبحث عن المستخدم في جدول users
        $sql = "SELECT * FROM users WHERE username = '$user'";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // التحقق من كلمة المرور (إذا كانت مشفرة)
            if (password_verify($pass, $row['password'])) {
                // بدء الجلسة وتخزين بيانات المستخدم
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['role'] = $row['role'];
                
                echo "<script>document.getElementById('errorMessage').className = 'message success'; document.getElementById('errorMessage').innerHTML = 'تم تسجيل الدخول بنجاح! يتم التوجيه الآن...'; document.getElementById('errorMessage').style.display = 'block';";
                
                // توجيه المستخدم حسب دوره
                if ($row['role'] == 'admin') {
                    echo "setTimeout(function() { window.location.href = 'admin_dashboard.php'; }, 2000);</script>";
                } elseif ($row['role'] == 'teacher') {
                    echo "setTimeout(function() { window.location.href = 'teacher_dashboard.php'; }, 2000);</script>";
                } else {
                    echo "setTimeout(function() { window.location.href = 'student_dashboard.php'; }, 2000);</script>";
                }
                
            } else {
                echo "<script>document.getElementById('errorMessage').style.display = 'block';</script>";
            }
        } else {
            echo "<script>document.getElementById('errorMessage').style.display = 'block';</script>";
        }
        
        $conn->close();
    }
    ?>
</body>
</html>