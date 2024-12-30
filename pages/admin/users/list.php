<?php
require_once '../../../config/config.php';
require_once '../../../config/db_connect.php';
require_once '../../../includes/auth.php';

// 检查管理员权限
requireAdmin();

$page_title = '用户管理';
include_once '../../../includes/navbar.php';

// 获取用户列表
$sql = "SELECT * FROM user ORDER BY UserID DESC";
$result = $conn->query($sql);
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>用户管理</h2>
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
                                <th>用户名</th>
                                <th>角色</th>
                                <th>邮箱</th>
                                <th>电话</th>
                                <th>注册时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($user = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $user['UserID']; ?></td>
                                    <td><?php echo htmlspecialchars($user['Username']); ?></td>
                                    <td>
                                        <?php echo $user['Role'] == 'admin' ? '管理员' : '用户'; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['Email'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($user['Phone'] ?? '-'); ?></td>
                                    <td><?php echo $user['RegisterTime'] ? date('Y-m-d H:i', strtotime($user['RegisterTime'])) : '-'; ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $user['UserID']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">暂无用户数据</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 编辑用户模态框 -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">编辑用户</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" name="user_id" id="editUserId">
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">邮箱</label>
                        <input type="email" class="form-control" id="editEmail" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">角色</label>
                        <select class="form-select" id="editRole" name="role">
                            <option value="user">普通用户</option>
                            <option value="admin">管理员</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="saveEdit">保存</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/sidebar.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 编辑用户
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    const editButtons = document.querySelectorAll('.edit-user');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            // 这里可以添加获取用户信息的 AJAX 请求
            document.getElementById('editUserId').value = userId;
        });
    });
    
    // 切换用户状态
    const toggleButtons = document.querySelectorAll('.toggle-status');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const currentStatus = this.dataset.status;
            // 这里可以添加更新用户状态的 AJAX 请求
        });
    });
});
</script>
</body>
</html> 