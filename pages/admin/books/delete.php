<?php
require_once '../../../config/config.php';
require_once '../../../config/db_connect.php';
require_once '../../../includes/auth.php';

// 检查管理员权限
requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
        
        // 开始事务
        $conn->begin_transaction();
        
        // 检查是否有相关订单
        $check_sql = "SELECT COUNT(*) as count FROM order_detail WHERE BookID = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $has_orders = $result->fetch_assoc()['count'] > 0;
        
        if ($has_orders) {
            throw new Exception('该图书已有订单记录，无法删除');
        }
        
        // 删除库存记录
        $sql = "DELETE FROM stock_record WHERE BookID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        
        // 删除购物车记���
        $sql = "DELETE FROM cart WHERE BookID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        
        // 删除图书
        $sql = "DELETE FROM book WHERE BookID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        
        if ($stmt->affected_rows === 0) {
            throw new Exception('图书不存在或删除失败');
        }
        
        $conn->commit();
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => '无效的请求']); 