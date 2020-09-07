<?php namespace InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */

	/** Config */
	if ( is_file( ABSPATH . 'app/config.php' ) )
		require_once ABSPATH . 'app/config.php';

	/** Assets **/
	require_once ABSPATH . 'app/assets/' . 'rdev-uri.php';

	require_once ABSPATH . 'app/assets/' . 'rdev-crypter.php';
	
	require_once ABSPATH . 'app/assets/' . 'rdev-database.php';
	
	require_once ABSPATH . 'app/assets/' . 'rdev-session.php';

	require_once ABSPATH . 'app/assets/' . 'rdev-options.php';

	require_once ABSPATH . 'app/assets/' . 'rdev-jsparse.php';

	require_once ABSPATH . 'app/assets/' . 'rdev-models.php';

	require_once ABSPATH . 'app/assets/' . 'rdev-user.php';

	require_once ABSPATH . 'app/assets/' . 'rdev-dashboard.php';

	require_once ABSPATH . 'app/assets/' . 'rdev-ajax.php';

	require_once ABSPATH . 'app/system/' . 'instaplanner.php';

?>
