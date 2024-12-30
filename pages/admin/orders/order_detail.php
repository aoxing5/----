<?php
require_once '../../../config/config.php';
require_once '../../../config/db_connect.php';
require_once '../../../includes/auth.php';

// 检查管理员权限
requireAdmin();

$page_title = '订单详情';
include_once '../../../includes/navbar.php';

// 订单状态转换函数
function getOrderStatusText($status) {
    switch ($status) {
        case 'pending': return '待付款';
        case 'paid': return '已支付';
        case 'shipped': return '已发货';
        case 'completed': return '已完成';
        case 'cancelled': return '已取消';
        default: return $status;
    }
}

// 获取状态对应的样式类
function getStatusClass($status) {
    switch ($status) {
        case 'pending': return 'warning';
        case 'paid': return 'primary';
        case 'shipped': return 'info';
        case 'completed': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}

// 获取订单ID
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 获取订单信息
$sql = "SELECT o.*, u.Username 
        FROM `order` o 
        LEFT JOIN user u ON o.UserID = u.UserID 
        WHERE o.OrderID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header('Location: ' . BASE_URL . 'pages/admin/orders/orders.php');
    exit();
}

// 获取订单项目
$sql = "SELECT oi.*, b.BookName, b.ImageURL 
        FROM order_detail oi 
        JOIN book b ON oi.BookID = b.BookID 
        WHERE oi.OrderID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$items = $stmt->get_result();
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>订单详情 #<?php echo $orderId; ?></h2>
                    <a href="orders.php" class="btn btn-secondary">返回列表</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">订单信息</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 150px;">订单号：</th>
                                <td><?php echo $order['OrderID']; ?></td>
                            </tr>
                            <tr>
                                <th>下单用户：</th>
                                <td><?php echo htmlspecialchars($order['Username']); ?></td>
                            </tr>
                            <tr>
                                <th>收货地址：</th>
                                <td><?php echo htmlspecialchars($order['ShippingAddress']); ?></td>
                            </tr>
                            <tr>
                                <th>联系电话：</th>
                                <td><?php echo htmlspecialchars($order['Phone']); ?></td>
                            </tr>
                            <tr>
                                <th>下单时间：</th>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($order['OrderTime'])); ?></td>
                            </tr>
                            <tr>
                                <th>订单状态：</th>
                                <td>
                                    <span class="badge bg-<?php echo getStatusClass($order['Status']); ?>">
                                        <?php echo getOrderStatusText($order['Status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>订单总额：</th>
                                <td>￥<?php echo number_format($order['TotalAmount'], 2); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">订单商品</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>商品</th>
                                <th>单价</th>
                                <th>数量</th>
                                <th>小计</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = $items->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item['ImageURL']): ?>
                                                <img src="<?php echo BASE_URL . $item['ImageURL']; ?>" 
                                                     alt="<?php echo htmlspecialchars($item['BookName']); ?>"
                                                     style="width: 50px; margin-right: 10px;">
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($item['BookName']); ?>
                                        </div>
                                    </td>
                                    <td>￥<?php echo number_format($item['Price'], 2); ?></td>
                                    <td><?php echo $item['Quantity']; ?></td>
                                    <td>￥<?php echo number_format($item['Price'] * $item['Quantity'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
