<?php if ($pagination): ?>

<div class="pagination clearfix">
	<h3 class="pagination-break-down">Page <?php echo $pageCurrent ?> of <?php echo count($pagination) ?></h3>

	<?php foreach ($pagination as $pageNumber => $page): ?>

	<a href="<?php echo $page['guid'] ?>" class="<?php echo $page['name'] . ($page['current'] ? ' is-current' : '') ?>"><?php echo ($page['name'] == 'page' ? $pageNumber : ucfirst($page['name'])) ?></a>

	<?php endforeach ?>
	
</div>

<?php endif ?>
