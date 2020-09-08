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

	/**
	*
	* Models
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license  MIT License
	* @access   public
	*/
	class JSParse
	{
		public static function Parse( $data )
		{

			return self::ParseValue( $data );
		}

		private static function AssocLoop( array $arr )
		{
			$result = '{';
			$c = 0;

			foreach ( $arr as $key => $value )
			{
				$c++;
				$result .= ( $c > 1 ? ', ' : '' ) . $key . ': ' . self::ParseValue( $value );
			}

			return $result . '}';
		}

		private static function ArrLoop( array $arr )
		{
			$result = '[';
			$c = 0;

			foreach ( $arr as $value )
			{
				$c++;
				$result .= ( $c > 1 ? ', ' : '' ) . self::ParseValue( $value );
			}

			return $result . ']';
		}

		private static function ParseValue( $value )
		{
			if( $value === true )
			{
				return 'true';
			}
			else if( $value === false )
			{
				return 'false';
			}
			else if( is_float( $value ) || is_int( $value ) )
			{
				return $value;
			}
			else if( is_array( $value ) )
			{
				if( self::IsAssoc( $value ) )
				{
					return self::AssocLoop( $value );
				}
				else
				{
					return self::ArrLoop( $value );
				}
			}
			else
			{
				return '\'' . $value . '\'';
			}
		}

		private static function IsAssoc( array $arr )
		{
			if (array() === $arr)
				return false;
			
			return array_keys($arr) !== range(0, count($arr) - 1);
		}
	}
