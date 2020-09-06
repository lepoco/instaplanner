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
?>
			<div class="instaplanner-splash_background">
				<picture>
					<source srcset="<?php echo $this->GetImage('bg.webp') ?>" type="image/webp">
					<source srcset="<?php echo $this->GetImage('bg.jpeg') ?>" type="image/jpeg">
					<img alt="Forward big background image" src="<?php echo $this->GetImage('bg.jpeg') ?>">
				</picture>
			</div>

			<section class="instaplanner-login">
				<div class="container">
					<div class="row">
						<div class="col-12 col-lg-6">
							<div class="instaplanner-login__logo">
								<img src="<?php echo $this->GetImage('instaplaner-white.svg') ?>" alt="InstaPlaner logo">
							</div>
						</div>
						<div class="col-12 col-lg-6">
							<div class="instaplanner-login__card">
								<form id="login-form" class="login-form">
									<div class="card">
										<div class="card-body">
											<input type="hidden" value="<?php echo $this->AjaxNonce( 'sign_in' ); ?>" name="nonce">
											<input type="hidden" value="sign_in" name="action">
											<div class="form-group">
												<label for="login">Login</label>
												<input type="text" class="form-control form-ig" name="login" id="login" placeholder="Enter username/email">
											</div>
											<div class="form-group" style="margin-top: 10px;">
												<label for="password">Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control form-ig" id="password" name="password" placeholder="Password">
													<div class="input-group-addon">
														<a href=""><svg style="width:15px;height:15px" viewBox="0 0 24 24"><path fill="currentColor" d="M17,7H22V17H17V19A1,1 0 0,0 18,20H20V22H17.5C16.95,22 16,21.55 16,21C16,21.55 15.05,22 14.5,22H12V20H14A1,1 0 0,0 15,19V5A1,1 0 0,0 14,4H12V2H14.5C15.05,2 16,2.45 16,3C16,2.45 16.95,2 17.5,2H20V4H18A1,1 0 0,0 17,5V7M2,7H13V9H4V15H13V17H2V7M20,15V9H17V15H20M8.5,12A1.5,1.5 0 0,0 7,10.5A1.5,1.5 0 0,0 5.5,12A1.5,1.5 0 0,0 7,13.5A1.5,1.5 0 0,0 8.5,12M13,10.89C12.39,10.33 11.44,10.38 10.88,11C10.32,11.6 10.37,12.55 11,13.11C11.55,13.63 12.43,13.63 13,13.11V10.89Z" /></svg></a>
													</div>
												</div>
											</div>
											<div id="login-alert" class="alert alert-danger fade show" role="alert" style="display: none;margin-top: 30px;">
												<strong>Holy guacamole!</strong> You entered an incorrect login or password
											</div>
										</div>
										<button type="submit" id="button-form" class="btn btn-ig">
											Submit
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
			<div class="instaplanner-home__footer">
				Copyright © <?php echo date('Y'); ?> RapidDev | MIT License
				<br>
				Background image: Aerial View Of Eiffel Tower by <i>Chris Molloy</i>
				<br>
				Icons: Bootstrap Icons by <i>The Bootstrap Authors</i>
				<br>
				Logo font: Billabong™ by <i>Russell Bean</i>
			</div>
<?php
	$this->GetFooter();
?>
