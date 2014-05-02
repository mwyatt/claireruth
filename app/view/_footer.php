<?php if (! $this->comingSoon): ?>

			<footer class="container-footer clearfix">
				<div class="inner">
					<div id="search">
	
    <?php include($this->pathView('_search')) ?>

					</div>
					<div id="menu">
	
    <?php include($this->pathView('_menu')) ?>

					</div>

    <?php include($this->pathView('_calling-card')) ?>

				</div>
			</footer>
        </div>

<?php endif ?>

		</div> <!-- .container -->
        <script src="<?php echo $this->url() ?>asset/main.js?v=1"></script>
    </body>
</html>
