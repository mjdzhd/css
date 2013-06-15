<script type="text/javascript">
    var request;
    $(function(){
        $('#keys').watermark('Search ...');
        $( "#addnewrow" ).button({icons: {primary: "ui-icon-newwin"}});
        $('input[type=submit]').each(function(){ $(this).replaceWith('<button type="' + $(this).attr('type') + '" name="'+$(this).attr('name')+'" id="'+$(this).attr('id')+'">' + $(this).val() + '</button>');});
        $('button[type=submit]').button({icons: {primary: 'ui-icon-circle-check'}});
        $('.resetan').button({icons: {primary: 'ui-icon-folder-open'}});
        $('#bt_carinon').button({icons: {primary: 'ui-icon-search'}});
        get_nonobat_list(1,'null');
        $('#form_non').dialog({
            autoOpen: false,
            height: 260,
            width: 400,
            modal: true,
            resizable : false,
            buttons: {
                "Simpan": function() {
                    save_barang_non();
                },
                "Batal": function() {
                    $(this).dialog('close');
                }
            },
            close : function(){
                reset_all();
            },
            open : function(){
                
            }
        });        
        $('#carinonobat').dialog({
            autoOpen: false,
            height: 200,
            title :'Form Pencarian Barang Non Obat',
            width: 400,
            modal: true,
            buttons: {
                "Cari": function() {
                    $('#form_carinon').submit();
                },
                "Batal": function() {
                    reset_all();
                    $(this).dialog('close');
                }
            },
            close : function(){
                reset_all();
            }
        });
        $('#konfirmasi_brg').dialog({
            autoOpen: false,
            title :'Konfirmasi',
            height: 200,
            width: 300,
            modal: true,
            resizable : false,
            close : function(){
                
            },
            open : function(){
                
            },
            buttons: [ 
                { text: "Ok", click: function() { 
                        save_barang_non();
                        $( this ).dialog( "close" ); 
                    } 
                }, 
                { text: "Batal", click: function() { 
                        $( this ).dialog( "close" ); 
                    } 
                } 
            ]
        });

        
        $('#showAll').click(function(){
            get_nonobat_list(1,'null');
        });
     
        $('#bt_carinon').click(function(){
            $('#carinonobat').dialog("open");
            $('#nama_cari').focus();
        });
        $('#resetkab').click(function(){
            reset_all();
        });
        $('#reset').click(function(){
            reset_all();
        });
        
        $('#addnewrow').click(function() {
            $('input[name=tipe]').val('add');
            $('#form_non').dialog("option",  "title", "Form Barang Non Obat");
            $('#form_non').dialog("open");
            $('.dinamis').hide();
            
        });
        $('#keys').live('keyup', function(e) {
            if (e.keyCode === 13) {
                $.ajax({
                    type : 'GET',
                    url: '<?= base_url('referensi/manage_barang_non') ?>/search',
                    data: 'search='+$('#keys').val(),
                    cache: false,
                    success: function(data) {
                        $('#obat_list').html(data);
                    }
                });
            }
        });
        $('.pabrik').autocomplete("<?= base_url('inv_autocomplete/load_data_pabrik') ?>",
        {
            parse: function(data){
                var parsed = [];
                for (var i=0; i < data.length; i++) {
                    parsed[i] = {
                        data: data[i],
                        value: data[i].nama // nama field yang dicari
                    };
                }
                $('input[name=id_pabrik]').val('');
                $('input[name=id_pabriks]').val('');
                return parsed;
            },
            formatItem: function(data,i,max){
                var str = '<div class=result>'+data.nama+'</div>';
                return str;
            },
            width: 320, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
            dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
        }).result(
        function(event,data,formated){
            $(this).attr('value',data.nama);
            $('input[name=id_pabrik]').val(data.id);
            $('input[name=id_pabriks]').val(data.id);
        });
        
        
        $('#form_carinon').submit(function(){
            var Url = '<?= base_url('referensi/manage_barang_non') ?>/search/';         
            
            if($('#nama_cari').val()===''){
                $('#msg_carinon').fadeIn('fast').html('Nama barang tidak boleh kosong !');
                $('#nama_cari').focus();
                return false;
            }else{    
                if(!request) {
                    request =  $.ajax({
                        type : 'POST',
                        url: Url+$('.noblock').html(),               
                        data: $(this).serialize(),
                        cache: false,
                        success: function(data) {
                            $('#non_list').html(data);                           
                            $('#carinonobat').dialog("close");
                            reset_all(); 
                            request = null;                            
                        }
                    });
                }
                return false;
            }
            return false;
        });
        
        $('#formnon').submit(function(){
            var nama_brg = $('#nama_brg').val()
            if(nama_brg ==''){
                $('.msg').fadeIn('fast').html('Nama barang tidak boleh kosong !');
                $('#nama_brg').focus();
                return false;
            } else if($('#kategori').val()==''){
                $('.msg').fadeIn('fast').html('kategori harus dipilih !');
                $('#kategori').focus();
                return false;
            }else{  
                $('.msg').fadeOut('fast');
                $.ajax({
                    url: '<?= base_url('referensi/manage_barang_non') ?>/cek',
                    data:'nama='+nama_brg,
                    cache: false,
                    dataType: 'json',
                    success: function(msg_non){
                       
                        if (!msg_non.status){
                            $('#text_konfirmasi_brg').html('Nama barang <b>"'+nama_brg+'"</b> sudah ada<br/> Apakah anda yakin akan menambahkannya lagi?');            
                        } else {
                            $('#text_konfirmasi_brg').html('Nama Obat <b>"'+nama_brg+'"</b> <br/> Apakah anda akan menyimpan data?'); 
                           
                        }
                        $('#konfirmasi_brg').dialog("open");
                    }
                }); 
           
            }
            return false;
        });
        
    });
    
    function save_barang_non(){
        var Url = '';       
        var tipe = $('input[name=tipe]').val();
        if(tipe === 'edit') {
            Url = '<?= base_url('referensi/manage_barang_non') ?>/edit/';
        } else {
            Url = '<?= base_url('referensi/manage_barang_non') ?>/add/';
        }
        if(!request) {
            request =  $.ajax({
                type : 'POST',
                url: Url+$('.noblock').html(),               
                data: $('#formnon').serialize(),
                cache: false,
                success: function(data) {
                    $('#non_list').html(data);
                    $('#form_non').dialog("close");
                    if(tipe === 'edit'){
                        alert_edit();
                    }else{
                        alert_tambah();
                    }
                    $('#form_non').dialog("close");
                    reset_all(); 
                    request = null;                            
                }
            });
        }          
    }
    
    
    function reset_all(){
        $('#msg_non').fadeOut('fast');
        $('#msg_carinon').fadeOut('fast');
        $('#nama_brg').val('');
        $('#nama_cari').val('');
        $('#kategori').val('');
        $('input[name=id_pabrik]').val('');
        $('#id_barang').val('');
        $('.pabrik').val('');
    }
    
    function get_nonobat_list(p,search){
        $.ajax({
            type : 'GET',
            url: '<?= base_url('referensi/manage_barang_non') ?>/list/'+p,
            data :'search='+search,
            cache: false,
            success: function(data) {
                $('#non_list').html(data);
                reset_all();
            }
        });
    }
    
    function delete_non(id){
        var del = confirm("Anda yakin akan menghapus data ini ?");
        if(del){
            $.ajax({
                type : 'GET',
                url: '<?= base_url('referensi/manage_barang_non') ?>/delete/'+$('.noblock').html(),
                data :'id='+id,
                cache: false,
                success: function(data) {
                    $('#non_list').html(data);
                    alert_delete();
                }
            });
        }
    }
    
    function edit_non(arr){
        var data = arr.split("#");
        $('input[name=id_barang]').val(data[0]);
        $('#nama_brg').val(data[1]);
        $('#kategori').val(data[2]);
        $('.pabrik').val(data[4]);
        $('input[name=id_pabrik]').val(data[3]);
        $('#hna_nb').val(numberToCurrency(data[5]));
        $('#b_konsinyasi').removeAttr('checked');
        if (data[6] === '1') {
            $('#b_konsinyasi').attr('checked','checked');
        }
        $('#stok_min').val(data[7]);
        $('#savebarang').removeAttr('disabled');
         
        $('input[name=tipe]').val('edit');
        $('#form_non').dialog("option",  "title", "Edit Data Master Barang Non Obat");
        $('#form_non').dialog("open");
    }
