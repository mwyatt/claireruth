<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>

<?php require_once($this->pathView() . 'admin/header/_resource.php'); ?>

</head>
<body<?php echo ($this->getBodyClass() ? ' class="' . $this->getBodyClass() . '"' : '') ?> data-url-base="<?php echo $this->url(); ?>">
	<!--[if lt IE 7]>
	    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->

<div class="wrap">
    <header class="main clearfix">
        <a class="title" href="<?php echo $this->url(); ?>" target="_blank" title="Open Homepage"><?php echo $this->get('options', 'site_title'); ?></a>
        
<?php if (array_key_exists('model_user', $this->data)): ?>

		<div class="user">
			<a href="#" class="name"><?php echo ($this->data['model_user']['first_name'] ? $this->data['model_user']['first_name'] . ' ' . $this->data['model_user']['last_name'] : $this->data['model_user']['email']); ?></a>
			<ul>
                <li><a href="<?php echo $this->url() ?>admin/profile/">Profile</a></li>
				<li><a href="?logout=true">Logout</a></li>
			</ul>
		</div>

<?php endif ?>

<?php echo $this->getFeedback(); ?>

<?php $menu = $this->get('model_admin_menu') ?>
<?php require_once($this->pathView('admin/header/_nav')); ?>

	</header>
