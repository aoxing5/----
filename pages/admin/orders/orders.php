<?php
require_once '../../../config/config.php';
require_once '../../../config/db_connect.php';
require_once '../../../includes/auth.php';

// 检查管理员权限
requireAdmin();

$page_title = '订单管理';
include_once '../../../includes/navbar.php';

// 获取订单列表
$sql = "SELECT o.*, u.Username 
        FROM `order` o 
        LEFT JOIN user u ON o.UserID = u.UserID 
        ORDER BY o.OrderTime DESC";
$orders = $conn->query($sql);

if (!$orders) {
    die('查询失败: ' . $conn->error);
}

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
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>订单管理</h2>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>订单号</th>
                                <th>用户</th>
                                <th>收货地址</th>
                                <th>联系电话</th>
                                <th>总金额</th>
                                <th>订单状态</th>
                                <th>下单时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $order['OrderID']; ?></td>
                                    <td><?php echo htmlspecialchars($order['Username']); ?></td>
                                    <td><?php echo htmlspecialchars($order['ShippingAddress']); ?></td>
                                    <td><?php echo htmlspecialchars($order['Phone']); ?></td>
                                    <td>￥<?php echo number_format($order['TotalAmount'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo getStatusClass($order['Status']); ?>">
                                            <?php echo getOrderStatusText($order['Status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($order['OrderTime'])); ?></td>
                                    <td>
                                        <a href="order_detail.php?id=<?php echo $order['OrderID']; ?>" 
                                           class="btn btn-sm btn-primary" title="查看详情">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if ($order['Status'] == 'pending'): ?>
                                            <button class="btn btn-sm btn-success" 
                                                    onclick="updateOrderStatus(<?php echo $order['OrderID']; ?>, 'paid')"
                                                    title="标记为已支付">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        <?php elseif ($order['Status'] == 'paid'): ?>
                                            <button class="btn btn-sm btn-info" 
                                                    onclick="updateOrderStatus(<?php echo $order['OrderID']; ?>, 'shipped')"
                                                    title="标记为已发货">
                                                <i class="bi bi-truck"></i>
                                            </button>
                                        <?php elseif ($order['Status'] == 'shipped'): ?>
                                            <button class="btn btn-sm btn-success" 
                                                    onclick="updateOrderStatus(<?php echo $order['OrderID']; ?>, 'completed')"
                                                    title="标记为已完成">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($order['Status'] != 'completed' && $order['Status'] != 'cancelled'): ?>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="updateOrderStatus(<?php echo $order['OrderID']; ?>, 'cancelled')"
                                                    title="取消订单">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        <?php endif; ?>
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

<script>
function updateOrderStatus(orderId, status) {
    const statusText = {
        'paid': '已支付',
        'shipped': '已发货',
        'completed': '已完成',
        'cancelled': '已取消'
    };
    
    if (confirm('确定要将订单状态更改为' + statusText[status] + '吗？')) {
        fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'order_id=' + orderId + '&status=' + status
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '更新失败');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('更新失败');
        });
    }
}
</script>

</body>
</html>
