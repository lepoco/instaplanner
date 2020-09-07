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
	* InstaPlanner
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class InstaPlanner
	{
		/**
		 * Information about the address from the Uri class
		 *
		 * @var Uri
		 * @access public
		 */
		public $Path;

		/**
		 * Information about the session from the Session class
		 *
		 * @var Session
		 * @access public
		 */
		public $Session;

		/**
		 * A global class that stores options
		 *
		 * @var Options
		 * @access public
		 */
		public $Options;

		/**
		 * A set of user management tools
		 *
		 * @var User
		 * @access public
		 */
		public $User;

		/**
		 * Master database instance, requires config.php
		 *
		 * @var Database
		 * @access public
		 */
		public $Database;

		/**
		* __construct
		* Triggers and instances all necessary classes
		*
		* @access   public
		* @return   Forward
		*/
		public function __construct()
		{
			$this->Init();

			//If the configuration file does not exist or is damaged, start the installation
			if( !DEFINED( 'INSTAPLANNER_DB_NAME' ) )
			{
				$this->LoadModel( 'install', 'Installer' );
			}
			else
			{
				//Mechanism of action depending on the first part of the url
				switch ( $this->Path->GetLevel( 0 ) )
				{
					case $this->Options->Get( 'dashboard', 'dashboard' ):
					case $this->Options->Get( 'login', 'login' ):
						new Dashboard( $this );
						break;

					default:
						$this->LoadModel( 'home', 'Schedule your Instagram posts' );
						break;
				}
			}

			exit; //Just in case
		}

		/**
		* Init
		* Instances all necessary classes
		*
		* @access   private
		* @return   void
		*/
		private function Init() : void
		{
			$this->InitPath();
			$this->InitSession();
			$this->InitDatabase();
			$this->InitOptions();
			$this->InitUser();
		}

		/**
		* IsConfig
		* Checks if the configuration file exists
		*
		* @access   private
		* @return   bool
		*/
		private function IsConfig() : bool
		{
			if ( is_file( ABSPATH . 'app/config.php' ) )
				return true;
			else
				return false;
		}

		/**
		* InitPath
		* Initializes the Uri class
		*
		* @access   private
		* @return   void
		*/
		private function InitPath() : void
		{
			$this->Path = new Uri();
			$this->Path->Parse();
		}

		/**
		* InitSession
		* Initializes the Session class
		*
		* @access   private
		* @return   void
		*/
		private function InitSession() : void
		{
			$this->Session = new Session();
			$this->Session->Open();
		}

		/**
		* InitDatabase
		* Initializes the Database class
		*
		* @access   private
		* @return   void
		*/
		private function InitDatabase() : void
		{
			if( $this->IsConfig() )
				$this->Database = new Database( INSTAPLANNER_DB_HOST, INSTAPLANNER_DB_NAME, INSTAPLANNER_DB_USER, INSTAPLANNER_DB_PASS );
			else
				$this->Database = null;
		}

		/**
		* InitOptions
		* Initializes the Options class
		*
		* @access   private
		* @return   void
		*/
		private function InitOptions() : void
		{
			$this->Options = new Options( $this->Database );
		}

		/**
		* InitUser
		* Initializes the User class
		*
		* @access   private
		* @return   void
		*/
		private function InitUser() : void
		{
			$this->User = new User( $this );
		}

		/**
		* LoadModel
		* Loads the page model (logic)
		* The page model is inherited from assets/rdev-models.php
		*
		* @access   private
		* @return   void
		*/
		public function LoadModel( string $name, string $displayname = null )
		{
			if ( is_file( ABSPATH . "app/models/rdev-$name.php" ) )
			{
				require_once ABSPATH . "app/models/rdev-$name.php";
				( new Model( $this, $name, $displayname, INSTAPLANNER_VERSION ) )->Print();
			}
			else
			{
				if( is_file( ABSPATH . "app/themes/pages/rdev-$name.php" ) )
				{
					//Display the page without additional logic
					( new Models( $this, $name, $displayname, INSTAPLANNER_VERSION ) )->Print();
				}
				else
				{
					exit( "Unable to find model '$name'" );
				}
			}
		}
	}
?>
