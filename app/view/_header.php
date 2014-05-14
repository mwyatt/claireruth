<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $this->getMeta('title') ?></title>   
    <meta name="keywords" content="<?php echo $this->getMeta('keywords') ?>">
    <meta name="description" content="<?php echo $this->getMeta('description') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="<?php echo $this->url() ?>asset/screen.css?v=1" media="screen, projection" rel="stylesheet" type="text/css" />
    <!-- // <script src="<?php echo $this->url() ?>js/vendor/respond.min.js"></script> -->
    <script src="<?php echo $this->url() ?>js/exclude/modernizr.js"></script>
</head>
<body<?php echo ($this->getBodyClass() ? ' class="' . $this->getBodyClass() . '"' : '') ?> data-url-base="<?php echo $this->url() ?>">
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

<div id="top"></div>
<div class="container container-site">
    <a href="#top" class="to-top js-smooth-scroll"><?php include($this->getPath('media/arrow-up.svg')) ?></a>
    <header class="container-header row js-container-header clearfix js-fixed-bar">
        <div class="container-header-inner">
    
    <?php include($this->pathView('_logo')) ?>

    <!--             <a href="<?php echo $this->url() ?>page/about-me/" class="propos">
                    <span class="propos-lettering">á propos de moi</span>
                    <span class="propos-flower"><img src="<?php echo $this->url() ?>media/sprite/flower.png" alt="Flower"></span>
                </a> 

                <a href="#" class="button tertiary header-button-history">l'histoire complète</a>
            -->


            <a href="#menu" class="js-smooth-scroll header-button-mobile header-button-mobile-menu js-header-button-mobile-menu"><?php include($this->getPath('media/menu.svg')) ?></a>
            <a href="#search" class="js-smooth-scroll header-button-mobile header-button-mobile-search js-header-button-mobile-search"><?php include($this->getPath('media/search.svg')) ?></a>

    <?php $menu = $mainMenu; ?>
    <?php include($this->pathView('_menu')) ?>
    <?php include($this->pathView('_search')) ?>

        </div>
    </header>
