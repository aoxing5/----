<?php
session_start();
require_once '../../config/db_connect.php';
require_once '../../includes/auth.php';

requireLogin();

// 获取用户统计数据
$user_id = $_SESSION['UserID'];
$stats = [
    'orders' => $conn->query("SELECT COUNT(*) FROM `order` WHERE UserID = $user_id")->fetch_row()[0],
    'spent' => $conn->query("SELECT COALESCE(SUM(TotalAmount), 0) FROM `order` WHERE UserID = $user_id")->fetch_row()[0],
    'cart_items' => $conn->query("SELECT COUNT(*) FROM cart WHERE UserID = $user_id")->fetch_row()[0]
];

// 获取最近订单
$recent_orders = $conn->query("
    SELECT o.*, COUNT(od.OrderDetailID) as ItemCount 
    FROM `order` o 
    LEFT JOIN order_detail od ON o.OrderID = od.OrderID 
    WHERE o.UserID = $user_id 
    GROUP BY o.OrderID 
    ORDER BY o.OrderTime DESC 
    LIMIT 5
");

// 获取用户信息
$user = $conn->query("SELECT * FROM user WHERE UserID = $user_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>个人中心 - 书店</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../../includes/navbar.php'; ?>
    <?php include '../../includes/customer_sidebar.php'; ?>

    <div class="main-content">
        <div class="container-fluid">
            <h1 class="page-title">个人中心</h1>
            
            <!-- 统计卡片 -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="title">总订单数</div>
                        <div class="value"><?php echo $stats['orders']; ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="title">消费总额</div>
                        <div class="value">
                            ￥<?php echo number_format($stats['spent'], 2); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="title">购物车</div>
                        <div class="value">
                            <?php echo $stats['cart_items']; ?>
                            <span class="unit">件商品</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- 最近订单 -->
                <div class="col-lg-8">
                    <div class="content-card">
                        <div class="card-header">
                            <h5 class="card-title">最近订单</h5>
                            <a href="../orders.php" class="btn btn-primary btn-sm">查看全部</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>订单号</th>
                                        <th>商品数量</th>
                                        <th>金额</th>
                                        <th>状态</th>
                                        <th>时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = $recent_orders->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $order['OrderID']; ?></td>
                                        <td><?php echo $order['ItemCount']; ?></td>
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

                <!-- 个人信息 -->
                <div class="col-lg-4">
                    <div class="content-card">
                        <div class="card-header">
                            <h5 class="card-title">个人信息</h5>
                            <a href="../profile.php" class="btn btn-primary btn-sm">编辑资料</a>
                        </div>
                        <div class="card-body">
                            <p><strong>用户名：</strong> <?php echo htmlspecialchars($user['Username']); ?></p>
                            <p><strong>注册时间：</strong> <?php echo date('Y-m-d', strtotime($user['RegisterTime'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script src="../../js/sidebar.js"></script>
</body>
</html> 