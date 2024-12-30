<?php
require_once '../../config/config.php';
require_once '../../config/db_connect.php';
require_once '../../includes/auth.php';

// 检查管理员权限
requireAdmin();

$page_title = '销售统计';
include_once '../../includes/navbar.php';

// 获取总销售额
$sql = "SELECT SUM(TotalAmount) as total FROM `order` WHERE Status != 'cancelled'";
$result = $conn->query($sql);
$totalSales = $result->fetch_assoc()['total'] ?? 0;

// 获取本月销售额
$sql = "SELECT SUM(TotalAmount) as monthly 
        FROM `order` 
        WHERE Status != 'cancelled' 
        AND MONTH(OrderTime) = MONTH(CURRENT_DATE())
        AND YEAR(OrderTime) = YEAR(CURRENT_DATE())";
$result = $conn->query($sql);
$monthlySales = $result->fetch_assoc()['monthly'] ?? 0;

// 获取销量前10的图书
$sql = "SELECT b.BookID, b.BookName, b.ImageURL, b.Price,
               SUM(od.Quantity) as total_sold,
               SUM(od.Quantity * od.Price) as total_revenue
        FROM book b
        JOIN order_detail od ON b.BookID = od.BookID
        JOIN `order` o ON od.OrderID = o.OrderID
        WHERE o.Status != 'cancelled'
        GROUP BY b.BookID
        ORDER BY total_sold DESC
        LIMIT 10";
$topBooks = $conn->query($sql);
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- 销售统计卡片 -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="stats-card">
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stats-info">
                        <h3>￥<?php echo number_format($totalSales, 2); ?></h3>
                        <p>总销售额</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="stats-info">
                        <h3>￥<?php echo number_format($monthlySales, 2); ?></h3>
                        <p>本月销售额</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 销量排行榜 -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">销量排行榜 TOP 10</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>排名</th>
                                <th>图书信息</th>
                                <th>单价</th>
                                <th>销量</th>
                                <th>销售额</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rank = 1;
                            while ($book = $topBooks->fetch_assoc()): 
                            ?>
                                <tr>
                                    <td><?php echo $rank++; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($book['ImageURL']): ?>
                                                <img src="<?php echo BASE_URL . $book['ImageURL']; ?>" 
                                                     alt="<?php echo htmlspecialchars($book['BookName']); ?>"
                                                     style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($book['BookName']); ?>
                                        </div>
                                    </td>
                                    <td>￥<?php echo number_format($book['Price'], 2); ?></td>
                                    <td><?php echo $book['total_sold']; ?></td>
                                    <td>￥<?php echo number_format($book['total_revenue'], 2); ?></td>
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