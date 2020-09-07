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

			<section class="instaplanner-404">
				<div class="container">
					<div class="row">
						<div class="col-12 col-lg-6">
							<div class="instaplanner-home__logo">
								<img src="<?php echo $this->GetImage('instaplaner-white.svg') ?>" alt="InstaPlaner logo">
							</div>
						</div>
						<div class="col-12 col-lg-6">
							<div class="instaplanner-home__card">
								<div class="card">
									<div class="card-body">
										<h1>ERROR 404</h1>
										<p>This page does not exist, you must be lost...</p>
									</div>
									<a href="<?php echo $this->baseurl . ($this->InstaPlanner->User->IsLoggedIn() ? $this->InstaPlanner->Options->Get( 'dashboard', 'dashboard' ) : '' ); ?>" class="btn btn-ig">
										Get me out of here!
									</a>
								</div>
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
