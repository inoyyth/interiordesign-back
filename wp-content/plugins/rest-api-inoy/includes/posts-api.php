<?php
function posts_count_view( $data ) {
    $post = get_post( $data['id'] );

    if ( empty( $post ) ) {
        return new WP_Error( 'posts_count_view', 'Invalid post', array( 'status' => 404 ) );
    }

    // Now update the ACF field (or whatever you wish)
    $count = (int) get_field('views', $post->ID);
    $count++;
    update_field('views', $count, $post->ID);

    return new WP_REST_Response($count);
}

add_action( 'rest_api_init', function () {
    register_rest_route( REST_API_INOY_ROUTE, '/countview/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'posts_count_view',
    ) );
} );