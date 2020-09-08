<?php namespace RapidDev\InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */

	use DateTime;
	use RapidDev\InstaPlanner\Database;

	/**
	*
	* User
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class User
	{
		/**
		 * Master class instance
		 *
		 * @var Master
		 * @access private
		 */
		private $Master;

		/**
		 * Active user id
		 *
		 * @var int
		 * @access private
		 */
		private $id;

		/**
		 * Current user
		 *
		 * @var array
		 * @access private
		 */
		private $User;

		/**
		* __construct
		* Class constructor
		*
		* @access   public
		*/
		public function __construct( Object &$parent )
		{
			$this->Master = $parent;
		}

		/**
		* LogIn
		* Sign in selected user
		*
		* @param	array $user
		* @access   public
		*/
		public function LogIn( array $user ) : void
		{
			$token = Crypter::Encrypt( Crypter::DeepSalter(30), 'token' );

			if( $this->Master->Database == null )
				$this->Master->Database = new Database( DB_HOST, DB_NAME, DB_USER, DB_PASS );

			$query = $this->Master->Database->query(
				"UPDATE rdev_users SET user_token = ?, user_last_login = ? WHERE user_id = ?",
				$token,
				(new DateTime())->format('Y-m-d H:i:s'),
				$user['user_id']
			);

			$this->id = $user['user_id'];

			session_regenerate_id();
			$_SESSION = array(
				'l' => true,
				'u' => $user['user_id'],
				't' => $token,
				'r' => $user['user_role']
			);
		}

		/**
		* LogOut
		* Sign out selected user and destroy session
		*
		* @access   public
		*/
		public function LogOut() : void
		{
			if( isset( $_SESSION['u'], $_SESSION['t'], $_SESSION['r'] ) )
			{
				if( $this->User == null )
					$this->GetUser( $_SESSION['u'] );

				$query = $this->Master->Database->query(
					"UPDATE rdev_users SET user_token = ? WHERE user_id = ?",
					'',
					$this->User['user_id'],
				);
			}

			$this->Master->Session->Destroy();
		}

		/**
		* IsLoggedIn
		* Checks if the user is correctly logged in
		*
		* @access   public
		*/
		public function IsLoggedIn() : bool
		{
			if( isset( $_SESSION['u'], $_SESSION['t'], $_SESSION['r'] ) )
			{
				if( $this->User == null )
					$this->GetUser( $_SESSION['u'] );

				if($this->User != null)
				{
					if( isset( $this->User['user_token'], $this->User['user_role'] ) )
					{
						if( Crypter::Compare($_SESSION['t'], $this->User['user_token'], 'token', false)  && $_SESSION['r'] == $this->User['user_role'] )
						{
							return true;
						}
					}
				}
			}
			
			return false;
		}

		public function Active() : array
		{
			if( $this->User == null )
				$this->GetUser( $this->id );

			return $this->User;
		}

		/**
		* GetUser
		* Get's user by id
		*
		* @param	int $id
		* @access   public
		*/
		private function GetUser( int $id )
		{
			if( $this->Master->Database != null )
			{
				$query = $this->Master->Database->query( "SELECT * FROM rdev_users WHERE user_id = ?", $id )->fetchArray();

				if($query != null)
				{
					$this->id = $query['user_id'];
					$this->User = $query;
				}
			}
		}

		public function UpadeField( $id, $value )
		{
			if( $this->User == null )
				$this->GetUser( $this->id );

			$this->User[ $id ] = $value;
		}

		/**
		* CurrentID
		* Get's user by id
		*
		* @param	int $id
		* @access   public
		*/
		public function CurrentID( )
		{
			if( $this->User == null )
				$this->GetUser( $this->id );

			return $this->User['user_id'];
		}

		/**
		* GetByName
		* Get's user by username
		*
		* @param	string $username
		* @access   public
		*/
		public function GetByName( string $username )
		{
			$query = $this->Master->Database->query( "SELECT user_id, user_email, user_password, user_role, user_token FROM rdev_users WHERE user_name = ?", $username )->fetchArray();
			return $query;
		}

		/**
		* GetByEmail
		* Get's user by e-mail
		*
		* @param	string $username
		* @access   public
		*/
		public function GetByEmail( string $email )
		{
			$query = $this->Master->Database->query( "SELECT user_id, user_name, user_password, user_role, user_token FROM rdev_users WHERE user_email = ?", $email )->fetchArray();
			return $query;
		}

		/**
		* IsAdmin
		* Is current user admin
		*
		* @param	bool
		* @access   public
		*/
		public function IsAdmin() : bool
		{
			if( $this->User == null )
				$this->GetUser( $this->id );

			if( isset($_SESSION['r']) )
			{
				if( $this->User['user_role'] == $_SESSION['r'] && $this->User['user_role'] == 'admin' )
					return true;
			}

			return false;
		}

		/**
		* IsManager
		* Is current user admin or manager
		*
		* @param	bool
		* @access   public
		*/
		public function IsManager() : bool
		{
			if( $this->IsAdmin() )
				return true;

			if( isset($_SESSION['r']) )
			{
				if( $this->User['user_role'] == $_SESSION['r'] && $this->User['user_role'] == 'manager' )
					return true;
			}

			return false;
		}
	}
