<?php if ($user): ?>
    
<div class="nav-header-user">
    <a class="nav-header-user-name" href="<?php echo $this->url() ?>admin/profile/" class="name"><?php echo ($user['first_name'] ? $user['first_name'] . ' ' . $user['last_name'] : $user['email']); ?></a>
    <div class="drop nav-header-user-drop">
        <a class="nav-header-user-drop-link" href="<?php echo $this->url() ?>admin/profile/">Profile</a>
        <a class="nav-header-user-drop-link" href="?logout=yes">Logout</a>
    </div>
</div>

<?php endif ?>
