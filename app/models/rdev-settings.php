<?php namespace InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
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
	* Model [Install]
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class Model extends Models
	{
		/**
		* GetAccounts
		* Get all instagram accounts
		*
		* @access   private
		*/
		protected function GetAccounts()
		{
			$query = $this->InstaPlanner->Database->query( "SELECT * FROM rdev_accounts" )->fetchAll();

			if( !empty( $query ) )
				return $query;
			else
				return array();
		}

		protected function Header()
		{	
			$add_account = false;
			if( isset($_GET['add_account']))
			{
				$add_account = true;
			}
			echo '<script>let register_account_nonce = \'' . $this->AjaxNonce( 'register_account' ) . '\';let add_new_account = ' . ($add_account ? 'true' : 'false') . ';</script>';
		}
	}