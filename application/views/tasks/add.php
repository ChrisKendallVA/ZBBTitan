<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-tasks"></span> <?php echo lang("ctn_820") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("tasks/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_821") ?></a>
</div>
</div>

<p><?php echo lang("ctn_822") ?></p>

<div class="panel panel-default">
<div class="panel-body">

 <?php echo form_open(site_url("tasks/add_task_process"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_823") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_824") ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="task-desc"></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_825") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" id="projectid" class="form-control">
                        <option value="-1"><?php echo lang("ctn_826") ?></option>
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>" <?php if($this->user->info->active_projectid == $r->ID) echo "selected" ?> <?php if(isset($_GET['projectid']) && $_GET['projectid'] == $r->ID) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_827") ?></label>
                    <div class="col-md-8">
                        <input type="text" name="start_date" class="form-control datepicker" id="start_date" >
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_828") ?></label>
                    <div class="col-md-8">
                        <input type="text" name="due_date" class="form-control datepicker" id="due_date" required >
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_829") ?></label>
                    <div class="col-md-8">
                        <select name="status" class="form-control">
                        <option value="1"><?php echo lang("ctn_830") ?></option>
                        <option value="2"><?php echo lang("ctn_831") ?></option>
                        <option value="3"><?php echo lang("ctn_832") ?></option>
                        <option value="4"><?php echo lang("ctn_833") ?></option>
                        <option value="5"><?php echo lang("ctn_834") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_835") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="assign" value="1" checked>
                        <span class="help-text"><?php echo lang("ctn_836") ?></span>
                    </div>
            </div>
            <?php if($this->settings->info->enable_calendar) : ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1566"); ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="calendar_event" value="1" unchecked>
                        <span class="help-text"><?php echo lang("ctn_1567"); ?></span>
                    </div>
            </div>
        <?php endif; ?>
            <div class="form-group" id="task_members">
            </div>
            <hr>
            <h4><?php echo lang("ctn_1487") ?></h4>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1488") ?></label>
                    <div class="col-md-8">
                        <select name="template_option" class="form-control" id="template_option">
                            <option value="0"><?php echo lang("ctn_54") ?></option>
                            <?php if($this->common->has_permissions(array("admin", "project_admin",
         "task_manage"), 
            $this->user)) : ?>
                            <option value="1"><?php echo lang("ctn_1489") ?></option>
                        <?php endif; ?>
                            <option value="2"><?php echo lang("ctn_1490") ?></option>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_1491") ?></span>
                    </div>
            </div>
            <div id="template_fields" style="display: none">
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1492") ?></label>
                        <div class="col-md-8">
                            <input type="text" name="template_start_days" class="form-control" value="0">
                            <span class="help-block"><?php echo lang("ctn_1493") ?></span>
                        </div>
                </div>
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1494") ?></label>
                        <div class="col-md-8">
                            <input type="text" name="template_due_days" class="form-control" value="0">
                            <span class="help-block"><?php echo lang("ctn_1495") ?></span>
                        </div>
                </div>
            </div>
            
            
            <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_837") ?>" />
            <?php echo form_close() ?>
</div>
</div>

</div>
<script type="text/javascript">
CKEDITOR.replace('task-desc', { height: '100'});

$(document).ready(function() {

    $('#template_option').change(function() {
        var value = $(this).val();
        if(value > 0) {
            $('#template_fields').css("display", "block");
        } else {
            $('#template_fields').css("display", "none");
        }
    });
    $('#projectid').change(function() {
        var projectid = $('#projectid').val();
        $.ajax({
             type: "GET",
             url: global_base_url + "tasks/get_team_members/" + projectid,
             data: {
             },
             success: function (msg) {
                 $('#task_members').html(msg);
             }
         });
    });
});
</script>