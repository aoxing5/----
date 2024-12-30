<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $error = '请输入用户名和密码';
    } else {
        try {
            // 查询用户
            $sql = "SELECT UserID, Username, Password, Role FROM user WHERE Username = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    
                    // 使用 password_verify 验证密码
                    if (password_verify($password, $user['Password'])) {
                        // 登录成功，设置session
                        $_SESSION['UserID'] = $user['UserID'];
                        $_SESSION['Username'] = $user['Username'];
                        $_SESSION['Role'] = $user['Role'];
                        
                        // 检查是否有重定向URL
                        if (isset($_SESSION['redirect_url'])) {
                            $redirect = $_SESSION['redirect_url'];
                            unset($_SESSION['redirect_url']);
                            header("Location: " . $redirect);
                        } else {
                            // 根据角色重定向
                            if ($user['Role'] === ROLE_ADMIN) {
                                header("Location: " . BASE_URL . "pages/admin/index.php");
                            } else {
                                header("Location: " . BASE_URL . "pages/books.php");
                            }
                        }
                        exit();
                    } else {
                        $error = '密码错误';
                    }
                } else {
                    $error = '用户不存在';
                }
            } else {
                throw new Exception("查询执行失败");
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $error = '登录时发生错误，请稍后重试';
        }
    }
}

$page_title = '登录';
include '../includes/navbar.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h3 class="card-title text-center mb-4">登录</h3>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">用户名</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">密码</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">登录</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="register.php" class="text-decoration-none">还没有账号？立即注册</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/sidebar.js"></script>
</body>
</html>