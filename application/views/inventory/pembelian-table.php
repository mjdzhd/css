<?php
$key = 0;
foreach ($list_data as $no => $rows) { ?>
    <tr class="tr_row">
        <td align="center"><?= ++$no ?> <input type="hidden" name="cur_hna[]" id="cur_hna<?= $key ?>" value="<?= $rows->hna ?>" /></td>
        <td><?= form_input('batch[]', NULL, 'size=10') ?></td>
        <td><input type=text name=pb[] id="pb<?= $key ?>" style="width: 100%" value="<?= $rows->barang ?> <?= ($rows->kekuatan == '1')?'':$rows->kekuatan ?> <?= $rows->satuan ?> <?= $rows->sediaan ?> <?= (($rows->generik == 'Non Generik')?'':$rows->pabrik) ?> @ <?= ($rows->isi=='1')?'':$rows->isi ?> <?= $rows->satuan_terkecil ?>" class=pb />
        <input type=hidden name=id_pb[] id="id_pb<?= $key ?>" value="<?= $rows->barang_packing_id ?>" class=pb />
        </td>
        <td><input type=text name=ed[] id="ed<?= $key ?>" size=8 value="<?= datefrompg($rows->ed) ?>" class=ed /></td>
        <td><input type=text name=jml[] id="jml<?= $key ?>" size=2 value="<?= $rows->masuk ?>" class=jml onblur="jmlSubTotal(<?= $key ?>);" /></td>
        <td><input type=text name=harga[] id="harga<?= $key ?>" size=6 value="" onblur="jmlSubTotal(<?= $key ?>);" class=harga /></td>
        <td><?= form_hidden('barang_id[]', $rows->barang_id) ?>
        <select style="border: 1px solid #ccc;" name="kemasan[]" id="kemasan<?= $key ?>"><option value="">Pilih kemasan ...</option>
            <?php $array_kemasan = $this->m_inventory->get_kemasan_by_barang($rows->barang_id); 
            foreach ($array_kemasan as $rowA) { ?>
                <option value="<?= $rowA->isi ?>-<?= $rowA->id ?>"><?= $rowA->nama ?></option>
            <?php } ?>
        </select></td>
        <td><input type=text name=isi[] readonly="readonly" id="isi<?= $key ?>" size=2 value="" class="isi" /></td>
        <td><input type=text name=diskon_pr[] id="diskon_pr<?= $key ?>" size=2 value="<?= $rows->diskon_supplier ?>" class=diskon_pr onkeyup="jmlSubTotal(<?= $key ?>);" /></td>
        <td><input type=text name=diskon_rp[] id="diskon_rp<?= $key ?>" size=6 value="<?= $rows->beli_diskon_rupiah ?>" onkeyup="FormNum(this);" onblur="jmlSubTotal(<?= $key ?>);" class=diskon_rp />
        <input type=hidden name=subtotal[] id="subttl<?= $key ?>" class="subttl" />
        </td>
        <td id="subtotal<?= $key ?>" align="right"></td>
        <td align=center>-</td>
        <td class=aksi>
            <input type="hidden" name="status[]" value="Ya" id="status<?= $key ?>" />
            <span class=delete onclick=eliminate(this);><?= img('assets/images/icons/delete.png') ?></span></td>
    </tr>
    <script type="text/javascript">
        $('#ed<?= $key ?>').datepicker({
            changeYear: true,
            changeMonth: true
        });
        $('#harga<?= $key ?>').blur(function() {
            var ppn = ($('#ppn').val()/100);
            var nama = $('#pb<?= $key ?>').val();
            var h_lama = parseInt($('#cur_hna<?= $key ?>').val());
            var h_b    = parseInt(currencyToNumber($('#harga<?= $key ?>').val()));
            var h_baru = h_b + (h_b*ppn);
            if (h_lama > h_baru) {
                var ok = confirm('Harga lama untuk '+nama+' = Rp. '+h_lama+', apakah anda akan mengubah HNA ?');
                if (!ok) {
                    $('#status<?= $key ?>').val('Tidak');
                } else {
                    $('#status<?= $key ?>').val('Ya');
                }
                $(this).focus();
                return false;
            }
        });
        $('#kemasan<?= $key ?>').change(function() {
            var isi = $(this).attr('value');
            var new_isi = isi.split("-");
            if (new_isi[0] !== '') {
                $('#isi<?= $key ?>').val(<?= $rows->isi ?>/new_isi[0]);
            } else {
                $('#isi<?= $key ?>').val('');
            }
        });
        $('#pb<?= $key ?>').autocomplete("<?= base_url('inv_autocomplete/load_data_packing_barang') ?>",
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
                    var isi = ''; var satuan = ''; var sediaan = ''; var pabrik = ''; var satuan_terbesar = ''; var kekuatan = '';
                    if (data.isi !== '1') { var isi = '@ '+data.isi; }
                    if (data.satuan !== null) { var satuan = data.satuan; }
                    if (data.kekuatan !== null && data.kekuatan !== '0') { var kekuatan = data.kekuatan; }
                    if (data.sediaan !== null) { var sediaan = data.sediaan; }
                    if (data.pabrik !== null) { var pabrik = data.pabrik; }
                    if (data.satuan_terbesar !== null) { var satuan_terbesar = data.satuan_terbesar; }
                    var str = '<div class=result>'+data.nama+' '+kekuatan+' '+satuan+' '+sediaan+' '+pabrik+' '+isi+' '+satuan+'</div>';
                    return str;
                },
                width: 370, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
                dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
            }).result(
            function(event,data,formated){
                var sisa = data.sisa;
                if (data.sisa == null) {
                    var sisa = 0;
                }
                var isi = ''; var satuan = ''; var sediaan = ''; var pabrik = ''; var satuan_terbesar = ''; var kekuatan = '';
                if (data.isi !== '1') { var isi = '@ '+data.isi; }
                if (data.kekuatan !== null && data.kekuatan !== '0') { var kekuatan = data.kekuatan; }
                if (data.satuan !== null) { var satuan = data.satuan; }
                if (data.sediaan !== null) { var sediaan = data.sediaan; }
                if (data.pabrik !== null) { var pabrik = data.pabrik; }
                if (data.satuan_terbesar !== null) { var satuan_terbesar = data.satuan_terbesar; }
                $(this).val(data.nama+' '+kekuatan+' '+satuan+' '+sediaan+' '+pabrik+' '+isi+' '+satuan_terbesar);
                $('#id_pb<?= $key ?>').val(data.id);
                $('#sisa<?= $key ?>').html(sisa);
            });
    </script>
