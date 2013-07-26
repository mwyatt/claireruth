<?php require_once('header.php'); ?>
<?php 
$covers[] = array(
	'title' => 'Photo Gallery'
	, 'guid' => $this->url('base') . 'gallery/'
	, 'description' => 'View photos from the recent 2013 tournament.'
	, 'button' => 'Go'
);
$covers[] = array(
	'title' => 'Summer League'
	, 'guid' => $this->url('base') . 'page/summer-league/'
	, 'description' => 'Register your interest for the upcoming summer league.'
	, 'button' => 'More Information'
);
$covers[] = array(
	'title' => 'Player Performance'
	, 'guid' => $this->url('base') . 'player/performance/'
	, 'description' => 'Visit the player performance to see who has gained the most ranking points throughout the season.'
	, 'button' => 'Go'
);
$covers[] = array(
	'title' => 'Results archive'
	, 'guid' => $this->url('base') . 'archive/'
	, 'description' => 'What happened last year? Find out here.'
	, 'button' => 'Vist the archive'
);
shuffle($covers);
?>
<?php if ($covers): ?>
	
<div class="cover">

	<?php foreach ($covers as $key => $cover): ?>
		
	<a href="<?php echo $this->get($cover, 'guid') ?>" class="inner bring-out clearfix <?php echo $this->urlFriendly($this->get($cover, 'title')) ?>">
		<h1><?php echo $this->get($cover, 'title') ?></h1>
		<p><?php echo $this->get($cover, 'description') ?></p>
		<span class="button"><?php echo $this->get($cover, 'button') ?></span>
	</a>

	<?php endforeach ?>

</div>

<?php endif ?>

<div class="content home clearfix">

<?php if ($this->get('model_maincontent')): ?>
    
	<div class="press">
		<a href="press/" class="all right">All press</a>
		<h2>Press Releases</h2>

    <?php foreach ($this->get('model_maincontent') as $press): ?>

		<a class="item clearfix" href="<?php echo $this->get($press, 'guid') ?>">
			<!-- <img src="#" alt=""> -->
			<h3><?php echo $this->get($press, 'title') ?></h3>
			<span class="date"><?php echo date('jS', $this->get($press, 'date_published')) . ' of ' . date('F Y', $this->get($press, 'date_published')) ?></span>
		</a>
        
    <?php endforeach ?>

	</div>

<?php endif ?>
<?php 
$covers = false;
$covers[] = array(
	'title' => 'Download the Handbook'
	, 'guid' => $this->url('base') . 'media/handbook.pdf'
	, 'theme' => 'orange'
);
$covers[] = array(
	'title' => 'Tables and results'
	, 'guid' => $this->url('base') . 'tables-and-results/'
	, 'theme' => 'green'
);
$covers[] = array(
	'title' => 'Player Performance'
	, 'guid' => $this->url('base') . 'player/performance/'
	, 'theme' => 'silver'
);
$covers[] = array(
	'title' => 'The Gallery'
	, 'guid' => $this->url('base') . 'gallery/'
	, 'theme' => 'red'
);
shuffle($covers);
?>
<?php if ($covers): ?>
	
<div class="ads">

	<?php foreach ($covers as $key => $cover): ?>
		
	<a href="<?php echo $this->get($cover, 'guid') ?>" class="<?php echo $this->urlFriendly($this->get($cover, 'title')) ?> ad <?php echo $this->get($cover, 'theme') ?>">
		<span></span>
		<h4><?php echo $this->get($cover, 'title') ?></h4>
	</a>

	<?php endforeach ?>

</div>

<?php endif ?>

	</div>
</div>

<?php require_once('footer.php'); ?>