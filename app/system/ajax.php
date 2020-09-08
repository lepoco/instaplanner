<?php namespace RapidDev\InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */
	
	use RapidDev\InstaPlanner\Ajax;
	
	/**
	*
	* InstaPlanner
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
    */
    class AjaxQuery extends Ajax
    {
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

		/**
		* register_account
		* Add a new account from Instagram profile and save it's profile picture
		*
		* @access   private
		* @return	void
		*/
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
			$media_library = $this->Master->Options->Get( 'profile_library', 'media/img/avatars/' );

			while ( is_file( ABSPATH . $media_library . $filename ) )
			{
				$filename = strtolower( Crypter::BaseSalter(30) ) . '.jpeg';
			}

			if( !$this->ForceFilePutContents( ABSPATH . $media_library . $filename, fopen( $_POST[ 'avatar' ], 'r' ) ) )
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

		/**
		* add_post
		* Add a new post to the database assigned to selected account
		*
		* @access   private
		* @return	void
		*/
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

			$media_library = $this->Master->Options->Get( 'posts_library', 'media/img/posts/' );

			while ( is_file( ABSPATH . $media_library . $filename ) )
			{
				$filename = strtolower( Crypter::BaseSalter(30) ) . $extension;
			}

			if ( !is_dir( ABSPATH . $media_library ) )
				mkdir( ABSPATH . $media_library, 0777, true );

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

		/**
		* save_reorder
		* Save the order in which the posts are displayed
		*
		* @access   private
		* @return	void
		*/
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

		/**
		* delete_post
		* Delete the post from the database and its photo
		*
		* @access   private
		* @return	void
		*/
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

			if ( !unlink( ABSPATH . $this->Master->Options->Get( 'posts_library', 'media/img/posts/' ) . $post_data['image'] ) )
				$this->Finish( self::ERROR_DELETING_FILE );

			$query = $this->Master->Database->query(
				"DELETE FROM rdev_posts WHERE id = ?",
				$post_id
			);

			$this->Finish( self::CODE_SUCCESS );
		}

		/**
		* update_post
		* Update the post description in the database
		*
		* @access   private
		* @return	void
		*/
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