<?php 
$key++;
}
?>
<script type="text/javascript">
$(function() {
    $('.diskon_pr').blur(function() {
        hitungDetail();
    });
    $('.net').focus(function() {
        hitungDetail(); 
    });
    var jml = $('.tr_row').length-1;
    for (j = 0; j <= jml; j++) {
        var dis_pr= $('#diskon_pr'+j).val();
        var dis_rp= $('#diskon_rp'+j).val();
        var harga = $('#harga'+j).val();
        if ($('#harga'+j).val() === '') {
            var harga = '0';
        }
        //var harga = parseInt(currencyToNumber(harga));
        
        var jumlah= $('#jml'+j).val();
        //$('#diskon_rp'+i).removeAttr('disabled');
        //$('#diskon_pr'+i).removeAttr('disabled');
        
        var subttl= (harga * jumlah);
        if (dis_pr !== 0 || dis_rp !== '') {
            var subttl = subttl - ((dis_pr/100)*harga)*jumlah;
            //$('#diskon_rp'+i).attr('disabled','disabled');
        }
        if (dis_rp !== '' || dis_rp !== 0) {
            var subttl = subttl - (dis_rp * jumlah);
            //$('#diskon_pr'+i).attr('disabled','disabled');
        }
        $('#subttl'+j).val(subttl);
        $('#subtotal'+j).html(numberToCurrency(subttl));
    }
});
</script>