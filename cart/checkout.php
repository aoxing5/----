<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';
require_once '../includes/auth.php';

// 检查用户是否登录
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // 开始事务
        $conn->begin_transaction();

        // 获取购物车商品
        $sql = "SELECT c.*, b.Price, b.Stock 
                FROM cart c 
                JOIN book b ON c.BookID = b.BookID 
                WHERE c.UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['UserID']);
        $stmt->execute();
        $cart_items = $stmt->get_result();

        if ($cart_items->num_rows == 0) {
            throw new Exception("购物车是空的");
        }

        // 计算总金额并检查库存
        $total_amount = 0;
        $items = [];
        while ($item = $cart_items->fetch_assoc()) {
            if ($item['Stock'] < $item['Quantity']) {
                throw new Exception("商品 {$item['BookID']} 库存不足");
            }
            $total_amount += $item['Price'] * $item['Quantity'];
            $items[] = $item;
        }

        // 创建订单
        $sql = "INSERT INTO `order` (UserID, TotalAmount, ShippingAddress, Phone, OrderTime, Status) 
                VALUES (?, ?, ?, ?, NOW(), 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idss", 
            $_SESSION['UserID'],
            $total_amount,
            $_POST['address'],
            $_POST['phone']
        );
        $stmt->execute();
        $order_id = $conn->insert_id;

        // 添加订单详情
        $sql = "INSERT INTO order_detail (OrderID, BookID, Quantity, Price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($items as $item) {
            $stmt->bind_param("iiid", 
                $order_id,
                $item['BookID'],
                $item['Quantity'],
                $item['Price']
            );
            $stmt->execute();

            // 更新库存
            $sql_update = "UPDATE book SET Stock = Stock - ? WHERE BookID = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ii", $item['Quantity'], $item['BookID']);
            $stmt_update->execute();
        }

        // 清空购物车
        $sql = "DELETE FROM cart WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['UserID']);
        $stmt->execute();

        // 提交事务
        $conn->commit();

        // 返回成功信息
        echo json_encode([
            'success' => true,
            'order_id' => $order_id
        ]);
        exit;

    } catch (Exception $e) {
        // 回滚事务
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// 如果不是POST请求，重定向到购物车页面
header('Location: ' . BASE_URL . 'cart/view.php');
exit; 