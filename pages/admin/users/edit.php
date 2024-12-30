<?php
require_once '../../../config/config.php';
require_once '../../../config/db_connect.php';
require_once '../../../includes/auth.php';

// 检查管理员权限
requireAdmin();

$page_title = '编辑用户';
include_once '../../../includes/navbar.php';

// 获取用户ID
$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 获取用户信息
$sql = "SELECT * FROM user WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header('Location: ' . BASE_URL . 'pages/admin/users/list.php');
    exit();
}

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        
        // 更新数据库
        $sql = "UPDATE user SET Username=?, Email=?, Phone=? WHERE UserID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $phone, $userId);
        
        if ($stmt->execute()) {
            header('Location: ' . BASE_URL . 'pages/admin/users/list.php');
            exit();
        } else {
            throw new Exception("更新用户失败");
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
                <h2 class="card-title">编辑用户</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">用户名</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?php echo htmlspecialchars($user['Username']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">邮箱</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($user['Email'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">电话</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?php echo htmlspecialchars($user['Phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">保存修改</button>
                        <a href="../users/list.php" class="btn btn-secondary">返回列表</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html> 