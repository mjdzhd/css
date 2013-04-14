<?php $this->load->view('message') ?>
<title><?= $title ?></title>
<div class="kegiatan">
    <script type="text/javascript">
        var data = '';
        $(function() {
 
            $('#tabs').tabs();
            my_ajax('<?= base_url() ?>referensi/data_provinsi','#pro');
        
            $('.pro').click(function(){
                if($('#pro').html()== ''){
                    my_ajax('<?= base_url() ?>referensi/data_provinsi','#pro');
                }
            });
            $('.kab').click(function(){
                if($('#kab').html()== ''){
                    my_ajax('<?= base_url() ?>referensi/data_kabupaten','#kab');
                }
            });
            /*$('.kec').click(function(){
                if($('#kec').html()== ''){
                    my_ajax('<?= base_url() ?>referensi/data_kecamatan','#kec');
                }
            });
            $('.kel').click(function(){
                if($('#kel').html()== ''){
                    my_ajax('<?= base_url() ?>referensi/data_kelurahan','#kel');
                }
            });      */
        
        
        
            $('#kelurahan, .kelurahan').autocomplete("<?= base_url('common/autocomplete?opsi=kelurahan') ?>",
            {
                parse: function(data){
                    var parsed = [];
                    for (var i=0; i < data.length; i++) {
                        parsed[i] = {
                            data: data[i],
                            value: data[i].nama // nama field yang dicari
                        };
                    }
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
                $('.id_pabrik').attr('value',data.id);
            });
        });
    
        function my_ajax(url,element){
            $.ajax({
                url: url,
                dataType: '',
                success: function( response ) {
                    $(element).html(response);
                }
            });
        }
        function paging(page,mytab){
        
            console.log(mytab);
            if(mytab == 1){
            
                get_provinsi_list(page);
            }else if(mytab == 2){
                get_kabupaten_list(page);
            }else if(mytab == 3){
                get_kecamatan_list(page);
            }else if(mytab == 4){
                get_kelurahan_list(page);
            }
       
        }
    </script>
    <h1><?= $title ?></h1>
    <div id="tabs">
        <ul>
            <li><a class="pro" href="#pro">Provinsi</a>  </li>
            <li><a class="kab" href="#kab">Kab. / Kodya</a>  </li>
<!--            <li><a class="kec" href="#kec">Kecamatan</a>  </li>
            <li><a class="kel" href="#kel">Kelurahan</a>  </li>-->
        </ul>

        <div id="pro"></div>
        <div id="kab"></div>
<!--        <div id="kec"></div>
        <div id="kel"></div>-->
    </div>


</div>