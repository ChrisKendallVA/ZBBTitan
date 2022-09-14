<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> <?php echo lang("ctn_1575") ?></div>
    <div class="db-header-extra form-inline">
      <a href="<?php echo site_url("stock/add_inventory") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_1576") ?></a>
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("stock/add_inventory_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_825") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control" id="project-load">
                        <?php foreach($projects->result() as $r) : ?>
                          <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group" id="items-area">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1577") ?></label>
                    <div class="col-md-8">
                        <select name="stockid" class="form-control" id="item-load">
                        	<option value="0"><?php echo lang("ctn_1578") ?> ...</option>
                        	<?php foreach($items->result() as $r) : ?>
                        		<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        	<?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_1579") ?>.</span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_617") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="quantity" value="0">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1450") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="price" value="0.00" id="price">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_261") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="cost" value="0.00" id="cost">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_752") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="note">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1580") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="replace" value="1"> <?php echo lang("ctn_53") ?>
                    </div>
            </div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1581") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>
<script type="text/javascript">
$(document).ready(function() {

	$('#project-load').on("change", function() {
		var projectid = $('#project-load').val();
		$.ajax({
			url: global_base_url + 'stock/load_project_items/' + projectid,
			type: 'GET',
			success: function(msg) {
				$('#items-area').html(msg);
				// code
				$('#item-load').on("change", function() {
					var itemid = $('#item-load').val();
					$.ajax({
						url: global_base_url + 'stock/load_item_data/' + itemid,
						type: 'GET',
						dataType : 'json',
						success: function(msg) {
                            $('#cost').val(msg.cost);
                            $('#price').val(msg.price);
						}
					});
				});
			}
		});
	});

	$('#item-load').on("change", function() {
		var itemid = $('#item-load').val();
		$.ajax({
			url: global_base_url + 'stock/load_item_data/' + itemid,
			type: 'GET',
			dataType : 'json',
			success: function(msg) {
				$('#cost').val(msg.cost);
                $('#price').val(msg.price);
				console.log("good");
			}
		});
	});
});
</script>