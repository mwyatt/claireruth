<article class="<?php echo $content['type']; ?>-single" data-id="<?php echo $content['id']; ?>">
	<h2 class="title"><?php echo $content['title']; ?></h2>
	<div class="description">

<?php echo $content['html']; ?>

	</div>
	<div class="date"><?php echo date('d/m/Y', $content['date_published']); ?></div>

<?php require($this->pathView() . 'entity/tags.php'); ?>

	<div class="author"><?php echo $content['user_name']; ?></div>

</article>
