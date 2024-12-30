<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// 确保用户是管理员
if (!isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// 处理入库/出库请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['book_id'];
    $quantity = (int)$_POST['quantity'];
    $type = $_POST['type']; // 'in' for 入库, 'out' for 出库
    $reference = $_POST['reference'];
    
    try {
        $pdo->beginTransaction();
        
        // 生成交易ID
        $transactionId = uniqid('TR');
        
        // 更新库存交易记录
        $stmt = $pdo->prepare("INSERT INTO inventory_transaction (TransactionID, BookID, Quantity, Type, Reference) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$transactionId, $bookId, $quantity, $type, $reference]);
        
        // 更新图书库存
        if ($type === 'in') {
            $stmt = $pdo->prepare("UPDATE book SET Stock = Stock + ? WHERE BookID = ?");
        } else {
            $stmt = $pdo->prepare("UPDATE book SET Stock = Stock - ? WHERE BookID = ?");
        }
        $stmt->execute([$quantity, $bookId]);
        
        $pdo->commit();
        $message = "库存更新成功！";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "操作失败：" . $e->getMessage();
    }
}

// 获取所有图书
$stmt = $pdo->query("SELECT BookID, BookName, Stock FROM book");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 获取最近的库存交易记录
$stmt = $pdo->query("SELECT t.*, b.BookName 
                     FROM inventory_transaction t 
                     JOIN book b ON t.BookID = b.BookID 
                     ORDER BY TransactionDate DESC 
                     LIMIT 10");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>库存管理</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>库存管理</h1>
        
        <?php if (isset($message)): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="inventory-form">
            <h2>库存操作</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="book_id">选择图书：</label>
                    <select name="book_id" required>
                        <?php foreach ($books as $book): ?>
                            <option value="<?php echo $book['BookID']; ?>">
                                <?php echo $book['BookName']; ?> (当前库存: <?php echo $book['Stock']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="type">操作类型：</label>
                    <select name="type" required>
                        <option value="in">入库</option>
                        <option value="out">出库</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="quantity">数量：</label>
                    <input type="number" name="quantity" min="1" required>
                </div>
                
                <div class="form-group">
                    <label for="reference">参考信息：</label>
                    <input type="text" name="reference" placeholder="例如：采购单号、销售单号">
                </div>
                
                <button type="submit" class="btn">提交</button>
            </form>
        </div>
        
        <div class="recent-transactions">
            <h2>最近交易记录</h2>
            <table>
                <thead>
                    <tr>
                        <th>交易ID</th>
                        <th>图书名称</th>
                        <th>数量</th>
                        <th>类型</th>
                        <th>参考信息</th>
                        <th>日期</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo $transaction['TransactionID']; ?></td>
                            <td><?php echo $transaction['BookName']; ?></td>
                            <td><?php echo $transaction['Quantity']; ?></td>
                            <td><?php echo $transaction['Type'] === 'in' ? '入库' : '出库'; ?></td>
                            <td><?php echo $transaction['Reference']; ?></td>
                            <td><?php echo $transaction['TransactionDate']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
