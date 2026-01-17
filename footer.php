<footer id="footer">
	<div class="footer-content">
		<p>Copyright &copy; <?php echo date('Y'); ?> <a title="<?php echo esc_attr(get_bloginfo('name')); ?>" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a> · 
		Powered by <a title="WordPress" href="https://cn.wordpress.org" target="_blank" rel="noopener">WordPress</a> · 
		Theme <a title="YLW3.0主题" href="https://www.yalewoo.com/ylw3.html" target="_blank" rel="noopener">YLW3</a> · 
		<a title="UCloud云主机" href="https://www.ucloud.cn/site/active/kuaijiesale.html?cps_code=KTqh1GQ3mkpCp6OvpQaRCm" target="_blank" rel="noopener">UCloud云主机</a></p>
		
		<div id="footer_menu">
			<?php wp_nav_menu(array('theme_location' => 'footer_menu')); ?>
		</div>
	</div>
	</div>
	
	<script>
	// 延迟加载百度统计（不阻塞页面渲染）
	window.addEventListener('load', function() {
		setTimeout(function() {
			var hm = document.createElement("script");
			hm.src = "https://hm.baidu.com/hm.js?32ff821f324bdf2e2fc17726c3591cab";
			hm.defer = true;
			document.head.appendChild(hm);
		}, 100);
	});
	</script>
</footer>

<?php wp_footer(); ?>

</body>
</html>