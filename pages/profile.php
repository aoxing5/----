<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';
require_once '../includes/auth.php';

// 检查用户是否登录
requireLogin();

$page_title = '个人信息';
include '../includes/navbar.php';

// 获取用户信息
$user_id = $_SESSION['UserID'];
$sql = "SELECT * FROM user WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    $sql = "UPDATE user SET Email = ?, Phone = ?, Address = ? WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $email, $phone, $address, $user_id);
    
    if ($stmt->execute()) {
        $success = "个人信息更新成功";
        // 更新显示的用户信息
        $user['Email'] = $email;
        $user['Phone'] = $phone;
        $user['Address'] = $address;
    } else {
        $error = "更新失败，请稍后重试";
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?php include '../includes/customer_sidebar.php'; ?>
        </div>
        
        <div class="col-md-9">
            <div class="p-4">
                <h2>个人信息</h2>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="username" class="form-label">用户名</label>
                        <input type="text" class="form-control" id="username" 
                               value="<?php echo htmlspecialchars($user['Username']); ?>" readonly>
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
                        <label for="address" class="form-label">地址</label>
                        <textarea class="form-control" id="address" name="address" 
                                  rows="3"><?php echo htmlspecialchars($user['Address'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">保存修改</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/sidebar.js"></script>
</body>
</html> 