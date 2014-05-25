<?php foreach ($item->children as $item): ?>

    <div class="nav-main">
        <a href="<?php echo $this->url('admin') . $item->url ?>" class="nav-main-link"><?php echo $item->name ?></a>

    <?php if ($item->children): ?>

        <div class="nav-main-drop">

        <?php include($this->pathView('admin/header/_nav-children')) ?>

        </div>

    <?php endif ?>
    
    </div>
    
<?php endforeach ?>
