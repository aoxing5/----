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
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        
        if ($quantity < 1) {
            throw new Exception('数量必须大于0');
        }
        
        // 开始事务
        $conn->begin_transaction();
        
        // 检查购物车项是否属于当前用户并获取图书信息
        $sql = "SELECT c.*, b.Stock 
                FROM cart c 
                JOIN book b ON c.BookID = b.BookID 
                WHERE c.CartID = ? AND c.UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cartId, $_SESSION['UserID']);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        
        if (!$item) {
            throw new Exception('购物车项不存在');
        }
        
        // 检查库存
        if ($quantity > $item['Stock']) {
            throw new Exception('库存不足');
        }
        
        // 更新数量
        $sql = "UPDATE cart SET Quantity = ? WHERE CartID = ? AND UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $cartId, $_SESSION['UserID']);
        
        if (!$stmt->execute()) {
            throw new Exception('更新失败');
        }
        
        $conn->commit();
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        if (isset($conn)) {
            $conn->rollback();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => '无效的请求']); 