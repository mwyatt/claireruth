<article class="<?php echo $content['type']; ?>-single" data-id="<?php echo $content['id']; ?>">
	<h2 class="title"><?php echo $content['title']; ?></h2>
	<div class="description">

<?php echo $content['html']; ?>

	</div>
	<div class="date"><?php echo date('d/m/Y', $content['date_published']); ?></div>

<?php if (array_key_exists('tag', $content)): ?>
	
	<div class="tags">

	<?php foreach ($content['tag'] as $tag): ?>
		
		<a href="<?php echo $tag['guid'] ?>" class="tag"><?php echo $tag['name'] ?></a>

	<?php endforeach ?>
		
	</div>

<?php endif ?>

	<div class="author"><?php echo $content['user_name']; ?></div>

</article>
