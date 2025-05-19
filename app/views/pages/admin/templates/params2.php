<div class="main section-12 row wrap">
    <div class="admin_infobox">
        <a href="javascript:history.go(-1)" class="admin_info_btn">Edellinen sivu</a>
    <?php 
        //var_dump($paramsUri);
        $url = implode('/', $paramsUri);
        $params = explode('data', $url);
        $params[1] = str_replace('/', '', $params[1]);
        $urlarr = explode('_', $params[0]);
        $urlfinal = '/';
        $urlfinal .= implode('/', $urlarr);
        if (!file_exists(DATA_PATH . $urlfinal . '/index.json')) {
            echo '<h2>This site data does not exist.';
            return;
        }
        $url_data_raw = file_get_contents(DATA_PATH . $urlfinal . '/index.json');
        $url_data_json = json_decode($url_data_raw);
        $hasblock = false;
        $block_data_final = new stdClass();
        $fblock_name = '';
        foreach ($url_data_json as $blockname => $blockdata) {
            if ($blockname === $params[1]) {
                $fblock_name = $blockname;
                $block_data_final = $blockdata;
                $hasblock = true;
                break;
            }
        }
        
        if (!$hasblock) {
            echo '<h2>This block does not exist!';
            return;
        }
        $counter = 0;
        $vcounter = 1;
        $html = '';
        foreach ($block_data_final->data as $column) {
            $inputfields = '';
            foreach ($column->data as $objname => $datafield) {
                foreach ($datafield as $name => $value) {
                    $string = $objname . '_' . $name;
                    $title = '';
                    if (isset(LANG['admin'][$string])) {
                        $title = LANG['admin'][$string];
                    }
                    else {
                        $title = $string;
                    }
                    if (is_object($value)) {
                        foreach ($value as $svaluefield => $svalue) {
                            $inputfields .= <<<EOS
                                <p>{$title}</p>
                                <input data-arrpos="{$counter}" data-subarr="true" data-subobjfield="{$svaluefield}" data-objname="{$objname}" data-objfield="{$name}" type="text" value="{$svalue}"/>
                            EOS;
                        }
                        continue;
                    }

                    $inputfields .= <<<EOS
                    <p>{$title}</p>
                    <input data-arrpos="{$counter}" data-objname="{$objname}" data-objfield="{$name}" type="text" value="{$value}"/>
                    EOS;
                    if ($objname === 'img' && $name === 'href') {
                        $inputfields .= <<<EOS
                            <a class="pickImg" href="#" data-arrpos="{$counter}" data-objname="{$objname}" data-objfield="{$name}">Valitse kuva</a>
                        EOS;
                    }
                    if ($objname === 'bgimg' && $name === 'href') {
                        $inputfields .= <<<EOS
                            <a class="pickImg" href="#" data-arrpos="{$counter}" data-objname="{$objname}" data-objfield="{$name}">Valitse kuva</a>
                        EOS;
                    }
                    /*$inputfields .= '<input type="text" value="' . $value . '"placeholder=""/>';*/
                }
            }
            $save_text = LANG['save'];
            $html .= <<<EOS
            <form class="admin_templates">
                <h1>Laatikko {$vcounter}</h1>
                {$inputfields}
                <button data-arrpos="{$counter}" class="savetemplate">{$save_text}</button>
            </form>
            EOS;
            $counter = $counter + 1;
            $vcounter = $vcounter + 1;
        }
        echo <<<EOS
            <div class="admin_container section-12">
                {$html}
            </div>
        EOS;

        ?>
        <script>
        $(document).ready(function(){
            let block_name = "<?php echo $fblock_name; ?>";
            let url = "<?php echo $urlfinal; ?>";
            let url_data_raw = <?php echo $url_data_raw; ?>;
            $(".savetemplate").click(function(e){
                e.preventDefault();
                let = arrPos = $(this).data('arrpos');
                let siblingElements = $(this).siblings("input");
                for(let counter = 0; counter < siblingElements.length; counter++) {
                    let subArr;
                    let objname = siblingElements[counter].dataset.objname;
                    let objfield = siblingElements[counter].dataset.objfield;
                    let objvalue = siblingElements[counter].value;
                    
                    if (siblingElements[counter].dataset.subarr) {
                        let subobjfield = siblingElements[counter].dataset.subobjfield;
                        url_data_raw[block_name]['data'][arrPos]['data'][objname][objfield][subobjfield] = objvalue;
                    }
                    else {
                        url_data_raw[block_name]['data'][arrPos]['data'][objname][objfield] = objvalue;
                    }
                }
                
                let data = {
                    url: url,
                    data: url_data_raw
                };
                let final_data = JSON.stringify(data);
                $.ajax({
                    url: "/templates/update",
                    contentType: "application/json",
                    type: "POST",
                    data: final_data
                }).done((res) => {
                    console.log(res);
                    switch(res.status) {
                        case 'success':
                            toastr.success(res.msg);
                        break;
                        case 'error':
                            toastr.error(res.msg);
                        break;
                        case 'warning':
                            toastr.warning(res.msg);
                        break;
                        default:
                            toastr.warning("Unknown error");
                        break;
                    }
                });
            });
        });
        </script>
    </div>
