<div class="sidebar">
    <div class="sidebar-menu">
        <a href="<?php echo BASE_URL; ?>pages/admin/dashboard.php" class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/dashboard.php') !== false ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i> 控制台
        </a>
        <a href="<?php echo BASE_URL; ?>pages/admin/books.php" class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/books/') !== false ? 'active' : ''; ?>">
            <i class="bi bi-book"></i> 图书管理
        </a>
        <a href="<?php echo BASE_URL; ?>pages/admin/stock_records.php" class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/stock_records.php') !== false ? 'active' : ''; ?>">
            <i class="bi bi-box-seam"></i> 库存管理
        </a>
        <a href="<?php echo BASE_URL; ?>pages/admin/orders/orders.php" class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/orders/') !== false ? 'active' : ''; ?>">
            <i class="bi bi-cart"></i> 订单管理
        </a>
        <a href="<?php echo BASE_URL; ?>pages/admin/users/list.php" class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/users/') !== false ? 'active' : ''; ?>">
            <i class="bi bi-people"></i> 用户管理
        </a>
        <a href="<?php echo BASE_URL; ?>pages/admin/sales.php" class="sidebar-menu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/sales.php') !== false ? 'active' : ''; ?>">
            <i class="bi bi-graph-up"></i> 销售统计
        </a>
    </div>
</div>