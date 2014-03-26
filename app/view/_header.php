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

    <div class="container container-site">

<?php if (! $this->isComingSoon()): ?>

<a href="#" class="to-top"><span class="icon"></span>Top</a>
    <header class="container-header row js-container-header">
        <div class="columns four">
    
    <?php require($this->pathView('_logo')) ?>

        </div>
        <div class="columns four">
            <a href="<?php echo $this->url() ?>page/about-me/" class="propos">
                <span class="propos-lettering">á propos de moi</span>
                <span class="propos-flower"><img src="<?php echo $this->url() ?>media/sprite/flower.png" alt="Flower"></span>
            </a>
        </div>
        <div class="columns four">
            <form class="header-search" method="get">
                <label for="search-text" class="label">Search</label>
                <input id="search-text" type="text" name="search" type="text" maxlength="75">
                <a href="#" class="submit button primary">Search</a>
                <input type="submit">
                <span class="icon close"></span>
            </form> 
            <a href="#" class="button tertiary header-button-history">l'histoire complète</a>
        </div>
        <span class="header-button-mobile-menu js-header-button-mobile-menu"></span>
        <div class="clearfix"></div>

    <?php $menu = $mainMenu; ?>
    <?php require($this->pathView('_menu')) ?>

    </header>
    
<?php endif ?>
