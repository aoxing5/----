document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');
    const toggleBtn = document.querySelector('.navbar-toggler');
    
    if (!sidebar || !backdrop || !toggleBtn) return;
    
    // 检查窗口大小，决定是否显示侧边栏
    function checkWindowSize() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        }
    }
    
    // 页面加载和窗口调整时检查
    checkWindowSize();
    window.addEventListener('resize', checkWindowSize);
    
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('show');
        backdrop.classList.toggle('show');
    });
    
    backdrop.addEventListener('click', function() {
        sidebar.classList.remove('show');
        backdrop.classList.remove('show');
    });
    
    // ESC键关闭侧边栏
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        }
    });
}); 