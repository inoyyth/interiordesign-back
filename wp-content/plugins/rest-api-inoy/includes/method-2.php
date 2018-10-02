<?php
add_action( 'rest_api_init', 'register_post_fields' );
// Register post meta
function register_post_meta() {
    $object_type = 'post';
    $args = array(
        'type'     	   => 'string',
        'description'  => 'A meta key associated with post views.',
        'single'   	   => true,
        'show_in_rest' => true,
    );

    register_meta( $object_type, 'post_views', $args );
}
?>