<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php //echo $this->getMeta('title') ?></title>	
	<meta name="keywords" content="<?php echo $this->getMeta('keywords') ?>">
	<meta name="description" content="<?php echo $this->getMeta('description') ?>">
	<meta name="viewport" content="width=device-width">
	<link href="<?php echo $this->url() ?>asset/admin/screen.css?v=1" media="screen, projection" rel="stylesheet" type="text/css" />
	<script src="<?php echo $this->url() ?>js/exclude/modernizr.js"></script>
</head>
<body<?php echo ($this->getBodyClass() ? ' class="' . $this->getBodyClass() . '"' : '') ?> data-url-base="<?php echo $this->url() ?>">
	<!--[if lt IE 7]>
	    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->

<div class="wrap wrap-full-width">

<?php if ($user): ?>
	
    <header class="main clearfix js-header-main">
    	<div class="wrap-full-width">
	    	<div class="inner-title-nav-user clearfix">
		        <a class="header-site-title" href="<?php echo $this->url() ?>" target="_blank" title="Open Homepage"><?php echo $this->getOption('site_title') ?></a>

	<?php require_once($this->pathView('admin/header/_user')) ?>
	<?php require_once($this->pathView('admin/header/_nav')) ?>

			</div>
			<div class="clearfix"></div>
			
	<?php $feedback = $sessionFeedback ?>
	<?php require_once($this->pathView('_feedback')) ?>

		</div>
	</header>

<?php else: ?>

	<div class="content login">

<?php endif ?>
