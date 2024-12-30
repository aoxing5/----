<?php
require_once '../../config/config.php';
require_once '../../config/db_connect.php';
require_once '../../includes/auth.php';

// 检查用户是否登录
requireLogin();

$page_title = '订单详情';
include '../../includes/navbar.php';

// 获取订单ID
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['UserID'];

// 获取订单信息
$sql = "SELECT o.* FROM `order` o WHERE o.OrderID = ? AND o.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("订单不存在或无权访问");
}

// 获取订单详情
$sql = "SELECT od.*, b.BookName, b.Author, b.ImageURL 
        FROM order_detail od 
        LEFT JOIN book b ON od.BookID = b.BookID 
        WHERE od.OrderID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$details = $stmt->get_result();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?php include '../../includes/customer_sidebar.php'; ?>
        </div>
        
        <div class="col-md-9">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>订单详情 #<?php echo $order_id; ?></h2>
                    <a href="orders.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> 返回订单列表
                    </a>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">订单信息</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>订单状态：</strong>
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
                                </p>
                                <p><strong>下单时间：</strong> <?php echo date('Y-m-d H:i', strtotime($order['OrderTime'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>支付状态：</strong> <?php echo $order['PaymentStatus'] ?? '未知'; ?></p>
                                <p><strong>总金额：</strong> ￥<?php echo number_format($order['TotalAmount'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">订单商品</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>商品</th>
                                        <th>作者</th>
                                        <th>单价</th>
                                        <th>数量</th>
                                        <th>小计</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($detail = $details->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($detail['ImageURL']): ?>
                                                        <img src="<?php echo BASE_URL . $detail['ImageURL']; ?>" 
                                                             alt="<?php echo htmlspecialchars($detail['BookName']); ?>"
                                                             style="width: 50px; margin-right: 10px;">
                                                    <?php endif; ?>
                                                    <?php echo htmlspecialchars($detail['BookName']); ?>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($detail['Author']); ?></td>
                                            <td>￥<?php echo number_format($detail['Price'], 2); ?></td>
                                            <td><?php echo $detail['Quantity']; ?></td>
                                            <td>￥<?php echo number_format($detail['Price'] * $detail['Quantity'], 2); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>总计：</strong></td>
                                        <td><strong>￥<?php echo number_format($order['TotalAmount'], 2); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>../js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>../js/sidebar.js"></script>
</body>
</html> 