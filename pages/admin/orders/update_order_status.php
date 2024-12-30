<?php
require_once '../../../config/config.php';
require_once '../../../config/db_connect.php';
require_once '../../../includes/auth.php';

// 检查管理员权限
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    
    // 验证状态
    $validStatuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
    if (!in_array($status, $validStatuses)) {
        echo json_encode(['success' => false, 'message' => '无效的状态']);
        exit;
    }
    
    // 更新订单状态
    $sql = "UPDATE `order` SET Status = ? WHERE OrderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $orderId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '更新失败']);
    }
} 