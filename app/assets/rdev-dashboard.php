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
	* Dashboard
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class Dashboard
	{
		/**
		 * InstaPlanner class instance
		 *
		 * @var InstaPlanner
		 * @access private
		 */
		private $InstaPlanner;

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
		public function __construct( InstaPlanner &$parent )
		{
			$this->InstaPlanner = $parent;

			$this->SetPage();
			$this->IfExists();


			if( !$this->InstaPlanner->User->IsLoggedIn() )
			{
				if( $this->subpage != 'login' && $this->subpage != 'ajax' )
					$this->RedirectTo( $this->InstaPlanner->Options->Get( 'login', 'login' ) );
			}
			else
			{
				if( $this->subpage == 'login' )
					$this->RedirectTo();
			}

			if( trim( $this->InstaPlanner->Path->GetLevel( 2 ) ) != '' && $this->subpage != 'account' )
			{
				$this->InstaPlanner->LoadModel( '404', 'Page not found' );
			}

			switch ($this->subpage)
			{
				case 'ajax':
					new Ajax( $this->InstaPlanner );
					break;

				case '__dashboard__':

					$this->InstaPlanner->LoadModel( 'dashboard', 'Schedule your Instagram posts' );
					break;

				case 'account':
					$this->SwapAccount( $this->InstaPlanner->Path->GetLevel( 2 ) );
					$this->InstaPlanner->LoadModel( 'dashboard', 'Schedule your Instagram posts' );
					break;

				case 'settings':
					$this->InstaPlanner->LoadModel( 'settings', 'Settings' );
					break;

				case 'login':
					$this->InstaPlanner->LoadModel( 'login', 'Sign in' );
					break;

				case 'signout':
					$this->InstaPlanner->User->LogOut();
					$this->InstaPlanner->Path->Redirect( $this->InstaPlanner->Options->Get( 'base_url', $this->InstaPlanner->Path->ScriptURI() ) . '/login' );
					break;
				
				default:
					$this->InstaPlanner->LoadModel( '404', 'Page not found' );
					break;
			}
			
			//End ajax query
			$this->InstaPlanner->Session->Close();
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
			$this->InstaPlanner->Path->Redirect(
				$this->InstaPlanner->Options->Get(
					'base_url',
					$this->InstaPlanner->Path->ScriptURI()
				) . $this->InstaPlanner->Options->Get( 'dashboard', 'dashboard' ) . '/' . $slug
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
			if( $this->InstaPlanner->Path->GetLevel( 1 ) == null )
				$this->subpage = '__dashboard__';
			else
				$this->subpage = $this->InstaPlanner->Path->GetLevel( 1 );
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
				$this->InstaPlanner->LoadModel( '404', 'Page not found' );
		}

		private function SwapAccount( $id ) : void
		{
			$query = $this->InstaPlanner->Database->query(
				"UPDATE rdev_users SET user_selected_account = ? WHERE user_id = ?",
				$id,
				$this->InstaPlanner->User->CurrentID()
			);

			$this->InstaPlanner->User->UpadeField('user_selected_account', $id);
		}
	}
?>
