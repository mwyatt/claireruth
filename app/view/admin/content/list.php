<?php require_once($this->pathView() . 'admin/_header.php'); ?>

<div class="content main-content<?php echo $this->url(2) ?>">
	<h1><?php echo ucfirst($this->url(2)); ?></h1>
	<div class="clearfix row">
		<a class="button right clearfix" href="<?php echo $this->url('current_noquery'); ?>new/" title="Create a new <?php echo ucfirst($this->url(2)); ?>">New</a>
	</div>

<?php if ($this->get('model_content')) : ?>
	<?php foreach ($this->get('model_content') as $rowContent) : ?>
		<?php include($this->pathView('admin/_content')); ?>
	<?php endforeach; ?>
<?php else: ?>
	
	<div class="nothing-yet">
		<p>No <?php echo ucfirst($this->url(2)); ?> have been created yet, why not <a href="<?php echo $this->url('current_noquery'); ?>new/">create</a> one now?</p>
	</div>
	
<?php endif ?>
		
</div>

<?php require_once($this->pathView() . 'admin/_footer.php'); ?>
