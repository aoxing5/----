<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';
require_once '../includes/auth.php';

// 检查用户是否登录
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $cartId = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
        
        // 验证购物车项是否属于当前用户
        $sql = "DELETE FROM cart WHERE CartID = ? AND UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cartId, $_SESSION['UserID']);
        
        if (!$stmt->execute()) {
            throw new Exception('删除失败');
        }
        
        if ($stmt->affected_rows === 0) {
            throw new Exception('购物车项不存在');
        }
        
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => '无效的请求']);
