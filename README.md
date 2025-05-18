# Contact Form Plugin

A simple and customizable contact form plugin for WordPress.

## Overview

**Contact Form Plugin** enables you to easily add a contact form to your WordPress site, manage form submissions in your dashboard, and configure recipient details. The plugin is built in PHP and leverages the Carbon Fields library for flexible options management.

## Features

- Simple and responsive contact form with fields for Name, Email, Phone, and Message.
- Submissions are managed as a custom post type in the WordPress admin.
- Email notifications sent to configurable recipient addresses.
- Options page to enable/disable the form, set recipient email, and customize confirmation message.
- REST API endpoint for AJAX-based form submissions.
- Nonce verification for security.
- Lightweight and easy to integrate.

## Images

![image](https://github.com/user-attachments/assets/82345e72-aa8c-4da7-a6ec-ccdc528ce345)

![image](https://github.com/user-attachments/assets/66bc77fe-b837-4712-a9b5-76de62b27abc)

![image](https://github.com/user-attachments/assets/a69d7717-2d8d-4304-9492-85b83b822819)

![image](https://github.com/user-attachments/assets/7e75b28b-99cd-4b90-96e0-fb91c36d643f)

![image](https://github.com/user-attachments/assets/14c4b6e8-0a3c-43ae-9407-860e2a7bfbd5)

![image](https://github.com/user-attachments/assets/247f98b0-2655-4568-9433-31445b721546)

![image](https://github.com/user-attachments/assets/460b164a-8af3-4844-81c9-a8072948f0be)

![image](https://github.com/user-attachments/assets/151af3a3-31c8-49d0-92a0-ea9b2e4c85a8)

![image](https://github.com/user-attachments/assets/4963f862-b2ba-4d91-a647-6683e5b16a04)

![image](https://github.com/user-attachments/assets/ed9a7457-ec59-40af-9980-f7ea8bcd51be)

![image](https://github.com/user-attachments/assets/2215d93e-4c71-41b4-a65f-39c592ed0dad)

![image](https://github.com/user-attachments/assets/5fd8a5ca-f003-45d5-8fc3-804c52b74482)


## Installation

1. **Clone or download** this repository to your `wp-content/plugins` directory:
   ```bash
   git clone https://github.com/KishanVyas308/Contact-Plugin.git
   ```
2. **Install dependencies** using Composer (required for Carbon Fields support):
   ```bash
   cd Contact-Plugin
   composer install
   ```
3. **Activate** the plugin from your WordPress admin dashboard.

## Usage

1. **Configure Plugin Settings:**
   - In the WordPress admin, go to **Contact Form** under the Settings menu.
   - Enable the plugin, set the recipient email, and customize the confirmation message.

2. **Add the Contact Form:**
   - The form can be rendered wherever you include the plugin’s template or shortcode (if implemented).
   - The form includes Name, Email, Phone, and Message fields.

3. **View Submissions:**
   - Submissions are stored as a custom post type and can be viewed from the admin panel.

## Technology Stack

- **Language:** PHP
- **WordPress** plugin structure
- **Carbon Fields** for options management (`htmlburger/carbon-fields-plugin`)
- **Composer** for dependency management

## File Structure

- `contact-plugin.php` - Main plugin bootstrap file.
- `includes/options-page.php` - Admin options/settings page.
- `includes/contact-form.php` - Core contact form logic and REST endpoint.
- `includes/templates/contact-form.php` - Contact form HTML template and JS.
- `composer.json` / `composer.lock` - Composer configuration and dependencies.

## Customization

- You may modify the form fields or add validation/custom logic inside `includes/contact-form.php` or the template.
- Additional options fields can be added via `includes/options-page.php` using Carbon Fields.

## Contributing

Pull requests and suggestions are welcome!

## License

This project is licensed under the MIT License.

## Author

**Kishan Vyas**

---

> **Note:**  
> This README was generated based on available source and may not cover all customizations or features implemented in the plugin.  
> [Browse the full source code on GitHub](https://github.com/KishanVyas308/Contact-Plugin/search?type=code)
