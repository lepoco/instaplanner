<?php namespace InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */

	$this->GetHeader();

	$active = 'main';
	if( isset($_GET['page']) )
	{
		switch ($_GET['page']) {
			case 'accounts':
				$active = 'accounts';
				break;
		}
	}
?>
		<div class="container" style="padding-top: 40px;">
			<div class="row">
				<div class="col-12 col-lg-4">
					<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						<a class="nav-link<?php echo ($active == 'main' ? ' active' : ''); ?>" id="v-pills-main-tab" data-toggle="pill" href="#v-pills-main" role="tab" aria-controls="v-pills-main" aria-selected="<?php echo ($active == 'main' ? 'true' : 'false'); ?>">Main</a>
						<a class="nav-link<?php echo ($active == 'accounts' ? ' active' : ''); ?>" id="v-pills-accounts-tab" data-toggle="pill" href="#v-pills-accounts" role="tab" aria-controls="v-pills-accounts" aria-selected="<?php echo ($active == 'accounts' ? 'true' : 'false'); ?>">Accounts</a>
						<a class="nav-link" id="v-pills-users-tab" data-toggle="pill" href="#v-pills-users" role="tab" aria-controls="v-pills-users" aria-selected="false">Users</a>
						<a class="nav-link" id="v-pills-encryption-tab" data-toggle="pill" href="#v-pills-encryption" role="tab" aria-controls="v-pills-encryption" aria-selected="false">Encryption</a>
					</div>
				</div>
				<div class="col-12 col-lg-8">
					<div class="tab-content" id="v-pills-tabContent">
						
						<div class="tab-pane fade show <?php echo ($active == 'main' ? ' active' : ''); ?>" id="v-pills-main" role="tabpanel" aria-labelledby="v-pills-main-tab">
							<h2 class="display-4" style="font-size: 26px;">Main</h2>
							<hr>
							<div class="form-group">
								<label for="site_url">Main website URL</label>
								<input type="text" class="form-control" name="site_url" id="site_url" placeholder="<?php echo $this->InstaPlanner->Options->Get('base_url'); ?>" value="<?php echo $this->InstaPlanner->Options->Get('base_url'); ?>">
								<small><span class="uppercase"><strong>Attention!</strong></span><br/>Change URL only if you have moved the site to a different domain or folder. Otherwise, access to the panel may be blocked.</small>
							</div>
							<div class="form-group">
								<label for="dashboard_url">Dashboard URL</label>
								<input type="text" class="form-control" name="dashboard_url" id="dashboard_url" placeholder="<?php echo $this->InstaPlanner->Options->Get('dashboard'); ?>" value="<?php echo $this->InstaPlanner->Options->Get('dashboard'); ?>">
							</div>
							<div class="form-group">
								<label for="input_login_url">Login URL</label>
								<input type="text" class="form-control" name="input_login_url" id="input_login_url" placeholder="<?php echo $this->InstaPlanner->Options->Get('login'); ?>" value="<?php echo $this->InstaPlanner->Options->Get('login'); ?>">
							</div>
						</div>

						<div class="tab-pane fade show <?php echo ($active == 'accounts' ? ' active' : ''); ?>" id="v-pills-accounts" role="tabpanel" aria-labelledby="v-pills-accounts-tab">
							<h2 class="display-4" style="font-size: 26px;">Accounts</h2>
							<hr>
							<div class="accordion">
<?php

	$accounts = $this->GetAccounts();
	if( !empty( $accounts ) ):
		$avatars_library = $this->InstaPlanner->Options->Get( 'profile_library', 'media/img/profile/' );
		foreach ($accounts as $account):
