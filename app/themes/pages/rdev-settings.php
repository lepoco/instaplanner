<?php namespace RapidDev\InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
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
								<label for="input_base_url">Main website URL</label>
								<input type="text" class="form-control" name="input_base_url" id="input_base_url" placeholder="<?php echo $this->Master->Options->Get('base_url'); ?>" value="<?php echo $this->Master->Options->Get('base_url'); ?>">
								<small><span class="uppercase"><strong>Attention!</strong></span><br/>Change URL only if you have moved the site to a different domain or folder. Otherwise, access to the panel may be blocked.</small>
							</div>
							<hr>
							<div class="form-group">
								<label for="input_dashboard_url">Dashboard path</label>
								<input type="text" class="form-control" name="input_dashboard_url" id="input_dashboard_url" placeholder="<?php echo $this->Master->Options->Get('dashboard'); ?>" value="<?php echo $this->Master->Options->Get('dashboard'); ?>">
							</div>
							<div class="form-group">
								<label for="input_login_url">Login path</label>
								<input type="text" class="form-control" name="input_login_url" id="input_login_url" placeholder="<?php echo $this->Master->Options->Get('login'); ?>" value="<?php echo $this->Master->Options->Get('login'); ?>">
							</div>
							<small><span class="uppercase">The paths are responsible for the login address and the administration panel. If you change them, you will change the default addresses.</small>
							<hr>
							<div class="form-group">
								<label for="input_media_path">Media library</label>
								<input type="text" class="form-control" name="input_media_library" id="input_media_library" placeholder="<?php echo $this->Master->Options->Get('media_library'); ?>" value="<?php echo $this->Master->Options->Get('media_library'); ?>">
							</div>
							<div class="form-group">
								<label for="input_posts_path">Posts library</label>
								<input type="text" class="form-control" name="input_posts_library" id="input_posts_library" placeholder="<?php echo $this->Master->Options->Get('posts_library'); ?>" value="<?php echo $this->Master->Options->Get('posts_library'); ?>">
							</div>
							<div class="form-group">
								<label for="input_profile_path">Profile pictures library</label>
								<input type="text" class="form-control" name="input_profile_library" id="input_profile_library" placeholder="<?php echo $this->Master->Options->Get('profile_library'); ?>" value="<?php echo $this->Master->Options->Get('profile_library'); ?>">
							</div>
							<small><span class="uppercase">These directories store photos and uploaded pictures. Changing these addresses will not move the files. You have to move the files to the new folders manually.</small>
						</div>

						<div class="tab-pane fade show <?php echo ($active == 'accounts' ? ' active' : ''); ?>" id="v-pills-accounts" role="tabpanel" aria-labelledby="v-pills-accounts-tab">
							<h2 class="display-4" style="font-size: 26px;">Accounts</h2>
							<hr>
							<div class="accordion">
<?php

	$accounts = $this->GetAccounts();
	if( !empty( $accounts ) ):
		$avatars_library = $this->Master->Options->Get( 'profile_library', 'media/img/profile/' );
		foreach ($accounts as $account):
?>
								<div class="media instaplanner__settings_profile--block">
									<img src="<?php echo $this->baseurl . $avatars_library . $account['avatar']; ?>" class="mr-3" alt="...">
									<div class="media-body">
										<h4><?php echo $account['full_name']; ?> | <i><?php echo $account['name']; ?></i></h4>
										<p>
											<?php echo $account['description']; ?>
											<br>
											<a target="_blank" rel="noopener" href="https://instagram.com/<?php echo $account['name']; ?>">https://instagram.com/<?php echo $account['name']; ?></a>
										</p>
										<strong>Followers: </strong> <?php echo $account['followers']; ?> <strong>Following: </strong> <?php echo $account['following']; ?> <strong>Posts: </strong> <?php echo $account['posts']; ?>
										<div style="display: flex;width: 100%;margin-top:8px">
											<button class="instaplaner__account--href" data-id="<?php echo $account['id']; ?>" href="#account_update">Update</button>
											<button class="instaplaner__account--href instaplaner__account--delete" data-id="<?php echo $account['id']; ?>" href="#account_delete"><span>Delete</span></button>
										</div>
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
									$option = $this->Master->Options->Get('force_dashboard_ssl');
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
					<button id="update-settings" type="submit" class="btn btn-block btn-outline-dark">Update settings</button>
				</div>
			</div>
		</div>
		<div class="modal fade instaplanner__center--modal instaplaner__deleteaccount" id="instaplaner__deleteaccount" data-keyboard="false" tabindex="-1" aria-labelledby="instaplaner__deleteaccount--label" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<strong>Look out!</strong>
						<p>Are you sure you want to delete this account?</p>
					</div>
					<div class="btn-group-vertical">
						<button id="instaplaner__deleteaccount--confirm" type="button" class="btn btn-outline-dark"><span>Delete</span></button>
						<button type="button" class="btn btn-outline-dark" data-dismiss="modal"><span>Cancel</span></button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade instaplanner__center--modal instaplaner__addaccount" id="instaplaner__addaccount" data-keyboard="false" tabindex="-1" aria-labelledby="instaplaner__addaccount--label" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<div class="alert instaplaner__addaccount--alert instaplaner__addaccount--alert__fetch" style="display: none">
								<div class="spinner-border" role="status">
									<span class="sr-only">Loading...</span>
								</div>
								<div>Downloading data from the Instagram...</div>
						</div>
						<div class="alert alert-danger instaplaner__addaccount--alert instaplaner__addaccount--alert__exists" style="display: none">
								<svg width="22px" height="22px" viewBox="0 0 16 16" class="bi bi-people" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1h7.956a.274.274 0 0 0 .014-.002l.008-.002c-.002-.264-.167-1.03-.76-1.72C13.688 10.629 12.718 10 11 10c-1.717 0-2.687.63-3.24 1.276-.593.69-.759 1.457-.76 1.72a1.05 1.05 0 0 0 .022.004zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10c-1.668.02-2.615.64-3.16 1.276C1.163 11.97 1 12.739 1 13h3c0-1.045.323-2.086.92-3zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
								</svg>
								<div>An account with the given name already exists</div>
						</div>
						<div class="alert alert-danger instaplaner__addaccount--alert instaplaner__addaccount--alert__error" style="display: none">
								<svg width="22px" height="22px" viewBox="0 0 16 16" class="bi bi-people" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.482 1.482 0 0 1 0-2.098L6.95.435zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
									<path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
								</svg>
								<div>Something went wrong...</div>
						</div>
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
