<?php
// 检查用户是否登录
function requireLogin() {
    if (!isset($_SESSION['UserID'])) {
        header('Location: ' . BASE_URL . 'pages/login.php');
        exit();
    }
}

// 检查用户是否是管理员
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . 'pages/403.php');
        exit();
    }
}

// 检查当前用户是否是管理员
function isAdmin() {
    return isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin';
}

// 获取当前用户ID
function getCurrentUserId() {
    return $_SESSION['UserID'] ?? null;
}

// 获取当前用户名
function getCurrentUsername() {
    return $_SESSION['Username'] ?? null;
} 