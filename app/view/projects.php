<?php require_once('header.php'); ?>

<h2>All Projects</h2>

<?php foreach ($project->getResult() as $project) : extract($project); ?>

	<h2><a href="<?php echo $guid; ?>"><?php echo $title; ?></a></h2>
	
	<a href="<?php echo media_url('upload/'.$attached[0]['filename']); ?>"><img src="<?php echo media_url('upload/thumb/'.$attached[0]['thumb']); ?>"></a>
	<br>
	
	<?php foreach ($tags as $tag) : extract($tag); ?>
		<a href="<?php echo $url; ?>"><?php echo $name; ?></a> / 
	<?php endforeach; ?>
	
<?php endforeach; ?>

<?php require_once('footer.php'); ?>