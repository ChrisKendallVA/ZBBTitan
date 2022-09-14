<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('project-description', { height: '100'});
});
</script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_766") ?></div>
    <div class="db-header-extra">
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("projects/edit_project_pro/" . $project->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_767") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="name" value="<?php echo $project->name ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_768") ?></label>
        <div class="col-md-8 ui-front">
        	<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->image ?>" class="user-icon" />
            <input type="file" class="form-control" name="userfile">
            <span class="help-block"><?php echo lang("ctn_769") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_770") ?></label>
        <div class="col-md-8">
            <textarea name="description" id="project-description"><?php echo $project->description ?></textarea>
        </div>
</div>
<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_771") ?></label>
                    <div class="col-md-8">
                        <input type="text" name="complete" class="form-control" value="<?php echo $project->complete ?>" >
                        <span class="help-block"><?php echo lang("ctn_772") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_773") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="complete_sync" value="1" <?php if($project->complete_sync) : ?>checked<?php endif; ?> >
                        <span class="help-block"><?php echo lang("ctn_774") ?></span>
                    </div>
            </div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_775") ?></label>
        <div class="col-md-8">
            <select name="catid" class="form-control">
            <?php foreach($categories->result() as $r) : ?>
            	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $project->catid) echo "selected" ?>><?php echo $r->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_776") ?></label>
        <div class="col-md-8">
            <select name="status" class="form-control">
                <option value="0"><?php echo lang("ctn_777") ?></option>
                <option value="1" <?php if($project->status == 1) echo "selected" ?>><?php echo lang("ctn_778") ?></option>
            </select>
        </div>
</div>
<h4><?php echo lang("ctn_779") ?></h4>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_780") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="calendar_id" value="<?php echo $project->calendar_id ?>">
                        <span class="help-block"><?php echo lang("ctn_781") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_782") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control jscolor" name="calendar_color" value="<?php echo $project->calendar_color ?>">
                    </div>
            </div>
            <?php foreach($fields->result() as $r) : ?>
            <div class="form-group">

                <label for="name-in" class="col-sm-4 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                <div class="col-sm-8">
                    <?php if($r->type == 0) : ?>
                        <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php echo $r->value ?>">
                    <?php elseif($r->type == 1) : ?>
                        <textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"><?php echo $r->value ?></textarea>
                    <?php elseif($r->type == 2) : ?>
                         <?php $options = explode(",", $r->options); ?>
                         <?php $values = array_map('trim', (explode(",", $r->value))); ?>
                        <?php if(count($options) > 0) : ?>
                            <?php foreach($options as $k=>$v) : ?>
                            <p><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(in_array($v,$values)) echo "checked" ?>> <?php echo $v ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif($r->type == 3) : ?>
                        <?php $options = explode(",", $r->options); ?>
                        
                        <?php if(count($options) > 0) : ?>
                            <?php foreach($options as $k=>$v) : ?>
                            <p><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if($r->value == $v) echo "checked" ?>> <?php echo $v ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php elseif($r->type == 4) : ?>
                        <?php $options = explode(",", $r->options); ?>
                        <?php if(count($options) > 0) : ?>
                            <select name="cf_<?php echo $r->ID ?>" class="form-control">
                            <?php foreach($options as $k=>$v) : ?>
                            <option value="<?php echo $k ?>" <?php if($r->value == $v) echo "selected" ?>><?php echo $v ?></option>
                            <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span class="help-text"><?php echo $r->help_text ?></span>
                </div>
        </div>
    <?php endforeach; ?>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_783") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>