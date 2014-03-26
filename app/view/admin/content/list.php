<?php require_once($this->pathView() . 'admin/_header.php') ?>

<div class="content main-content<?php echo $this->url(2) ?>">
	<a class="button right" href="<?php echo $this->url('current_noquery') ?>new/" title="Create a new <?php echo ucfirst($this->url(2)) ?>">New</a>
	<h1 class="h3 mb1"><?php echo ucfirst($this->url(2)) ?></h1>

<?php include($this->pathView('_contents')) ?>
<?php if (! $contents): ?>
	
	<div class="nothing-yet">
		<p>No <?php echo ucfirst($this->url(2)) ?> have been created yet, why not <a href="<?php echo $this->url('current_noquery') ?>new/">create</a> one now?</p>
	</div>
	
<?php endif ?>
		
</div>

<?php require_once($this->pathView() . 'admin/_footer.php') ?>
