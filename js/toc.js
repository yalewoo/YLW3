/**
 * 文章目录生成器 - 原生 JavaScript 实现
 * 自动提取文章中的标题生成目录导航（无需 jQuery）
 */
(function() {
    function initTableOfContents() {
        // 获取必要的 DOM 元素
        const articleContent = document.getElementById('article-content');
        const tocContainer = document.querySelector('.table-of-contents');
        const tocList = document.querySelector('.toc-list');
        const toc = document.getElementById('toc');
        
        if (!articleContent || !tocList || !toc) {
            console.warn('目录所需的 DOM 元素不存在');
            return;
        }
        
        // 获取所有标题
        const headings = Array.from(articleContent.querySelectorAll('h2, h3, h4'));
        
        // 如果标题少于 2 个，隐藏目录
        if (headings.length < 2) {
            toc.style.display = 'none';
            return;
        }
        
        // 创建折叠按钮（预分配空间防止CLS）
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'toc-toggle-btn';
        toggleBtn.title = '目录';
        toggleBtn.textContent = '📑';
        toggleBtn.setAttribute('aria-label', '切换目录');
        document.body.appendChild(toggleBtn);
        
        // 按钮点击处理
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toc.classList.toggle('toc-visible');
            toggleBtn.classList.toggle('toc-active');
        });
        
        // 点击目录外关闭（仅窄屏幕）
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 1024) {
                const isClickInToc = toc.contains(e.target) || toggleBtn.contains(e.target);
                if (!isClickInToc) {
                    toc.classList.remove('toc-visible');
                    toggleBtn.classList.remove('toc-active');
                }
            }
        });
        
        // 使用 DocumentFragment 批量插入，减少重排
        const fragment = document.createDocumentFragment();
        
        // 为每个标题添加 id 并生成目录项
        headings.forEach((heading, index) => {
            const headingText = heading.textContent.trim();
            const headingLevel = heading.tagName.toLowerCase();
            const headingId = 'heading-' + index;
            
            // 添加 id 到标题
            heading.id = headingId;
            
            // 创建目录项
            const li = document.createElement('li');
            li.className = `toc-item toc-${headingLevel}`;
            li.innerHTML = `<a href="#${headingId}">${headingText}</a>`;
            fragment.appendChild(li);
        });
        
        // 添加评论区导航
        const commentsSection = document.getElementById('comments');
        const respondSection = document.getElementById('respond');
        
        if (commentsSection || respondSection) {
            // 添加分隔线
            const divider = document.createElement('li');
            divider.className = 'toc-divider';
            fragment.appendChild(divider);
            
            // 添加评论区链接
            if (commentsSection) {
                const commentItem = document.createElement('li');
                commentItem.className = 'toc-item toc-extra';
                commentItem.innerHTML = '<a href="#comments">💬 评论区</a>';
                fragment.appendChild(commentItem);
            }
            
            // 添加我要评论链接
            if (respondSection) {
                const respondItem = document.createElement('li');
                respondItem.className = 'toc-item toc-extra';
                respondItem.innerHTML = '<a href="#respond">✏️ 我要评论</a>';
                fragment.appendChild(respondItem);
            }
        }
        
        // 一次性插入所有项，减少重排
        tocList.appendChild(fragment);
        
        // 目录项点击处理
        tocList.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return;
            
            e.preventDefault();
            const targetId = link.getAttribute('href');
            const target = document.querySelector(targetId);
            
            if (target) {
                const targetOffset = target.getBoundingClientRect().top + window.pageYOffset - 80;
                window.scrollTo({
                    top: targetOffset,
                    behavior: 'smooth'
                });
                
                // 窄屏幕时点击后关闭目录
                if (window.innerWidth <= 1024) {
                    toc.classList.remove('toc-visible');
                    toggleBtn.classList.remove('toc-active');
                }
            }
        });
        
        // 滚动时高亮当前章节
        const tocItems = tocList.querySelectorAll('.toc-item:not(.toc-divider) a');
        
        window.addEventListener('scroll', function() {
            const scrollPos = window.scrollY + 100;
            
            headings.forEach((heading, index) => {
                const headingTop = heading.offsetTop;
                const nextHeading = headings[index + 1];
                const nextTop = nextHeading ? nextHeading.offsetTop : document.documentElement.scrollHeight;
                
                if (scrollPos >= headingTop && scrollPos < nextTop) {
                    tocItems.forEach(item => item.classList.remove('active'));
                    if (tocItems[index]) {
                        tocItems[index].classList.add('active');
                    }
                }
            });
        }, { passive: true }); // 使用 passive 优化滚动性能
    }
    
    // 在 DOM 加载完成后初始化（使用 requestIdleCallback 推迟执行，避免阻塞渲染）
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            if ('requestIdleCallback' in window) {
                requestIdleCallback(initTableOfContents);
            } else {
                // 降级方案：延迟 100ms
                setTimeout(initTableOfContents, 100);
            }
        });
    } else {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(initTableOfContents);
        } else {
            setTimeout(initTableOfContents, 100);
        }
    }
})();

