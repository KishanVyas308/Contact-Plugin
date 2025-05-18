<?php

add_shortcode('contact', 'show_contact_form');

add_action('rest_api_init', 'create_rest_endpoint');

add_action('init', 'create_submission_page');

add_action('add_meta_boxes', 'create_meta_box');

add_filter('manage_submission_posts_columns', 'custom_submission_columns');

add_filter('manage_submission_posts_custom_column', 'custom_submission_columns_data', 10, 2);


function custom_submission_columns_data($column, $post_id)
{
    switch ($column) {
        case 'name':
            echo esc_html(get_post_meta($post_id, 'name', true));
            break;
        case 'email':
            echo esc_html(get_post_meta($post_id, 'email', true));
            break;
        case 'phone':
            echo esc_html(get_post_meta($post_id, 'phone', true));
            break;
        case 'message':
            echo esc_html(get_post_meta($post_id, 'message', true));
            break;
    }
}

function custom_submission_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name', 'contact-plugin'),
        'email' => __('Email', 'contact-plugin'),
        'phone' => __('Phone', 'contact-plugin'),
        'message' => __('Message', 'contact-plugin'),
        'date' => __('Date', 'contact-plugin'),
    );

    return $columns;
}

function create_meta_box()
{
    add_meta_box(
       'custom_contact_form', 'submission', 'display_submission', 'submission',
    );
}

function display_submission($post)
{
    $name = get_post_meta($post->ID, 'name', true);
    $email = get_post_meta($post->ID, 'email', true);
    $phone = get_post_meta($post->ID, 'phone', true);
    $message = get_post_meta($post->ID, 'message', true);

    echo "<h2>Submission Details</h2>";
    echo "<p><strong>Name:</strong> " . esc_html($name) . "</p>";
    echo "<p><strong>Email:</strong> " . esc_html($email) . "</p>";
    echo "<p><strong>Phone:</strong> " . esc_html($phone) . "</p>";
    echo "<p><strong>Message:</strong><br>" . nl2br(esc_html($message)) . "</p>";
}


function create_submission_page()
{
    $args = [

        'public' => true,
        'has_archive' => true,
        'menu_position' => 30,
        'publicly_queryable' => false,
        'labels' => [
            'name' => 'Submissions',
            'singular_name' => 'Submission',
            'edit_item' => 'View Submission',
        ],
        'capabilities' => [
            'create_posts' => 'do_not_allow', 
        ],
        'supports' => false,
        'capability_type' => 'post',
        'capabilities' => [
            'create_posts' => 'do_not_allow', // Disable post creation
        ],
        'map_meta_cap' => true,


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

    $recipient_email = get_plugin_options('contact_plugin_recipients');

    if ( !$recipient_email ) {
        $recipient_email = $admin_email;
    }

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
    wp_mail($recipient_email, $subject, $message, $header);


    // set the confirmation message

    $confirmation_message ='Thank you for your message. We will get back to you soon.';
    if (  get_plugin_options('contact_plugin_message') ) {
        $confirmation_message = get_plugin_options('contact_plugin_message');
        
        $confirmation_message = str_replace('{name}', $params['name'], $confirmation_message);
    }

    return new WP_REST_Response(array(
        'status' => 'success',
        'message' => $confirmation_message,
    ), 200);
}
