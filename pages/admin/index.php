<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/db_connect.php';
require_once '../../includes/auth.php';

// 验证管理员权限
requireAdmin();

$page_title = '控制台';
$extra_css = ['css/admin.css'];

// 获取统计数据
$stats = [
    'orders' => 0,
    'revenue' => 0,
    'users' => 0,
    'books' => 0
];

// 获取订单数量和总收入
$sql = "SELECT COUNT(*) as order_count, SUM(TotalAmount) as total_revenue FROM `order`";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $stats['orders'] = $row['order_count'];
    $stats['revenue'] = $row['total_revenue'] ?? 0;
}

// 获取用户数量
$sql = "SELECT COUNT(*) as user_count FROM user WHERE Role = 'customer'";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $stats['users'] = $row['user_count'];
}

// 获取图书数量
$sql = "SELECT COUNT(*) as book_count FROM book";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $stats['books'] = $row['book_count'];
}

// 获取最近订单
$recent_orders = $conn->query("
    SELECT o.*, u.Username 
    FROM `order` o 
    JOIN user u ON o.UserID = u.UserID 
    ORDER BY o.OrderTime DESC 
    LIMIT 5
");

// 获取库存预警
$low_stock = $conn->query("
    SELECT * FROM book 
    WHERE Stock < 10 
    ORDER BY Stock ASC 
    LIMIT 5
");
?>

<?php include '../../includes/navbar.php'; ?>

<div class="main-content">
    <div class="admin-container">
        <div class="content-header">
            <h2 class="content-title">控制台</h2>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-cart"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $stats['orders']; ?></h3>
                        <p>总订单数</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-currency-yen"></i>
                    </div>
                    <div class="stats-info">
                        <h3>￥<?php echo number_format($stats['revenue'], 2); ?></h3>
                        <p>总收入</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $stats['users']; ?></h3>
                        <p>用户数量</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $stats['books']; ?></h3>
                        <p>图书数量</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 销售统计 -->
        <div class="row">
            <div class="col-md-8">
                <div class="content-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">最近订单</h5>
                        <a href="/bookstore/pages/admin/orders.php" class="btn btn-sm btn-primary">
                            查看全部
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>订单号</th>
                                    <th>客户</th>
                                    <th>金额</th>
                                    <th>状态</th>
                                    <th>时间</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $recent_orders->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $order['OrderID']; ?></td>
                                    <td><?php echo htmlspecialchars($order['Username']); ?></td>
                                    <td>￥<?php echo number_format($order['TotalAmount'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $order['Status'] == 'completed' ? 'success' : 'warning'; ?>">
                                            <?php echo $order['Status'] == 'completed' ? '已完成' : '待处理'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($order['OrderTime'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-card">
                    <div class="card-header">
                        <h5 class="mb-0">库存预警</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>图书</th>
                                    <th>库存</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($book = $low_stock->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($book['BookName']); ?></td>
                                    <td>
                                        <span class="badge bg-danger">
                                            <?php echo $book['Stock']; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/bookstore/js/bootstrap.bundle.min.js"></script>
<script src="/bookstore/js/sidebar.js"></script>
</body>
</html> 