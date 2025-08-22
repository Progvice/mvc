<div class="section-12 height-50 wrap detailbox_container">
    <?php echo $template->load(['name' => 'Actionmenu', 'data' => ['title' => LANG['personelsystem']]]) ?>
    <?php echo $template->load(['name' => 'PersonnelList', 'data' => $personnels]) ?>
</div>