<div class="sidebar">
    <div class="sidebar-menu">
        <a href="<?php echo BASE_URL; ?>pages/books.php" class="sidebar-menu-item <?php echo strpos($_SERVER['PHP_SELF'], 'books.php') !== false ? 'active' : ''; ?>">
            <i class="bi bi-book"></i> 浏览图书
        </a>
        <a href="<?php echo BASE_URL; ?>pages/user/orders.php" class="sidebar-menu-item <?php echo strpos($_SERVER['PHP_SELF'], 'orders.php') !== false ? 'active' : ''; ?>">
            <i class="bi bi-cart"></i> 我的订单
        </a>
        <a href="<?php echo BASE_URL; ?>pages/profile.php" class="sidebar-menu-item <?php echo strpos($_SERVER['PHP_SELF'], 'profile.php') !== false ? 'active' : ''; ?>">
            <i class="bi bi-person"></i> 个人信息
        </a>
        <a href="<?php echo BASE_URL; ?>pages/logout.php" class="sidebar-menu-item <?php echo strpos($_SERVER['PHP_SELF'], 'logout.php') !== false ? 'active' : ''; ?>">
            <i class="bi bi-box-arrow-right"></i> 退出登录
        </a>
    </div>
</div> 