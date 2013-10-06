<?php require_once('header.php'); ?>

<div class="content home clearfix">

<?php $rowContents = $this->get('model_content') ?>
<?php include($this->pathView('_contents')); ?>

	<a href="<?php echo $this->url() ?>post/" class="button primary">All Posts</a>
</div>

<?php require_once('footer.php'); ?>
