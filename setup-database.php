<?php
/**
 * Database Setup Script
 * استخدم هذا الملف لإنشاء قاعدة البيانات تلقائياً
 * 
 * ⚠️ مهم: احذف هذا الملف بعد الانتهاء من الإعداد!
 */

// إعدادات قاعدة البيانات - عدّل هذه القيم
$db_host = 'localhost';
$db_name = 'ramadan_imsakiya';  // اسم قاعدة البيانات
$db_user = 'your_database_user'; // اسم المستخدم
$db_pass = 'your_database_password'; // كلمة المرور

// إنشاء اتصال بدون تحديد قاعدة البيانات
try {
    $pdo = new PDO("mysql:host=$db_host;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>✅ الاتصال بنجاح!</h2>";
    echo "<p>جارٍ إنشاء قاعدة البيانات...</p>";
    
    // إنشاء قاعدة البيانات
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p>✅ قاعدة البيانات '$db_name' تم إنشاؤها بنجاح</p>";
    
    // استخدام قاعدة البيانات
    $pdo->exec("USE `$db_name`");
    
    // قراءة ملف SQL
    $sqlFile = __DIR__ . '/database.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("ملف database.sql غير موجود!");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // تقسيم SQL إلى statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^--/', $stmt) && !preg_match('/^CREATE DATABASE/i', $stmt) && !preg_match('/^USE/i', $stmt);
        }
    );
    
    // تنفيذ كل statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // تجاهل الأخطاء إذا كانت الجداول موجودة
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "<p style='color: orange;'>⚠️ تحذير: " . $e->getMessage() . "</p>";
                }
            }
        }
    }
    
    echo "<h2>✅ تم إنشاء قاعدة البيانات والجداول بنجاح!</h2>";
    echo "<h3>الخطوات التالية:</h3>";
    echo "<ol>";
    echo "<li>عدّل ملف <code>config/database.php</code> بإعدادات قاعدة البيانات</li>";
    echo "<li>اختبر الاتصال: <a href='api/test-connection.php'>api/test-connection.php</a></li>";
    echo "<li><strong style='color: red;'>احذف هذا الملف (setup-database.php) بعد الانتهاء!</strong></li>";
    echo "</ol>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ خطأ في الاتصال:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h3>تحقق من:</h3>";
    echo "<ul>";
    echo "<li>اسم المستخدم وكلمة المرور صحيحة</li>";
    echo "<li>اسم قاعدة البيانات متاح</li>";
    echo "<li>صلاحيات المستخدم تسمح بإنشاء قواعد البيانات</li>";
    echo "</ul>";
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ خطأ:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

?>

<style>
body {
    font-family: 'Cairo', Arial, sans-serif;
    direction: rtl;
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #f5f5f5;
}
h2 {
    color: #2c5530;
}
code {
    background: #e0e0e0;
    padding: 2px 6px;
    border-radius: 3px;
}
</style>
