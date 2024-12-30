<?php
require_once '../../config/config.php';
require_once '../../config/db_connect.php';
require_once '../../includes/auth.php';

// 检查管理员权限
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $conn->begin_transaction();

        $bookId = (int)$_POST['book_id'];
        $quantity = (int)$_POST['quantity'];
        $type = $_POST['type'];
        $remark = $_POST['remark'];

        // 更新图书库存
        if ($type == 'in') {
            $sql = "UPDATE book SET Stock = Stock + ? WHERE BookID = ?";
        } else {
            $sql = "UPDATE book SET Stock = Stock - ? WHERE BookID = ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $bookId);
        $stmt->execute();

        // 记录库存变动
        $sql = "INSERT INTO stock_record (BookID, Quantity, Type, OperatorID, Remark, RecordTime) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisis", 
            $bookId, 
            $quantity, 
            $type,
            $_SESSION['UserID'],
            $remark
        );
        $stmt->execute();

        $conn->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
} 