</script>

    <?= form_button('', 'Tambah Data', 'id=addnewrow style="margin-left: 2px;"') ?>
    <?= form_button('', 'Reset', 'class=resetan id=showAll style="margin-left: 0px;"') ?>  
    <div style="margin-bottom: 2px; float: right;"><?= form_input('barang_cari', null, 'id=keys size=10 style="padding: 4px 5px 5px 5px; min-width: 200px;"') ?></div>
    <br/><br/>
<div id="form_non" style="display: none;position: static; background: #fff; padding: 10px;">
    <div class="msg" id="msg_non"></div>
    <?= form_open('', 'id=formnon') ?>
    <?= form_hidden('tipe') ?>
    <?= form_hidden('id_barang', '', 'id=id_barang') ?>
    <table width="100%">
        <tr>
            <td width="20%" align="right">Nama:</td>
            <td><?= form_input('nama', '', 'id=nama_brg class=nama size=40') ?> </td>
        </tr>
        <tr>
            <td width="20%" align="right">Kategori:</td>
            <td><?= form_dropdown('kategori', $kategori, null, 'id=kategori') ?></td>
        </tr>
        <tr>
            <td width="20%" align="right">Pabrik:</td>
            <td>
                <?= form_input('pabrikbarang', '', 'class=pabrik size=40') ?>
                <?= form_hidden('id_pabrik') ?>
            </td>
        </tr>
        <tr>
            <td align="right">HNA (Rp.)</td>
            <td><?= form_input('hna_nb', null, 'id=hna_nb onkeyup=FormNum(this)') ?></td>
        </tr>
        <tr>
            <td align="right">Konsinyasi?:</td>
            <td><?= form_checkbox('b_konsinyasi', '1', FALSE, 'id=b_konsinyasi') ?></td>
        </tr>
        <tr>
            <td align="right">Stok Minimal</td>
            <td><?= form_input('stok_min', null, 'id=stok_min onkeyup=FormNum(this)') ?></td>
        </tr>
    </table>
    <?= form_close() ?>
</div>

<div id="konfirmasi_brg" style="padding: 20px;">
    <div id="text_konfirmasi_brg"></div>
</div>

<div id="non_list" class="data-list"></div>


