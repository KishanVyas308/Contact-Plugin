<?php

add_shortcode('contact', 'show_contact_form');

add_action('rest_api_init', 'create_rest_endpoint');

add_action('init', 'create_submission_page');



function create_submission_page()
{
    $args = [

        'public' => true,
        'has_archive' => true,
        'labels' => [
            'name' => 'Submissions',
            'singular_name' => 'Submission',
        ],
        // 'capabilities' => [
        //     'create_posts' => 'do_not_allow', 
        // ],
        'supports' => [
            'custom-fields',
        ],

    ];


    register_post_type('submission', $args);
}

function show_contact_form()
{
    include MY_PLUGIN_PATH . '/includes/templates/contact-form.php';
}

function create_rest_endpoint()
{
    register_rest_route('v1/contact-form', 'submit', array(

        'methods' => 'POST',
        'callback' => 'handle_enquiry',

    ));
}

function handle_enquiry($request)
{
    // Get the nonce from the X-WP-Nonce header
    $nonce = $request->get_header('x-wp-nonce');
    $params = $request->get_params();

    if (! wp_verify_nonce($nonce, 'wp_rest')) {
        return new WP_Error('rest_forbidden', esc_html__('You are not allowed to do this.', 'text-domain'), array('status' => 401));
    }

    // send email   
    $header = [];

    $admin_email = get_bloginfo('admin_email');
    $admin_name = get_bloginfo('name');

    $header[] =  "From: {$admin_name} <{$admin_email}>";
    $header[] = "Reply-To: {$params['name']} <{$params['email']}>";
    $header[] = "Content-Type: text/html; charset=UTF-8";

    $subject = "New enquiry from {$params['name']}";

    $message = '';
    $message .= "<h2>New Contact Form Submission</h2>";
    $message .= "<p><strong>Name:</strong> " . esc_html($params['name']) . "</p>";
    $message .= "<p><strong>Email:</strong> " . esc_html($params['email']) . "</p>";
    $message .= "<p><strong>Phone:</strong> " . esc_html($params['phone']) . "</p>";
    $message .= "<p><strong>Message:</strong><br>" . nl2br(esc_html($params['message'])) . "</p>";
    

    // Create a new post of type 'submission'
    $post_data = array(
        'post_title' => 'Submission from ' . $params['name'],
        'post_content' => $message,
        'post_status' => 'publish',
        'post_type' => 'submission',
    );

    // Insert the post into the database
    $post_id = wp_insert_post($post_data);

    add_post_meta($post_id, 'name', $params['name']);
    add_post_meta($post_id, 'email', $params['email']);
    add_post_meta($post_id, 'phone', $params['phone']);
    add_post_meta($post_id, 'message', $params['message']);

    // Send the email
    wp_mail($admin_email, $subject, $message, $header);

    return new WP_REST_Response(array(
        'status' => 'success',
        'message' => 'Form submitted successfully',
    ), 200);
}