?>
								<div class="media instaplanner__settings_profile--block">
									<img src="<?php echo $this->baseurl . $avatars_library . $account['avatar']; ?>" class="mr-3" alt="...">
									<div class="media-body">
										<h4><?php echo $account['full_name']; ?> | <i><?php echo $account['name']; ?></i></h4>
										<p><?php echo $account['description']; ?></p>
										<strong>Followers: </strong> <?php echo $account['followers']; ?> <strong>Following: </strong> <?php echo $account['following']; ?> <strong>Posts: </strong> <?php echo $account['posts']; ?>
									</div>
								</div>
<?php endforeach; else: ?>
							<div>
								<p>
									It looks like you don't have any Instagram accounts saved.
									<br>
									<strong>
										Try to add new ones with the button below
									</strong>
								</p>
							</div>
<?php endif; ?>
								
							</div>
							<button data-toggle="modal" data-target="#instaplaner__addaccount" class="btn btn-block btn-primary" style="margin-top: 20px;">
								Add new
							</button>
						</div>

						<div class="tab-pane fade" id="v-pills-encryption" role="tabpanel" aria-labelledby="v-pills-encryption-tab">
							<h2 class="display-4" style="font-size: 26px;">Connection encryption</h2>
							<hr>
							<div class="form-group">
								<label for="force_dashboard_ssl">Force SSL connection for dashboard</label>
								<select class="form-control" name="force_dashboard_ssl" id="force_dashboard_ssl">
									<?php
									$option = $this->InstaPlanner->Options->Get('force_dashboard_ssl');
									?>
									<option value="1"<?php echo $option ? ' selected="selected"' : ""; ?>>Enabled</option>
									<option value="2"<?php echo !$option ? ' selected="selected"' : ""; ?>>Disabled</option>
								</select>
							</div>
							<small>
								You don't have to spend money on certificates from companies like Comodo or RapidSSL.
								<br>
								You can generate a free certificate with <a href="https://letsencrypt.org/" target="_blank" rel="noopener">Let's Encrypt</a>.
								<hr>
								SSL is recommended.
								<br>
								You protect both yourself and your users against a number of attacks. MIDM and Session Hijacking are one of the most dangerous. Never put safety second.
							</small>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-4">
					<hr>
					<button id="save-settings" type="submit" class="btn btn-block btn-outline-dark">Save settings</button>
				</div>
			</div>
		</div>
		<div class="modal fade instaplaner__addaccount" id="instaplaner__addaccount" data-keyboard="false" tabindex="-1" aria-labelledby="instaplaner__addaccount--label" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form id="instaplaner__addaccount--form">
							<input type="hidden" value="<?php echo $this->AjaxNonce( 'add_post' ); ?>" name="nonce" id="addphoto_nonce">
							<input type="hidden" value="add_post" name="action" id="addphoto_action">
							<input type="hidden" value="1" name="input_account" id="addphoto_account">
							<div class="form-group">
								<label for="input-account-name">Name</label>
								<input type="text" class="form-control form-ig" name="input-account-name" id="input-account-name" placeholder="Enter Instagram username">
							</div>
							<div class="instaplaner__addaccount--preview">
								<hr>
								<div>
									<div>
										<img src="" alt="Profile avatar">
									</div>
									<div>
										<h3></h3>
										<h4></h4>
										<p id="instaplaner__addaccount--description"></p>
										<a href="#profile_link" id="instaplaner__addaccount--url"></a>
										<hr>
										<strong>Followers: </strong><span id="instaplaner__addaccount--followers"></span> <strong>Following: </strong><span id="instaplaner__addaccount--following"></span> <strong>Posts: </strong><span id="instaplaner__addaccount--posts"></span>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="btn-group-vertical">
						<button id="instaplaner__addaccount--fetch" type="button" class="btn btn-outline-dark">Fetch data from Instagram</button>
						<button id="instaplaner__addaccount--save" disabled="disabled" type="button" class="btn btn-outline-dark">Save account</button>
						<button type="button" class="btn btn-outline-dark" data-dismiss="modal"><span>Cancel</span></button>
					</div>
				</div>
			</div>
		</div>
<?php
	$this->GetFooter();
?>
