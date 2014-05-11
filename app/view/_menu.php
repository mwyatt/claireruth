<?php if ($menu): ?>
	
<nav class="menu clearfix">

	<?php foreach ($menu as $menuItem): ?>

	<a href="<?php echo $this->url() . $menuItem->url ?>" class="menu-item"><?php echo $menuItem->name ?></a>

	<?php endforeach ?>
	
</nav>

<?php endif ?>
