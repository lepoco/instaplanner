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
	$dashboard = $this->baseurl . $this->Master->Options->Get( 'dashboard', 'dashboard' ) . '/';
?>
<?php if( empty( $this->GetAccounts() ) ): ?>
		<div class="instaplanner__dashboard_new"/>
			<div>
				<div class="container">
					<div class="row">
						<div class="col-12 col-lg-6 offset-lg-3">
							<div class="instaplanner-home__card">
								<div class="card">
									<div class="card-body">
										<h1>Hey!</h1>
										<p>It looks like you don't have any instagram accounts saved yet.</p>
									</div>
									<a href="<?php echo $dashboard . 'settings/?page=accounts&add_account=true' ?>" class="btn btn-ig">
										Add new account
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php else: ?>
		<div class="instaplaner__profile">
			<div class="container">
				<div class="row">
					<div class="col-12 col-lg-4">
						<div class="instaplaner__profile__header">
							<div class="instaplaner__profile__header--container">
								<img class="instaplaner__profile__header--image" src="<?php echo $this->baseurl . $this->Master->Options->Get( 'profile_library', 'media/img/profile/' ) . $this->CurrentAccount('avatar'); ?>" alt="Insta Planer profile picture">
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-8">
						<div class="instaplaner__profile__description">
							<div class="instaplaner__profile__description--title">
								<div class="dropdown">
									<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<?php echo strtolower( $this->CurrentAccount('name') ); ?>
										<svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-chevron-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
										</svg>
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<?php foreach ( $this->GetAccounts() as $account ) {
											echo ($this->CurrentAccount('id') != $account['id'] ? '<a class="dropdown-item" href="' . $dashboard . 'account/' . $account['id'] . '">' . strtolower( $account['name'] ) . '</a>' : '');
										} ?>
									</div>
								</div>
								<div style="display: flex;width: 100%;">
									<a class="instaplaner__profile__description--href" href="<?php echo $dashboard . 'settings' ?>">Settings</a>
									<a class="instaplaner__profile__description--href" href="<?php echo $dashboard . 'signout' ?>">Sign Out</a>
									<a class="instaplaner__profile__description--href instaplaner__profile__description--share" href="#share" style="max-width: 50px;">
										<svg width="13px" height="13px" viewBox="0 0 16 16" class="bi bi-arrow-90deg-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
										</svg>
									</a>
								</div>
							</div>
						</div>

						<ul class="list-inline">
							<li class="list-inline-item">
								<strong><?php echo $this->CurrentAccount('posts'); ?></strong> posts
							</li>
							<li class="list-inline-item">
								<strong><?php echo $this->CurrentAccount('followers'); ?></strong> followers
							</li>
							<li class="list-inline-item">
								<strong><?php echo $this->CurrentAccount('following'); ?></strong> following
							</li>
						</ul>

						<h1><?php echo $this->CurrentAccount('full_name'); ?></h1>
						<p>
							<?php echo $this->CurrentAccount('description'); ?>
						</p>
						<a target="_blank" rel="noopener" href="<?php echo $this->CurrentAccount('website'); ?>" class="instaplaner__profile--link"><?php echo trim(str_replace(array('https://', 'http://', 'www.'), array('','',''), $this->CurrentAccount('website')), '/'); ?></a>
						<div class="instaplaner__profile--followers">
							Followed by <a href="https://rdev.cc/?ref=instaplaner" target="_blank" rel="noopener">rapiddev</a> and <a href="https://4geek.co/" target="_blank" rel="noopener">4geek.co</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<main class="instaplaner__main">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="instaplaner__main__nav">
							<a id="instaplaner_nav_list" href="#" class="active">
								<svg width="12" height="12" viewBox="0 0 16 16" class="bi bi-grid-3x3-gap" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
								</svg>
								<span>Preview</span>
							</a>
							<a id="instaplaner_nav_reorder" href="#">
								<svg width="12" height="12" viewBox="0 0 16 16" class="bi bi-arrows-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M7.646.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 1.707V5.5a.5.5 0 0 1-1 0V1.707L6.354 2.854a.5.5 0 1 1-.708-.708l2-2zM8 10a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 14.293V10.5A.5.5 0 0 1 8 10zM.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L1.707 7.5H5.5a.5.5 0 0 1 0 1H1.707l1.147 1.146a.5.5 0 0 1-.708.708l-2-2zM10 8a.5.5 0 0 1 .5-.5h3.793l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L14.293 8.5H10.5A.5.5 0 0 1 10 8z"/>
								</svg>
								<span>Reorder</span>
							</a>
							<a id="instaplaner_nav_add" href="#" data-toggle="modal" data-target="#instaplaner__addphoto">
								<svg width="12" height="12" viewBox="0 0 16 16" class="bi bi-cloud-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
									<path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
								</svg>
								<span>Add new photo</span>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="container">
				<div class="row instaplaner__loader">
					<div class="col-12">
						<div class="text-center">
							<div class="spinner-border" role="status">
								<span class="sr-only">Loading...</span>
							</div>
						</div>
					</div>
				</div>
				<div id="instaplaner__posts" class="row">
					
					<!--<div class="col-12 col-lg-4 instaplaner__post instaplaner__post--preview"> <!-- instaplaner__post--preview -->
						<!--<div class="instaplaner__posts--post">
							<div class="instaplaner__posts__square" style="background-image: url('https://scontent-frx5-1.cdninstagram.com/v/t51.12442-15/e35/c74.315.949.949a/s150x150/64739174_2287426444670038_8666411748486845748_n.jpg?_nc_ht=scontent-frx5-1.cdninstagram.com&amp;_nc_cat=110&amp;_nc_ohc=4i3zM6CIUgEAX_TKp9j&amp;_nc_tp=16&amp;oh=2b9a651380e736ada8dcd11bb0d821f4&amp;oe=5F544F98');">
							</div>
							<div class="instaplaner__posts--description">
								<p>Opis</p>
							</div>
							<div class="instaplaner__posts--drag">
								<div class="instaplaner__posts--drag__icon"></div>
							</div>
						</div>
					</div>
				-->
				</div>
			</div>
		</main>
