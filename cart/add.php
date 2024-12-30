<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';
require_once '../includes/auth.php';

// 检查用户是否登录
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $bookId = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
        
        // 检查图书是否存在且有库存
        $sql = "SELECT BookID, Stock FROM book WHERE BookID = ? AND Stock > 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        
        if (!$book) {
            throw new Exception('图书不存在或库存不足');
        }
        
        // 开始事务
        $conn->begin_transaction();
        
        // 检查购物车是否已有此商品
        $sql = "SELECT CartID, Quantity FROM cart WHERE UserID = ? AND BookID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $_SESSION['UserID'], $bookId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_item = $result->fetch_assoc();
        
        if ($cart_item) {
            // 更新数量
            $sql = "UPDATE cart SET Quantity = Quantity + 1 WHERE CartID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $cart_item['CartID']);
        } else {
            // 新增记录
            $sql = "INSERT INTO cart (UserID, BookID, Quantity) VALUES (?, ?, 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $_SESSION['UserID'], $bookId);
        }
        
        if (!$stmt->execute()) {
            throw new Exception('添加到购物车失败');
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
