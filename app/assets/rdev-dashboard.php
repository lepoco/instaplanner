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

 	use RapidDev\InstaPlanner\AjaxQuery;

	/**
	*
	* Dashboard
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class Dashboard
	{
		/**
		 * Master class instance
		 *
		 * @var Master
		 * @access private
		 */
		private $Master;

		/**
		 * Current dashboard page
		 *
		 * @var string
		 * @access private
		 */
		private $subpage;

		/**
		 * List of available pages
		 *
		 * @var array
		 * @access private
		 */
		private static $pages = array(
			'__dashboard__',
			'ajax',
			'account',
			'signout',
			'settings',
			'login'
		);

		/**
		* __construct
		* Class constructor
		*
		* @access   public
		*/
		public function __construct( Object &$parent )
		{
			$this->Master = $parent;

			$this->SetPage();
			$this->IfExists();


			if( !$this->Master->User->IsLoggedIn() )
			{
				if( $this->subpage != 'login' && $this->subpage != 'ajax' )
					$this->RedirectTo( $this->Master->Options->Get( 'login', 'login' ) );
			}
			else
			{
				if( $this->subpage == 'login' )
					$this->RedirectTo();
			}

			if( trim( $this->Master->Path->GetLevel( 2 ) ) != '' && $this->subpage != 'account' )
			{
				$this->Master->LoadModel( '404', 'Page not found' );
			}

			switch ($this->subpage)
			{
				case 'ajax':
					new AjaxQuery( $this->Master );
					break;

				case '__dashboard__':

					$this->Master->LoadModel( 'dashboard', 'Schedule your Instagram posts' );
					break;

				case 'account':
					$this->SwapAccount( $this->Master->Path->GetLevel( 2 ) );
					$this->RedirectTo();
					break;

				case 'settings':
					$this->Master->LoadModel( 'settings', 'Settings' );
					break;

				case 'login':
					$this->Master->LoadModel( 'login', 'Sign in' );
					break;

				case 'signout':
					$this->Master->User->LogOut();
					$this->Master->Path->Redirect( $this->Master->Options->Get( 'base_url', $this->Master->Path->ScriptURI() ) . '/login' );
					break;
				
				default:
					$this->Master->LoadModel( '404', 'Page not found' );
					break;
			}
			
			//End ajax query
			$this->Master->Session->Close();
			exit;
		}

		/**
		* RedirectLogin
		* Redirect to login if illegal dashboard page
		*
		* @access   private
		*/
		private function RedirectTo( $slug = null ) : void
		{
			$this->Master->Path->Redirect(
				$this->Master->Options->Get(
					'base_url',
					$this->Master->Path->ScriptURI()
				) . $this->Master->Options->Get( 'dashboard', 'dashboard' ) . '/' . $slug
			);
		}

		/**
		* SetPage
		* Defines current dashboard page
		*
		* @access   private
		*/
		private function SetPage() : void
		{
			if( $this->Master->Path->GetLevel( 1 ) == null )
				$this->subpage = '__dashboard__';
			else
				$this->subpage = $this->Master->Path->GetLevel( 1 );
		}

		/**
		* IfExists
		* Checks if the selected page exists
		*
		* @access   private
		*/
		private function IfExists() : void
		{	
			if( !in_array( $this->subpage, self::$pages ) )
				$this->Master->LoadModel( '404', 'Page not found' );
		}

		private function SwapAccount( $id ) : void
		{
			$query = $this->Master->Database->query(
				"UPDATE rdev_users SET user_selected_account = ? WHERE user_id = ?",
				$id,
				$this->Master->User->CurrentID()
			);

			//$this->Master->User->UpadeField('user_selected_account', $id);
		}
	}
