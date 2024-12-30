<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';
require_once '../includes/auth.php';

$page_title = '浏览图书';

// 搜索和过滤
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 获取图书列表
$sql = "SELECT b.*, c.CategoryName 
        FROM book b 
        LEFT JOIN category c ON b.CategoryID = c.CategoryID";
if ($search) {
    $sql .= " WHERE b.BookName LIKE ? OR b.Author LIKE ?";
}
$sql .= " ORDER BY b.BookID DESC";

if ($search) {
    $stmt = $conn->prepare($sql);
    $search_param = "%$search%";
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $books = $stmt->get_result();
} else {
    $books = $conn->query($sql);
}
?>

<div class="main-content">
    <?php include '../includes/navbar.php'; ?>
    <?php include '../includes/customer_sidebar.php'; ?>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2>浏览图书</h2>
            </div>
        </div>

        <!-- 搜索框 -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form class="d-flex" method="GET">
                    <input type="text" class="form-control me-2" name="search" 
                           placeholder="搜索图书名称或作者..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">搜索</button>
                </form>
            </div>
        </div>

        <!-- 图书列表 -->
        <div class="row">
            <?php while ($book = $books->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <a href="book_detail.php?id=<?php echo $book['BookID']; ?>" class="text-decoration-none">
                            <?php if ($book['ImageURL']): ?>
                                <img src="<?php echo BASE_URL . $book['ImageURL']; ?>" 
                                     class="book-cover" 
                                     alt="<?php echo htmlspecialchars($book['BookName']); ?>">
                            <?php endif; ?>
                            <div class="book-info">
                                <h5 class="book-title"><?php echo htmlspecialchars($book['BookName']); ?></h5>
                                <p class="book-author"><?php echo htmlspecialchars($book['Author']); ?></p>
                                <p class="book-price">￥<?php echo number_format($book['Price'], 2); ?></p>
                            </div>
                        </a>
                        <button class="btn btn-primary w-100" onclick="addToCart(<?php echo $book['BookID']; ?>)">
                            加入购物车
                        </button>
                        
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
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