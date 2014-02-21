<?php require_once($this->getBasePath('_header.php')) ?>

	<h1 style="<?php echo $data['style']['h1'] ?>">Password Recovery</h1>
	<p style="<?php echo $data['style']['p'] ?>">To choose a new password, please click the following link and follow the instructions.</p>
	<p style="<?php echo $data['style']['p'] ?>"><a style="<?php echo $data['style']['a'] ?>" href="<?php echo $this->url() ?>admin/?code=<?php echo $session->get('password_recovery', 'code') ?>"><?php echo $this->url() ?>admin/?code=<?php echo $session->get('password_recovery', 'code') ?></a></p>

<?php require_once($this->getBasePath('_footer.php')) ?>
