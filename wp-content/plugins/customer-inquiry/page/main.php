<div class="container" style="width: 99%;">
    <div class="row">
        <div class="col-lg-12">
            <table id="example" class="table table-striped table-bordered" style="width:100%"> 
                <thead> 
                    <tr>
                        <th>ID</th> 
                        <th>Name</th> 
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th> 
                    </tr> 
                </thead> 
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detail Inquiry</h4>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-group">
                <label>Name</label>
                <input class="form-control" id="inquiry-name" readonly>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" id="inquiry-email" readonly>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input class="form-control" id="inquiry-phone" readonly>
            </div>
            <div class="form-group">
                <label>Type Work</label>
                <input class="form-control" id="inquiry-type-work" readonly>
            </div>
            <div class="form-group">
                <label>Starting Project</label>
                <input class="form-control" id="inquiry-starting-project" readonly>
            </div>
            <div class="form-group">
                <label>Budget</label>
                <input class="form-control" id="inquiry-budget" readonly>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input class="form-control" id="inquiry-location" readonly>
            </div>
            <div class="form-group">
                <label>Inquiry Time</label>
                <input class="form-control" id="inquiry-datetime" readonly>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
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
            'data': {action: 'getInquiryContact'},
            'dataType': 'json',
            "type": "GET"
        },
        'columns': [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'phone' }
        ],
        "columnDefs": [{
                "targets": [4],
                "visible": true,
                "searchable": false,
                "sortable": false,
                "defaultContent": "<center><button class='btn btn-warning btn-xs' id='detail_btn'>Detail</button></center>"
        }],
    });
		
    jQuery("#reload_table").click(function() {
        jQuery('#example').DataTable().ajax.reload();
    });

    jQuery('#example tbody').on('click', '#detail_btn', function () {
        var data_row = table.row(jQuery(this).closest('tr')).data();
        console.log(data_row);
        jQuery('#inquiry-name').val(data_row.name);
        jQuery('#inquiry-email').val(data_row.email);
        jQuery('#inquiry-phone').val(data_row.phone);
        jQuery('#inquiry-type-work').val(data_row.type_works);
        jQuery('#inquiry-starting-project').val(data_row.starting_project);
        jQuery('#inquiry-budget').val(data_row.budget);
        jQuery('#inquiry-location').val(data_row.location);
        jQuery('#inquiry-datetime').val(data_row.datetime);
        jQuery('#myModal').modal('show');
    });
});
</script>