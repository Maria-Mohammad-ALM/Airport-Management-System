<?php
// إعدادات الاتصال بقاعدة البيانات
$db_user = 'airport'; // اسم المستخدم لقاعدة البيانات
$db_password = '123'; // كلمة المرور لقاعدة البيانات
$db_host = 'localhost/XE'; // اسم الخادم والـ SID الخاص بقاعدة البيانات

// محاولة الاتصال بقاعدة البيانات
try {
    $conn = oci_connect($db_user, $db_password, $db_host);
    if (!$conn) {
        $e = oci_error();
        throw new Exception($e['message']);
    }
} catch (Exception $e) {
    echo "Error connecting to the database: " . $e->getMessage();
    exit;
}

// إغلاق الاتصال عند الانتهاء (يمكنك استخدام هذه الوظيفة يدويًا)
function closeConnection($conn) {
    oci_close($conn);
}
?>