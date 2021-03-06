<script type="text/javascript">
    $(function() {
        $(".sortable").tablesorter({sortList:[[1,0]]});
        $('#checkall').live('click', function() {
            $('#checkall .ui-button-text').html('Uncheck all');
            $('#checkall').attr('id', 'uncheckall');
            $('.check').attr('checked', 'checked');
        });
        $('#uncheckall').live('click', function() {
            $('#uncheckall .ui-button-text').html('Check all');
            $('#uncheckall').attr('id', 'checkall');
            $('.check').removeAttr('checked');
        });
    });
</script>
<table class="sortable" id="table" width="100%">
    <thead>
    <tr>
        <th width="5%" class="nosort"><h3>#</h3></th>
        <th width="5%" class="nosort"><h3>No.</h3></th>
        <th width="30%"><h3>Nama Form</h3></th>
        <th width="30%"><h3>Module</h3></th>
        
    </tr>
    </thead>
    <tbody>
    <?php foreach ($privilege as $key => $rows) : ?>
        <tr class="<?= ($key % 2 == 1) ? 'even' : 'odd' ?>">
            <td class="aksi" align="center">
                <?php
                $check = false;

                $check = in_array($rows->id, $user_priv);


                echo form_checkbox('data[]', $rows->id, $check,'class=check');
                ?>
            </td>
            <td align="center"><?= ++$key ?></td>
            <td><?= $rows->form_nama ?></td>
            <td><?= $rows->modul ?></a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?= form_close() ?>