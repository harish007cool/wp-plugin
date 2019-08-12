<?php
/**
 * Plugin Name: WP Twilio
 * Description: A simple plugin to add SMS capability to your website using the Twilio API. Allows developers to easily extend the settings page and built in functionality.
 * Version: 1.1.0
 * Author: 
 * License: 
 */

 
 
 /*css-start */ 

define("HOME_URL", home_url());
//echo HOME_URL;
add_action( 'admin_init','my_style');
 
function my_style() {
    wp_register_style('my_style_a', plugins_url('/css/Tables.min.css',__FILE__ ));	
    wp_enqueue_style('my_style_a');
	
    wp_register_style('my_style_b', plugins_url('/css/jquery.emojipicker.css',__FILE__ ));	
    wp_enqueue_style('my_style_b');

    wp_register_style('my_style_c', plugins_url('/css/jquery.emojipicker.tw.css',__FILE__ ));	
    wp_enqueue_style('my_style_c');	
	
	wp_register_style('my_style_d', plugins_url('/css/custom.css',__FILE__ ));	
    wp_enqueue_style('my_style_d');	
	

}

/*css-end */






/* START Cronjob Scheduler  */

if ( ! defined( 'DIR_PATH' ) ) {
 define( 'DIR_PATH', plugin_dir_path( __FILE__ ) );
}
echo DIR_PATH . 'vendor/autoload.php';



require DIR_PATH . 'vendor/autoload.php';


// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

function minute_cronjob_action2 () {
	
// Your Account SID and Auth Token from twilio.com/console

date_default_timezone_set('Asia/Kolkata');
$time = date('H:i:s');

global $wpdb;	 
	 $table_name = $wpdb->prefix . "add_twilio_account";
     $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
    foreach ( $result as $print )  
	{
		
$sid = $print->account_sid;   
$token = $print->auth_token;
}
	 
	$table_name = $wpdb->prefix . "add_twilio_number";
    $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
    foreach ( $result as $print )  
{  
    
   $twilio_number = $print->twilio_number;  
  
}


global $wpdb;
	$table_name = $wpdb->prefix . "bulk_message";
    $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
	$i = 1;
	echo count($result);
    foreach ( $result as $print )  
{  
   
 $recipient_number = $print->sms_number;  

$client = new Client($sid, $token);
echo $msg = "this is Test sms by cronjob @$i $time";$i++;
try{
// Use the client to do fun stuff like send text messages!
$client->messages->create(
// the number you'd like to send the message to
$recipient_number,
 
 array(
        "from" => $twilio_number,
        "body" => $msg
    )
);

//echo "message has been sent. msgbody: $msg";
    $table_name = $wpdb->prefix . 'single_message';
    $rows_affected_a = "INSERT INTO  $table_name(twilio_number,recipient_number,message,status,date_time) values ('".$twilio_number."','".$recipient_number."','".$msg."','1',now())";
    require( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $rows_affected_a );


}
catch(Exception $e){
    echo 'error: ' .$e->getMessage();
    $error = 1;
 //echo "message not sent.";  
$table_name = $wpdb->prefix . 'single_message';

    $rows_affected = "INSERT INTO  $table_name(twilio_number,recipient_number,message,status,date_time) values ('".$twilio_number."','".$sms_number1."','".$msg."','0',now())";
     require( ABSPATH . 'wp-admin/includes/upgrade.php' );
     dbDelta( $rows_affected );
	 
   }	
 }
} 


add_action('my_minute_action', 'minute_cronjob_action2'); 
/* END Cronjob Scheduler  */
    

	
	
global $wpdb;	 
	 $table_name = $wpdb->prefix . "add_twilio_account";
     $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
    foreach ( $result as $print )  
	{

  
  
$sid = $print->account_sid;   
$token = $print->auth_token;

}

	$table_name = $wpdb->prefix . "add_twilio_number";
    $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
    foreach ( $result as $print )  
{   
   
   $twilio_number = $print->twilio_number;  
  
}
if(isset ($_POST['submit']))
{

$recipient_number = $_POST['recipient_number'];   

$sms = $_POST['sms'];

$client = new Client($sid, $token);
$msg = $sms;
try{
// Use the client to do fun stuff like send text messages!
$client->messages->create(
    // the number you'd like to send the message to
    $recipient_number,
 
 array(
        "from" => $twilio_number,
        "body" => $msg
    )
);

//echo "message has been sent. msgbody: $msg";

    $table_name = $wpdb->prefix . 'single_message';
    $rows_affected_a = "INSERT INTO  $table_name(twilio_number,recipient_number,message,status,date_time) values ('".$twilio_number."','".$recipient_number."','".$msg."','1',now())";
    require( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $rows_affected_a );

}
catch(Exception $e){
    echo 'error: ' .$e->getMessage();
    $error = 1;
 //echo "message not sent.";  

$table_name = $wpdb->prefix . 'single_message';

    $rows_affected = "INSERT INTO  $table_name(twilio_number,recipient_number,message,status,date_time) values ('".$twilio_number."','".$sms_number1."','".$msg."','0',now())";
     require( ABSPATH . 'wp-admin/includes/upgrade.php' );
     dbDelta( $rows_affected );
	 
     
   
   }	

}


 
/*  custom_shortcode */

