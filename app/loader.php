<?php
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */
	namespace RapidDev\InstaPlanner;
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

	/** Config */
	if ( is_file( ABSPATH . 'app/config.php' ) )
		require_once ABSPATH . 'app/config.php';

	/** Assets **/
	require_once ASSPATH . 'rdev-uri.php';

	require_once ASSPATH . 'rdev-crypter.php';
	
	require_once ASSPATH . 'rdev-database.php';
	
	require_once ASSPATH . 'rdev-session.php';

	require_once ASSPATH . 'rdev-options.php';

	require_once ASSPATH . 'rdev-jsparse.php';

	require_once ASSPATH . 'rdev-models.php';

	require_once ASSPATH . 'rdev-user.php';

	require_once ASSPATH . 'rdev-ajax.php';

	/** System **/
	require_once SYSPATH . 'ajax.php';

	require_once SYSPATH . 'router.php';
	
	require_once SYSPATH . 'master.php';
