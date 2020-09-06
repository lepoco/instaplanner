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

			<section class="instaplanner-home">
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
										<h1>InstaPlanner | <?php echo $this->Description(); ?></h1>
										<p>Schedule posts for your Instagram profile with InstaPlanner. You can add, save and organize photos. You can also easily write descriptions to them.</p>
									</div>
									<a href="https://github.com/rapiddev/InstaPlanner" target="_blank" rel="noopener" class="btn btn-ig">
										<div class="github">
											<svg style="width:18px;height:18px" fill="#fff" viewBox="0 0 24 24"><path d="M12,2A10,10 0 0,0 2,12C2,16.42 4.87,20.17 8.84,21.5C9.34,21.58 9.5,21.27 9.5,21C9.5,20.77 9.5,20.14 9.5,19.31C6.73,19.91 6.14,17.97 6.14,17.97C5.68,16.81 5.03,16.5 5.03,16.5C4.12,15.88 5.1,15.9 5.1,15.9C6.1,15.97 6.63,16.93 6.63,16.93C7.5,18.45 8.97,18 9.54,17.76C9.63,17.11 9.89,16.67 10.17,16.42C7.95,16.17 5.62,15.31 5.62,11.5C5.62,10.39 6,9.5 6.65,8.79C6.55,8.54 6.2,7.5 6.75,6.15C6.75,6.15 7.59,5.88 9.5,7.17C10.29,6.95 11.15,6.84 12,6.84C12.85,6.84 13.71,6.95 14.5,7.17C16.41,5.88 17.25,6.15 17.25,6.15C17.8,7.5 17.45,8.54 17.35,8.79C18,9.5 18.38,10.39 18.38,11.5C18.38,15.32 16.04,16.16 13.81,16.41C14.17,16.72 14.5,17.33 14.5,18.26C14.5,19.6 14.5,20.68 14.5,21C14.5,21.27 14.66,21.59 15.17,21.5C19.14,20.16 22,16.42 22,12A10,10 0 0,0 12,2Z" /></svg>
											<p>InstaPlanner | <?php echo $this->Description(); ?></p>
										</div>
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
