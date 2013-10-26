<?php if ($menu): ?>
    
<nav class="nav-main-container">

    <?php foreach ($menu as $level1): ?>

        <div class="nav-main-level1<?php echo ($level1['current'] ? ' is-current' : '') ?>">
            <a href="<?php echo $level1['url'] ?>" class="nav-main-level1-link"><?php echo $level1['title'] ?></a>

        <?php if ($level1['children']): ?>

            <div class="nav-main-level1-drop">

            <?php foreach ($level1['children'] as $level2): ?>

                <a href="<?php echo $level2['url'] ?>" class="nav-main-level2-link"><?php echo $level2['title'] ?></a>
                
            <?php endforeach ?>

            </div>

        <?php endif ?>
        
        </div>
        
    <?php endforeach ?>

</nav>

<?php endif ?>
