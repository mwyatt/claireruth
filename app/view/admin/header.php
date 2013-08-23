<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>

<?php require_once($this->pathView() . 'admin/header-resources.php'); ?>

    </head>
    <body data-url-base="<?php echo $this->urlHome(); ?>">
    	<!--[if lt IE 7]>
    	    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    	<![endif]-->

    <div class="wrap">
        <header class="main clearfix">
            <a class="title" href="<?php echo $this->urlHome(); ?>" target="_blank" title="Open Homepage"><?php echo $this->get('options', 'site_title'); ?></a>
            
<?php if (array_key_exists('model_mainuser', $this->data)): ?>

			<div class="user">
				<a href="#" class="name"><?php echo ($this->data['model_mainuser']['first_name'] ? $this->data['model_mainuser']['first_name'] . ' ' . $this->data['model_mainuser']['last_name'] : $this->data['model_mainuser']['email']); ?></a>
				<ul>
                    <li><a href="<?php echo $this->urlHome() ?>admin/profile/">Profile</a></li>
					<li><a href="?logout=true">Logout</a></li>
				</ul>
			</div>

<?php endif ?>

<?php if ($this->get('model_mainmenu', 'admin')): ?>
    
            <nav class="main">
                <ul>

    <?php foreach ($this->get('model_mainmenu', 'admin') as $item): ?>

                    <li<?php echo ($this->get($item, 'current') ? ' class="current"' : '') ?>><a class="button" href="<?php echo $this->get($item, 'guid') ?>"><?php echo $this->get($item, 'name') ?></a></li>
        
    <?php endforeach ?>

                </ul>
            </nav>

<?php endif ?>
<?php if ($this->get('model_mainmenu', 'admin_sub')): ?>
    
            <nav class="sub">
                <ul>

    <?php foreach ($this->get('model_mainmenu', 'admin_sub') as $item): ?>

                    <li<?php echo ($this->get($item, 'current') ? ' class="current"' : '') ?>><a href="<?php echo $this->get($item, 'guid') ?>"><?php echo $this->get($item, 'name') ?></a></li>
        
    <?php endforeach ?>

                </ul>
            </nav>

<?php endif ?>
<?php echo $this->getFeedback(); ?>

		</header>
        