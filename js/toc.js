/**
 * 文章目录生成器
 * 自动提取文章中的标题生成目录导航
 */
jQuery(document).ready(function($) {
    var articleContent = $('#article-content');
    var tocList = $('.toc-list');
    var toc = $('#toc');
    
    // 获取所有标题 (h2, h3, h4)
    var headings = articleContent.find('h2, h3, h4');
    
    // 如果标题少于2个，隐藏目录
    if (headings.length < 2) {
        toc.hide();
        return;
    }
    
    // 创建折叠按钮（窄屏幕时显示）
    var toggleBtn = $('<button class="toc-toggle-btn" title="目录">📑</button>');
    $('body').append(toggleBtn);
    
    // 点击按钮切换目录显示
    toggleBtn.on('click', function() {
        toc.toggleClass('toc-visible');
        toggleBtn.toggleClass('toc-active');
    });
    
    // 点击目录外区域关闭目录（窄屏幕时）
    $(document).on('click', function(e) {
        if (window.innerWidth <= 1024) {
            if (!$(e.target).closest('.table-of-contents, .toc-toggle-btn').length) {
                toc.removeClass('toc-visible');
                toggleBtn.removeClass('toc-active');
            }
        }
    });
    
    // 为每个标题添加 id 并生成目录项
    headings.each(function(index) {
        var $heading = $(this);
        var headingText = $heading.text();
        var headingLevel = this.tagName.toLowerCase();
        
        // 生成唯一 id
        var headingId = 'heading-' + index;
        $heading.attr('id', headingId);
        
        // 创建目录项
        var tocItem = $('<li></li>')
            .addClass('toc-item toc-' + headingLevel)
            .html('<a href="#' + headingId + '">' + headingText + '</a>');
        
        tocList.append(tocItem);
    });
    
    // 添加评论区导航
    var commentsSection = $('#comments');
    var respondSection = $('#respond');
    
    if (commentsSection.length || respondSection.length) {
        // 添加分隔线
        tocList.append('<li class="toc-divider"></li>');
        
        // 添加评论区链接
        if (commentsSection.length) {
            var commentsItem = $('<li></li>')
                .addClass('toc-item toc-extra')
                .html('<a href="#comments">💬 评论区</a>');
            tocList.append(commentsItem);
        }
        
        // 添加我要评论链接
        if (respondSection.length) {
            var respondItem = $('<li></li>')
                .addClass('toc-item toc-extra')
                .html('<a href="#respond">✏️ 我要评论</a>');
            tocList.append(respondItem);
        }
    }
    
    // 点击目录项平滑滚动
    $('.toc-list a').on('click', function(e) {
        e.preventDefault();
        var targetId = $(this).attr('href');
        var target = document.querySelector(targetId);
        
        if (target) {
            var targetOffset = target.getBoundingClientRect().top + window.pageYOffset - 80;
            window.scrollTo({
                top: targetOffset,
                behavior: 'smooth'
            });
            
            // 窄屏幕时点击后关闭目录
            if (window.innerWidth <= 1024) {
                toc.removeClass('toc-visible');
                toggleBtn.removeClass('toc-active');
            }
        }
    });
    
    // 滚动时高亮当前章节
    var tocItems = $('.toc-item a');
    
    $(window).on('scroll', function() {
        var scrollPos = $(window).scrollTop() + 100;
        
        headings.each(function(index) {
            var $heading = $(this);
            var headingTop = $heading.offset().top;
            var nextHeading = headings.eq(index + 1);
            var nextTop = nextHeading.length ? nextHeading.offset().top : $(document).height();
            
            if (scrollPos >= headingTop && scrollPos < nextTop) {
                tocItems.removeClass('active');
                tocItems.eq(index).addClass('active');
            }
        });
    });
    
    // 初始触发一次滚动事件
    $(window).trigger('scroll');
});
