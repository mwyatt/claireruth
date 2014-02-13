<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $this->getMeta('title'); ?></title>   
    <meta name="keywords" content="<?php echo $this->getMeta('keywords'); ?>">
    <meta name="description" content="<?php echo $this->getMeta('description'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="<?php echo $this->url(); ?>css/screen.css?v=2" media="screen, projection" rel="stylesheet" type="text/css" />
    <!-- // <script src="<?php echo $this->url(); ?>js/vendor/respond.min.js"></script> -->
    <script src="<?php echo $this->url(); ?>js/exclude/modernizr.js"></script>
</head>
<body<?php echo ($this->getBodyClass() ? ' class="' . $this->getBodyClass() . '"' : '') ?> data-url-base="<?php echo $this->url(); ?>">
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

<?php if (! $this->comingSoon): ?>
    
    <a href="#" class="to-top"><span class="icon"></span>Top</a>
    <div class="wrap">
        <header class="main clearfix">
            <a href="<?php echo $this->url() ?>page/about-me/" class="propos">
                <span class="propos-lettering">á propos de moi</span>
                <span class="propos-flower"><img src="<?php echo $this->url() ?>media/sprite/flower.png" alt="Flower"></span>
            </a>

    <?php require_once($this->pathView('_logo')) ?>

            <form class="search" method="get">
                <label for="search-text" class="label">Search</label>
                <input id="search-text" type="text" name="search" type="text" maxlength="75">
                <a href="#" class="submit button primary">Search</a>
                <input type="submit">
                <span class="icon close"></span>
            </form> 
            <a href="#" class="button tertiary">l'histoire complète</a>
        </header>
        
<?php endif ?>
