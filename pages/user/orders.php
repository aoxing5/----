<?php
require_once '../../config/config.php';
require_once '../../config/db_connect.php';
require_once '../../includes/auth.php';

// 检查用户是否登录
requireLogin();

$page_title = '我的订单';
include '../../includes/navbar.php';

// 获取当前用户的订单
$user_id = $_SESSION['UserID'];
$sql = "SELECT o.*, 
        COUNT(od.OrderDetailID) as ItemCount,
        GROUP_CONCAT(b.BookName SEPARATOR ', ') as BookNames
        FROM `order` o
        LEFT JOIN order_detail od ON o.OrderID = od.OrderID
        LEFT JOIN book b ON od.BookID = b.BookID
        WHERE o.UserID = ?
        GROUP BY o.OrderID
        ORDER BY o.OrderTime DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?php include '../../includes/customer_sidebar.php'; ?>
        </div>
        
        <div class="col-md-9">
            <div class="p-4">
                <h2>我的订单</h2>
                
                <div class="table-responsive mt-4">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>订单号</th>
                                <th>商品</th>
                                <th>总金额</th>
                                <th>订单状态</th>
                                <th>下单时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($order = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $order['OrderID']; ?></td>
                                        <td>
                                            <?php 
                                            $books = explode(', ', $order['BookNames']);
                                            echo count($books) > 2 
                                                ? htmlspecialchars(implode(', ', array_slice($books, 0, 2)) . '...')
                                                : htmlspecialchars($order['BookNames']);
                                            ?>
                                            <small class="text-muted">(<?php echo $order['ItemCount']; ?>件)</small>
                                        </td>
                                        <td>￥<?php echo number_format($order['TotalAmount'], 2); ?></td>
                                        <td>
                                            <?php
                                            $status_class = [
                                                'pending' => 'warning',
                                                'paid' => 'info',
                                                'shipped' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ][$order['Status']] ?? 'secondary';
                                            
                                            $status_text = [
                                                'pending' => '待付款',
                                                'paid' => '已付款',
                                                'shipped' => '已发货',
                                                'completed' => '已完成',
                                                'cancelled' => '已取消'
                                            ][$order['Status']] ?? $order['Status'];
                                            ?>
                                            <span class="badge bg-<?php echo $status_class; ?>">
                                                <?php echo $status_text; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($order['OrderTime'])); ?></td>
                                        <td>
                                            <a href="order_detail.php?id=<?php echo $order['OrderID']; ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> 查看详情
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <p class="my-5">暂无订单数据</p>
                                        <a href="<?php echo BASE_URL; ?>pages/books.php" class="btn btn-primary">
                                            去购物
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>../js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>../js/sidebar.js"></script>
</body>
</html> 