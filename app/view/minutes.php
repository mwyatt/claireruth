<?php require_once('header.php'); ?>

<div class="content clearfix<?php echo ' ' . $this->get('information', 'type') ?>">
	<h1><?php echo ucfirst($this->get('information', 'type')) ?></h1>

<?php if ($this->get('model_maincontent')) : ?>
	<?php foreach ($this->get('model_maincontent') as $minute): ?>
		<?php $media = $this->get($minute, 'media'); ?>
		<?php $media = current($media); ?>
		
	<div class="item clearfix">
		<a class="button" href="<?php echo $media['guid']; ?>" target="_blank">Download</a>
		<h2><a href="<?php echo $media['guid']; ?>" target="_blank"><?php echo ($minute['type'] == 'minutes' ? date('D jS F Y', $this->get($minute, 'date_published')) : $minute['title']) ?></a></h2>
	</div>

	<?php endforeach ?>
<?php else: ?>	
	<?php require_once($this->pathView() . 'nothing-yet.php'); ?>
<?php endif; ?>	

</div>

<?php require_once('footer.php'); ?>
