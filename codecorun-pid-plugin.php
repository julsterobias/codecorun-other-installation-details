<?php
/** 
 * 
 * Plugin Name: Codecorun - Plugin Installation Details
 * Plugin URI: https://codecorun.com/plugins/plugin-installation-details/
 * Description: A simple plugin to display other installation details of the plugin in your website such as installation date and the author
 * Author:      Codecorun
 * Plugin Type: information
 * Author URI: https://codecorun.com
 * Version: 1.0.0
 * Text Domain: codecorun-plugin-installation-details
 * 
 * 
*/

defined( 'ABSPATH' ) or die( 'No access area' );
define('CODECORUN_PID_PATH', plugin_dir_path( __FILE__ ));
define('CODECORUN_PID_URL', plugin_dir_url( __FILE__ ));
define('CODECORUN_PID_PREFIX','codecorun_pid');
define('CODECORUN_PID_VERSION','1.0.1');

add_action( 'init', 'codecorun_pid_load_textdomain' );
function codecorun_pid_load_textdomain() {
	load_plugin_textdomain( 'codecorun-plugin-installation-details', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

function codecorun_pid_install(){
    codecorun_pid_register_plugin( 'codecorun-plugin-installation-details/codecorun-pid-plugin.php' );
}
register_activation_hook( __FILE__, 'codecorun_pid_install' );

//autoload classes
spl_autoload_register(function ($class) {

	if(strpos($class,CODECORUN_PID_PREFIX) !== false){
		$class = preg_replace('/\\\\/', '{'.CODECORUN_PID_PREFIX.'}', $class);
        $fullclass = $class;
		$class = explode('{'.CODECORUN_PID_PREFIX.'}', $class);
		if(!empty(end($class))){
			$filename = str_replace("_", "-", end($class));
            $admin = (strpos($fullclass,'admin') !== false)? 'admin/' : null;
			include $admin.'includes/'.$filename.'.php';
		}
	}

});


add_action('plugins_loaded','codecorun_pdi_init');
function codecorun_pdi_init(){
	
	if(current_user_can('administrator')){
		//load admin class
		new codecorun\pid\admin\codecorun_pid_admin_class();
	}
	
}

if( !function_exists('codecorun_pid_register_plugin') ){

    function  codecorun_pid_register_plugin( $plugin = null )
    {

        if( !$plugin )
            return;

        $code_pid = get_option('codecorun_pid_details');

        $it_has = false;

        $data = [
            'plugin_name' => $plugin,
            'plugin_installation_date' => current_datetime()->format('Y-m-d H:i:s'),
            'status' => 'active',
            'author' => get_current_user_id()
        ];

        if( $code_pid ){
            
            foreach( $code_pid as $index => $pid ){
                if( $pid[ 'plugin_name' ] == $plugin){
                    $it_has = true;
                    $pid[ 'plugin_reactivated_date' ] = current_datetime()->format('Y-m-d H:i:s');
                    $pid[ 'author' ] = get_current_user_id();
                    $code_pid[ $index ] = $pid;
                    break;
                }
            }
           if( !$it_has ){
                $code_pid[] = $data;
                update_option( 'codecorun_pid_details', $code_pid );    
           }else{
                update_option( 'codecorun_pid_details', $code_pid );
           }
            

        }else{
            update_option( 'codecorun_pid_details', [ $data ] );
        }
    }

}
?>