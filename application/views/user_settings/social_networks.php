<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-cog"></span> <?php echo lang("ctn_224") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("user_settings/change_password") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_225") ?></a> <a href="<?php echo site_url("user_settings/user_data") ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_1511") ?></a> <a href="<?php echo site_url("user_settings/paying_account") ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_1329") ?></a> <a href="<?php echo site_url("user_settings/social_networks") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_1615") ?></a>
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_1615") ?></li>
</ol>


<div class="panel panel-default">
<div class="panel-body">

 	<h2><?php echo lang("ctn_1616") ?></h2>
  	<p><?php echo lang("ctn_1617") ?></p>

  	<?php if($this->user->info->oauth_provider) : ?>
  	<?php if($this->user->info->oauth_provider == "twitter") : ?>	
  		<p>Twitter - <a href="<?php echo site_url("user_settings/deauth/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_1618") ?></a></p>
  	<?php endif; ?>
  	<?php if($this->user->info->oauth_provider == "google") : ?>	
  		<p>Google - <a href="<?php echo site_url("user_settings/deauth/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_1618") ?></a></p>
  	<?php endif; ?>
  	<?php if($this->user->info->oauth_provider == "facebook") : ?>	
  		<p>Facebook - <a href="<?php echo site_url("user_settings/deauth/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_1618") ?></a></p>
  	<?php endif; ?>
  	<?php endif; ?>

</div>
</div>
</div>