function custom_shortcode() {
?>	


<div class="container_a">
  <form method="post">
  
    <label for="recipient_number">Recipient Number</label>
    <input type="text" id="recipient_number" name="recipient_number" value="+919410372172" placeholder="Enter Recipient Number.." required>

    
    <label for="sms">SMS</label>
    <textarea id="sms" name="sms" placeholder="Write something.." style="height:200px" required ></textarea>

    <input type="submit" value="Submit" name="submit">
 
</div>
</form>
<?php	
	
}
add_shortcode( 'abs', 'custom_shortcode' );




/*Plugin_CREATE_TABLE_START */

 register_activation_hook(__FILE__, 'reviews_plugin_tables' );
 
function reviews_plugin_tables(){	
global $wpdb;

$table_name = $wpdb->prefix . 'add_twilio_account';
$add_twilio_account = "CREATE TABLE $table_name (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `account_sid` varchar(300) NOT NULL ,
  `auth_token` varchar(300) NOT NULL ,
  `date_time` datetime NOT NULL,   
   UNIQUE KEY `id` (`id`)  
   ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;";
 
require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta( $add_twilio_account );


$table_name = $wpdb->prefix . 'add_twilio_number';
$add_twilio_number = "CREATE TABLE $table_name (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) DEFAULT NULL,
  `twilio_number` varchar(100) DEFAULT NULL,
  `date_time` datetime NOT NULL,
   UNIQUE KEY `id` (`id`)
)  ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;";
dbDelta( $add_twilio_number );


$table_name = $wpdb->prefix . 'single_message';
$single_message = "CREATE TABLE $table_name (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `twilio_number` varchar(200) DEFAULT NULL,
  `recipient_number` varchar(100) DEFAULT NULL,
  `message` varchar(500) DEFAULT NULL,
  `date_time` datetime NOT NULL,
   UNIQUE KEY `id` (`id`)
)  ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
";
dbDelta( $single_message );


$table_name = $wpdb->prefix . 'bulk_message';
$bulk_message = "CREATE TABLE $table_name (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `message_type` varchar(50) NOT NULL,
  `sms_number` varchar(100) NOT NULL,
  `twilio_number` varchar(200) NOT NULL,
  `message` varchar(100) NOT NULL,
  `date_time` datetime NOT NULL,
   UNIQUE KEY `id` (`id`)
)  ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;";
dbDelta( $bulk_message );


$table_name = $wpdb->prefix . 'add_groups';
$add_groups = "CREATE TABLE $table_name (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) DEFAULT NULL,
  `number` varchar(200) DEFAULT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `country` varchar(200) DEFAULT NULL,
  `date_time` datetime NOT NULL,
   UNIQUE KEY `id` (`id`)
)  ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $add_groups );

}
/*Plugin_CREATE_TABLE_END */

/*Plugin_TABLE_DATA_INSERT-START */ 

register_activation_hook(__FILE__, 'wp_create_table_insert_data' );

function wp_create_table_insert_data()
{
global $wpdb;
$table_name = $wpdb->prefix . 'add_twilio_account';

    $rows_affected = "INSERT INTO  $table_name(id,account_sid,auth_token,date_time) values ('1','Remove this text & Enter Account SID','Remove this text & Enter Account Token ',now())";
     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
     dbDelta( $rows_affected );
}


/*Plugin_TABLE_DATA_INSERT-END */ 

?>
 
 
 
<?php 
define( 'TWL_CORE_VERSION', '1.1.0' );
define( 'TWL_CORE_OPTION_PAGE', 'twilio-options' );

if( !defined( 'TWL_TD' ) ) {
	define( 'TWL_TD', 'twilio-core' );
}

if( !defined( 'TWL_PATH' ) ) {
	define( 'TWL_PATH', plugin_dir_path( __FILE__ ) );
}

require_once( TWL_PATH . 'vendor/autoload.php' );
require_once( TWL_PATH . 'helpers.php' );
require_once( TWL_PATH . 'url-shorten.php' );
if ( is_admin() ) {
	require_once( TWL_PATH . 'admin-pages.php' );
}

class WP_Twilio_Core {
	private static $instance;
	private $page_url;

