<?php if ($menu): ?>
    
<nav class="nav-header-container">

    <?php foreach ($menu as $level1): ?>

        <div class="nav-header-level1<?php echo ($level1['current'] ? ' is-current' : '') ?>">
            <a href="<?php echo $level1['url'] ?>" class="nav-header-level1-link"><?php echo $level1['title'] ?></a>

        <?php if (array_key_exists('children', $level1)): ?>

            <div class="nav-header-level1-drop">

            <?php foreach ($level1['children'] as $level2): ?>

                <a href="<?php echo $level2['url'] ?>" class="nav-header-level2-link"><?php echo $level2['title'] ?></a>
                
            <?php endforeach ?>

            </div>

        <?php endif ?>
        
        </div>
        
    <?php endforeach ?>

</nav>

<?php endif ?>
