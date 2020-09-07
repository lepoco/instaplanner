<?php namespace InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package Master
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */

	/**
	*
	* Ajax
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class Ajax
	{
		/** ERROR CODES */
		private const ERROR_UNKNOWN                  = 'e00';
		private const ERROR_MISSING_ACTION           = 'e01';
		private const ERROR_MISSING_NONCE            = 'e02';
		private const ERROR_INVALID_NONCE            = 'e03';
		private const ERROR_INVALID_ACTION           = 'e04';
		private const ERROR_INSUFFICIENT_PERMISSIONS = 'e05';
		private const ERROR_MISSING_ARGUMENTS        = 'e06';
		private const ERROR_EMPTY_ARGUMENTS          = 'e07';
		private const ERROR_ENTRY_EXISTS             = 'e08';
		private const ERROR_ENTRY_DONT_EXISTS        = 'e09';
		private const ERROR_INVALID_URL              = 'e10';
		private const ERROR_INVALID_PASSWORD         = 'e11';
		private const ERROR_PASSWORDS_DONT_MATCH     = 'e12';
		private const ERROR_PASSWORD_TOO_SHORT       = 'e13';
		private const ERROR_PASSWORD_TOO_SIMPLE      = 'e14';
		private const ERROR_INVALID_EMAIL            = 'e15';
		private const ERROR_SPECIAL_CHARACTERS       = 'e16';
		private const ERROR_FILE_TYPE                = 'e17';
		private const ERROR_SAVING_FILE              = 'e18';
		private const ERROR_DELETING_FILE            = 'e19';

		private const CODE_SUCCESS                   = 's01';
		
		/**
		 * Master class instance
		 *
		 * @var Master
		 * @access private
		 */
		private $Master;

		/**
		 * Current ajax action
		 *
		 * @var string
		 * @access private
		 */
		private $action = '';

		/**
		 * Current ajax nonce
		 *
		 * @var string
		 * @access private
		 */
		private $nonce = '';

		/**
		* __construct
		* Class constructor
		*
		* @access   public
		*/
		public function __construct( Object &$parent )
		{
			$this->Master = $parent;

			if( $this->IsNull() )
				exit('Bad gateway');

			if ( !isset( $_POST['action'] ) )
				exit( self::ERROR_MISSING_ACTION );
			else
				$this->action = filter_var( $_POST['action'], FILTER_SANITIZE_STRING );

			if ( !isset( $_POST['nonce'] ) )
				exit( self::ERROR_MISSING_NONCE );
			else
				$this->nonce = filter_var( $_POST['nonce'], FILTER_SANITIZE_STRING );

			if( !$this->ValidNonce() )
				exit( self::ERROR_INVALID_NONCE );

			if( !$this->ValidAction() )
				exit(self::ERROR_INVALID_ACTION);
			else
				$this->{$this->action}();

			$this->Finish();
		}

		/**
		* ValidNonce
		* Nonce validation
		*
		* @access   private
		* @return	bool
		*/
		private function ValidNonce() : bool
		{
			if( isset( $_POST['nonce'] ) )
				if( Crypter::Compare( 'ajax_' . $this->action . '_nonce', $this->nonce, 'nonce' ) )
					return true;
				else
					return false;
			else
				return false;
		}

		/**
		* ValidAction
		* Action validation
		*
		* @access   private
		* @return	bool
		*/
		private function ValidAction() : bool
		{
			if( method_exists( $this, $this->action ) )
				return true;
			else
				return false;
		}

		/**
		* IsNull
		* If $_POST is not empty
		*
		* @access   private
		* @return	bool
		*/
		private function IsNull() : bool
		{
			if( !empty( $_POST ) )
				return false;
			else
				return true;
		}

		/**
		* Finish
		* End ajax script
		*
		* @access   private
		* @return	bool
		*/
		private function Finish( $text = null, $json = false )
		{
			$this->Master->Session->Close();

			if( $text == null )
				echo ERROR_UNKNOWN;
			else
				if( $json )
					echo json_encode( $text, JSON_UNESCAPED_UNICODE );
				else
					echo $text;

			exit;
		}


		/**
			Ajax methods
		*/

		/**
		* sign_in
		* The action is triggered on login
		*
		* @access   private
		* @return	void
		*/
		private function sign_in() : void
		{
			if( !isset( $_POST['login'], $_POST['password'] ) )
				$this->Finish( self::ERROR_MISSING_ARGUMENTS );

			if( empty( $_POST['login'] ) || empty( $_POST['password'] ) )
				$this->Finish( self::ERROR_ENTRY_DONT_EXISTS );

			$login = filter_var( $_POST['login'], FILTER_SANITIZE_STRING );
			$password = filter_var( $_POST['password'], FILTER_SANITIZE_STRING );

			$user = $this->Master->User->GetByName( $login );

			if( empty( $user ))
				$user = $this->Master->User->GetByEmail( $login );
			
			if( empty( $user ))
				$this->Finish( self::ERROR_ENTRY_DONT_EXISTS );

			if( !Crypter::Compare( $password, $user['user_password'], 'password' ) )
				$this->Finish( self::ERROR_ENTRY_DONT_EXISTS );

			$this->Master->User->LogIn( $user );

			$this->Finish( self::CODE_SUCCESS );
		}

		private function add_post() : void
		{
			if( !$this->Master->User->IsManager() )
				$this->Finish( self::ERROR_INSUFFICIENT_PERMISSIONS );

			if( !isset(
				$_POST[ 'input-description' ],
				$_POST[ 'input-account' ]
			) )
				$this->Finish( self::ERROR_MISSING_ARGUMENTS );

			if( !isset(
				$_FILES[ 'input-file' ]
			) )
				$this->Finish( self::ERROR_MISSING_ARGUMENTS );

			if( !in_array(
				strtolower( pathinfo( $_FILES[ 'input-file' ][ 'name' ], PATHINFO_EXTENSION ) ), array( 'jpg', 'jpeg', 'png', 'gif' )
			) )
				$this->Finish( self::ERROR_FILE_TYPE );

			$extension = '';
			switch ( $_FILES[ 'input-file' ][ 'type' ] )
			{
				case 'image/png':
					$extension = '.png';
					break;
				case 'image/jpeg':
					$extension = '.jpeg';
					break;
				case 'image/gif':
					$extension = '.gif';
					break;
			}

			$filename = strtolower( Crypter::BaseSalter(30) ) . $extension;

			$media_library = $this->Master->Options->Get( 'media_library', 'media/img/posts/' );

			while ( is_file( ABSPATH . $media_library . $filename ) )
			{
				$filename = strtolower( Crypter::BaseSalter(30) ) . $extension;
			}

			if( move_uploaded_file( $_FILES['input-file']['tmp_name'], ABSPATH . $media_library . $filename ) )
			{
				$query = $this->Master->Database->query(
					"INSERT INTO rdev_posts (account_id, image, description) VALUES (?,?,?)",
					(int)filter_var( $_POST[ 'input-account' ], FILTER_SANITIZE_NUMBER_INT ),
					$filename,
					filter_var( $_POST[ 'input-description' ], FILTER_SANITIZE_STRING )
				);

				$this->Finish( '[' . $query->lastInsertID() . ', "' . $filename . '"]' );
			}
			else
			{
				$this->Finish( self::ERROR_UNKNOWN );
			}
		}

		private function save_reorder() : void
		{
			if( !$this->Master->User->IsManager() )
				$this->Finish( self::ERROR_INSUFFICIENT_PERMISSIONS );

			if( !isset(
				$_POST[ 'order' ],
				$_POST[ 'account' ]
			) )
				$this->Finish( self::ERROR_MISSING_ARGUMENTS );

			$query = $this->Master->Database->query(
				"UPDATE rdev_accounts SET post_order = ? WHERE id = ?",
				filter_var( $_POST[ 'order' ], FILTER_SANITIZE_STRING ),
				(int)$_POST[ 'account' ]
			);
			
			$this->Finish( self::CODE_SUCCESS );
		}

		private function register_account() : void
		{
			if( !$this->Master->User->IsManager() )
				$this->Finish( self::ERROR_INSUFFICIENT_PERMISSIONS );

			if( !isset(
				$_POST[ 'name' ],
				$_POST[ 'full_name' ],
				$_POST[ 'biography' ],
				$_POST[ 'url' ],
				$_POST[ 'avatar' ],
				$_POST[ 'followers' ],
				$_POST[ 'following' ],
				$_POST[ 'posts' ]
			) )
				$this->Finish( self::ERROR_MISSING_ARGUMENTS );

			$filename = strtolower( Crypter::BaseSalter(30) ) . '.jpeg';
			$media_library = $this->Master->Options->Get( 'profile_library', 'media/img/profile/' );

			while ( is_file( ABSPATH . $media_library . $filename ) )
			{
				$filename = strtolower( Crypter::BaseSalter(30) ) . '.jpeg';
			}

			if( !file_put_contents( ABSPATH . $media_library . $filename, fopen( $_POST[ 'avatar' ], 'r' ) ) )
			{ 
				$this->Finish( self::ERROR_SAVING_FILE );
			}

			$query = $this->Master->Database->query(
				"INSERT INTO rdev_accounts (name, full_name, avatar, website, posts, followers, following, description) VALUES (?,?,?,?,?,?,?,?)",
				filter_var( $_POST[ 'name' ], FILTER_SANITIZE_STRING ),
				filter_var( $_POST[ 'full_name' ], FILTER_SANITIZE_STRING ),
				$filename, //image
				filter_var( $_POST[ 'url' ], FILTER_SANITIZE_STRING ),
				filter_var( $_POST[ 'posts' ], FILTER_SANITIZE_STRING ),
				filter_var( $_POST[ 'followers' ], FILTER_SANITIZE_STRING ),
				filter_var( $_POST[ 'following' ], FILTER_SANITIZE_STRING ),
				filter_var( $_POST[ 'biography' ], FILTER_SANITIZE_STRING )
			);

			$query = $this->Master->Database->query(
				"UPDATE rdev_users SET user_selected_account = ? WHERE user_id = ?",
				$query->lastInsertID(),
				$this->Master->User->CurrentID()
			);
			
			$this->Finish( self::CODE_SUCCESS );
		}

		private function delete_post() : void
		{
			if( !$this->Master->User->IsManager() )
				$this->Finish( self::ERROR_INSUFFICIENT_PERMISSIONS );

			if( !isset(
				$_POST[ 'post' ]
			) )
				$this->Finish( self::ERROR_MISSING_ARGUMENTS );

			$post_id = (int)filter_var( $_POST[ 'post' ], FILTER_SANITIZE_NUMBER_INT );

			$post_data = $this->Master->Database->query(
				"SELECT image FROM rdev_posts WHERE id = ?",
				$post_id
			)->fetchArray();

			if( empty( $post_data ) )
				$this->Finish( self::ERROR_ENTRY_DONT_EXISTS );

			if ( !unlink( ABSPATH . $this->Master->Options->Get( 'media_library', 'media/img/posts/' ) . $post_data['image'] ) )
				$this->Finish( self::ERROR_DELETING_FILE );

			$query = $this->Master->Database->query(
				"DELETE FROM rdev_posts WHERE id = ?",
				$post_id
			);

			$this->Finish( self::CODE_SUCCESS );
		}

		private function update_post() : void
		{
			if( !$this->Master->User->IsManager() )
				$this->Finish( self::ERROR_INSUFFICIENT_PERMISSIONS );

			if( !isset(
				$_POST[ 'post' ],
				$_POST[ 'description' ]
			) )
				$this->Finish( self::ERROR_MISSING_ARGUMENTS );

			$post_id = (int)filter_var( $_POST[ 'post' ], FILTER_SANITIZE_NUMBER_INT );

			$post_data = $this->Master->Database->query(
				"SELECT image FROM rdev_posts WHERE id = ?",
				$post_id
			)->fetchArray();

			if( empty( $post_data ) )
				$this->Finish( self::ERROR_ENTRY_DONT_EXISTS );

			$query = $this->Master->Database->query(
				"UPDATE rdev_posts SET description = ? WHERE id = ?",
				filter_var( $_POST[ 'description' ], FILTER_SANITIZE_STRING ),
				$post_id
			);

			$this->Finish( self::CODE_SUCCESS );
		}
	}
?>
