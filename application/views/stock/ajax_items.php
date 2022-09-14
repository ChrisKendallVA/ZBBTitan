<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1577") ?></label>
                    <div class="col-md-8">
                        <select name="stockid" class="form-control" id="item-load">
                        	<option value="0"><?php echo lang("ctn_1578") ?> ...</option>
                        	<?php foreach($items->result() as $r) : ?>
                        		<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        	<?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_1582") ?></span>
                    </div>