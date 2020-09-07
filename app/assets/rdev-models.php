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
	* @license  MIT License
	* @access   public
	*/
	class Models
	{
		/**
		 * Master class instance
		 *
		 * @var object
		 * @access protected
		 */
		protected $Master;

		/**
		 * CMS version
		 *
		 * @var string
		 * @access protected
		 */
		protected $version;

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
		* @param    Master $Master
		* @param    string $name
		* @param    string $displayname
		*/
		public function __construct( Object &$Master, string $name, string $displayname, string $ver = '1.0.0' )
		{
			$this->Master = $Master;

			$this->name = $name;
			$this->displayname = $displayname;
			$this->version = $ver;

			$this->themes = ABSPATH . 'app/themes/';
			$this->PreBuild();
		}

		/**
		* PreBuild
		* Prepare and display the page
		*
		* @access   protected
		*/
		protected function PreBuild()
		{
			$this->BuildNonces();

			$this->SetBaseUrl();
			$this->SetPrefetch();

			$this->SetStyles();
			$this->SetScripts();

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

		/**
		* BuildNonces
		* Verification nonce for the site
		*
		* @access   protected
		* @param    string $name
		*/
		protected function BuildNonces()
		{
			$this->body_nonce = Crypter::BaseSalter(40);
			$this->js_nonce = Crypter::BaseSalter(40);
		}

		/**
		* AjaxGateway
		* Return the ajax gateway address
		*
		* @access   protected
		*/
		protected function AjaxGateway()
		{
			return $this->baseurl . $this->Master->Options->Get( 'dashboard', 'dashboard' ) . '/ajax';
		}

		/**
		* AjaxNonce
		* Create a new nonce for the ajax gateway
		*
		* @access   protected
		* @param    string $name
		*/
		protected function AjaxNonce( $name )
		{
			return Crypter::Encrypt( 'ajax_' . $name . '_nonce', 'nonce' );
		}

		/**
		* SetBaseUrl
		* Set the base site address
		*
		* @access   protected
		*/
		protected function SetBaseUrl()
		{
			$this->baseurl = $this->Master->Options->Get( 'base_url', $this->Master->Path->RequestURI() );
		}
		
		/**
		* GetView
		* Display the page view (based on the model name)
		*
		* @access   protected
		*/
		protected function GetView()
		{
			require_once $this->themes . "pages/rdev-$this->name.php";
			exit;
		}

		/**
		* Title
		* Display the site name for the address bar
		*
		* @access   protected
		*/
		protected function Title()
		{
			echo $this->Master->Options->Get('site_name', 'InstaPlanner') . ($this->displayname != NULL ? ' | ' . $this->displayname : '');
		}

		/**
		* Description
		* Get description of the site from the database
		*
		* @access   protected
		*/
		protected function Description()
		{
			return $this->Master->Options->Get('site_description', 'Schedule your Instagram posts');
		}

		/**
		* GetHeader
		* Include the header theme
		*
		* @access   protected
		*/
		protected function GetHeader()
		{
			require_once $this->themes . 'rdev-header.php';
		}

		/**
		* GetFooter
		* Include the footer theme
		*
		* @access   protected
		*/
		protected function GetFooter()
		{
			require_once $this->themes . 'rdev-footer.php';
		}

		/**
		* GetImage
		* Take the image address along with the url
		*
		* @access   protected
		* @param    string $name
		*/
		protected function GetImage( $name )
		{
			return $this->baseurl . $this->Master->Options->Get( 'media_library', 'media/img/' ) . $name;
		}

		/**
		* SetPrefetch
		* Prepare url addresses for prefetch
		*
		* @access   protected
		*/
		protected function SetPrefetch()
		{
			$this->prefetch = array(
				'//ogp.me',
				'//schema.org',
				'//cdnjs.cloudflare.com'
			);
		}

		/**
		* SetStyles
		* Prepare the styles used
		*
		* @access   protected
		*/
		protected function SetStyles()
		{
			$this->styles = array(
				array( 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css', 'sha512-MoRNloxbStBcD8z3M/2BmnT+rg4IsMxPkXaGh2zD6LGNNFE80W3onsAhRcMAMrSoyWL9xD7Ert0men7vR8LUZg==', '4.5.2' ),
				array( $this->baseurl . 'media/css/instaplanner.css', '', $this->version )
			);
		}

		/**
		* SetScripts
		* Prepare the scripts used
		*
		* @access   protected
		*/
		protected function SetScripts()
		{
			$this->scripts = array(
				array( 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js', 'sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==', '3.5.1' ),
				array( 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js', 'sha512-hCP3piYGSBPqnXypdKxKPSOzBHF75oU8wQ81a6OiGXHFMeKs9/8ChbgYl7pUvwImXJb03N4bs1o1DzmbokeeFw==', '2.4.4' ),
				array( 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js', 'sha512-M5KW3ztuIICmVIhjSqXe01oV2bpe248gOxqmlcYrEzAvws7Pw3z6BK0iGbrwvdrUQUhi3eXgtxp5I8PDo9YfjQ==', '4.5.2' ),
				array( 'https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js', 'sha512-TZlMGFY9xKj38t/5m2FzJ+RM/aD5alMHDe26p0mYUMoCF5G7ibfHUQILq0qQPV3wlsnCwL+TPRNK4vIWGLOkUQ==', '4.4.2' ),
				array( 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js', 'sha512-hDWGyh+Iy4Mr9AHOzUP2+Y0iVPn/BwxxaoSleEjH/i1o4EVTF/sh0/A1Syii8PWOae+uPr+T/KHwynoebSuAhw==', '2.0.6' ),
				array( 'https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.2/Sortable.min.js' , 'sha512-ELgdXEUQM5x+vB2mycmnSCsiDZWQYXKwlzh9+p+Hff4f5LA+uf0w2pOp3j7UAuSAajxfEzmYZNOOLQuiotrt9Q==', '1.10.2' ),
				array( $this->baseurl . 'media/js/instaplanner.js', '', $this->version )
			);
		}

		/**
		* PrintPrefetch
		* Print prepared addresses for prefetch
		*
		* @access   protected
		*/
		protected function PrintPrefetch()
		{
			foreach ( $this->prefetch as $dns )
			{
				echo "\t\t" . '<link rel="dns-prefetch" href="' . $dns . '" />' . PHP_EOL;
			}
		}

		/**
		* PrintStyles
		* Print prepared styles
		*
		* @access   protected
		*/
		protected function PrintStyles()
		{
			foreach ( $this->styles as $style )
			{
				echo "\t\t" . '<link type="text/css" rel="stylesheet" href="' . $style[0] . (isset($style[2]) ? '?ver=' . $style[2] : '') . '" integrity="' . $style[1] . '" crossorigin="anonymous" />' . PHP_EOL;
			}
		}

		/**
		* PrintScripts
		* Print prepared scripts
		*
		* @access   protected
		*/
		protected function PrintScripts()
		{
			foreach ( $this->scripts as $script )
			{
				echo "\t\t" . '<script type="text/javascript" src="' . $script[0] . (isset($script[2]) ? '?ver=' . $script[2] : '') . '" integrity="' . $script[1] . '" crossorigin="anonymous"></script>' . PHP_EOL;
			}
		}

		/**
		* Print
		* Print whole page
		*
		* @access   protected
		*/
		public function Print()
		{
			$this->GetView();
			$this->Master->Session->Close();
			$this->Master->Database->Close();
			//Kill script :(
			exit;
		}
	}

?>