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

		protected $accounts = array();
		protected $current_account = array();

		protected function GetAccounts()
		{
			if( empty( $this->accounts ) )
			{
				$this->accounts = $this->Master->Database->query( "SELECT * FROM rdev_accounts" )->fetchAll();
			}

			if( !empty( $this->accounts ) )
				return $this->accounts;
			else
				return array();
		}

		protected function CurrentAccount( $key = null )
		{
			if( empty( $this->current_account ) )
			{
				$accounts = $this->GetAccounts();
				$current_user = $this->Master->User->Active();
				foreach ($accounts as $account)
				{
					if( $account['id'] == $current_user['user_selected_account'] )
					{
						$this->current_account = $account;
						break;
					}
				}
			}

			if( $key == null )
				return $this->current_account;
			else
				return $this->current_account[ $key ];
			
		}
		/**
		* Header
		* Prints data in header
		*
		* @access   private
		*/
		public function Header()
		{
			/*	
			let profile_data = {
				name: 'themakatka',
				short_description: 'Patrycja | Parisian Style',
				site_name: 'themakatka.com',
				site_url: 'https://themakatka.com',
				avatar: 'https://rdev.lan/dev/instaplaner/media/img/profile/avatar.jpg'
			};
			*/
			$media_library = $this->Master->Options->Get( 'posts_library', 'media/img/posts/' );
			
			$query = $this->Master->Database->query( "SELECT * FROM rdev_accounts WHERE id = 1" )->fetchArray();
			$order = json_decode( $this->CurrentAccount( 'post_order' ), true );

			if( $order == '' )
				$order = array();

			$query = $this->Master->Database->query( "SELECT * FROM rdev_posts WHERE account_id = ? ORDER BY id DESC", (int)$this->CurrentAccount( 'id' ) )->fetchAll();
			$photos = '';
			if( !empty( $query ) )
			{
				$c = 0;
				foreach ($query as $post)
				{
					if ( !in_array($post['id'], $order) )
					{
						$c++;
						$photos .= ($c > 1 ? ',' : '') . '[' . $post['id'] . ', \'' . $this->baseurl . $media_library . $post['image'] . '\', \'' . preg_replace("/[\n\r]/", '\n', $post[ 'description' ]) . '\']';
					}
				}

				foreach ( $order as $key)
				{
					if( $key == 0 )
					{
						$c++;
						$photos .= ($c > 1 ? ',' : '') . '[0, \'\', \'Empty photo\']';
					}
					else
					{
						foreach ( $query as $post )
						{
							if( $post['id'] == $key )
							{
								$c++;
								$photos .= ($c > 1 ? ',' : '') . '[' . $post['id'] . ', \'' . $this->baseurl . $media_library . $post['image'] . '\', \'' . preg_replace("/[\n\r]/", '\n', $post[ 'description' ]) . '\']';
							}
						}
					}
				}
			}


			echo "\t\t" . '<script>let instaplaner_photos = [' . $photos . '];let profile_data = {};let order_nonce = \'' . $this->AjaxNonce( 'save_reorder' ) . '\';let current_account = ' . ($this->CurrentAccount( 'id' ) != '' ? $this->CurrentAccount( 'id' ) : 1) . ';let delete_nonce = \'' . $this->AjaxNonce( 'delete_post' ) . '\';let update_nonce = \'' . $this->AjaxNonce( 'update_post' ) . '\';</script>';
		}
	}