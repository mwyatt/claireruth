<?php if ($pagination): ?>

<div class="pagination clearfix">

	<?php foreach ($pagination as $pageNumber => $page): ?>

	<a href="<?php echo $page['url'] ?>" class="<?php echo $page['name'] . ($page['current'] ? ' is-current' : '') ?>"><?php echo ($page['name'] == 'page' ? $pageNumber : ucfirst($page['name'])) ?></a>

	<?php endforeach ?>
	
</div>

<?php endif ?>
