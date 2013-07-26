<?php require_once('header.php'); ?>

<div class="content archive">
	<h1>Archive</h1>

<?php if ($this->get('model_ttarchive')) : ?>
	
	<ul>

	<?php foreach ($this->get('model_ttarchive') as $archive): ?>

		<li><a href="<?php echo $this->get($archive, 'guid'); ?>"><?php echo $this->get($archive, 'title') ?></a></li>

	<?php endforeach ?>

		<li><a href="archive-older/" target="_blank">Older</a></li>
	</ul>

<?php endif; ?>	

</div>

<?php require_once('footer.php'); ?>
