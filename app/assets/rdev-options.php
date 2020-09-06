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
	* Options
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class Options
	{
		/**
		 * Database instance
		 *
		 * @var Database
		 * @access private
		 */
		private $database;

		/**
		 * Options table
		 *
		 * @var array
		 * @access private
		 */
		private $options = array();

		/**
		* __construct
		* Class constructor
		*
		* @access   public
		*/
		public function __construct( $db )
		{
			if( $db != null )
			{
				$this->database = $db;
				$this->Init();
			}
		}

		/**
		* Init
		* Get options from database
		*
		* @access   private
		*/
		private function Init()
		{
			$query = $this->database->query( 'SELECT * FROM rdev_options' )->fetchAll();

			if( !empty($query) )
			{
				foreach ( $query as $option )
				{
					$this->options[ $option['option_name'] ] = $option['option_value'];
				}
			}
		}

		/**
		* Update
		* Update option in array and database
		*
		* @access   public
		*/
		public function Update( $name, $value )
		{
			if( $this->database != null )
			{

			}
		}

		/**
		* Get
		* Get option from array
		*
		* @access   public
		*/
		public function Get( $name, $default = NULL, $raw = false )
		{
			if( isset( $this->options[ $name ] ) )
			{
				return ( $raw ? $this->options[ $name ] : $this->ParseType( $this->options[ $name ] ) );
			}
			else
			{
				return $default;
			}
		}

		/**
		* ParseType
		* Returns parsed option to selected data type
		*
		* @access   public
		*/
		private function ParseType( $option )
		{
			if( $option === 'true')
			{
				return true;
			}
			else if( $option === 'false' )
			{
				return false;
			}
			else if( is_int( $option ) )
			{
				return intval( $option );
			}
			else
			{
				return $option;
			}
		}
	}
?>
