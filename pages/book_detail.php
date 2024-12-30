<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';
require_once '../includes/auth.php';

$page_title = '图书详情';

// 获取图书ID
$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 获取图书详情
$sql = "SELECT b.*, c.CategoryName, p.PublisherName 
        FROM book b 
        LEFT JOIN category c ON b.CategoryID = c.CategoryID 
        LEFT JOIN publisher p ON b.PublisherID = p.PublisherID 
        WHERE b.BookID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

// 获取同类推荐图书
$sql = "SELECT * FROM book 
        WHERE CategoryID = ? AND BookID != ? 
        ORDER BY RAND() LIMIT 4";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $book['CategoryID'], $book_id);
$stmt->execute();
$recommended_books = $stmt->get_result();
?>

<div class="main-content">
    <?php include '../includes/navbar.php'; ?>
    <?php include '../includes/customer_sidebar.php'; ?>
    <div class="container">
        <?php if ($book): ?>
            <div class="row">
                <!-- 图书详情 -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php if ($book['ImageURL']): ?>
                                        <img src="<?php echo BASE_URL . $book['ImageURL']; ?>" 
                                             class="img-fluid rounded" 
                                             alt="<?php echo htmlspecialchars($book['BookName']); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-8">
                                    <h2><?php echo htmlspecialchars($book['BookName']); ?></h2>
                                    <p class="text-muted">作者：<?php echo htmlspecialchars($book['Author']); ?></p>
                                    <p class="text-danger h4">￥<?php echo number_format($book['Price'], 2); ?></p>
                                    <div class="mb-3">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($book['CategoryName']); ?></span>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($book['PublisherName']); ?></span>
                                    </div>
                                    <p>库存：<?php echo $book['Stock']; ?></p>
                                    <?php if ($book['Stock'] > 0): ?>
                                        <button class="btn btn-primary" onclick="addToCart(<?php echo $book['BookID']; ?>)">
                                            <i class="bi bi-cart-plus"></i> 加入购物车
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>
                                            <i class="bi bi-x-circle"></i> 暂时缺货
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- 图书描述 -->
                            <div class="mt-4">
                                <h4>图书简介</h4>
                                <p><?php echo nl2br(htmlspecialchars($book['Description'] ?? '暂无简介')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 推荐图书 -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">同类推荐</h5>
                        </div>
                        <div class="card-body">
                            <?php while ($recommended = $recommended_books->fetch_assoc()): ?>
                                <div class="d-flex mb-3">
                                    <?php if ($recommended['ImageURL']): ?>
                                        <img src="<?php echo BASE_URL . $recommended['ImageURL']; ?>" 
                                             style="width: 60px; height: 80px; object-fit: cover;"
                                             class="me-3" 
                                             alt="<?php echo htmlspecialchars($recommended['BookName']); ?>">
                                    <?php endif; ?>
                                    <div>
                                        <a href="book_detail.php?id=<?php echo $recommended['BookID']; ?>" 
                                           class="text-decoration-none">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($recommended['BookName']); ?></h6>
                                        </a>
                                        <p class="text-danger mb-0">￥<?php echo number_format($recommended['Price'], 2); ?></p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                图书不存在或已下架
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function addToCart(bookId) {
    fetch('<?php echo BASE_URL; ?>cart/add.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'book_id=' + bookId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('已添加到购物车');
        } else {
            alert(data.message || '添加失败');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('添加失败');
    });
}
</script>

</body>
</html> 