<?php
// 确保已经包含了配置文件
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/config.php';
}
require_once __DIR__ . '/../includes/auth.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - 书店' : '书店'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="<?php echo BASE_URL; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>css/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>css/style.css" rel="stylesheet">
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link href="<?php echo BASE_URL . $css; ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
    <nav class="navbar">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button">
                <i class="bi bi-list"></i>
            </button>

            <span class="navbar-brand">
                <i class="bi bi-book"></i>
                <?php echo isAdmin() ? '书店管理系统' : '在线书店'; ?>
            </span>

            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['UserID'])): ?>
                    <?php if (!isAdmin()): ?>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>cart/view.php">
                            <i class="bi bi-cart"></i> 购物车
                        </a>
                    <?php endif; ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <?php echo htmlspecialchars($_SESSION['Username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/logout.php">
                                <i class="bi bi-box-arrow-right"></i> 退出登录
                            </a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>pages/login.php">登录</a>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>pages/register.php">注册</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <?php if (isset($_SESSION['UserID'])): ?>
            <?php if (isAdmin()): ?>
                <!-- 管理员菜单 -->
                <div class="sidebar-menu">
                    <a href="<?php echo BASE_URL; ?>pages/admin/index.php" 
                       class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/index.php') !== false ? 'active' : ''; ?>">
                        <i class="bi bi-speedometer2"></i> 控制台
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/admin/books.php" 
                       class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/books') !== false ? 'active' : ''; ?>">
                        <i class="bi bi-book"></i> 图书管理
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/admin/stock_records.php" 
                       class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/stock_records.php') !== false ? 'active' : ''; ?>">
                        <i class="bi bi-box-seam"></i> 库存管理
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/admin/orders/orders.php" 
                       class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false ? 'active' : ''; ?>">
                        <i class="bi bi-cart"></i> 订单管理
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/admin/users/list.php" 
                       class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active' : ''; ?>">
                        <i class="bi bi-people"></i> 用户管理
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/admin/sales.php" 
                       class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/sales.php') !== false ? 'active' : ''; ?>">
                        <i class="bi bi-graph-up"></i> 销售统计
                    </a>
                    <div class="sidebar-divider"></div>
                    <a href="<?php echo BASE_URL; ?>pages/logout.php" 
                       class="sidebar-menu-item text-danger">
                        <i class="bi bi-box-arrow-left"></i> 退出登录
                    </a>
                </div>
            <?php else: ?>
                <!-- 用户菜单 -->
                <div class="sidebar-menu">
                    <a href="<?php echo BASE_URL; ?>pages/books.php" 
                       class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/pages/books.php') !== false ? 'active' : ''; ?>">
                        <i class="bi bi-book"></i> 浏览图书
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/orders.php" 
                       class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/pages/orders.php') !== false ? 'active' : ''; ?>">
                        <i class="bi bi-cart"></i> 我的订单
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/profile.php" 
                       class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/pages/profile.php') !== false ? 'active' : ''; ?>">
                        <i class="bi bi-person"></i> 个人信息
                    </a>
                    <div class="sidebar-divider"></div>
                    <a href="<?php echo BASE_URL; ?>pages/logout.php" 
                       class="sidebar-menu-item text-danger">
                        <i class="bi bi-box-arrow-left"></i> 退出登录
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="sidebar-backdrop"></div>