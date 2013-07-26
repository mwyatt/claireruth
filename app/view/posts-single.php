<?php require_once('header.php'); ?>

<?php if ($post = $post->getResult()) : extract($post[0]); ?>

	<h2><?php echo $title; ?></h2>

	<a href="<?php echo $guid; ?>"><?php echo $title; ?></a>
	<i><?php echo $date_published; ?></i>
	
<?php endif; ?>

<?php require_once('footer.php'); ?>