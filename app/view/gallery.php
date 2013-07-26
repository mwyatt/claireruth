<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content gallery clearfix">
	<h1><?php echo ($this->get('album_title') ? $this->get('album_title') : 'Gallery') ?></h1>

<?php if ($this->get('folder')): ?>

	<div class="col left">
		<h2>Albums</h2>
		<nav>

	<?php foreach ($this->get('folder') as $folder): ?>

			<a class="folder" href="<?php echo $this->get($folder, 'guid') ?>"><?php echo $this->get($folder, 'title') ?></a>

	<?php endforeach ?>

		</nav>
	</div>
	<div class="col right">

	<?php if ($this->get('file')): ?>
		<?php foreach ($this->get('file') as $file): ?>

		<a target="_blank" class="file" href="<?php echo $this->get($file, 'guid') ?>"><img src="<?php echo $this->get($file, 'timthumb') ?>" alt="<?php echo $this->get($file, 'filename') ?>"></a>

		<?php endforeach ?>
	<?php else: ?>
		<?php if ($this->get('album_title')): ?>
			<?php require_once($this->pathView() . 'nothing-yet.php'); ?>
		<?php endif ?>
	<?php endif; ?>	

	</div>
	
<?php else: ?>
	<?php require_once($this->pathView() . 'nothing-yet.php'); ?>
<?php endif; ?>	

</div>

<?php require_once($this->pathView() . 'footer.php'); ?>
