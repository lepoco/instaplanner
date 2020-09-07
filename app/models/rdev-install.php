<?php namespace InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */

	use Mysqli;

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
		/**
		* Get
		* Get install form
		*
		* @access   private
		*/
		public function Get()
		{
			$this->InstallForm();
			exit;
		}

		/**
		* Post
		* Get install form
		*
		* @access   private
		*/
		public function Post()
		{
			$this->InstallForm();
			exit;
		}

		/**
		* InstallForm
		* Parse and verify install form
		*
		* @access   private
		*/
		private function InstallForm()
		{
			$result = array(
				'status' => 'error',
				'message' => 'Something went wrong!'
			);

			if (!isset(
				$_POST['action'],
				$_POST['input_scriptname'],
				$_POST['input_baseuri'],
				$_POST['input_db_name'],
				$_POST['input_db_user'],
				$_POST['input_db_host'],
				$_POST['input_db_password'],
				$_POST['input_user_name'],
				$_POST['input_user_password']
			))
			{
				$result['message'] = 'Missing fields';
				exit(json_encode($result));
			}

			if($_POST['action'] != 'setup')
			{
				$result['message'] = 'Inavlid action';
				exit(json_encode($result));
			}

			if(trim($_POST['input_user_name']) === '')
			{
				$result['message'] = 'User name empty';
				exit(json_encode($result));
			}

			if(trim($_POST['input_user_password']) === '')
			{
				$result['message'] = 'Password empty';
				exit(json_encode($result));
			}

			if(trim($_POST['input_db_name']) === '')
			{
				$result['message'] = 'DB name empty';
				exit(json_encode($result));
			}

			if(trim($_POST['input_db_user']) === '')
			{
				$result['message'] = 'DB user empty';
				exit(json_encode($result));
			}

			if(trim($_POST['input_db_host']) === '')
			{
				$result['message'] = 'DB host empty';
				exit(json_encode($result));
			}
			
			//error_reporting(0);
			$database = new Mysqli($_POST['input_db_host'], $_POST['input_db_user'], $_POST['input_db_password'], $_POST['input_db_name']);
			if ($database->connect_error)
			{
				$result['message'] = 'Unable to connect to database';
				exit(json_encode($result));
			}

			$this->BuildResources($database, array(
				'path' => filter_var($_POST['input_scriptname'], FILTER_SANITIZE_STRING),
				'db_name' => filter_var($_POST['input_db_name'], FILTER_SANITIZE_STRING),
				'db_host' => filter_var($_POST['input_db_host'], FILTER_SANITIZE_STRING),
				'db_user' => filter_var($_POST['input_db_user'], FILTER_SANITIZE_STRING),
				'db_pass' => $_POST['input_db_password'],
				'baseuri' => filter_var($_POST['input_baseuri'], FILTER_SANITIZE_STRING),
				'user_name' => filter_var($_POST['input_user_name'], FILTER_SANITIZE_STRING),
				'user_pass' => $_POST['input_user_password'],
			));

			$result['status'] = 'success';
			exit( json_encode( $result, JSON_UNESCAPED_UNICODE ) );
		}

		/**
		* BuildResources
		* Creates config file and database tables
		*
		* @param	Mysqli $database
		* @param	array $args
		* @access   private
		*/
		private function BuildResources($database, $args) : void
		{
			$this->BuildHtaccess($args['path']);
			$this->BuildConfig($args);
			$this->BuildTables($database, $args);
		}

		/**
		* SetAlgo
		* Defines Password hash type
		*
		* @access   private
		* @return   void
		*/
		private function SetAlgo() : string
		{
			/** Password hash type */
			if(defined('PASSWORD_ARGON2ID'))
				return 'PASSWORD_ARGON2ID';
			else if(defined('PASSWORD_ARGON2I'))
				return 'PASSWORD_ARGON2I';
			else if(defined('PASSWORD_BCRYPT'))
				return 'PASSWORD_BCRYPT';
			else if(defined('PASSWORD_DEFAULT'))
				return 'PASSWORD_DEFAULT';
		}

		/**
		* BuildHtaccess
		* Creates a .htaccess file
		*
		* @access   private
		* @param	string $dir
		* @return   void
		*/
		private function BuildHtaccess(string $dir = '/') : void
		{
			if($dir == '/')
				$dir = '';

			$htaccess  = "";
			$htaccess .= "Options All -Indexes\n\n";
			$htaccess .= "<IfModule mod_rewrite.c>\n";
			$htaccess .= "RewriteEngine On\nRewriteBase /\nRewriteCond %{REQUEST_URI} ^(.*)$\nRewriteCond %{REQUEST_FILENAME} !-f\n";
			$htaccess .= "RewriteRule .* $dir/index.php [L]\n</IfModule>";

			$path = ABSPATH . '.htaccess';
			file_put_contents( $path, $htaccess );
		}

		/**
		* BuildConfig
		* Creates config file
		*
		* @access   private
		* @param	array $args
		* @return   void
		*/
		private function BuildConfig( $args ) : void
		{

			$config  = "";
			$config .= "<?php namespace InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );\n/**\n * @package InstaPlanner\n *\n * @author Leszek Pomianowski\n * @copyright Copyright (c) 2020, RapidDev\n * @license https://opensource.org/licenses/MIT\n * @link https://rdev.cc/\n */";

			$config .= "\n\n\t/** Passwords hash type */\n\tdefine( 'INSTAPLANNER_ALGO', " . $this->SetAlgo() . " );";

			$config .= "\n\n\t/** Database table */\n\tdefine( 'INSTAPLANNER_DB_NAME', '" . $args['db_name'] . "' );";
			$config .= "\n\t/** Database table */\n\tdefine( 'INSTAPLANNER_DB_HOST', '" . $args['db_host'] . "' );";
			$config .= "\n\t/** Database table */\n\tdefine( 'INSTAPLANNER_DB_USER', '" . $args['db_user'] . "' );";
			$config .= "\n\t/** Database table */\n\tdefine( 'INSTAPLANNER_DB_PASS', '" . $args['db_pass'] . "' );";

			$config .= "\n\n\t/** Session salt */\n\tdefine( 'SESSION_SALT', '" . Crypter::DeepSalter(50) . "' );";
			$config .= "\n\t/** Passowrd salt */\n\tdefine( 'PASSWORD_SALT', '" . Crypter::DeepSalter(50) . "' );";
			$config .= "\n\t/** Nonce salt */\n\tdefine( 'NONCE_SALT', '" . Crypter::DeepSalter(50) . "' );";

			$config .= "\n\n\t/** Debugging */\n\tdefine( 'INSTAPLANNER_DEBUG', false );";

			$config .= "\n\n?>\n";

			$path = ABSPATH . 'app/config.php';
			file_put_contents( $path, $config );
		}

		/**
		* BuildTables
		* Creates database tables
		*
		* @access   private
		* @param	Mysqli $database
		* @param	array $args
		* @return   void
		*/
		private function BuildTables( $database, $args ) : void
		{
			$database->set_charset('utf8');

			$dbFile = file( ABSPATH . 'app/system/rdev-database.sql' );
			$queryLine = '';

			// Loop through each line
			foreach ($dbFile as $line)
			{
				//Skip comments and blanks
				if (substr($line, 0, 2) == '--' || $line == '' || substr($line, 0, 1) == '#')
					continue;
				
				$queryLine .= $line;

				if (substr(trim($line), -1, 1) == ';')
				{
					$database->query($queryLine);
					$queryLine = '';
				}
			}

			$this->FillData($database, $args);
		}

		/**
		* BuildTables
		* Creates database tables
		*
		* @access   private
		* @param	Mysqli $db
		* @param	array $args
		* @return   void
		*/
		private function FillData( $database, $args )
		{
			require_once ABSPATH . 'app/config.php';

			//Static
			$database->query("INSERT IGNORE INTO rdev_options (option_name, option_value) VALUES " . 
				"('version', '" . INSTAPLANNER_VERSION . "'), " .
				"('site_name', 'InstaPlanner'),  " .
				"('site_description', 'Schedule your Instagram posts'),  " .
				"('media_library', 'media/img/'),  " .
				"('posts_library', 'media/img/posts/'),  " .
				"('profile_library', 'media/img/avatars/'),  " .
				"('dashboard', 'dashboard'),  " .
				"('login', 'login'),  " .
				"('timezone', 'UTC'), " .
				"('date_format', 'j F Y'), " .
				"('time_format', 'H:i'), " .
				"('force_dashboard_ssl', 'false'), " .
				"('charset', 'UTF8'), " .
				"('dashboard_posts', '30'), " .
				"('cache', 'false'), " .
				"('dashboard_gzip', 'false'),  " .
				"('redirect_404', 'false'), " .
				"('redirect_404_direction', ''), " .
				"('redirect_home', 'false'), " .
				"('redirect_home_direction', '')"
			);

			//Binded
			if($query = $database->prepare("INSERT IGNORE INTO rdev_options (option_name, option_value) VALUES ('base_url', ?)"))
			{
				$query->bind_param('s', $args['baseuri']);
				$query->execute();
			}

			if($query = $database->prepare("INSERT IGNORE INTO rdev_options (option_name, option_value) VALUES ('ssl', ?)"))
			{
				$ssl = $this->Master->Path->ssl ? 'true' : 'false';
				$query->bind_param('s', $ssl);
				$query->execute();
			}

			if($query = $database->prepare("INSERT IGNORE INTO rdev_users (user_name, user_display_name, user_password, user_token, user_role, user_status) VALUES (?, ?, ?, ?, ?, ?)"))
			{

				$password = Crypter::Encrypt($args['user_pass'], 'password');
				$token = '';
				$role = 'admin';
				$status = 1;

				$query->bind_param('ssssss',
					$args['user_name'],
					$args['user_name'],
					$password,
					$token,
					$role,
					$status
				);
				$query->execute();
			}

			$this->Master->User->LogIn(array('user_id' => 1, 'user_role' => 'admin'));

			$database->close();
		}

		/**
		* Footer
		* Prints data in footer
		*
		* @access   private
		*/
		public function Footer()
		{
			echo "\t\t" . '<script>jQuery("#input_user_password").on("change paste keyup",function(){let e=jQuery(this).val(),s=zxcvbn(e);""!==e?jQuery(".def_password--strength").html("Strength: <strong>"+{0:"Worst ☹",1:"Bad ☹",2:"Weak ☹",3:"Good 🙃",4:"Strong 🙂"}[s.score]+"</strong><br/><span class=\'feedback\'>"+s.feedback.warning+" "+s.feedback.suggestions+"</span"):jQuery(".def_password--strength").html("")});</script>';
		}
	}