<?php
// 启动会话（如果尚未启动）
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 定义网站根目录URL
define('BASE_URL', '/bookstore/');

// 定义物理路径
define('ROOT_PATH', dirname(__DIR__) . '/');
define('ADMIN_PATH', ROOT_PATH . 'pages/admin/');

// 数据库配置
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '123123');
define('DB_NAME', 'book');
define('DB_CHARSET', 'utf8mb4');

// 用户角色
define('ROLE_ADMIN', 'admin');
define('ROLE_USER', 'user');

// 订单状态
define('ORDER_STATUS_PENDING', 'pending');
define('ORDER_STATUS_PAID', 'paid');
define('ORDER_STATUS_SHIPPED', 'shipped');
define('ORDER_STATUS_COMPLETED', 'completed');
define('ORDER_STATUS_CANCELLED', 'cancelled');

// 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 设置字符集
ini_set('default_charset', 'UTF-8'); 