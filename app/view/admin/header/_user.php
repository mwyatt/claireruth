<?php if ($user): ?>
    
<div class="nav-user js-hover-addclass nav-main-level1">
    <a class="nav-user-name ellipsis" href="<?php echo $this->url() ?>admin/profile/" class="name js-hover-addclass-trigger"><?php echo ($user->first_name ? $user->first_name . ' ' . $user->last_name : $user->email) ?></a>
    <div class="drop nav-user-drop js-hover-addclass-drop nav-main-level1-drop">
        <a class="nav-user-drop-link nav-main-level2-link" href="<?php echo $this->url() ?>admin/profile/">Profile</a>
        <a class="nav-user-drop-link nav-main-level2-link" href="?logout=yes">Logout</a>
    </div>
</div>

<?php endif ?>