</div>
<div class="img_selector">
    <div class="is_inner">
        <h1>Valitse kuva</h1>
        <a href="#" class="close_img_selector"><i class="fas fa-times"></i></a>
        <p style="text-align: center;">Voit myös lisätä kuvan klikkaamalla alla olevasta napista</p>
        <div class="section-12 row center">
            <a href="/admin/galleria/addimg/galleria" target="_blank">Lisää kuva</a>
            <a href="#" id="refreshImg">Päivitä kuvat</a>
        </div>
        <div class="isn_imgs"></div>
    </div>
</div>
<script>
    $(document).ready(() => {
        $(".pickImg, #refreshImg").click((e) => {
            $("body").css('overflow', 'hidden');
            e.preventDefault();
            const arrpos = e.target.getAttribute('data-arrpos');
            const objname = e.target.getAttribute('data-objname');
            const objfield = e.target.getAttribute('data-objfield');
            const objvalue = e.target.getAttribute('value');
            $("#refreshImg").attr('data-arrpos', arrpos);
            $("#refreshImg").attr('data-objname', objname);
            $("#refreshImg").attr('data-objfield', objfield);
            $("#refreshImg").attr('data-objvalue', objvalue);
            $.ajax({
                method: 'GET',
                url: '/admin/galleria/list'
            }).done((res) => {
                if (res.status) {
                    $(".isn_imgs").empty();
                    $(".img_selector").css('display', 'flex');
                    res.data.foreach (img => {
                        const html = `
                            <div class="is_img">
                                <img src="${img.imgpath}"/>
                                <a data-url="${img.imgpath}" data-arrpos="${arrpos}" data-objname="${objname}" data-objfield="${objfield}" data-objvalue="${objvalue}" class="chooseimg" href="javascript:;">Valitse kuva</a>
                            </div>
                        `;
                        $(".isn_imgs").append(html);
                    });
                }
            });
        });
        $(".close_img_selector").click((e) => {
            $("body").removeAttr('style');
            const scrollPos = $(window).scrollTop();
            console.log(scrollPos);
            e.preventDefault();
            e.stopPropagation();
            $(".img_selector").css('display', 'none');
            $(window).scrollTop(scrollPos);
            return false;
        });
        $("body").on('click', '.chooseimg', (e) => {
            const arrpos = e.target.getAttribute('data-arrpos');
            const objname = e.target.getAttribute('data-objname');
            const objfield = e.target.getAttribute('data-objfield');
            const objvalue = e.target.getAttribute('value');
            const url = e.target.getAttribute('data-url');
            const elem = $("input").filter(`[data-arrpos="${arrpos}"][data-objname="${objname}"][data-objfield="${objfield}"]`);
            if (elem.length === 1) {
                elem[0].setAttribute('value', url);
                $(".img_selector").css('display', 'none');
                toastr.success('Kuva muutettu onnistuneesti');
                $("body").removeAttr('style');
                return;
            }
            toastr.warning('Kuvaa ei asetettu');
        });
    });

</script>