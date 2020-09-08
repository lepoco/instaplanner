<?php namespace RapidDev\InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
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
		protected const ERROR_UNKNOWN                  = 'e00';
		protected const ERROR_MISSING_ACTION           = 'e01';
		protected const ERROR_MISSING_NONCE            = 'e02';
		protected const ERROR_INVALID_NONCE            = 'e03';
		protected const ERROR_INVALID_ACTION           = 'e04';
		protected const ERROR_INSUFFICIENT_PERMISSIONS = 'e05';
		protected const ERROR_MISSING_ARGUMENTS        = 'e06';
		protected const ERROR_EMPTY_ARGUMENTS          = 'e07';
		protected const ERROR_ENTRY_EXISTS             = 'e08';
		protected const ERROR_ENTRY_DONT_EXISTS        = 'e09';
		protected const ERROR_INVALID_URL              = 'e10';
		protected const ERROR_INVALID_PASSWORD         = 'e11';
		protected const ERROR_PASSWORDS_DONT_MATCH     = 'e12';
		protected const ERROR_PASSWORD_TOO_SHORT       = 'e13';
		protected const ERROR_PASSWORD_TOO_SIMPLE      = 'e14';
		protected const ERROR_INVALID_EMAIL            = 'e15';
		protected const ERROR_SPECIAL_CHARACTERS       = 'e16';
		protected const ERROR_FILE_TYPE                = 'e17';
		protected const ERROR_SAVING_FILE              = 'e18';
		protected const ERROR_DELETING_FILE            = 'e19';

		protected const CODE_SUCCESS                   = 's01';
		
		/**
		 * Master class instance
		 *
		 * @var Master
		 * @access protected
		 */
		protected $Master;

		/**
		 * Current ajax action
		 *
		 * @var string
		 * @access protected
		 */
		protected $action = '';

		/**
		 * Current ajax nonce
		 *
		 * @var string
		 * @access protected
		 */
		protected $nonce = '';

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
		* ForceFilePutContents
		* Nonce validation
		*
		* @access   protected
		* @return	bool
		*/
		function ForceFilePutContents( string $filepath, $data )
		{
			try
			{
				$isInFolder = preg_match("/^(.*)\/([^\/]+)$/", $filepath, $filepathMatches );

				if( $isInFolder )
				{
					$folderName = $filepathMatches[1];
					$fileName = $filepathMatches[2];
					
					if ( !is_dir( $folderName ) )
					{
						mkdir( $folderName, 0777, true );
					}
				}

				file_put_contents( $filepath, $data );

				return true;
			}
			catch( Exception $e )
			{
				return false;
			}
		}

		/**
		* ValidNonce
		* Nonce validation
		*
		* @access   protected
		* @return	bool
		*/
		protected function ValidNonce() : bool
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
		* @access   protected
		* @return	bool
		*/
		protected function ValidAction() : bool
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
		* @access   protected
		* @return	bool
		*/
		protected function IsNull() : bool
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
		* @access   protected
		* @return	bool
		*/
		protected function Finish( $text = null, $json = false )
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
	}
