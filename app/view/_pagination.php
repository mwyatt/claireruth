<?php if ($pagination && $paginationSummary): ?>

<div class="pagination clearfix">
	<h3><?php echo $paginationSummary ?></h3>

	<?php foreach ($pagination as $pageNumber => $page): ?>

	<a href="<?php echo $page->url ?>" class="<?php echo $page->name . ($page->current ? ' is-current' : '') ?>"><?php echo ($page->name == 'page' ? $pageNumber : ucfirst($page->name)) ?></a>

	<?php endforeach ?>
	
</div>

<?php endif ?>
