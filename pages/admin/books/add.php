<?php
require_once '../../../config/config.php';
require_once '../../../config/db_connect.php';
require_once '../../../includes/auth.php';

// 检查管理员权限
requireAdmin();

$page_title = '添加图书';
include_once '../../../includes/navbar.php';

// 获取分类列表
$sql = "SELECT * FROM category ORDER BY CategoryName";
$categories = $conn->query($sql);

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $bookName = trim($_POST['bookName']);
        $author = trim($_POST['author']);
        $categoryId = (int)$_POST['categoryId'];
        $price = (float)$_POST['price'];
        $stock = (int)$_POST['stock'];
        $description = trim($_POST['description']);
        
        // 处理图片上传
        $imageUrl = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = __DIR__ . '/../../../uploads/books/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $fileName = uniqid() . '.' . $fileExt;
            $uploadFile = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $imageUrl = 'uploads/books/' . $fileName;
            }
        }
        
        // 插入数据库
        $sql = "INSERT INTO book (BookName, Author, CategoryID, Price, Stock, Description, ImageURL) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiidss", $bookName, $author, $categoryId, $price, $stock, $description, $imageUrl);
        
        if ($stmt->execute()) {
            header('Location: ' . BASE_URL . 'pages/admin/books.php');
            exit();
        } else {
            throw new Exception("添加图书失败");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">添加图书</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="bookName" class="form-label">书名</label>
                        <input type="text" class="form-control" id="bookName" name="bookName" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="author" class="form-label">作者</label>
                        <input type="text" class="form-control" id="author" name="author" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="categoryId" class="form-label">分类</label>
                        <select class="form-select" id="categoryId" name="categoryId" required>
                            <option value="">请选择分类</option>
                            <?php while ($category = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $category['CategoryID']; ?>">
                                    <?php echo htmlspecialchars($category['CategoryName']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="price" class="form-label">价格</label>
                        <input type="number" class="form-control" id="price" name="price" 
                               step="0.01" min="0" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="stock" class="form-label">库存</label>
                        <input type="number" class="form-control" id="stock" name="stock" 
                               min="0" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">描述</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">封面图片</label>
                        <input type="file" class="form-control" id="image" name="image" 
                               accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">添加图书</button>
                        <a href="../books.php" class="btn btn-secondary">返回列表</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html> 