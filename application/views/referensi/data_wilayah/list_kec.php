<div id="resume">
    <br/>
    <h3>
        Halaman <?= $page ?> dari <?= (ceil($jumlah / $limit)==0)?1:ceil($jumlah / $limit) ?> (Total <?= $jumlah ?> data )
    </h3>

</div>
<table cellpadding="0" cellspacing="0" class="tabel" width="60%">
    <tr>
        <th width="10%">No.</th>
        <th width="20%">Nama</th>
        <th width="20%">Kabupaten</th>
        <th width="20%">Kode</th>
        <th width="10%">Aksi</th>
    </tr>
    <?php if ($kecamatan != null): ?>
        <?php foreach ($kecamatan as $key => $kab): ?>
            <tr class="<?php echo ($key % 2) ? "even" : "odd" ?>">
                <td align="center"><?= $kab->nomor ?></td>
                <td><?php echo $kab->nama ?></td>
                <td><?php echo $kab->kabupaten ?></td>
                <td><?php echo $kab->kode ?></td>
                <td class="aksi"> 
                    <a class="edit" onclick="edit_kecamatan('<?= $kab->id ?>','<?= $kab->nama ?>','<?= $kab->kabupaten_id ?>','<?= $kab->kabupaten ?>','<?= $kab->kode ?>')"></a>
                    <a class="delete" onclick="delete_kecamatan('<?= $kab->id ?>')"></a>
                </td> 
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>

</table>
<br/>
<div id="paging"><?= $paging ?></div>