<?php endif; ?>
		<div class="modal fade instaplaner__addphoto" id="instaplaner__addphoto" data-keyboard="false" tabindex="-1" aria-labelledby="instaplaner__addphoto--label" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<form id="instaplanner_addphoto--form">
							<input type="hidden" value="<?php echo $this->AjaxNonce( 'add_post' ); ?>" name="nonce" id="addphoto_nonce">
							<input type="hidden" value="add_post" name="action" id="addphoto_action">
							<input type="hidden" value="<?php echo $this->CurrentAccount('id'); ?>" name="input_account" id="addphoto_account">
							<div class="form-group">
								<label for="input-description">Description</label>
								<textarea class="form-control form-control-sm" id="input-description" name="input-description" rows="3"></textarea>
							</div>
							<div class="form-group">
								<label for="input-file">Image</label>
								<input type="file" class="form-control-file" id="input-file" name="input-file" accept="image/x-png,image/gif,image/jpeg">
							</div>
						</form>
					</div>
					<div class="btn-group-vertical">
						<button id="instaplanner_addphoto--upload" type="button" class="btn btn-outline-dark">Upload</button>
						<button id="btn-add-blank" type="button" class="btn btn-outline-dark">Add a blank photo</button>
						<button type="button" class="btn btn-outline-dark" data-dismiss="modal"><span>Close</span></button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade instaplaner__editphoto" id="instaplaner__editphoto" data-keyboard="false" tabindex="-1" aria-labelledby="instaplaner__editphoto--label" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<img src="https://rdev.lan/dev/instaplaner/media/img/posts/2.jpg" class="card-img-top" alt="...">
					<div class="modal-body">
						<form>
							<div class="form-group">
								<label for="input-current-description">Description</label>
								<textarea class="form-control form-control-sm" id="input-current-description" name="input-current-description" rows="3"></textarea>
							</div>
						</form>
						<div class="instaplaner__editphoto--alert alert alert-danger" style="display: none;">
							Are you sure you want to delete this photo?
							<hr>
							<button class="instaplaner__editphoto--delete__confirm btn btn-sm btn-outline-danger">Confirm</button>
						</div>
					</div>
					<div class="btn-group-vertical">
						<button type="button" class="instaplaner__editphoto--update btn btn-outline-dark">Update</button>
						<!--<button type="button" class="instaplaner__editphoto--copy btn btn-outline-dark" data-clipboard-text="Empty">Copy description</button>-->
						<a href="#" download="#" type="button" class="instaplaner__editphoto--download btn btn-outline-dark">Download photo</a>
						<button type="button" class="instaplaner__editphoto--delete btn btn-outline-dark"><span>Delete</span></button>
						<button type="button" class="instaplaner__editphoto--cancel btn btn-outline-dark" data-dismiss="modal"><span>Close</span></button>
					</div>
				</div>
			</div>
		</div>
<?php
	$this->GetFooter();
?>
