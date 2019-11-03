<div class="container" style="width: 99%;">
    <div class="row">
        <div class="panel panel-default">
        <form method="post" action="<?php echo admin_url('admin.php?page=save_newsletter'); ?>">
        <div class="panel-heading">Create New Newsletter</div>
            <div class="panel-body">
                <div class="formgroup">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="formgroup">
                    <label>Message</label>
                    <textarea name="message" class="form-control" id="editor1" required></textarea>
                </div>
            </div>
            <div class="panel-footer"><input type="submit" value="Save" class="btn btn-sm btn-success"></div>
            </form>
        </div>
    </div>
</div>

<script>
    CKEDITOR.replace( 'editor1' );
</script>