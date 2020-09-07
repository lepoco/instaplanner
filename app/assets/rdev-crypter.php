<?php namespace InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */

	use DateTime;

	/**
	*
	* Crypter
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class Crypter
	{

		/**
		* Encrypt
		* Encrypts data depending on the selected method, default password
		*
		* @access   public
		* @param	string $string
		* @param	string $type
		* @return   string hash_hmac()
		*/
		public static function Encrypt(string $text, string $type = 'password') : string
		{
			if($type == 'password')
				return (defined( 'INSTAPLANNER_ALGO' ) ? password_hash( hash_hmac( 'sha256', $text, PASSWORD_SALT ), INSTAPLANNER_ALGO ) : '' );
			else if($type == 'nonce')
				return (defined( 'NONCE_SALT' ) ? hash_hmac('sha1', $text . (new DateTime())->format('Y-m-d'), NONCE_SALT) : '' );
			else if($type == 'token')
				return (defined( 'SESSION_SALT' ) ? hash_hmac('sha256', $text, SESSION_SALT) : '' );
		}

		/**
		* Compare
		* Compares encrypted data with those in the database
		*
		* @access   public
		* @param	string $text
		* @param	string $compare_text
		* @param	string $type
		* @param	bool   $plain
		* @return	bool   true/false
		*/
		public static function Compare(string $text, string $compare_text, string $type = 'password', bool $plain = true) : bool
		{
			if( $type == 'password' )
			{
				if (password_verify(($plain ? hash_hmac('sha256', $text, PASSWORD_SALT) : $text), $compare_text))
					return true;
				else
					return false;
			}
			else if( $type == 'nonce' )
			{
				if( ( $plain ? hash_hmac( 'sha1', $text . (new DateTime())->format('Y-m-d'), NONCE_SALT ) : $text ) == $compare_text )
					return true;
				else
					return false;
			}
			else if( $type == 'token' )
			{
				if( ( $plain ? hash_hmac( 'sha256', $text, SESSION_SALT ) : $text ) == $compare_text )
					return true;
				else
					return false;
			}
		}

		public static function BaseSalter(int $length) : string
		{
			self::SrandSeed();

			$characters = 'abcdefghijklmnopqrstuvwxyz0123456789GHIJKLMNOPQRSTUVWXYZABCDEF';
			$randomString = '';
			for ($i = 0; $i < $length; $i++)
				$randomString .= $characters[mt_rand(0, 61)]; //Mersenne Twist
			
			return $randomString;
		}

		public static function DeepSalter(int $length) : string
		{
			self::SrandSeed();

			$characters = '+*&^%#abcdefghijklmnopqrstuvwxyz0123456789GHIJKLMNOPQRSTUVWXYZ_-=@.,?!ABCDEF';
			$randomString = '';
			for ($i = 0; $i < $length; $i++)
				$randomString .= $characters[mt_rand(0, 75)]; //Mersenne Twist
			
			return $randomString;
		}

		private static function SrandSeed() : void
		{
			$characters = '+MNT%#aefbcklmnQRSX67D*&^YZ_oJKLUVWpqijP-=@.z012345EFrstuvdg,?!ABChwxy89GHIO';
			$crx = '';
			for ($i = 0; $i < 50; $i++)
				$crx .= $characters[mt_rand(0, 75)];

			$rand = crc32( self::MakeSeed() . '@' . $crx ) * 2147483647;
			mt_srand($rand);
		}

		private static function MakeSeed() : int
		{
			list($usec, $sec) = explode(' ', microtime());
			return $sec + $usec * 1000000;
		}
	}
?>