	private function __construct() {
	$this->set_page_url();
	}

	public function init() {
		$options = $this->get_options();

		load_plugin_textdomain( TWL_TD, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		if ( is_admin() ) {
			/** Settings Pages **/
			add_action( 'admin_init', array( $this, 'register_settings' ), 1000 );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 1000 );

		}

		/** User Profile Settings **/
		if( isset( $options['mobile_field'] ) && $options['mobile_field'] ) {
			add_filter( 'user_contactmethods', 'twl_add_contact_item', 10 );
		}
	}

	/**
	 * Add the Twilio item to the Settings menu
	 * @return void
	 * @access public
	 */
	public function admin_menu() {
		add_options_page( __( 'Twilio', TWL_TD ), __( 'Twilio', TWL_TD ), 'administrator', TWL_CORE_OPTION_PAGE, array( $this, 'display_tabs' ) );
	}

	/**
	 * Determines what tab is being displayed, and executes the display of that tab
	 * @return void
	 * @access public
	 */
	public function display_tabs() {
		$options = $this->get_options();
		$tabs = $this->get_tabs();
		$current = ( !isset( $_GET['tab'] ) ) ? current( array_keys( $tabs ) ) : $_GET['tab'];
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div><h2><?php _e( 'Twilio For SMS', TWL_TD ); ?></h2>
			<br>
			<h2 class="nav-tab-wrapper"><?php
			foreach( $tabs as $tab => $name ) {
				$classes = array( 'nav-tab' );
				if( $tab == $current ) {
					$classes[] = 'nav-tab-active';
				}
				$href = esc_url( add_query_arg( 'tab', $tab, $this->page_url ) );
				echo '<a class="' . implode( ' ', $classes ) . '" href="' . $href . '">' . $name . '</a>';
			}
			?>
			</h2>

			<?php do_action( 'twl_display_tab', $current, $this->page_url ); ?>
		</div>
		<?php
	}

	/**
	 * Saves the URL of the plugin settings page into the class property
	 * @return void
	 * @access public
	 */
	public function set_page_url() {
		$base = admin_url( 'options-general.php' );
		$this->page_url = add_query_arg( 'page',  TWL_CORE_OPTION_PAGE, $base );
	}

	/**
	 * Returns an array of settings tabs, extensible via a filter
	 * @return void
	 * @access public
	 */
	public function get_tabs() {
		$default_tabs = array(
			 'add_twilio_account' => __( 'Add Twilio Account', TWL_TD ),
			 'add_twilio_number' => __( 'Add Twilio Number', TWL_TD ),
			 'single_sms' => __( 'Single SMS', TWL_TD ),
			 'groups' => __( 'Groups', TWL_TD ),
		     'bulk_sms' => __( 'Bulk SMS', TWL_TD ),
             'sent_sms' => __( 'Sent SMS', TWL_TD ),
             'pending_sms' => __( 'Pending SMS', TWL_TD ),
			 
			 
			 'campaign' => __( 'Campaign', TWL_TD ),

  //'extensions' => __( 'Get Extensions', TWL_TD ),
		);
		return apply_filters( 'twl_settings_tabs', $default_tabs );
	}
	
	
	


	/**
	 * Register/Whitelist our settings on the settings page, allow extensions and other plugins to hook into this
	 * @return void
	 * @access public
	 */
	public function register_settings() {
		register_setting( TWL_CORE_SETTING, TWL_CORE_OPTION, 'twl_sanitize_option' );
		do_action( 'twl_register_additional_settings' );
	}

	/**
	 * Original get_options unifier
	 * @return array List of options
	 * @access public
	 */
	public function get_options() {
	return twl_get_options();
	}

	/**
	 * Get the singleton instance of our plugin
	 * @return class The Instance
	 * @access public
	 */
	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new WP_Twilio_Core();
		}

		return self::$instance;
	}

	/**
	 * Adds the options to the options table
	 * @return void
	 * @access public
	 */
	public static function plugin_activated() {
		add_option( TWL_CORE_OPTION, twl_get_defaults() );
		add_option( TWL_LOGS_OPTION, '' );

	}

	/**
	 * Deletes the options to the options table
	 * @return void
	 * @access public
	 */
	public static function plugin_uninstalled() {
		delete_option( TWL_CORE_OPTION );
		delete_option( TWL_LOGS_OPTION );		

	}

}

$twl_instance = WP_Twilio_Core::get_instance();
add_action( 'plugins_loaded', array( $twl_instance, 'init' ) );
register_activation_hook( __FILE__, array( 'WP_Twilio_Core', 'plugin_activated' ) );
register_uninstall_hook( __FILE__, array( 'WP_Twilio_Core', 'plugin_uninstalled' ) );

 ?>