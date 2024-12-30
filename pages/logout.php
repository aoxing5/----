<?php
require_once '../config/config.php';

// 清除所有会话变量
$_SESSION = array();

// 销毁会话 cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// 销毁会话
session_destroy();

// 重定向到登录页面
header('Location: ' . BASE_URL . 'pages/login.php');
exit();
