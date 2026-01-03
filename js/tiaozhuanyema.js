/**
 * 页码跳转功能 - 原生 JavaScript 实现
 * 无需 jQuery 依赖
 */
(function() {
    function initPagination() {
        // 页码输入框跳转
        const goBtn = document.querySelector('.page_navi a.go_btn');
        const pageInput = document.getElementById('page_input');
        
        if (!goBtn || !pageInput) return;
        
        goBtn.addEventListener('click', function(event) {
            event.preventDefault(); // 取消默认动作
            
            const pageMax = pageInput.getAttribute('max'); // 获取最大页码
            const inputValue = pageInput.value.trim();
            
            if (inputValue === '') {
                alert('请输入页码');
                return false;
            }
            
            const pageNum = parseInt(inputValue);
            const maxNum = parseInt(pageMax);
            
            if (pageNum < 1 || pageNum > maxNum) {
                alert(`请输入1至${pageMax}之间的数字`);
                return false;
            }
            
            // 从页码列表中获取任意一个链接，此处获取第二个链接
            const pageLinks = document.querySelectorAll('.page_navi a');
            if (pageLinks.length < 3) {
                console.error('页码链接不足');
                return false;
            }
            
            const pageLink = pageLinks[2].getAttribute('href');
            // 将页码数字替换
            const goLink = pageLink.replace(/\/page\/([0-9]+)/g, `/page/${pageNum}`);
            location.href = goLink; // 跳转
        });
        
        // 页码输入框 - 仅允许数字和禁用输入法
        pageInput.addEventListener('keypress', function(event) {
            const keyCode = event.keyCode || event.which;
            // 仅允许数字 48-57 (0-9)
            if (!(keyCode >= 48 && keyCode <= 57)) {
                return false;
            }
        });
        
        // 输入验证 - 只保留数字，允许粘贴
        pageInput.addEventListener('input', function() {
            // 移除非数字字符
            this.value = this.value.replace(/[^\d]/g, '');
        });
    }
    
    // 在 DOM 加载完成后初始化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPagination);
    } else {
        initPagination();
    }
})();