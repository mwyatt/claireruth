<?php if ($menu): ?>
    <?php $item = new StdClass() ?>
    <?php $item->children = $menu; ?>
    
<nav class="nav-main-container">

    <?php include($this->pathView('admin/header/_nav-children')) ?>

</nav>

<?php endif ?>
