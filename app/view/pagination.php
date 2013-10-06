<?php if ($this->get('pagination')): ?>

<div class="pagination clearfix">
	<h3 class="pagination-title">Pages:</h3>

	<?php foreach ($this->get('pagination') as $pageNumber => $page): ?>

	<a href="<?php echo $page['guid'] ?>" class="<?php echo $page['name'] . ($page['current'] ? ' is-current' : '') ?>"><?php echo ($page['name'] == 'page' ? $pageNumber : ucfirst($page['name'])) ?></a>

	<?php endforeach ?>
	
</div>

<?php endif ?>