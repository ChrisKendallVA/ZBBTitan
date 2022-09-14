<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> <?php echo lang("ctn_1575") ?></div>
    <div class="db-header-extra form-inline">
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("stock/edit_stock_pro/" . $stock->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="<?php echo $stock->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_271") ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="cat-description"><?php echo $stock->description ?></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1583") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="price" value="<?php echo $stock->price ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1584") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="cost" value="<?php echo $stock->cost ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_825") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control">
                          <option value="0"><?php echo lang("ctn_449") ?></option>
                        <?php foreach($projects->result() as $r) : ?>
                          <option value="<?php echo $r->ID ?>" <?php if($r->ID == $stock->projectid) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('cat-description', { height: '100'});
});
</script>
