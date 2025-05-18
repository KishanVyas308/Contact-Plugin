<link rel="stylesheet" href="https://classless.de/classless.css">

<form id="enquiry_form" action="#" method="POST">
    <label for="name">Name</label>
    <input type="text" name="name" >

    <label for="email">Email</label>
    <input type="text" name="email" >

    <label for="phone">Phone</label>
    <input type="text" name="phone" >

    <label for="message">Message</label><br />
    <textarea name="message" ></textarea> <br />

    <button type="submit">Submit</button>
</form>

<div id="form_response"></div>

<script>
document.getElementById('enquiry_form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = e.target;
    var data = new URLSearchParams(new FormData(form)).toString();

    console.log(data);

    fetch( "<?php echo get_rest_url( null, 'v1/contact-form/submit' );  ?>", {
        method: 'POST',
        body: data
    }).then(function(response) {
        return response.json();
    }).then(function(data) {
        console.log(data);
        document.getElementById('form_response').innerHTML = data.message;
    }).catch(function(error) {
        console.error('Error:', error);
    });
});
</script>
