<?php
/* 
Plugin Name: Vivet Widget
Plugin URI: https://www.cubiq.digital/
Description:  Plugin de Vivet.app para agregar Widget
Version: 1.0.0
Author: Vivet.app
Author URI: https://vivet.app/
License: GPLv2 or later
*/


class Vivet_Widget{


    public static function init(){

        register_activation_hook(__FILE__, array(__CLASS__,'vivet_app_widget_activate'));
        register_deactivation_hook(__FILE__, array(__CLASS__, 'vivet_app_widget_deactivate'));
        add_action('admin_menu', array (__CLASS__,'vivet_app_widget_admin'));
        add_action( 'admin_enqueue_scripts', array (__CLASS__,'vivet_app_widget_resources'));
        add_action('wp_footer', array(__CLASS__, 'vivet_app_widget_insert_script'));
    }

    public static function vivet_app_widget_activate()
    {
        global $wpdb;
        $table = $wpdb->prefix .'vivet_app_widget';
        $sql = "CREATE TABLE $table (
            `id` INT NOT NULL AUTO_INCREMENT , 
            `landing_guid` VARCHAR(255) NULL ,
            PRIMARY KEY (`id`)
        )";
        $wpdb->query($sql);
    }

    public static function vivet_app_widget_deactivate()
    {
        global $wpdb;
        $table = $wpdb->prefix .'vivet_app_widget';
        $sql = "DROP TABLE $table";
        $wpdb->query($sql);
    }


    public static function vivet_app_widget_admin()
    {
        add_menu_page(
            __( 'Vivet Widget Landig', 'textdomain' ),
            'Vivet widget',
            'manage_options',
            plugin_dir_path(__FILE__).'admin/admin_landing.php',
            '',
            plugin_dir_url(__FILE__).'/public/img/icon.png',
            null
        );
        
    }
    public static function vivet_app_widget_resources(){
        wp_register_style( 'index.css', plugin_dir_url( __FILE__ ) . './public/css/index.css');
        wp_enqueue_style( 'index.css');
    }

    public static function vivet_app_widget_insert_script() {
        global $wpdb;
        $table = $wpdb->prefix .'vivet_app_widget';
        $sql = "SELECT `landing_guid` FROM $table WHERE `id` = 1";
        $result = $wpdb->get_results($sql);
        $id = $result[0]->landing_guid;
        ?>
            <script type="text/javascript">
                const landingId = "<?php echo esc_attr($id) ?>"
                window.widget=window.widget||[];window.widget.init=function(d){window.widget.params=d;
                const f="vivet-js";if(document.getElementById(f)){return}
                const c=`https://central.vivet.app/widget/view/${landingId}.js`;
                const a=document.createElement("script");a.type="text/javascript";a.id=f;a.async=true;a.src=c;
                const b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)};window.widget.init();
            </script>
        <?php
    }

}
Vivet_Widget::init();
Vivet_Widget::vivet_app_widget_activate();

