<?php
session_start();
require_once '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);

    // 检查用户名是否已存在
    $check_sql = "SELECT UserID FROM user WHERE Username = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error = "用户名已存在";
    } else {
        // 创建新用户
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (Username, Password, Email, ContactInfo, Address, Role) 
                VALUES (?, ?, ?, ?, ?, 'customer')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $hashed_password, $email, $contact, $address);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "注册成功，请登录";
            header("Location: login.php");
            exit();
        } else {
            $error = "注册失败: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户注册</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">用户注册</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="post" action="">
                            <div class="mb-3">
                                <label>用户名</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            
                            <div class="mb-3">
                                <label>密码</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label>邮箱</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label>联系方式</label>
                                <input type="text" class="form-control" name="contact">
                            </div>
                            
                            <div class="mb-3">
                                <label>地址</label>
                                <textarea class="form-control" name="address" rows="3"></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">注册</button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p>已有账号? <a href="login.php">立即登录</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>