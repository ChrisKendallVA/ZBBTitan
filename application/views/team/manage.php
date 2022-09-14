<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_887") ?></div>
    <div class="db-header-extra form-inline"> 
    <div class="btn-group">
    <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo lang("ctn_844") ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <li><a href="<?php echo site_url("team") ?>"><?php echo lang("ctn_845") ?></a></li>
    <?php foreach($projects->result() as $r) : ?>
      <?php if($r->ID == $projectid) $proj = $r; ?>
      <li><a href="<?php echo site_url("team/manage/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>
</div>


    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_910") ?></button>


</div>
</div>

<?php if(isset($projectid) && $projectid > 0 && isset($proj)) : ?>
  <?php $project = $proj; ?>
<p><?php echo lang("ctn_911") ?> <strong><?php echo $project->name ?></strong> <?php if($this->user->info->active_projectid == $projectid) : ?> (<?php echo lang("ctn_912") ?>) <?php endif; ?></p>
<?php else: ?>
<?php endif; ?>


<?php if($projectid > 0) : ?>
<?php
// update admin permission if they are an admin
    $update_admin = 0;

    $team = $this->team_model
            ->get_member_of_project($this->user->info->ID, $projectid);
    if($team->num_rows() > 0) {
        $team = $team->row();
         if($team->admin) {
            $update_admin = 1;
        }
    } else {
        $team = null;
    }

    // if they have the right user role, they can update admin permissions
    if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user)) {
        $update_admin = 1;
    }
?>
<h4>Project: <a href="<?php echo site_url("projects/view/" . $project->ID) ?>"><?php echo $project->name ?></a></h4>
 <?php echo form_open(site_url("team/manage_update/" . $projectid), array("class" => "form-horizontal")) ?>
 <div class="table-responsive">
<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_132") ?></td><td colspan="12"><?php echo lang("ctn_307") ?></td></tr>
<tr><td><strong><?php echo lang("ctn_21") ?></strong></td><td><strong><?php echo lang("ctn_35") ?></strong></td><td><strong><?php echo lang("ctn_703") ?></strong></td><td><strong><?php echo lang("ctn_675") ?></strong></td><td><strong><?php echo lang("ctn_872") ?></strong></td><td><strong><?php echo lang("ctn_989") ?></strong></td><td><strong><?php echo lang("ctn_447") ?></strong></td><td><strong><?php echo lang("ctn_512") ?></strong></td><td><strong><?php echo lang("ctn_515") ?></strong></td><td><strong><?php echo lang("ctn_1141") ?></strong></td><td><strong><?php echo lang("ctn_1527") ?></strong></td><td><strong><?php echo lang("ctn_542") ?></strong></td><td><strong><?php echo lang("ctn_591") ?></strong></td></tr>
<?php foreach($members->result() as $r) : ?>
<tr><td><?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)) ?> 

<?php if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user) || $this->common->has_team_permissions(array("admin", "team"), $team)) : ?>
<?php if($r->admin) : ?>
    <?php if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user) || $this->common->has_team_permissions(array("admin"), $team)) : ?>
    <a href="<?php echo site_url("team/remove_member/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-xs btn-danger"><?php echo lang("ctn_674") ?></a>
    <?php endif; ?>
<?php else : ?>
<a href="<?php echo site_url("team/remove_member/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-xs btn-danger"><?php echo lang("ctn_674") ?></a>
<?php endif; ?>
<?php endif; ?>

