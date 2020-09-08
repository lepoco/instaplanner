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

	/** Verify PHP version */
	if ( version_compare( $ver = PHP_VERSION, $req = '7.0.11', '<' ) )
		exit( sprintf( 'You are running PHP %s, but InstaPlanner needs at least <strong>PHP %s</strong> to run.', $ver, $req ) );
	
	/** Instaplaner version */
	define( 'INSTAPLANNER_VERSION', '1.0.0' );
	
	/** Main constants for all files */
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

	/** Initialization file */
	if ( !is_file( ABSPATH . 'app/loader.php' ) )
		exit('Fatal error');
	require_once ABSPATH . 'app/loader.php' ;

	/** Init **/
	( new InstaPlanner() );
