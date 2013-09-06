<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>

<?php require_once($this->pathView() . 'header-resources.php'); ?>

    </head>
    <body<?php echo ($this->getBodyClass() ? ' class="' . $this->getBodyClass() . '"' : '') ?> data-url-base="<?php echo $this->urlHome(); ?>">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        
        <a href="#" class="top"><span class="icon"></span>Top</a>
        <div class="wrap">
            <header class="main">
                <a href="<?php echo $this->urlHome() ?>page/contact-us/" class="button contact-us">Contact us</a>
                <a class="logo" href="<?php echo $this->urlHome(); ?>" title="<?php echo $this->get('options', 'site_title'); ?> Logo">

<?php include(BASE_PATH . 'media/logo.svg'); ?>

                </a>
                <form class="search" method="get">
                    <label for="search-text" class="label">Search</label>
                    <input id="search-text" type="text" name="search" type="text" maxlength="75">
                    <a href="#" class="submit button primary">Search</a>
                    <input type="submit">
                    <span class="icon close"></span>
                </form> 
                <a href="#" class="button secondary">l'histoire complète</a>
                <nav class="sub">
                    <label>Menu</label>
                    <div class="inner">
                        <span class="close"></span>
                        <a href="<?php echo $this->urlHome(); ?>page/coaching/">Coaching</a>
                        <a href="<?php echo $this->urlHome(); ?>page/schools/">Schools</a>
                        <a href="<?php echo $this->urlHome(); ?>page/town-teams/">Town Teams</a>
                        <a href="<?php echo $this->urlHome(); ?>page/summer-league/">Summer League</a>
                        <a href="<?php echo $this->urlHome(); ?>page/local-clubs/">Local Clubs</a>
                    </div>
                </nav> 
            </header>
