<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';
require_once '../includes/auth.php';

// 检查用户是否登录
requireLogin();

$page_title = '我的购物车';


// 获取购物车内容
$sql = "SELECT c.*, b.BookName, b.Price, b.Stock, b.ImageURL 
        FROM cart c 
        JOIN book b ON c.BookID = b.BookID 
        WHERE c.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['UserID']);
$stmt->execute();
$cart_items = $stmt->get_result();

$total = 0;
?>

<div class="main-content">
    <?php include '../includes/navbar.php'; ?>
    <?php include '../includes/customer_sidebar.php'; ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">我的购物车</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($cart_items->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>图书</th>
                                            <th style="width: 150px;">数量</th>
                                            <th style="width: 120px;">单价</th>
                                            <th style="width: 120px;">小计</th>
                                            <th style="width: 80px;">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($item = $cart_items->fetch_assoc()): 
                                            $subtotal = $item['Price'] * $item['Quantity'];
                                            $total += $subtotal;
                                        ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($item['ImageURL']): ?>
                                                            <img src="<?php echo BASE_URL . $item['ImageURL']; ?>" 
                                                                 alt="<?php echo htmlspecialchars($item['BookName']); ?>"
                                                                 class="me-3" style="width: 60px; height: 80px; object-fit: cover;">
                                                        <?php endif; ?>
                                                        <div>
                                                            <h6 class="mb-0"><?php echo htmlspecialchars($item['BookName']); ?></h6>
                                                            <small class="text-muted">库存: <?php echo $item['Stock']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <button class="btn btn-outline-secondary" type="button" 
                                                                onclick="updateQuantity(<?php echo $item['CartID']; ?>, -1)">-</button>
                                                        <input type="number" class="form-control text-center" 
                                                               id="quantity_<?php echo $item['CartID']; ?>"
                                                               value="<?php echo $item['Quantity']; ?>" 
                                                               min="1" max="<?php echo $item['Stock']; ?>"
                                                               style="width: 60px;"
                                                               onchange="updateQuantity(<?php echo $item['CartID']; ?>, this.value)">
                                                        <button class="btn btn-outline-secondary" type="button" 
                                                                onclick="updateQuantity(<?php echo $item['CartID']; ?>, 1)">+</button>
                                                    </div>
                                                </td>
                                                <td>￥<?php echo number_format($item['Price'], 2); ?></td>
                                                <td>￥<?php echo number_format($subtotal, 2); ?></td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm" 
                                                            onclick="removeFromCart(<?php echo $item['CartID']; ?>)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">收货地址</label>
                                                <input type="text" class="form-control" id="address" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">联系电话</label>
                                                <input type="tel" class="form-control" id="phone" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-end">
                                                <h5 class="mb-3">总计：￥<?php echo number_format($total, 2); ?></h5>
                                                <button class="btn btn-primary" onclick="checkout()">结算</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                                <h4 class="mt-3">购物车是空的</h4>
                                <p class="text-muted">快去添加一些图书吧</p>
                                <a href="<?php echo BASE_URL; ?>pages/books.php" class="btn btn-primary">浏览图书</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateQuantity(cartId, change) {
    let quantityInput = document.getElementById('quantity_' + cartId);
    let currentQuantity = parseInt(quantityInput.value);
    let maxStock = parseInt(quantityInput.getAttribute('max'));
    
    if (typeof change === 'string') {
        currentQuantity = parseInt(change);
    } else {
        currentQuantity += change;
    }
    
    if (currentQuantity < 1) {
        currentQuantity = 1;
    }
    
    if (currentQuantity > maxStock) {
        alert('超出库存数量');
        currentQuantity = maxStock;
        quantityInput.value = maxStock;
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>cart/update_quantity.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'cart_id=' + cartId + '&quantity=' + currentQuantity
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || '更新失败');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('更新失败');
        location.reload();
    });
}

function removeFromCart(cartId) {
    if (!confirm('确定要删除这件商品吗？')) {
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>cart/remove.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'cart_id=' + cartId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
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

function checkout() {
    const address = document.getElementById('address').value;
    const phone = document.getElementById('phone').value;
    
    if (!address || !phone) {
        alert('请填写收货地址和联系电话');
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>cart/checkout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'address=' + encodeURIComponent(address) + '&phone=' + encodeURIComponent(phone)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('订单创建成功');
            window.location.href = '<?php echo BASE_URL; ?>pages/user/order_detail.php?id=' + data.order_id;
        } else {
            alert(data.message || '结算失败');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('结算失败');
    });
}
</script>

</body>
</html>