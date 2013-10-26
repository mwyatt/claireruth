<?php if ($this->get('model_user')): ?>

			<footer class="main clearfix">
				<p class="footer-site-title"><?php echo $this->get('options', 'site_title') . ' ' . date('Y') ?></p>
			</footer>
			
<?php endif ?>

	        <script src="<?php echo $this->url(); ?>js/vendor/jquery-1.8.2.min.js"></script>
	        <script src="<?php echo $this->url(); ?>js/vendor/jquery.magnific-popup.min.js"></script>
	        <script src="<?php echo $this->url(); ?>js/vendor/wysihtml5-advanced.js"></script>
	        <script src="<?php echo $this->url(); ?>js/vendor/wysihtml5-0.3.0.js"></script>
	        <script src="<?php echo $this->url(); ?>js/admin/main.js"></script>
		</div>
    </body>
</html>
