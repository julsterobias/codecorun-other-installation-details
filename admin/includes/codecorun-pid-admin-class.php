<?php
/**
 * 
 * wcdr-admin-class
 * @version 1.0
 * 
 */
namespace codecorun\pid\admin;

defined( 'ABSPATH' ) or die( 'No access area' );

class codecorun_pid_admin_class
{

    public function __construct()
    {

        add_filter( 'manage_plugins_columns', [$this, 'column_header'] );
        add_action( 'manage_plugins_custom_column', [$this, 'column_value'], 10, 2 );
        add_action( 'activated_plugin', [$this,'reg_activated_plugin'], 10, 2 );
        
    }

    /**
     * 
     * column_header
     * @since 1.0.0
     * @param array
     * @return array
     * 
     * 
     */
    public function column_header( $columns ) {
        $columns['codecorun_pid_date_installed'] = __('Date Installed', 'codecorun-plugin-installation-details');
        $columns['codecorun_pid_date_reactivated'] = __('Date Reactivated', 'codecorun-plugin-installation-details');
        $columns['codecorun_pid_author'] = __('Author', 'codecorun-plugin-installation-details');
        return $columns;
    }

    /**
     * 
     * 
     * column_value
     * @since 1.0.0
     * @param string - column name
     * @param string - plugin file path
     * @return 
     * 
     * 
     */
    public function column_value( $column_name, $plugin_file ) {
        $pids = get_option( 'codecorun_pid_details' );
        if ( 'codecorun_pid_date_installed' === $column_name ) {

            if( $pids ){
                foreach( $pids as $pid ){
                    if( $pid[ 'plugin_name' ] == $plugin_file ){
                        $date_format = get_option('date_format');
                        $time_format = get_option('time_format');
                        echo date( $date_format, strtotime($pid[ 'plugin_installation_date' ]) ) .' - '. date( $time_format, strtotime($pid[ 'plugin_installation_date' ]) );
                    }
                }
            }

        }
        if( 'codecorun_pid_author' == $column_name ){
            if( $pids ){
                foreach( $pids as $pid ){
                    if( $pid[ 'plugin_name' ] == $plugin_file ){
                        $author = $pid[ 'author' ];
                        $user = get_userdata( $author );
                        echo '<a href="user-edit.php?user_id='.$author.'" target="_blank">'.$user->user_login.'</a>';
                    }
                }
            }
            
        }

        if( 'codecorun_pid_date_reactivated' == $column_name ){
            if( $pids ){
                foreach( $pids as $pid ){
                    if( $pid[ 'plugin_name' ] == $plugin_file ){

                        if( isset($pid[ 'plugin_reactivated_date' ]) ){
                            $date_format = get_option('date_format');
                            $time_format = get_option('time_format');
                            echo date( $date_format, strtotime($pid[ 'plugin_reactivated_date' ]) ) .' - '. date( $time_format, strtotime($pid[ 'plugin_reactivated_date' ]) );
                        }
                        
                    }
                }
            }
            
        }
    }

    /**
     * 
     * reg_activated_plugin
     * @since 1.0.0
     * @param string - plugin file path
     * @param unknown
     * @return 
     * 
     * 
     */
    public function reg_activated_plugin( $plugin, $network_activation ) {
        // do stuff
        codecorun_pid_register_plugin( $plugin );
    }
}
?>