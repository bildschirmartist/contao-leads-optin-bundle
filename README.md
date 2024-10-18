# Contao Leads-OptIn Bundle

[![Latest Version on Packagist](http://img.shields.io/packagist/v/cgoit/contao-leads-optin-bundle.svg?style=flat)](https://packagist.org/packages/cgoit/contao-leads-optin-bundle)
![Dynamic JSON Badge](https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Fraw.githubusercontent.com%2FcgoIT%2Fcontao-leads-optin-bundle%2Fmain%2Fcomposer.json&query=%24.require%5B%22contao%2Fcore-bundle%22%5D&label=Contao%20Version)
[![Installations via composer per month](http://img.shields.io/packagist/dm/cgoit/contao-leads-optin-bundle.svg?style=flat)](https://packagist.org/packages/cgoit/contao-leads-optin-bundle)
[![Installations via composer total](http://img.shields.io/packagist/dt/cgoit/contao-leads-optin-bundle.svg?style=flat)](https://packagist.org/packages/cgoit/contao-leads-optin-bundle)

***This is a fork from [cboelter/contao-lead-option](https://github.com/cboelter/contao-leads-optin).
This fork supports notification_center 2.x and leads 3.x.***

Most of the work for Version 2.X was done by [Sebastian Zoglowek](https://github.com/zoglo) thanks for this!

This Extension provides double opt-in functions for the [Contao-Leads](https://github.com/terminal42/contao-leads) extension.

## Setup ##

Setup is currently only possible by composer or the contao manager. Just require or search for ```cgoit/contao-leads-optin-bundle``` and install it.

## Configuration ##

#### Notifications ####

The extension knows two different types of notifications. One is the notification sent to the user, if he submits the form.
This contains the optin link and additional informations. It is called "Anfragen speichern: OptIn". 
The second one is a notification which is sent, if a user successfully clicked on the opt in link. It is called "Anfragen speichern: OptIn Bestätigung".

![Notification list](docs/screenshot-notification-list.png)

1. Anfragen speichern: OptIn

This notification can use the following simple tokens to send nofications to the user:

``` lead_*, formconfig_*, admin_email, raw_data, optin_token, optin_url ```

The token ```lead_*``` can use every form form like this way: ```lead_email```.
The token ```formconfig_*``` can use every form config like this way: ```formconfig_id```.

![Notification](docs/screenshot-notification.png)

2. Anfragen speichern: OptIn Bestätigung

This notification can use the following simple tokens to send nofications to the user:
 
``` lead_*, formconfig_*, admin_email ```

The token ```lead_*``` can use every form field like this way: ```lead_email```.
The token ```formconfig_*``` can use every form config like this way: ```formconfig_id```.

It is not mandatory and can be used e.g for notify the admin about a new registration.

![Notification Success](docs/screenshot-notification-success.png)

*General note: The known simpletoken ##form_\*## will not work in this extension, you need to use ##lead_\*## instead.*

#### Form configuration ####

To use the module, there must be a contao form. Create the form and the form field inside the contao form manager.
This extension is an addon for the leads extension. So you need to enable this inside the form you want to use the extension.

You have to enable the "Anfragen speichern" checkbox inside the form configuration. After enabling the checkbox, you will see
the base configuration for leads and another checkbox "Anfragen speicher OptIn". You have to setup the base configuration, then you can
enable the opt in. Simply choose a notification you have created in the first step, select the ip tracking or not and finally choose a redirect page for the OptIn-Link.

![Form configuration](docs/screenshot-form-configuration.png)

*General note: You have to enable the email field to be stored by leads, if it is not enable, the extension will not work*

#### OptIn-Page / Handling module ####

To get the extension working you have to create a new frontend module of the type "OptIn Verarbeitung". 

In this module you can define a note for the successful optin and a note for an optin with errors. The module automatically decides
which text it will show to the user. As a third configuration you can select a success notification (see notifications section).

Include this module on the page, you have selected in form configuration as the "OptIn Zielseite". The opt in will now work.

![Module verification configuration](docs/screenshot-optin-verification.png)

#### Export ####

This extension provides a custom exporter for the opt in. In the export configuration you can see a new export type called "OptIn-Export CSV (.csv)".
It provides a custom data collector and exporter to export all needed data for the optin. You only need to select this exporter and give it a name.

The people icon in the backend list of leads shows you the opt in state -> green means opt in is done -> grey means opt in is not done right now.

![Export configuration](docs/screenshot-optin-export.png)

This is it ... you can now use the extension. If you think you have found a bug, feel free to open a github issue or a pull request :-) Thanks!

This documentation is sponsored by Stefan Senn (Thanks for that!). 