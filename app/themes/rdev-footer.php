<?php namespace InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */
?>
		<div class="instaplaner__toast instaplaner__toast--container">
			<!-- Then put toasts within -->
			<div class="toast instaplaner__toast__mode" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
				<div class="toast-header">
					<strong class="mr-auto">Instaplaner</strong>
					<small class="text-muted">just now</small>
					<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="toast-body">
				</div>
			</div>
		</div>
<?php if ($this->name != 'home' && $this->name != 'login' && $this->name != 'install'): ?>
		<footer class="instaplaner__footer">
			<div class="container">
				<div class="row">
					<div class="col-12 col-lg-6">
						<ul class="list-inline instaplaner__footer__links">
							<li class="list-inline-item">
								<a href="https://github.com/rapiddev/instaplaner" rel="nofollow noopener" target="_blank">Source Code on GitHub</a>
							</li>
							<li class="list-inline-item">
								<a href="https://rdev.cc/" rel="nofollow noopener" target="_blank">RapidDev</a>
							</li>
							<li class="list-inline-item">
								<a href="https://instagram.com/" rel="nofollow noopener" target="_blank">Instagram</a>
							</li>
						</ul>
					</div>
					<div class="col-12 col-lg-6">
						<div class="instaplaner__footer__copyright">
							© 2020 RAPIDDEV | INSTAPLANER
							<br>
							BASED ON INSTAGRAM FROM FACEBOOK
						</div>
					</div>
				</div>
			</div>
		</footer>
		<div class="instaplaner__footer--clone"></div>
<?php endif; ?>
<?php foreach ($this->scripts as $script): ?>
		<script type="text/javascript" src="<?php echo $script[0] . (isset($script[2]) ? '?ver=' . $script[2] : '') ?>" integrity="<?php echo $script[1]; ?>" crossorigin="anonymous"></script>
<?php endforeach ?>
<?php if( method_exists( $this, 'Footer' ) ) { $this->Footer(); } ?>
	</body>
</html>