<?php if ($this->get('model_user')): ?>

			<footer class="main clearfix">
				<p class="footer-site-title"><?php echo $this->get('options', 'site_title') . ' ' . date('Y') ?></p>
			</footer>
			
<?php endif ?>

	        <script src="<?php echo $this->url(); ?>js/public/main-admin.js?v=2"></script>
		</div>
    </body>
</html>
