<?php require_once($this->pathView() . 'admin/_header.php') ?>

<div class="main-content<?php echo $this->url->getPathPart(2) ?>">
	<div class="clearfix">

<?php if ($statuses): ?>
	<?php foreach ($statuses as $status): ?>
		
		<a class="button right" href="<?php echo $this->url('current_noquery') ?>?status=<?php echo $status ?>">Status: <?php echo ucfirst($status) ?></a>

	<?php endforeach ?>
<?php endif ?>

		<a class="button right" href="<?php echo $this->url('current_noquery') ?>new/" title="Create a new <?php echo ucfirst($this->url->getPathPart(2)) ?>">New</a>
	</div>
	<h1 class="h3 mb1"><?php echo ucfirst($this->url->getPathPart(2)) ?></h1>

<?php include($this->pathView('_contents')) ?>
<?php if (! $contents): ?>
	
	<div class="nothing-yet">
		<p>No <?php echo ucfirst($this->url->getPathPart(2)) ?> have been created yet, why not <a href="<?php echo $this->url('current_noquery') ?>new/">create</a> one now?</p>
	</div>
	
<?php endif ?>
		
</div>

<?php require_once($this->pathView() . 'admin/_footer.php') ?>
