<?php require_once($this->pathView('admin/_header')); ?>
<?php require_once($this->pathView('_logo')) ?>

	<form method="post">

<?php $feedback = $this->get('session_feedback') ?>
<?php require_once($this->pathView('_feedback')); ?>

        <div class="row">
            <label for="login_email">Email Address</label>
            <input id="login_email" type="text" name="login_email" autofocus="autofocus" value="<?php echo $this->get('session_formfield', 'login_email') ?>">
        </div>
        <div class="row">
            <label for="login_password">Password</label>
            <input id="login_password" type="password" name="login_password">
        </div>
        <div class="row clearfix">
            <input type="submit" name="login">
            <a href="#" class="submit button">Login</a>
        </div>
    </form>
</div>

<?php require_once($this->pathView('admin/_footer')); ?>
