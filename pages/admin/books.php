<?php
require_once '../../config/config.php';
require_once '../../config/db_connect.php';
require_once '../../includes/auth.php';

// 检查管理员权限
requireAdmin();

$page_title = '图书管理';
include_once '../../includes/navbar.php';

// 获取图书列表
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '';
$sql = "SELECT b.*, c.CategoryName, p.PublisherName 
        FROM book b 
        LEFT JOIN category c ON b.CategoryID = c.CategoryID 
        LEFT JOIN publisher p ON b.PublisherID = p.PublisherID";
if ($search) {
    $sql .= " WHERE b.BookName LIKE ? OR b.Author LIKE ?";
}
$sql .= " ORDER BY b.BookID DESC";

if ($search) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $books = $stmt->get_result();
} else {
    $books = $conn->query($sql);
}
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>图书管理</h2>
                    <a href="<?php echo BASE_URL; ?>pages/admin/books/add.php" class="btn btn-primary">
                        <i class="bi bi-plus"></i> 添加图书
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <form class="d-flex" method="GET">
                    <input type="text" class="form-control me-2" name="search" 
                           placeholder="搜索图书名称、作者或ISBN..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">搜索</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>封面</th>
                                <th>书名</th>
                                <th>作者</th>
                                <th>分类</th>
                                <th>价格</th>
                                <th>库存</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $row_number = 1; ?>
                            <?php while ($book = $books->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row_number++; ?></td>
                                    <td>
                                        <?php if ($book['ImageURL']): ?>
                                            <img src="<?php echo BASE_URL . $book['ImageURL']; ?>" 
                                                 alt="<?php echo htmlspecialchars($book['BookName']); ?>" 
                                                 style="height: 50px;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($book['BookName']); ?></td>
                                    <td><?php echo htmlspecialchars($book['Author']); ?></td>
                                    <td><?php echo htmlspecialchars($book['CategoryName']); ?></td>
                                    <td>￥<?php echo number_format($book['Price'], 2); ?></td>
                                    <td><?php echo $book['Stock']; ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>pages/admin/books/edit.php?id=<?php echo $book['BookID']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" 
                                                onclick="deleteBook(<?php echo $book['BookID']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
function deleteBook(bookId) {
    if (!confirm('确定要删除这本图书吗？此操作不可恢复。')) {
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>pages/admin/books/delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'book_id=' + bookId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('删除成功');
            location.reload();
        } else {
            alert(data.message || '删除失败');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('删除失败');
    });
}
</script>

</body>
</html>
