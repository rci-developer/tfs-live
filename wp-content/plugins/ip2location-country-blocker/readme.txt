=== IP2Location Country Blocker ===

Contributors: IP2Location
Donate link: https://www.ip2location.com
Tags: ip2location, block country, block proxy, redirection, ip address, 403, ipv4, ipv6, detect proxy, ip2proxy
Requires at least: 2.0
Tested up to: 4.9
Stable tag: 2.13.0

Blocks unwanted visitors from accessing your frontend (blog pages) or backend (admin area) by countries or proxy servers.

== Description ==

This plugin enables user to block unwanted visitors from accessing your frontend (blog pages) or backend (admin area) by countries or proxy servers.

Key Features

* Allow you to block the access from multiple countries.
* Allow you to block the access from anonymous proxies.
* Allow you to whitelist the crawler, for example, Google, Bing, Yandex, and so on, to index your pages (SEO friendly).
* Supports IPv4 and IPv6
* Default to 403 error (Permission Denied) display
* Allow you to customize your own 403 page.
* Send you email notification if some one is trying to access your admin area.
* Provide you statistical report of traffics blocked.

This plugin supports both IP2Location BIN data and web service for geolocation queries. If you would like to use the BIN data, you can easily download and update the BIN data via the plugin settings page. Alternatively, you can also manually download and update the BIN data file using the below links:

