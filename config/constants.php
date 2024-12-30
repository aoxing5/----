<?php
// 用户角色
define('ROLE_ADMIN', 'admin');
define('ROLE_CUSTOMER', 'customer');

// 订单状态
define('ORDER_PENDING', 'pending');
define('ORDER_COMPLETED', 'completed');
define('ORDER_CANCELLED', 'cancelled');

// 分页设置
define('ITEMS_PER_PAGE', 10);

// 上传文件设置
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
