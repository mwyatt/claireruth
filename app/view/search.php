<?php require_once('header.php'); ?>

<div class="content search">
	<h1>Search results</h1>
	<p>You searched for "<?php echo $this->get('search_query') ?>". Which returned <?php echo ($this->get('model_search') ? count($this->get('model_search')) : '0') ?> result<?php echo (count($this->get('model_search')) > 1 || ! $this->get('model_search') ? 's' : '') ?>.</p>

<?php if ($this->get('model_search')) : ?>

	<div class="result">

<?php foreach ($this->get('model_search') as $search): ?>

		<a href="<?php echo $search['guid'] ?>" class="<?php echo $search['type'] ?>" title="View <?php echo $search['type'] ?> <?php echo $search['name'] ?>"><?php echo $search['name'] ?></a>	

	<?php endforeach ?>

	</div>

<?php endif; ?>	

	<form class="main" method="get">
	    <label for="form-search">Search</label>
	    <input id="form-search" type="text" name="search" type="search" maxlength="75" autofocus="autofocus" value="<?php echo $this->get('search_query') ?>">
	    <a href="#" class="submit button">Search</a>
	    <input type="submit">
	</form> 
</div>

<?php require_once('footer.php'); ?>
