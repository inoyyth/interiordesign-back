<div class="container" style="width: 99%;">
    <div class="row">
        <div class="col-lg-12" style="margin-bottom: 10px;">
            <a href="<?php echo admin_url('admin.php?page=add_newsletter'); ?>" class="btn btn-sm btn-primary">Create New</a>
        </div>
        <div class="col-lg-12">
            <table id="example" class="table table-striped table-bordered" style="width:100%"> 
                <thead> 
                    <tr>
                        <th>ID</th> 
                        <th>Title</th>
                        <th>Action</th> 
                    </tr> 
                </thead> 
            </table>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function() {
    var table = jQuery('#example').DataTable({
        "paging":   true,
        "ordering": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo admin_url( 'admin-ajax.php'); ?>",
            'data': {action: 'getNewsletter'},
            'dataType': 'json',
            "type": "GET"
        },
        'columns': [
            { data: 'id' },
            { data: 'title' }
        ],
        "columnDefs": [{
            "targets": [2],
            "visible": true,
            "searchable": false,
            "sortable": false,
            "defaultContent": "<center><button class='btn btn-primary btn-xs' id='share_btn'>Share Now</button> " +
                "| <button class='btn btn-warning btn-xs' id='edit_btn'>Edit</button> " +
                "| <button class='btn btn-danger btn-xs' id='delete_btn'>Delete</button></center>"
        }],
    });
		
    jQuery("#reload_table").click(function() {
        jQuery('#example').DataTable().ajax.reload();
    });

    jQuery('#example tbody').on('click', '#edit_btn', function () {
        var data_row = table.row(jQuery(this).closest('tr')).data();
        window.location.href = "<?php echo admin_url('admin.php'); ?>?page=edit_newsletter&id="+data_row.id;
    });

    jQuery('#example tbody').on('click', '#delete_btn', function () {
        var data_row = table.row(jQuery(this).closest('tr')).data();
        var confirm_delete = confirm("Want to delete?");
        if (confirm_delete) {
            window.location.href = "<?php echo admin_url('admin.php'); ?>?page=delete_newsletter&id="+data_row.id;
        }
    });

    jQuery('#example tbody').on('click', '#share_btn', function () {
        var data_row = table.row(jQuery(this).closest('tr')).data();
        var confirm_share = confirm("Want to Share?");
        if (confirm_share) {
            window.location.href = "<?php echo admin_url('admin.php'); ?>?page=share_newsletter&id="+data_row.id;
        }
    });
});
</script>