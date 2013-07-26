<?php require_once('app/cc/view/header.php'); ?>

	<?php if ($posts->getResult()) : ?>	
	<div>
		<?php while ($posts->nextRow()) : ?>

		<form class="edit_post" method="post">

			<input type="hidden" name="edit_post" value="true">
			
			<div>
				<input type="text" name="title" placeholder="<?php echo $posts->getRow('title'); ?>" autofocus="autofocus" value="<?php echo $posts->getRow('title'); ?>">					
			</div>
			
			<div>
				<p><?php echo $posts->getRow('guid'); ?></p>
				<a href="<?php echo $posts->getRow('guid'); ?>" title="Preview <?php echo $posts->getRow('title'); ?>">Preview</a>
			</div>
			
			<div>

				<!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
				<textarea id="elm1" name="html" rows="10" cols="50" autofocus="autofocus">
			<?php echo $posts->getRow('html'); ?>
				</textarea>
				<input type="reset" name="reset" value="Reset" />

			</div>
			
			<br><input class="" type="submit">
			
		</form>

<!-- work on this !! -->


<?php
echo '<pre>';
print_r ($posts->getRow('media'));
echo '</pre>';
?>

		<?php endwhile; ?>
	</div>
	<?php endif; ?>	

<?php require_once('app/cc/view/footer.php'); ?>