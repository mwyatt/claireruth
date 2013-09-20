<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

<?php if ($this->get('model_ttfixture')): ?>
	<?php foreach ($this->get('model_ttfixture') as $row): ?>
		
	<url>
		<loc><?php echo $row['guid'] ?></loc>
		<changefreq>yearly</changefreq>
		<priority>0.6</priority>
	</url>

	<?php endforeach ?>
<?php endif ?>
<?php if ($this->get('model_ttdivision')): ?>
	<?php foreach ($this->get('model_ttdivision') as $row): ?>
		
	<url>
		<loc><?php echo $row['guid'] ?></loc>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
	</url>

	<?php endforeach ?>
<?php endif ?>
<?php if ($this->get('model_ttteam')): ?>
	<?php foreach ($this->get('model_ttteam') as $row): ?>
		
	<url>
		<loc><?php echo $row['guid'] ?></loc>
		<changefreq>weekly</changefreq>
		<priority>0.7</priority>
	</url>

	<?php endforeach ?>
<?php endif ?>
<?php if ($this->get('model_ttplayer')): ?>
	<?php foreach ($this->get('model_ttplayer') as $row): ?>
		
	<url>
		<loc><?php echo $row['guid'] ?></loc>
		<changefreq>weekly</changefreq>
		<priority>0.7</priority>
	</url>

	<?php endforeach ?>
<?php endif ?>
<?php if ($this->get('model_content_page')): ?>
	<?php foreach ($this->get('model_content_page') as $row): ?>
		
	<url>
		<loc><?php echo $row['guid'] ?></loc>
		<changefreq>weekly</changefreq>
		<priority>0.7</priority>
	</url>

	<?php endforeach ?>
<?php endif ?>
<?php if ($this->get('model_content_press')): ?>
	<?php foreach ($this->get('model_content_press') as $row): ?>
		
	<url>
		<loc><?php echo $row['guid'] ?></loc>
		<changefreq>weekly</changefreq>
		<priority>0.9</priority>
	</url>

	<?php endforeach ?>
<?php endif ?>
<?php if ($this->get('model_content_minutes')): ?>
	<?php foreach ($this->get('model_content_minutes') as $row): ?>
		
	<url>
		<loc><?php echo $row['guid'] ?></loc>
		<changefreq>weekly</changefreq>
		<priority>0.6</priority>
	</url>

	<?php endforeach ?>
<?php endif ?>
<?php if ($this->get('model_content_cup')): ?>
	<?php foreach ($this->get('model_content_cup') as $row): ?>
		
	<url>
		<loc><?php echo $row['guid'] ?></loc>
		<changefreq>weekly</changefreq>
		<priority>0.6</priority>
	</url>

	<?php endforeach ?>
<?php endif ?>

</urlset>