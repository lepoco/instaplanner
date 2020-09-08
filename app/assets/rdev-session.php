<?php namespace RapidDev\InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */

	/**
	*
	* Session
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class Session
	{
		/**
		* Open
		* Opens a new session
		*
		* @access   public
		* @return   void
		*/
		public function Open() : void
		{
			session_start();
			session_regenerate_id();
		}

		/**
		* Destroy
		* Destroys the session and data in it
		*
		* @access   public
		* @return   void
		*/
		public function Destroy() : void
		{
			session_destroy();
		}

		/**
		* Open
		* Closes the current session
		*
		* @access   public
		* @return   void
		*/
		public function Close() : void
		{
			
		}
	}
