<title><?= $title ?></title>

<script type="text/javascript" src="<?= base_url('assets/js/jquery-print.min.js') ?>"></script>
<script type="text/javascript">
    function PrintElem(elem) {
        $('#cetak').hide();
        Popup($(elem).printElement());
        $('#cetak').show();
    }

    function Popup(data) {
        //var mywindow = window.open('<?= $title ?>', 'Print', 'height=400,width=800');
        mywindow.document.write('<html><head><title> <?= $title ?> </title>');
        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.print();
        mywindow.close();

        return true;
    }

</script>
<div id="mydiv">
<?php
    foreach ($penjualan as $rows);
?>
<table style="border-bottom: 1px solid #000; font-size: 16px;" width="100%">
    <tr><td colspan="4" align="center" style="text-transform: uppercase; font-size: 16px;"><?= $apt->nama ?></td> </tr>
    <tr><td colspan="4" align="center" style="font-size: 16px;"><?= $apt->alamat ?> <?= $apt->kabupaten ?></td> </tr>
    <tr><td colspan="4" align="center" style="font-size: 16px;">Telp. <?= $apt->telp ?>,  Fax. <?= $apt->fax ?>, Email <?= $apt->email ?></td> </tr>
</table>
    <center><h1><?= $title ?></h1></center>
<table class="content-printer" style="border-bottom: 1px solid #000; width: 100%">
    <tr><td>No.:</td><td><?= $rows->id ?></td></tr>
    <tr><td>Nama:</td><td><?= $rows->pasien ?></td></tr>
    <tr><td>Dokter:</td><td><?= $rows->dokter ?></td></tr>
</table>

<table width="100%" style="border-bottom: 1px solid #000">
    <tr>
        <th align="left" width="20%" style="border-bottom: 1px dashed #ccc;">Barang</th>
        <th width="15%" align="right" style="border-bottom: 1px dashed #ccc;">Harga</th>
        <th width="15%" style="border-bottom: 1px dashed #ccc;">Diskon(%)</th>
        <th width="15%" style="border-bottom: 1px dashed #ccc;">Jumlah</th>
    </tr>
<?php

$no = 1;
$tagihan = 0;
$diskon = 0;
foreach ($penjualan as $key => $data) {
    $harga = ($data->hna*($data->margin/100))+$data->hna;
    ?>
    <tr valign="top" class="<?= ($key%2==0)?'even':'odd' ?> tr_row">
        <td width="60%"><?= $data->barang." ".(($data->kekuatan == '1')?'':$data->kekuatan)." ". $data->satuan." ".$data->sediaan." @ ".(($data->isi==1)?'':$data->isi)." ".$data->satuan_terkecil ?></td>
        <td align="right" id=hj<?= $no ?>><?= rupiah($harga) ?></td>
        <td align="center" id=diskon<?= $no ?>><?= $data->jual_diskon_percentage ?></td>
        <!--<td><?= rupiah(($data->jual_harga - (($data->jual_harga*($data->percent/100))))*$data->pakai_jumlah) ?></td>-->
        <td align="center"><?= $data->keluar ?></td>
    </tr>
<?php 
$tagihan = $tagihan + ($harga*$data->keluar) - (($harga*($data->jual_diskon_percentage/100))*$data->keluar);
$no++;
}

foreach ($penjualan as $rows);
$ppn = (isset($rows->ppn)?$rows->ppn:'0')*$tagihan;
$jml_ppn = isset($rows->ppn)?$rows->ppn:'0';
?>
</table>
<table width="100%">
    <tr><td>Total Tagihan:</td><td align="right"><?= inttocur($tagihan) ?></td></tr>
    <?php 
    $byapotek = 0;
    if ($rows->resep_id != NULL) { 
    $biaya = $this->db->query("select sum(t.nominal) as jasa_apoteker from resep_r rr join tarif t on (t.id = rr.tarif_id) where rr.resep_id = '".$rows->resep_id."'")->row();
    $byapotek = $biaya->jasa_apoteker;
    ?>
    <tr><td>Biaya Apoteker:</td><td align="right"><?= rupiah($biaya->jasa_apoteker) ?></td></tr>
    <?php } 
    $diskon_member = $tagihan*($rows->diskon_member/100);
    $totals = $tagihan-$diskon_member+$byapotek+($jml_ppn/100*$tagihan);
    ?>
    <tr><td>Diskon:</td><td align="right"><?= ($diskon_member) ?></td></tr>
    <tr><td>PPN (%):</td><td align="right"><?= $jml_ppn ?></td></tr>
    <tr><td>Total:</td><td align="right"><?= inttocur($totals) ?></td></tr>
</table>
<p align="center">
    <span id="SCETAK"><input type="button" class="tombol" value="Cetak" id="cetak" onClick="PrintElem('#mydiv')"/></span>
</p>
</div>
<?php die; ?>