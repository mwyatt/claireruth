<?php require_once('header.php'); ?>
<h2>Yearly Posts</h2>
<?php
echo '<pre>';
print_r($post->getResult());
echo '</pre>';
?>

<?php require_once('footer.php'); ?>