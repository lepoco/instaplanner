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

	use RapidDev\InstaPlanner\Models as Template;

	/**
	*
	* Model [Dashboard]
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class Model extends Template
	{

		protected function Init()
		{
			$add_account = false;
			if( isset($_GET['add_account']))
			{
				$add_account = true;
			}

			$this->AddPageData( 'register_new_account', $add_account );
			$this->AddPageData( 'register_account_nonce', $this->AjaxNonce( 'register_account' ) );
		}
		/**
		* GetAccounts
		* Get all instagram accounts
		*
		* @access   private
		*/
		protected function GetAccounts()
		{
			$query = $this->Master->Database->query( "SELECT * FROM rdev_accounts" )->fetchAll();

			if( !empty( $query ) )
				return $query;
			else
				return array();
		}
	}
