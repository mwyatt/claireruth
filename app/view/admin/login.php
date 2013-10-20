<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>

<?php require_once($this->pathView('admin/header/_resource')); ?>

    </head>
    <body<?php echo ($this->getBodyClass() ? ' class="' . $this->getBodyClass() . '"' : '') ?>>
    	<!--[if lt IE 7]>
    	    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    	<![endif]-->

       	<div class="wrap">
    		<div class="content clearfix">
    			<a class="logo" href="<?php echo $this->url(); ?>" title="Open Homepage"><span>4</span></a>
    			<form method="post">

<?php $feedback = $this->get('session_feedback') ?>
<?php require_once($this->pathView('_feedback')); ?>

                    <div class="row">
                        <label for="login-email">Email Address</label>
                        <input id="login-email" type="text" name="login_email" autofocus="autofocus" value="<?php // echo $this->get('session_formfield', 'login_email') ?>">
                    </div>
                    <div class="row">
                        <label for="login-password">Password</label>
                        <input id="login-password" type="password" name="login_password">
                    </div>
                    <div class="row clearfix">
                        <input type="submit" name="login">
                        <a href="#" class="submit button">Login</a>
                    </div>
                    <div class="row">
                        <p><a href="<?php echo $this->url() ?>admin/recovery/" class="forgot-password">Forgot password?</a></p>
                    </div>
                </form>
    		</div>

<?php require_once($this->pathView('admin/_footer')); ?>
