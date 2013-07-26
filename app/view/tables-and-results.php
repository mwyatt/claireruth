<?php require_once('header.php'); ?>

<div class="content tables-and-results clearfix">
	<h1>Tables and Results</h1>

<?php if ($this->get('model_ttdivision')): ?>
    <?php foreach ($this->get('model_ttdivision') as $division): ?>

    <div class="division-<?php echo strtolower($this->get($division, 'name')) ?>">
        <h4><a href="<?php echo $this->get($division, 'guid') ?>"><?php echo $this->get($division, 'name') ?></a></h4>
        <a href="<?php echo $this->get($division, 'guid') ?>">Overview</a>
        <a href="<?php echo $this->get($division, 'guid') ?>merit/">Merit Table</a>
        <a href="<?php echo $this->get($division, 'guid') ?>league/">League Table</a>
    </div>        
        
    <?php endforeach ?>
<?php endif ?>

</div>

<?php require_once('footer.php'); ?>
