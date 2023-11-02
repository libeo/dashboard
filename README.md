# Custom Drupal Module "Personalized Dashboard"  (Drupal Version: 9 & 10)

## Description

This custom Drupal module adds a personalized dashboard based on user roles. This dashboard is accessible through an item "My Dashboard" in the main administration menu.

The dashboard lists the following sections:

### Content Section
- Links to contents of different types, pointing to the "list of contents" page with a filter by content type.
- Adjustment of the "Add content" link to allow adding content of the selected type.

### Media Section
- Links to media of different types.

### Configuration Section
- Links to the list of users.
- Links to the list of webforms.
- Links to the configuration pages of certain features.

## Installation
To install the module, follow these steps:

1. Download the module to your custom modules folder.
2. Enable the module via the Drupal administration interface or using Drush with the command `drush en custom_dashboard`.
3. Configure the permissions to access the personalized dashboard via "Administration > People > Permissions".

## Configuration
Once the module is installed and activated, you can configure the personalized dashboard via "Administration > Structure > Personalized Dashboard".

The personalized dashboard is composed of three main sections:

### 1. Content Section
This section lists the different content types available on your site. Each content type comes with a link that takes you to the list of contents of that type, with a filter applied. For each content type, you can also add content directly from this page.

### 2. Media Section
This section lists the different media types available on your site, such as audio, documents, images, videos, etc. Each media type comes with a link that takes you to the list of media of that type.

### 3. Configuration Section
This section lists links to different configuration and administration pages of your site, such as:

- List of users: Access the list of registered users on your site.
- List of webforms: Access the list of forms and their submissions.
- Site settings: Access Configuration page.

Each link in the "Content", "Media", and "Configuration" sections is dynamically generated based on the permissions of the connected user. Make sure the permissions are correctly configured for each user role on your site.
