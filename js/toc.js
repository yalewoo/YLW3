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
    
    // 点击目录项平滑滚动
    $('.toc-list a').on('click', function(e) {
        e.preventDefault();
        var targetId = $(this).attr('href');
        var targetOffset = $(targetId).offset().top - 80;
        
        $('html, body').animate({
            scrollTop: targetOffset
        }, 300);
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
