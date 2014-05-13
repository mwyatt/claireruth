<?php require_once($this->pathView() . 'admin/_header.php') ?>

<div class="content <?php echo $this->url->getPathPart(2) ?>-list">
	<h1><?php echo ucfirst($this->url->getPathPart(2)) ?></h1>

<?php if ($modelTag) : ?>
	<?php foreach ($modelTag as $tag) : ?>
		<?php include($this->pathView('admin/_tag')) ?>
	<?php endforeach ?>
<?php else: ?>
	
	<div class="nothing-yet">
		<p>No <?php echo ucfirst($this->url->getPathPart(2)) ?> have been created yet, why not <a href="<?php echo $this->url('current_noquery') ?>new/">create</a> one now?</p>
	</div>
	
<?php endif ?>
		
</div>

<?php require_once($this->pathView() . 'admin/_footer.php') ?>
