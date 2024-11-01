<?php
global $wpdb;
$table = $wpdb->prefix .'vivet_app_widget';
$sql = "SELECT `landing_guid` FROM $table WHERE `id` = 1";
$result = $wpdb->get_results($sql);
$landing = $result[0]->landing_guid;


if(isset($_POST['vivet_app_widget_value_id'])){
    $name = sanitize_text_field($_POST['vivet_app_widget_value_id']);
    if(!$landing){
        $result = $wpdb->insert($table, array(
            'id' => null,
            'landing_guid' => $name
        ));
        $landing = $name;
    }else{
        $wpdb->update($table, 
            array(
                'id' => 1,
                'landing_guid' => $name
            ),
            array(
                'id' => 1,
            )
        );
        $landing = $name;
    }
}


?>


<div class="wrap">
    <h1><?php esc_html_e(get_admin_page_title(), 'text_domain'); ?></h1>
    <hr>
    <form action="" method="post" class="col col-6">
        <p><?php esc_html_e( 'Cargar id de landing', 'text_domain' ); ?></p>
        <input type="text" name="vivet_app_widget_value_id" id="input_vivet_app" value="<?php echo esc_attr($landing);?>" required>
        <button class="button"><?php esc_html_e( 'Cargar', 'text_domain' ); ?></button>
    </form>
</div>