</td><td><input type="checkbox" name="admin_<?php echo $r->userid ?>" value="1" <?php if($r->admin) echo "checked" ?> <?php if(!$update_admin) echo "disabled" ?>></td><td><input type="checkbox" name="team_<?php echo $r->userid ?>" value="1" <?php if($r->team) echo "checked" ?>></td><td><input type="checkbox" name="timers_<?php echo $r->userid ?>" value="1" <?php if($r->time) echo "checked" ?>></td><td><input type="checkbox" name="files_<?php echo $r->userid ?>" value="1" <?php if($r->file) echo "checked" ?>></td><td><input type="checkbox" name="tasks_<?php echo $r->userid ?>" value="1" <?php if($r->task) echo "checked" ?>></td><td><input type="checkbox" name="calendar_<?php echo $r->userid ?>" value="1" <?php if($r->calendar) echo "checked" ?>></td><td><input type="checkbox" name="finance_<?php echo $r->userid ?>" value="1" <?php if($r->finance) echo "checked" ?>></td><td><input type="checkbox" name="notes_<?php echo $r->userid ?>" value="1" <?php if($r->notes) echo "checked" ?>></td><td><input type="checkbox" name="reports_<?php echo $r->userid ?>" value="1" <?php if($r->reports) echo "checked" ?>></td><td><input type="checkbox" name="documentation_<?php echo $r->userid ?>" value="1" <?php if($r->doc) echo "checked" ?>></td><td><input type="checkbox" name="invoices_<?php echo $r->userid ?>" value="1" <?php if($r->invoice) echo "checked" ?>></td><td><input type="checkbox" name="client_<?php echo $r->userid ?>" value="1" <?php if($r->client) echo "checked" ?>></td></tr>
<?php endforeach; ?>
</table>
</div>
<?php if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user) || $this->common->has_team_permissions(array("admin", "team"), $team)) : ?>
<p><input type="submit" class="btn btn-primary" value="Update"></p>
        <?php echo form_close() ?>
    <?php else: ?>
        <p><?php echo lang("ctn_1610") ?></p>
    <?php endif; ?>
<?php else : ?>

<?php foreach($projects->result() as $rr) : ?>

<?php
    // update admin permission if they are an admin
    $update_admin = 0;

    // Get team permissions of current user
    $team = $this->team_model
            ->get_member_of_project($this->user->info->ID, $rr->ID);
    if($team->num_rows() > 0) {
        $team = $team->row();
        if($team->admin) {
            $update_admin = 1;
        }
    } else {
        $team = null;
    }

    // if they have the right user role, they can update admin permissions
    if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user)) {
        $update_admin = 1;
    }

?>

<h4>Project: <a href="<?php echo site_url("projects/view/" . $rr->ID) ?>"><?php echo $rr->name ?></a></h4>
<?php $members = $this->team_model->get_members_for_project($rr->ID);?>
<?php if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user) || $this->common->has_team_permissions(array("admin", "team"), $team)) : ?>
 <?php echo form_open(site_url("team/manage_update/" . $rr->ID), array("class" => "form-horizontal")) ?>
<?php endif; ?>
 <div class="table-responsive">
<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_132") ?></td><td colspan="12"><?php echo lang("ctn_307") ?></td></tr>
<tr><td><strong><?php echo lang("ctn_21") ?></strong></td><td><strong><?php echo lang("ctn_35") ?></strong></td><td><strong><?php echo lang("ctn_703") ?></strong></td><td><strong><?php echo lang("ctn_675") ?></strong></td><td><strong><?php echo lang("ctn_872") ?></strong></td><td><strong><?php echo lang("ctn_989") ?></strong></td><td><strong><?php echo lang("ctn_447") ?></strong></td><td><strong><?php echo lang("ctn_512") ?></strong></td><td><strong><?php echo lang("ctn_515") ?></strong></td><td><strong><?php echo lang("ctn_1141") ?></strong></td><td><strong><?php echo lang("ctn_1527") ?></strong></td><td><strong><?php echo lang("ctn_542") ?></strong></td><td><strong><?php echo lang("ctn_591") ?></strong></td></tr>
<?php foreach($members->result() as $r) : ?>
<tr><td><?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)) ?> 

