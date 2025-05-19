<div class="main section-12 column height-100">
    <div class="admin_infobox admin_templates section-12 column center uploadarea">
        <h1>Lisää kuva galleriaan <?php echo urldecode($params[0]); ?></h1>
        <p>Tällä sivulla voit lisätä kuvia valitsemaasi galleriaan.</p>
        <div ondrop="handleDrop(event);" ondragover="handleDrag(event);" id="dropsite" class="dropsite">
            <label for="fileinput" class="fullsize"></label>
            <div class="icon"><i class="fas fa-file-upload"></i></div>
            <p id="changetext">Klikkaa tästä tai vedä kuvatiedosto tähän</p>
            <p>Tiedoston maksimikoko <?php echo ini_get('upload_max_filesize'); ?>B</p>
        </div>
        <form id="imgsubmit" class="section-6 column center">
            <input accept="image/*" id="fileinput" type="file"/>
            <input id="imgname" type="text" name="imgname" placeholder="Kuvan nimi"/>
            <input id="imgdesc" type="text" name="description" placeholder="Kuvaile kuvaa muutamalla sanalla"/>
            <button id="submitbtn" class="admin_info_btn" type="submit">Lähetä kuva</button>
        </form>
    </div>
</div>
<script>
        var file;
        const PHP_MAX_FILE_SIZE = parseInt('<?php echo ini_get('upload_max_filesize'); ?>');
        const initialText = "Klikkaa tästä tai vedä kuvatiedosto tähän <?php echo ini_get('upload_max_filesize'); ?>B";
        function handleDrop(event) {
            event.preventDefault();
            if (event.dataTransfer.files.length > 1) {
                toastr.warning("Voit pudottaa vain yhden tiedoston kerrallaan!");
                return;
            }
            let filesize = Number.parseFloat(event.dataTransfer.files[0].size / (1024 * 1024)).toFixed(2);
            if (filesize >= PHP_MAX_FILE_SIZE) {
                toastr.warning('Tiedosto on liian suuri!');
                return;
            }
            $("#changetext").text(event.dataTransfer.files[0].name + ' ' + filesize + 'MB');
            file = event.dataTransfer.files[0];
            console.log(file);
        }
        function handleDrag(event) {
            event.preventDefault();
        }
        $(document).ready(function() {
            $("#fileinput").change(function(e) {
                e.preventDefault();
                if (event.target.files.length > 1) {
                    toastr.warning("Voit ladata ainoastaan yhden tiedoston!");
                    return;
                }
                let filesize = Number.parseFloat(event.target.files[0].size / (1024 * 1024)).toFixed(2);
                if (filesize >= PHP_MAX_FILE_SIZE) {
                    toastr.warning('Tiedosto on liian suuri!');
                    return;
                }
                $("#changetext").text(event.target.files[0].name + ' ' + filesize + 'MB');
                file = e.target.files[0];
            });
            $("#imgsubmit").submit(function(e) {
                e.preventDefault();
                let name = $("#imgname").val();
                let desc = $("#imgdesc").val();
                const formData = new FormData();
                formData.append('file', file);
                formData.append('gname', '<?php echo urldecode($params[0]); ?>');
                formData.append('description', desc);
                formData.append('name', name);
                let loadingInterval;
                $.ajax({
                    url: '/admin/galleria/upload',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#submitbtn").attr('disabled', true);
                        let dots = '';
                        loadingInterval = setInterval(() => {
                            if (dots.length > 2) {
                                dots = '';
                            }
                            dots = dots + '.';
                            $("#submitbtn").text('Lähetetään' + dots);
                        }, 500);
                    },
                    success: function(data) {
                        $("#submitbtn").attr('disabled', false);
                        if (!data.status) {
                            toastr.warning(data.msg)
                            return;
                        }
                        toastr.success(data.msg);
                        file = null;
                        $("#imgname").val("");
                        $("#imgdesc").val("");
                        $("#changetext").text(initialText);
                    },
                    complete: function() {
                        clearInterval(loadingInterval);
                        $("#submitbtn").text('Lähetä kuva');
                    },  
                    error: function(xhr, status, error) {

                    }
                })
            }); 
        });
</script>