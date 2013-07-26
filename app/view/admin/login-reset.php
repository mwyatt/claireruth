<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>

<?php require_once($this->pathView() . 'admin/header-resources.php'); ?>

    </head>
    <body>
    	<!--[if lt IE 7]>
    	    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    	<![endif]-->

       	<div class="wrap">
    		<div class="content login clearfix">
    			<form method="post">
    				<input type="hidden" name="form_login_reset" value="true">

<?php echo $this->getFeedback(); ?>

                    <div class="row">
                        <label for="password">New password</label>
                        <input id="password" type="password" name="password">
                    </div>
                    <div class="row">
                        <input type="submit">
                        <a href="#" class="submit button">Change</a>
                    </div>
                </form>
    		</div>
            <script src="<?php echo $this->urlHome(); ?>js/vendor/jquery-1.8.2.min.js"></script>
            <script src="<?php echo $this->urlHome(); ?>js/admin/main.js"></script>
    	</div>
    </body>
</html>