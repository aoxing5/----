<?php
require_once __DIR__ . '/config.php';

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception("连接失败: " . $conn->connect_error);
    }
    
    // 设置字符集
    if (!$conn->set_charset(DB_CHARSET)) {
        throw new Exception("设置字符集失败: " . $conn->error);
    }
    
} catch (Exception $e) {
    error_log("数据库连接错误: " . $e->getMessage());
    die("数据库连接错误，请稍后重试");
}

// 注册关闭连接的函数
register_shutdown_function(function() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
});