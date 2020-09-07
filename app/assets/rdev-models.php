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
	* Models
	*
	* @author   Leszek Pomianowski <https://rdev.cc>
	* @license	MIT License
	* @access   public
	*/
	class Models
	{
		/**
		 * Master class instance
		 *
		 * @var Master
		 * @access protected
		 */
		protected $Master;

		/**
		 * Basename of view
		 *
		 * @var string
		 * @access protected
		 */
		protected $name;

		/**
		 * Displayed name in title
		 *
		 * @var string
		 * @access protected
		 */
		protected $displayname;

		/**
		 * Themes path
		 *
		 * @var string
		 * @access protected
		 */
		protected $themes;

		/**
		 * List of frontend styles
		 *
		 * @var array
		 * @access protected
		 */
		protected $styles;

		/**
		 * List of frontend scripts
		 *
		 * @var array
		 * @access protected
		 */
		protected $scripts;

		/**
		 * List of sites to dns prefetch
		 *
		 * @var array
		 * @access protected
		 */
		protected $prefetch;

		/**
		 * Root url of website
		 *
		 * @var string
		 * @access protected
		 */
		protected $baseurl;

		/**
		 * Nonce for secured javascript 
		 *
		 * @var string
		 * @access protected
		 */
		protected $js_nonce;

		/**
		 * Nonce for DOM verification
		 *
		 * @var string
		 * @access protected
		 */
		protected $body_nonce;

		/**
		 * Site address for ip location
		 *
		 * @var string
		 * @access protected
		 */
		protected $geoip = '';

		/**
		* __construct
		* Class constructor
		*
		* @access   public
		* @param	Master $Master
		* @param	string $name
		* @param	string $displayname
		*/
		public function __construct( Object &$Master, string $name, string $displayname )
		{
			$this->Master = $Master;

			$this->name = $name;
			$this->displayname = $displayname;
			$this->themes = ABSPATH . 'app/themes/';

			$this->SetGeoIP();

			$this->BuildNonces();

			$this->SetBaseUrl();
			$this->SetPrefetch();

			$this->GetStyles();
			$this->GetScripts();

			if( method_exists( $this, 'Init' ) )
				$this->Init();

			if($_GET != NULL)
				if( method_exists( $this, 'Get' ) )
					$this->Get();
				else
					$this->GetView();

			if($_POST != NULL)
				if( method_exists( $this, 'Post' ) )
					if( method_exists( $this, 'Get' ) )
						$this->Get();
					else
						$this->GetView();
				else
					$this->GetView();
		}

		protected function AjaxGateway()
		{
			return $this->baseurl . $this->Master->Options->Get( 'dashboard', 'dashboard' ) . '/ajax';
		}

		protected function AjaxNonce( $name )
		{
			return Crypter::Encrypt( 'ajax_' . $name . '_nonce', 'nonce' );
		}

		protected function SetGeoIP()
		{
			$this->geoip = ' https://freegeoip.app/';
		}

		protected function BuildNonces()
		{
			$this->body_nonce = Crypter::BaseSalter(40);
			$this->js_nonce = Crypter::BaseSalter(40);
		}

		protected function SetBaseUrl()
		{
			$this->baseurl = $this->Master->Options->Get('base_url', $this->Master->Path->RequestURI());
		}
		
		protected function GetView()
		{
			require_once $this->themes . "pages/rdev-$this->name.php";
			exit;
		}

		protected function Title()
		{
			echo $this->Master->Options->Get('site_name', 'Master') . ($this->displayname != NULL ? ' | ' . $this->displayname : '');
		}

		protected function Description()
		{
			return $this->Master->Options->Get('site_description', 'Schedule your Instagram posts');
		}

		protected function GetHeader()
		{
			require_once $this->themes . 'rdev-header.php';
		}

		protected function GetFooter()
		{
			require_once $this->themes . 'rdev-footer.php';
		}

		protected function GetImage($name)
		{
			return $this->baseurl . 'media/img/' . $name;
		}

		protected function SetPrefetch()
		{
			$this->prefetch = array(
				'//ogp.me',
				'//schema.org',
				'//cdnjs.cloudflare.com'
			);
		}

		protected function GetStyles()
		{
			$this->styles = array(
				array( 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css', 'sha512-MoRNloxbStBcD8z3M/2BmnT+rg4IsMxPkXaGh2zD6LGNNFE80W3onsAhRcMAMrSoyWL9xD7Ert0men7vR8LUZg==', '4.5.2' ),
				array( $this->baseurl . 'media/css/instaplanner.css', '', INSTAPLANNER_VERSION )
			);
		}

		protected function GetScripts()
		{
			$this->scripts = array(
				array( 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js', 'sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==', '3.5.1' ),
				array( 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js', 'sha512-hCP3piYGSBPqnXypdKxKPSOzBHF75oU8wQ81a6OiGXHFMeKs9/8ChbgYl7pUvwImXJb03N4bs1o1DzmbokeeFw==', '2.4.4' ),
				array( 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js', 'sha512-M5KW3ztuIICmVIhjSqXe01oV2bpe248gOxqmlcYrEzAvws7Pw3z6BK0iGbrwvdrUQUhi3eXgtxp5I8PDo9YfjQ==', '4.5.2' ),
				array( 'https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js', 'sha512-TZlMGFY9xKj38t/5m2FzJ+RM/aD5alMHDe26p0mYUMoCF5G7ibfHUQILq0qQPV3wlsnCwL+TPRNK4vIWGLOkUQ==', '4.4.2' ),
				array( 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js', 'sha512-hDWGyh+Iy4Mr9AHOzUP2+Y0iVPn/BwxxaoSleEjH/i1o4EVTF/sh0/A1Syii8PWOae+uPr+T/KHwynoebSuAhw==', '2.0.6' ),
				array( 'https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.2/Sortable.min.js' , 'sha512-ELgdXEUQM5x+vB2mycmnSCsiDZWQYXKwlzh9+p+Hff4f5LA+uf0w2pOp3j7UAuSAajxfEzmYZNOOLQuiotrt9Q==', '1.10.2' ),
				array( $this->baseurl . 'media/js/instaplanner.js', '', INSTAPLANNER_VERSION )
			);
		}

		public function Print()
		{
			$this->GetView();
			$this->Master->Session->Close();
			//Kill script :(
			exit;
		}

		public function GetUsers() : array
		{
			$query = $this->Master->Database->query( "SELECT * FROM rdev_users" )->fetchAll();

			if( !empty( $query ) )
			{
				return $query;
			}

			return array();
		}
	}

?>