<div class="plugins_listing">
    <div class="section-12 titles">
        <div class="section-3">Plugin author</div>
        <div class="section-3">Plugin name</div>
        <div class="section-3">Plugin version</div>
    </div>
<?php
foreach($plugins as $plugin){
$string = <<<PLUGIN
<div class="section-12">
        <div class="section-3">{$plugin[0]}</div>
        <div class="section-3">{$plugin[1]}</div>
        <div class="section-3">{$plugin[2]}</div>
    </div>
PLUGIN;
echo $string;
}
?>
</div>    