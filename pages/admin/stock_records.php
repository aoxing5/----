<?php
require_once '../../config/config.php';
require_once '../../config/db_connect.php';
require_once '../../includes/auth.php';

// 检查管理员权限
requireAdmin();

$page_title = '库存管理';
include_once '../../includes/navbar.php';

// 获取库存记录
$sql = "SELECT sr.*, b.BookName, u.Username 
        FROM stock_record sr
        JOIN book b ON sr.BookID = b.BookID
        JOIN user u ON sr.OperatorID = u.UserID
        ORDER BY sr.RecordTime DESC";
$records = $conn->query($sql);
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>库存管理</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockModal">
                        <i class="bi bi-plus-lg"></i> 新增库存变动
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>图书</th>
                                <th>变动数量</th>
                                <th>类型</th>
                                <th>操作人</th>
                                <th>备注</th>
                                <th>时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($record = $records->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $record['RecordID']; ?></td>
                                    <td><?php echo htmlspecialchars($record['BookName']); ?></td>
                                    <td><?php echo $record['Quantity']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $record['Type'] == 'in' ? 'success' : 'warning'; ?>">
                                            <?php echo $record['Type'] == 'in' ? '入库' : '出库'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($record['Username']); ?></td>
                                    <td><?php echo htmlspecialchars($record['Remark']); ?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($record['RecordTime'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 新增库存变动模态框 -->
<div class="modal fade" id="addStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增库存变动</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="stockForm">
                    <div class="mb-3">
                        <label class="form-label">图书</label>
                        <select class="form-select" name="book_id" required>
                            <?php
                            $books = $conn->query("SELECT BookID, BookName FROM book ORDER BY BookName");
                            while ($book = $books->fetch_assoc()) {
                                echo "<option value='{$book['BookID']}'>{$book['BookName']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">变动类型</label>
                        <select class="form-select" name="type" required>
                            <option value="in">入库</option>
                            <option value="out">出库</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">数量</label>
                        <input type="number" class="form-control" name="quantity" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">备注</label>
                        <textarea class="form-control" name="remark" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="submitStock()">确定</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="<?php echo BASE_URL; ?>js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/sidebar.js"></script>

<script>
function submitStock() {
    const form = document.getElementById('stockForm');
    const formData = new FormData(form);
    
    fetch('<?php echo BASE_URL; ?>pages/admin/stock_update.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('库存更新成功');
            location.reload();
        } else {
            alert(data.message || '更新失败');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('更新失败');
    });
}

// 添加这段初始化代码
document.addEventListener('DOMContentLoaded', function() {
    // 初始化所有模态框
    var myModal = new bootstrap.Modal(document.getElementById('addStockModal'));
    
    // 为新增按钮添加点击事件
    document.querySelector('[data-bs-target="#addStockModal"]').addEventListener('click', function() {
        myModal.show();
    });
});
</script>

</body>
</html>