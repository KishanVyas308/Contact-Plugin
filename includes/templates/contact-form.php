<?php if(get_plugin_options('contact_plugin_active'))  :?>

<link rel="stylesheet" href="https://classless.de/classless.css">

<div id="form_response"></div>
<form id="enquiry_form" action="#" method="POST">

    <?php wp_nonce_field('wp_rest'); ?>

    <label for="name">Name</label>
    <input type="text" name="name">

    <label for="email">Email</label>
    <input type="text" name="email">

    <label for="phone">Phone</label>
    <input type="text" name="phone">

    <label for="message">Message</label><br />
    <textarea name="message"></textarea> <br />

    <button type="submit">Submit</button>
</form>


<script>
    document.getElementById('enquiry_form').addEventListener('submit', function(e) {
        e.preventDefault();
        var form = e.target;
        var formData = new FormData(form);

        // Get the nonce value from the hidden input
        var nonce = form.querySelector('input[name="_wpnonce"]').value;

        fetch("<?php echo get_rest_url(null, 'v1/contact-form/submit'); ?>", {
                method: 'POST',
                headers: {
                    'X-WP-Nonce': nonce
                },
                body: formData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.status === 'error') {
                    document.getElementById('form_response').innerHTML = data.message;
                    return;
                }
                document.getElementById('form_response').innerHTML = data.message;
                form.reset();
            })
            .catch(function(error) {
                console.error('Error:', error);
            });
    });
</script>


<?php else : ?>

    <div class="error">
        <p><?php esc_html_e('Contact form is not active', 'contact-plugin'); ?></p>
    </div>


<?php endif; ?>