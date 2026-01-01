/**
 * 点赞功能 - 原生 JavaScript 实现
 * 无需 jQuery 依赖
 */
(function() {
    // 点赞处理函数
    function handleLike(element) {
        if (element.classList.contains('done')) {
            alert('您已赞过本文章');
            return false;
        }

        element.classList.add('done');
        
        const id = element.dataset.id;
        const action = element.dataset.action;
        const rateHolder = element.querySelector('.count');
        
        if (!id || !action || !rateHolder) {
            console.error('点赞元素缺少必要属性');
            return false;
        }
        
        const ajaxData = new URLSearchParams({
            action: 'specs_zan',
            um_id: id,
            um_action: action
        });
        
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: ajaxData
        })
        .then(response => response.text())
        .then(data => {
            rateHolder.innerHTML = data;
        })
        .catch(error => {
            console.error('点赞请求失败:', error);
            element.classList.remove('done');
        });
        
        return false;
    }
    
    // 初始化 - 使用事件委托
    document.addEventListener('click', function(e) {
        const likeBtn = e.target.closest('.specsZan');
        if (likeBtn) {
            handleLike(likeBtn);
        }
    });
})();