<?php
session_start();
require_once 'config/db_connect.php';

// 如果未登录，重定向到登录页面
if (!isset($_SESSION['username'])) {
    header("Location: pages/login.php");
    exit();
}

// 根据角色重定向到相应页面
if ($_SESSION['role'] == ROLE_ADMIN) {
    header("Location: pages/admin/index.php");
} else {
    header("Location: pages/user/index.php");
}
exit();