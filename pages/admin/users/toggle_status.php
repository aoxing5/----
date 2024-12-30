<?php
require_once '../../../config/config.php';
require_once '../../../config/db_connect.php';
require_once '../../../includes/auth.php';

// 检查管理员权限
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 0;
    
    // 不允许修改管理员状态
    $sql = "UPDATE user SET Status = ? WHERE UserID = ? AND Role != 'admin'";
    $stmt = $conn->prepare($sql);
    $new_status = $status == 1 ? 0 : 1;
    $stmt->bind_param("ii", $new_status, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '更新失败']);
    }
} 