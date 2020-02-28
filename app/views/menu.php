<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 28/10/2018
 * Time: 15:49
 */
?>

<ul class="menu">
    <?php
    $i = 1;

    for ($i = 1; $i < 5; $i++) {
        $k = $i * 2;
        if ($i == 1) {
            ?>
            <li class="menu-item active"><a href="#">Menu <?php echo $i?></a> <span class="main-arrow"></span>
            <?php
        }else {
            ?>
            <li class="menu-item"><a href="#">Menu <?php echo $i?></a>
            <?php
        }
        ?>
                <ul class="submenu">
                    <?php for ($j = 1; $j < $k; $j++) { ?>
                        <li><a href="#">Sub menu<?php echo $j ?></a></li>
                    <?php }?>
                </ul>
            </li>
        <?php
    }
    ?>
</ul>