Geolocation file download:
[IP2Location & IP2Proxy LITE database (Free)](https://lite.ip2location.com "IP2Location LITE database")
[IP2Location & IP2Proxy Commercial database (Comprehensive)](https://ip2location.com "IP2Location commercial database")

If you would like to use the web service, please visit [IP2Location Web Service](https://www.ip2location.com/web-service "IP2Location Web Service") or [IP2Location Web Service](https://www.ip2location.com/ip2proxy-web-service "IP2Proxy Web Service") for details.

= More Information =
Please visit us at [https://www.ip2location.com](https://www.ip2location.com "https://www.ip2location.com")

== Frequently Asked Questions ==
= Do I need to download the BIN file after the plugin installation? =
Yes, the plugin only provide you an outdated sample BIN file.

= Where can I download the BIN file? =
You can download the free LITE edition at [https://lite.ip2location.com](https://lite.ip2location.com "https://lite.ip2location.com") or commercial edition at [https://www.ip2location.com](https://www.ip2location.com "https://www.ip2location.com"). Decompress the downloaded .BIN file and upload it to `wp-content/plugins/ip2location-country-blocker`.

= Do I need to update the BIN file? =
We encourage you to update your BIN file every month so that your plugin works with the latest IP geolocation result. The update usually be ready on the 1st week of every calendar month.

= What is the frontend? =
The frontend means your blog pages.

= What is the backend? =
The backend means the wordpress admin pages.

= Can I select multiple countries for blocking? =
Yes, you can.

= Can I send an 403 page on blocked IP? =
Yes, you can use the default 403 provided in this plugin.

= Can I custom my own error page? =
Yes, you can create a new page on wordpress and design your own error display. Once completed, you can mark your error page as "private" and configure the error redirection at the setting page.

= Can I configure email notification if user was trying to access my admin page? =
Yes, you can configure email notification if an user from blocked countries list was trying to access your admin page.

= Does this plugin works with "Cache Plugin", such as W3 Total Cache?
No. You must disable the "Cache Plugin" for our plugin to function correctly.

= How do I test the plugin?
You can use https://www.locabrowser.com to test the result.

= Unable to find your answer here? =
Send us email at support@ip2location.com

== Screenshots ==

1. **Country lookup by ip address** - Allow you to perform country lookup by entering a IP address.
2. **Frontend blocking** - Select countries that you would like to block from accessing your blog pages. Page redirection supported.
3. **Backend blocking** - Select countries that you would like to block the visitors from accessing your admin area (wp-login) page. Page redirection supported.
4. **Custom error page** - Custom your own error page to suit your wordpress theme.
5. **Email Alert** - Notify you with details when an user was trying to access your admin page.
6. **Statistic Page** - View blocked traffics and countries.


== Changelog ==

* 2.13.0 Added option to enable/disable forwarder IP.
* 2.12.0 Added option to purge all logs.
* 2.11.3 Ignored Facebook crawler.
* 2.11.2 Removed Facebook from bot list.
* 2.11.1 Fixed charts display error.
* 2.11.0 Added debug log.
* 2.10.4 Fixed custom blocking not working.
* 2.10.3 Minor bug fixed.
* 2.10.2 Fixed syntax issues when using on older version of PHP.
* 2.10.1 Minor changes.
* 2.10.0 IP2Location database update changed to use download token.
* 2.9.2 Fix minor bugs.
* 2.9.1 Separated IP2Proxy as an optional service.
* 2.9.0 Added proxy detection using IP2Proxy.
* 2.8.8 Minor changes.
* 2.8.7 Minor update.
* 2.8.6 Added Serbia in the country list.
* 2.8.5 Only adminstrators will be listed in notification email list.
* 2.8.4 Fixed warnings message when there is no data in statistic charts.
* 2.8.3 Fixed charts alignment issues when viewing with smaller screen.
* 2.8.2 Separated charts into frontend and backend.
* 2.8.1 Fixed notice dismiss issue.
* 2.8.0 Allow custom bots/crawlers to bypass. Supports wildcard IP address blocking.
* 2.7.5 Fixed empty country name in statistic charts.
* 2.7.4 Added bots detection.
* 2.7.3 Fixed bug in logging. Updated IP2Location database.
* 2.7.2 Fixed empty country information in notification email.
* 2.7.1 Skip blocking if user logged in as administrator.
* 2.7.0 Added feature to whitelist or blacklist IP. Also option to skip blocking for logged in users.
* 2.6.7 Fixed ban list cannot be empty.
* 2.6.6 Bugs fixed.
* 2.6.5 Improved Javascript performance.
* 2.6.4 Fixed Javascript conflicts with other plugins.
* 2.6.3 Fixed typo error.
* 2.6.2 Minor bug fixed.
* 2.6.1 Fixed upgrade script.
* 2.6.0 Various changes for better user experience and performance.
* 2.5.3 Fixed conflicts when multiple IP2Location plugins installed.
* 2.5.2 Fixed Web service lookup issue.
* 2.5.1 Fixed setting page issue.
* 2.5.0 Use IP2Location PHP 8.0.2 library for lookup.
* 2.4.5 Use latest IP2Location library for lookup.
* 2.4.4 Fixed close sticky information panel issue.
* 2.4.3 Fixed uninstall function.
* 2.4.2 Prevent settings lost when deactivate/activate the plugin.
* 2.4.1 Use latest IP2Location library for lookup and updated the setting page.
* 2.4.0 Added option to disable log.
* 2.3.9 Reverted changes to support older PHP version.
* 2.3.8 Fixed warning message in WordPress 4.3.
* 2.3.7 Fixed compatible issue with PHP 5.3 and earlier.
* 2.3.6 Fixed compatible issue with PHP 5.3.
* 2.3.5 Fixed issue when upgrading from previous version.
* 2.3.4 Use latest IP2Location library for lookup.
* 2.3.3 Fixed redirect issue with iOS devices.
* 2.3.2 Fixed security issues for backend blocking.
* 2.3.10 Tested with WordPress 4.4.
* 2.3.1 Minor bug fixed.
* 2.3.0 Fixed layout issue. Added warning if blocking own country.
* 2.2.5 Fixed issue with secret code to by pass blocking.
* 2.2.4 Fixed issue with Query IP. Prevent admin from blocking themselves in admin area.
* 2.2.2 Fixed session issues.
* 2.2.2 Fixed blocking failed in backend area.
* 2.2.0 Added IP2Location web service support. Minor layout changes, and code behind rewrote.
* 2.1.0 Added statistic to log blocked traffics.
* 2.0.3 Fixed redirection issue that may not work if additional header information defined by other plugins.
* 2.0.2 Updated redirection using javascript to rectify the not working issues reported under certain circumstances
* 2.0.1 Fixed crash issue with other IP2Location plugins.
* 2.0.0 Added IPv6 supports.
* 1.9.2 Emergency bug fix.
* 1.9.1 Fixed performance issues.
* 1.9.0 Added logic to verify if the default old sample bin used for checking.
* 1.8.0 Fixed the country display issue: South Georgia And The South Sandwich Islands
* 1.7.0 Fixed download script errors.
* 1.6.0 Added user details in the email alert message.
* 1.5.0 Support secret code to bypass backend validation.
* 1.4.0 Send email notification if an user from blocked countries was trying to access your backend page.
* 1.3.0 Move the configuration page to settings, to alleviate the confusion of setting page location.
* 1.2.0 Allow user to custom their own error page.
* 1.1.0 Added dropdown selection for product code.

== Installation ==
### Using WordPress Dashboard
1. Select **Plugins -> Add New**.
1. Search for "IP2Location Country Blocker".
1. Click on *Install Now* to install the plugin.
1. Click on *Activate* button to activate the plugin.
1. Download IP2Location database from https://lite.ip2location.com/database/ip-country (Free) or https://www.ip2location.com/databases/db1-ip-country (Commercial).
1. Download IP2Proxy database from https://lite.ip2location.com/database/px1-ip-country (Free) or https://www.ip2location.com/databases/px1-ip-country (Commercial).
1. Decompress the .BIN file and upload to `wp-content/plugins/ip2location-country-blocker`.
1. If you have IP2Location Web service purchased at https://www.ip2location.com/web-service, insert your API key in the Settings tab.
1. If you have IP2Proxy Web service purchased at https://www.ip2location.com/ip2proxy-web-service, insert your API key in the Settings tab.
1. You can now start using IP2Location Country Blocker to block visitors.

### Manual Installation
1. Upload the plugin to `/wp-content/plugins/ip2location-country-blocker` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Download IP2Location database from https://lite.ip2location.com/database/ip-country (Free) or https://www.ip2location.com/databases/db1-ip-country (Commercial).
1. Download IP2Proxy database from https://lite.ip2location.com/database/px1-ip-country (Free) or https://www.ip2location.com/databases/px1-ip-country (Commercial).
1. Decompress the .BIN file and upload to `wp-content/plugins/ip2location-country-blocker`.
1. If you have IP2Location Web service purchased at https://www.ip2location.com/web-service, insert your API key in the Settings tab.
1. If you have IP2Proxy Web service purchased at https://www.ip2location.com/ip2proxy-web-service, insert your API key in the Settings tab.
1. You can now start using IP2Location Country Blocker to block visitors.

Please take note that this plugin requires minimum **PHP version 5.4**.