<?php if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user) || $this->common->has_team_permissions(array("admin", "team"), $team)) : ?>
<?php if($r->admin) : ?>
    <?php if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user) || $this->common->has_team_permissions(array("admin"), $team)) : ?>
    <a href="<?php echo site_url("team/remove_member/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-xs btn-danger">Remove</a>
    <?php endif; ?>
<?php else : ?>
<a href="<?php echo site_url("team/remove_member/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-xs btn-danger">Remove</a>
<?php endif; ?>
<?php endif; ?>

</td><td><input type="checkbox" name="admin_<?php echo $r->userid ?>" value="1" <?php if($r->admin) echo "checked" ?> <?php if(!$update_admin) echo "disabled" ?>></td><td><input type="checkbox" name="team_<?php echo $r->userid ?>" value="1" <?php if($r->team) echo "checked" ?>></td><td><input type="checkbox" name="timers_<?php echo $r->userid ?>" value="1" <?php if($r->time) echo "checked" ?>></td><td><input type="checkbox" name="files_<?php echo $r->userid ?>" value="1" <?php if($r->file) echo "checked" ?>></td><td><input type="checkbox" name="tasks_<?php echo $r->userid ?>" value="1" <?php if($r->task) echo "checked" ?>></td><td><input type="checkbox" name="calendar_<?php echo $r->userid ?>" value="1" <?php if($r->calendar) echo "checked" ?>></td><td><input type="checkbox" name="finance_<?php echo $r->userid ?>" value="1" <?php if($r->finance) echo "checked" ?>></td><td><input type="checkbox" name="notes_<?php echo $r->userid ?>" value="1" <?php if($r->notes) echo "checked" ?>></td><td><input type="checkbox" name="reports_<?php echo $r->userid ?>" value="1" <?php if($r->reports) echo "checked" ?>></td><td><input type="checkbox" name="documentation_<?php echo $r->userid ?>" value="1" <?php if($r->doc) echo "checked" ?>></td><td><input type="checkbox" name="invoices_<?php echo $r->userid ?>" value="1" <?php if($r->invoice) echo "checked" ?>></td><td><input type="checkbox" name="client_<?php echo $r->userid ?>" value="1" <?php if($r->client) echo "checked" ?>></td></tr>
<?php endforeach; ?>
</table>
</div>
<?php if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user) || $this->common->has_team_permissions(array("admin", "team"), $team)) : ?>
<p><input type="submit" class="btn btn-primary" value="Update <?php echo $rr->name ?>"></p>
        <?php echo form_close() ?>
    <?php else: ?>
        <p><?php echo lang("ctn_1610") ?></p>
    <?php endif; ?>
        <hr>
<?php endforeach; ?>
<?php endif; ?>

</div>


<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_910") ?></h4>
      </div>
      <div class="modal-body ui-front">
         <?php echo form_open(site_url("team/add_team_member"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_25") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="username" value="" id="username-search">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_825") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control">
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_35") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="admin" value="1">
                        <p><?php echo lang("ctn_1598") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_703") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="team" value="1">
                        <p><?php echo lang("ctn_1599") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_675") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="timer" value="1">
                        <p><?php echo lang("ctn_1600") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_872") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="files" value="1">
                        <p><?php echo lang("ctn_1601") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_989") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="tasks" value="1">
                        <p><?php echo lang("ctn_1602") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_447") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="calendar" value="1">
                        <p><?php echo lang("ctn_1603") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_512") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="finance" value="1">
                        <p><?php echo lang("ctn_1604") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_515") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="notes" value="1">
                        <p><?php echo lang("ctn_1605") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1141") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="reports" value="1">
                        <p><?php echo lang("ctn_1606") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1218") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="client" value="1">
                        <p><?php echo lang("ctn_1607") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1527") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="documentation" value="1">
                        <p><?php echo lang("ctn_1608") ?></p>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_542") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="checkbox" name="invoices" value="1">
                        <p><?php echo lang("ctn_1609") ?></p>
                    </div>
            </div>
            
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_910") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>