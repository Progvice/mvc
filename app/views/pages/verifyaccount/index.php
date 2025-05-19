<div class="section-12 column fourofour center">
    <div class="fof_inner">
        <h2 class="bigtext"><?php echo $msg['title'] ?></h2>
        <p><?php echo isset($msg['desc']) ? $msg['desc'] : ''?></p>
        <?php if (isset($msg['status'])) { ?>

            <a href="/login" class="button"><?php echo LANG['login'] ?></a>

        <?php } ?>
    </div>
</div>
