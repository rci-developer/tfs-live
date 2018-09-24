<?php
/**
 * Plugin Name: IP2Location Country Blocker
 * Plugin URI: http://ip2location.com/tutorials/wordpress-ip2location-country-blocker
 * Description: Block visitors from accessing your website or admin area by their country.
 * Version: 2.13.0
 * Author: IP2Location
 * Author URI: http://www.ip2location.com.
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
define('IP2LOCATION_COUNTRY_BLOCKER_ROOT', __DIR__ . DS);

// Initial IP2LocationCountryBlocker class.
$ip2location_country_blocker = new IP2LocationCountryBlocker();

register_activation_hook(__FILE__, [$ip2location_country_blocker, 'set_defaults']);

add_action('init', [$ip2location_country_blocker, 'check_block']);
add_action('admin_enqueue_scripts', [$ip2location_country_blocker, 'plugin_enqueues']);
add_action('admin_notices', [$ip2location_country_blocker, 'plugin_admin_notices']);
add_action('wp_ajax_update_ip2location_country_blocker_database', [$ip2location_country_blocker, 'download_database']);
add_action('wp_ajax_ip2location_country_blocker_admin_notice', [$ip2location_country_blocker, 'plugin_dismiss_admin_notice']);
add_action('upgrader_process_complete', [$ip2location_country_blocker, 'upgrader'], 10, 2);
add_action('wp_footer', [$ip2location_country_blocker, 'footer']);

class IP2LocationCountryBlocker
{
	protected $global_status = '';

	private $countries = ['AF' => 'Afghanistan', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote D\'Ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'Democratic Republic of Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TP' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'FX' => 'France, Metropolitan', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GN' => 'Guinea', 'GW' => 'Guinea-bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard and Mc Donald Islands', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran (Islamic Republic of)', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KR' => 'Korea, Republic of', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People\'s Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libyan Arab Jamahiriya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macau', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States of', 'MD' => 'Moldova, Republic of', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'AN' => 'Netherlands Antilles', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'VC' => 'Saint Vincent and the Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'SR' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovak Republic', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia And The South Sandwich Islands', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SH' => 'St. Helena', 'PM' => 'St. Pierre and Miquelon', 'SD' => 'Sudan', 'SJ' => 'Svalbard and Jan Mayen Islands', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania, United Republic of', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Minor Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican City State (Holy See)', 'VE' => 'Venezuela', 'VN' => 'Viet Nam', 'VG' => 'Virgin Islands (British)', 'VI' => 'Virgin Islands (U.S.)', 'WF' => 'Wallis and Futuna Islands', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'YU' => 'Yugoslavia', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];

	private $robots = [
		'baidu'   => 'Baidu',
		'bingbot' => 'Bing',
		'google'  => 'Google',
		'msnbot'  => 'MSN',
		'slurp'   => 'Yahoo',
		'yandex'  => 'Yandex',
	];

	public function __construct()
	{
		// Make sure this plugin loaded as first priority.
		$wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR . '/$2', __FILE__);
		$this_plugin = plugin_basename(trim($wp_path_to_this_file));
		$active_plugins = get_option('active_plugins');
		$this_plugin_key = array_search($this_plugin, $active_plugins);

		if ($this_plugin_key) {
			array_splice($active_plugins, $this_plugin_key, 1);
			array_unshift($active_plugins, $this_plugin);
			update_option('active_plugins', $active_plugins);
		}

		add_action('admin_menu', [$this, 'add_admin_menu']);
	}

	public function upgrader($upgrader, $options)
	{
		if ($options['action'] == 'update' && $options['type'] == 'plugin') {
			foreach ($options['plugins'] as $plugin) {
				if ($plugin == plugin_basename(__FILE__)) {
					$this->perform_upgrade();
				}
			}
		}
	}

	public function admin_page()
	{
		if (!is_admin()) {
			return;
		}

		add_action('wp_enqueue_script', 'load_jquery');
		wp_enqueue_script('ip2location_country_blocker_chosen_js', 'https://cdnjs.cloudflare.com/ajax/libs/chosen/1.7.0/chosen.jquery.min.js', [], null, true);
		wp_enqueue_script('ip2location_country_blocker_chart_js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js', [], null, true);
		wp_enqueue_script('ip2location_country_blocker_tagsinput_js', plugins_url('/assets/js/jquery.tagsinput.min.js', __FILE__), [], null, true);

		wp_enqueue_style('ip2location_country_blocker_chosen_css', esc_url_raw('https://cdnjs.cloudflare.com/ajax/libs/chosen/1.7.0/chosen.min.css'), [], null);
		wp_enqueue_style('ip2location_country_blocker_tagsinput_css', esc_url_raw('https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css'), [], null);
		wp_enqueue_style('ip2location_country_blocker_custom_css', plugins_url('/assets/css/custom.css', __FILE__), [], null);

		if (get_option('ip2location_country_blocker_lookup_mode') == 'bin') {
			// Get BIN database
			if (($database = $this->get_database_file()) !== null) {
				update_option('ip2location_country_blocker_database', $database);
			}

			if (($date = $this->get_database_date()) !== null) {
				if (strtotime($date) < strtotime('-2 months')) {
					$this->global_status = '
					<div id="message" class="error">
						<p><strong>WARNING</strong>: Your IP2Location database was outdated. We strongly recommend you to download the latest version for accurate result.</p>
					</div>';
				}
			}
		}

		if (get_option('ip2location_country_blocker_px_lookup_mode') == 'px_bin') {
			// Get BIN database
			if (($database = $this->get_px_database_file()) !== null) {
				update_option('ip2location_country_blocker_px_database', $database);
			}

			if (($date = $this->get_px_database_date()) !== null) {
				if (strtotime($date) < strtotime('-2 months')) {
					$this->global_status = '
					<div id="message" class="error">
						<p><strong>WARNING</strong>: Your IP2Proxy database was outdated. We strongly recommend you to download the latest version for accurate result.</p>
					</div>';
				}
			}
		}

		if (class_exists('W3_Cache') || function_exists('wp_super_cache_init') || class_exists('Cache_Enabler') || class_exists('WpFastestCache') || class_exists('SC_Advanced_Cache') || class_exists('LiteSpeed_Cache') || class_exists('HyperCache')) {
			$this->global_status .= '
			<div id="message" class="error">
				<p><strong>ERROR</strong>: You have WordPress cache plugin installed. Please deactivate the plugin in order IP2Location Country Blocker to work properly.</p>
			</div>';
		}

		$tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'frontend';

		switch ($tab) {
			// Backend
			case 'backend':
				$backend_status = '';

				$enable_backend = (isset($_POST['submit']) && isset($_POST['enable_backend'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['enable_backend']))) ? 0 : get_option('ip2location_country_blocker_backend_enabled'));
				$backend_block_mode = (isset($_POST['backend_block_mode'])) ? $_POST['backend_block_mode'] : get_option('ip2location_country_blocker_backend_block_mode');
				$backend_banlist = (isset($_POST['backend_ban_list'])) ? $_POST['backend_ban_list'] : (!isset($_POST['submit']) ? get_option('ip2location_country_blocker_backend_banlist') : '');
				$backend_banlist = (!is_array($backend_banlist)) ? [$backend_banlist] : $backend_banlist;
				$backend_option = (isset($_POST['backend_option'])) ? $_POST['backend_option'] : get_option('ip2location_country_blocker_backend_option');
				$backend_error_page = (isset($_POST['backend_error_page'])) ? $_POST['backend_error_page'] : get_option('ip2location_country_blocker_backend_error_page');
				$backend_redirect_url = (isset($_POST['backend_redirect_url'])) ? $_POST['backend_redirect_url'] : get_option('ip2location_country_blocker_backend_redirect_url');
				$bypass_code = (isset($_POST['bypass_code'])) ? $_POST['bypass_code'] : get_option('ip2location_country_blocker_bypass_code');
				$backend_ip_blacklist = (isset($_POST['backend_ip_blacklist'])) ? $_POST['backend_ip_blacklist'] : get_option('ip2location_country_blocker_backend_ip_blacklist');
				$backend_ip_whitelist = (isset($_POST['backend_ip_whitelist'])) ? $_POST['backend_ip_whitelist'] : get_option('ip2location_country_blocker_backend_ip_whitelist');
				$backend_skip_bots = (isset($_POST['submit']) && isset($_POST['backend_skip_bots'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['backend_skip_bots']))) ? 0 : get_option('ip2location_country_blocker_backend_skip_bots'));
				$backend_bots_list = (isset($_POST['backend_bots_list'])) ? $_POST['backend_bots_list'] : (!isset($_POST['submit']) ? get_option('ip2location_country_blocker_backend_bots_list') : '');
				$backend_bots_list = (!is_array($backend_bots_list)) ? [$backend_bots_list] : $backend_bots_list;
				$backend_block_proxy = (isset($_POST['submit']) && isset($_POST['backend_block_proxy'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['backend_block_proxy']))) ? 0 : get_option('ip2location_country_blocker_backend_block_proxy'));

				$result = $this->get_location($this->get_ip());
				$my_country_code = $result['country_code'];
				$my_country_name = $result['country_name'];

				if (isset($_POST['submit'])) {
					if ($backend_option == 2 && !filter_var($backend_error_page, FILTER_VALIDATE_URL)) {
						$backend_status = '
						<div id="message" class="error">
							<p><strong>ERROR</strong>: Please choose a custom error page.</p>
						</div>';
					} elseif ($backend_option == 3 && !filter_var($backend_redirect_url, FILTER_VALIDATE_URL)) {
						$backend_status = '
						<div id="message" class="error">
							<p><strong>ERROR</strong>: Please provide a valid URL for redirection.</p>
						</div>';
					} else {
						update_option('ip2location_country_blocker_backend_enabled', $enable_backend);
						update_option('ip2location_country_blocker_backend_block_mode', $backend_block_mode);
						update_option('ip2location_country_blocker_backend_banlist', $backend_banlist);
						update_option('ip2location_country_blocker_backend_option', $backend_option);
						update_option('ip2location_country_blocker_backend_redirect_url', $backend_redirect_url);
						update_option('ip2location_country_blocker_backend_error_page', $backend_error_page);
						update_option('ip2location_country_blocker_bypass_code', $bypass_code);
						update_option('ip2location_country_blocker_backend_ip_blacklist', $backend_ip_blacklist);
						update_option('ip2location_country_blocker_backend_ip_whitelist', $backend_ip_whitelist);
						update_option('ip2location_country_blocker_backend_skip_bots', $backend_skip_bots);
						update_option('ip2location_country_blocker_backend_bots_list', $backend_bots_list);
						update_option('ip2location_country_blocker_backend_block_proxy', $backend_block_proxy);

						$backend_status = '
						<div id="message" class="updated">
							<p>Changes saved.</p>
						</div>';
					}
				}

				echo '
				<div class="wrap">
					<h1>IP2Location Country Blocker</h1>
					<p>Blocks unwanted visitors from accessing your frontend (blog pages) or backend (admin area) by countries or proxy servers.</p>
					' . $this->admin_tabs() . '

					' . $backend_status . '

					<form id="form_backend_settings" method="post" novalidate="novalidate">
						<input type="hidden" name="my_country_code" id="my_country_code" value="' . $my_country_code . '" />
						<input type="hidden" name="my_country_name" id="my_country_name" value="' . $my_country_name . '" />
						<div style="margin-top:20px;">
							<label for="enable_backend">
								<input type="checkbox" name="enable_backend" id="enable_backend"' . (($enable_backend) ? ' checked' : '') . '>
								Enable Backend Blocking
							</label>
						</div>

						<table class="form-table" style="margin-left:20px;">
							<tr>
								<td>
									<fieldset>
										<legend class="screen-reader-text"><span>Blocking Mode</span></legend>
										<label><input type="radio" name="backend_block_mode" value="1"' . (($backend_block_mode == 1) ? ' checked' : '') . ' class="input-field" /> Block countries listed below.</label><br />
										<label><input type="radio" name="backend_block_mode" value="2"' . (($backend_block_mode == 2) ? ' checked' : '') . ' class="input-field" /> Block all countries <strong>except</strong> countries listed below.</label>
									</fieldset>

									<select name="backend_ban_list[]" id="backend_ban_list" data-placeholder="Choose Country..." multiple="true" class="chosen input-field">';

									foreach ($this->countries as $country_code => $country_name) {
										echo '
											<option value="' . $country_code . '"' . (($this->is_in_array($country_code, $backend_banlist)) ? ' selected' : '') . '> ' . $country_name . '</option>';
									}

				echo '
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="backend_skip_bots">
										<input type="checkbox" name="backend_skip_bots" id="backend_skip_bots"' . (($backend_skip_bots) ? ' checked' : '') . ' class="input-field">
										Do not block the below bots and crawlers.
									</label>
									<div style="margin-top:10px">
										<select name="backend_bots_list[]" id="backend_bots_list" data-placeholder="Choose Robot..." multiple="true" class="chosen input-field">';

										foreach ($this->robots as $robot_code => $robot_name) {
											echo '
												<option value="' . $robot_code . '"' . (($this->is_in_array($robot_code, $backend_bots_list)) ? ' selected' : '') . '> ' . $robot_name . '</option>';
										}

					echo '
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label for="backend_block_proxy">
										<input type="checkbox" name="backend_block_proxy" id="backend_block_proxy"' . (($backend_block_proxy) ? ' checked' : '') . ' class="input-field' . (get_option('ip2location_country_blocker_px_lookup_mode') ? '' : ' disabled') . '">
										Block proxy IP.
									</label>
									<p class="description">
										IP2Proxy Lookup Mode is required for this option. You can enable/disable the IP2Proxy Lookup Mode at the Settings tab.
									</p>
								</td>
							</tr>
							<tr>
								<td>
									<p>
										<strong>Show the following page when a visitor is blocked.</strong>
									</p>

									<fieldset>
										<legend class="screen-reader-text"><span>Error Option</span></legend>

										<label>
											<input type="radio" name="backend_option" id="backend_option_1" value="1"' . (($backend_option == 1) ? ' checked' : '') . ' class="input-field">
											Default Error 403 Page
										</label>
										<br />
										<label>
											<input type="radio" name="backend_option" id="backend_option_2" value="2"' . (($backend_option == 2) ? ' checked' : '') . ' class="input-field">
											Custom Error Page :
											<select name="backend_error_page" id="backend_error_page" class="input-field">';

											$pages = get_pages(['post_status' => 'publish,private']);

											foreach ($pages as $page) {
												echo '
												<option value="' . $page->guid . '"' . (($backend_error_page == $page->guid) ? ' selected' : '') . '>' . $page->post_title . '</option>';
											}

					echo '
											</select>
										</label>
										<br />
										<label>
											<input type="radio" name="backend_option" id="backend_option_3" value="3"' . (($backend_option == 3) ? ' checked' : '') . ' class="input-field">
											URL :
											<input type="text" name="backend_redirect_url" id="backend_redirect_url" value="' . $backend_redirect_url . '" class="regular-text code input-field" />
										</label>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td>
									<p style="margin-bottom:10px">
										<strong>Secret code to bypass blocking (Max 20 characters):</strong>
									</p>

									<input type="text" name="bypass_code" id="bypass_code" maxlength="20" value="' . $bypass_code . '" class="regular-text code input-field" />
									<p class="description">
										This is the secret code used to bypass all blocking to backend page. It take precedence over all block settings configured. To bypass, you just need to append the <strong>secret_code</strong> parameter with above value to wp-login.php page. For example, http://www.example.com/wp-login.php<code>?secret_code=1234567</code>
									</p>
								</td>
							</tr>
							<tr>
								<td>
									<p style="margin-bottom:10px">
										<strong>Blacklist the below IP addresses:</strong>
									</p>

									<fieldset>
										<legend class="screen-reader-text"><span>Blacklist</span></legend>
										<input type="text" name="backend_ip_blacklist" id="backend_ip_blacklist" value="' . $backend_ip_blacklist . '" class="regular-text ip-address-list" />
										<p class="description">Use asterisk (*) for wildcard matching. E.g.: 8.8.8.* will match IP from 8.8.8.0 to 8.8.8.255.</p>
									</fieldset>
								</td>
							</tr>

							<tr>
								<td>
									<p style="margin-bottom:10px">
										<strong>Whitelist the below IP addresses:</strong>
									</p>

									<fieldset>
										<legend class="screen-reader-text"><span>Blacklist</span></legend>
										<input type="text" name="backend_ip_whitelist" id="backend_ip_whitelist" value="' . $backend_ip_whitelist . '" class="regular-text ip-address-list" />
										<p class="description">Use asterisk (*) for wildcard matching. E.g.: 8.8.8.* will match IP from 8.8.8.0 to 8.8.8.255.</p>
									</fieldset>
								</td>
							</tr>
						</table>

						<p class="submit">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
						</p>
					</form>

					<div class="clear"></div>
				</div>';
				break;

			// Statistic
			case 'statistic':
				global $wpdb;

				if (isset($_POST['purge'])) {
					$wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . 'ip2location_country_blocker_log');
				}

				// Remove logs older than 30 days.
				$wpdb->query('DELETE FROM ' . $wpdb->prefix . 'ip2location_country_blocker_log WHERE date_created <="' . date('Y-m-d H:i:s', strtotime('-30 days')) . '"');

				// Prepare logs for last 30 days.
				$results = $wpdb->get_results('SELECT DATE_FORMAT(date_created, "%Y-%m-%d") AS date, side, COUNT(*) AS total FROM ' . $wpdb->prefix . 'ip2location_country_blocker_log GROUP BY date, side ORDER BY date', OBJECT);

				$lines = [];
				for ($d = 30; $d > 0; --$d) {
					$lines[date('Y-m-d', strtotime('-' . $d . ' days'))][1] = 0;
					$lines[date('Y-m-d', strtotime('-' . $d . ' days'))][2] = 0;
				}

				foreach ($results as $result) {
					$lines[$result->date][$result->side] = $result->total;
				}

				ksort($lines);

				$labels = [];
				$frontend_access = [];
				$backend_access = [];

				foreach ($lines as $date => $value) {
					$labels[] = $date;
					$frontend_access[] = ($value[1]) ? $value[1] : 0;
					$backend_access[] = ($value[2]) ? $value[2] : 0;
				}

				$frontends = ['countries' => [], 'colors' => [], 'totals' => []];
				$backends = ['countries' => [], 'colors' => [], 'totals' => []];

				// Prepare blocked countries.
				$results = $wpdb->get_results('SELECT side,country_code, COUNT(*) AS total FROM ' . $wpdb->prefix . 'ip2location_country_blocker_log GROUP BY country_code, side ORDER BY total DESC;', OBJECT);

				foreach ($results as $result) {
					if ($result->side == 1) {
						$frontends['countries'][] = addslashes($this->get_country_name($result->country_code));
						$frontends['colors'][] = 'get_color()';
						$frontends['totals'][] = $result->total;
					} else {
						$backends['countries'][] = addslashes($this->get_country_name($result->country_code));
						$backends['colors'][] = 'get_color()';
						$backends['totals'][] = $result->total;
					}
				}

				// Add index to table id not exist.
				$results = $wpdb->get_results('SELECT COUNT(*) AS total FROM information_schema.statistics WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "' . $wpdb->prefix . 'ip2location_country_blocker_log" AND INDEX_NAME = "idx_ip_address"', OBJECT);

				if ($results[0]->total == 0) {
					$wpdb->query('ALTER TABLE `' . $wpdb->prefix . 'ip2location_country_blocker_log` ADD INDEX `idx_ip_address` (`ip_address`);');
				}

				echo '
				<div class="wrap">
					<h2>IP2Location Country Blocker</h2>
					<p>Blocks unwanted visitors from accessing your frontend (blog pages) or backend (admin area) by countries or proxy servers.</p>
					' . $this->admin_tabs() . '

					<h3>Block Statistic For The Past 30 Days</h3>

					<p>
						<canvas id="line_chart" style="width:100%;height:400px"></canvas>
					</p>

					<p>
						<div style="float:left;width:400px;margin-right:30px">
							<h3>Frontend</h3>';

							if (empty($frontends['countries'])) {
								echo '
								<div style="border:1px solid #E1E1E1;padding:10px;background-color:#fff">No data available.</div>';
							} else {
								echo '
								<canvas id="pie_chart_frontend" style="width:100%;height:300px"></canvas>

								<h4>Top 10 IP Address Blocked</h4>

								<table class="wp-list-table widefat striped">
									<thead>
										<tr>
											<td>IP Address</td>
											<td>Total</td>
										</tr>
									</thead>
									<tbody>';

								$results = $wpdb->get_results('SELECT ip_address, COUNT(*) AS total FROM ' . $wpdb->prefix . 'ip2location_country_blocker_log WHERE side = "1" GROUP BY ip_address ORDER BY total DESC LIMIT 10;', OBJECT);

								foreach ($results as $result) {
									echo '
											<tr>
												<td>' . $result->ip_address . '</td>
												<td>' . $result->total . '</td>
											</tr>';
								}

								echo '
									</tbody>
								</table>';
							}

							echo '
						</div>

						<div style="float:left;width:400px">
							<h3>Backend</h3>';

							if (empty($backends['countries'])) {
								echo '
								<div style="border:1px solid #E1E1E1;padding:10px;background-color:#fff">No data available.</div>';
							} else {
								echo '
								<canvas id="pie_chart_backend" style="width:100%;height:300px"></canvas>

								<h4>Top 10 IP Address Blocked</h4>

									<table class="wp-list-table widefat striped">
										<thead>
											<tr>
												<td>IP Address</td>
												<td>Total</td>
											</tr>
										</thead>
										<tbody>';

								$results = $wpdb->get_results('SELECT ip_address, COUNT(*) AS total FROM ' . $wpdb->prefix . 'ip2location_country_blocker_log WHERE side = "2" GROUP BY ip_address ORDER BY total DESC LIMIT 10;', OBJECT);

								foreach ($results as $result) {
									echo '
									<tr>
										<td>' . $result->ip_address . '</td>
										<td>' . $result->total . '</td>
									</tr>';
								}

								echo '
										</tbody>
									</table>';
							}

							echo '
						</div>
					</p>

					<div class="clear"></div>

					<p>
						<form id="form-purge" method="post">
							<input type="hidden" name="purge" value="true">
							<input type="submit" name="submit" id="btn-purge" class="button button-primary" value="Purge All Logs" />
						</form>
					</p>
				</div>
				<script>
				jQuery(document).ready(function($){
					function get_color(){
						var r = Math.floor(Math.random() * 200);
						var g = Math.floor(Math.random() * 200);
						var b = Math.floor(Math.random() * 200);

						return \'rgb(\' + r + \', \' + g + \', \' + b + \', 0.4)\';
					}

					var ctx = document.getElementById(\'line_chart\').getContext(\'2d\');
					var line = new Chart(ctx, {
						type: \'line\',
						data: {
							labels: [\'' . implode('\', \'', $labels) . '\'],
							datasets: [{
								label: \'Frontend\',
								data: [' . implode(', ', $frontend_access) . '],
								backgroundColor: get_color()
							}, {
								label: \'Backend\',
								data: [' . implode(', ', $backend_access) . '],
								backgroundColor: get_color()
							}]
						},
						options: {
							title: {
								display: true,
								text: \'Access Blocked\'
							}
						}
					});';

					if (!empty($frontends['countries'])) {
						echo '
						var ctx = document.getElementById(\'pie_chart_frontend\').getContext(\'2d\');
						var pie = new Chart(ctx, {
							type: \'pie\',
							data: {
								labels: [\'' . implode('\', \'', $frontends['countries']) . '\'],
								datasets: [{
									backgroundColor: [' . implode(', ', $frontends['colors']) . '],
									data: [' . implode(', ', $frontends['totals']) . ']
								}]
							},
							options: {
								title: {
									display: true,
									text: \'Access Blocked By Country\'
								}
							}
						});';
					}

					if (!empty($backends['countries'])) {
						echo '
						var ctx = document.getElementById(\'pie_chart_backend\').getContext(\'2d\');
						var pie = new Chart(ctx, {
							type: \'pie\',
							data: {
								labels: [\'' . implode('\', \'', $backends['countries']) . '\'],
								datasets: [{
									backgroundColor: [' . implode(', ', $backends['colors']) . '],
									data: [' . implode(', ', $backends['totals']) . ']
								}]
							},
							options: {
								title: {
									display: true,
									text: \'Access Blocked By Country\'
								}
							}
						});';
					}

					echo '
				});
				</script>';
				break;

			// IP Query
			case 'ip-query':
				$ip_query_status = '';

				$ip_address = (isset($_POST['ip_address'])) ? $_POST['ip_address'] : $this->get_ip();

				if (isset($_POST['submit'])) {
					if (!filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
						$ip_query_status = '
						<div id="message" class="error">
							<p><strong>ERROR</strong>: Please enter an IP address.</p>
						</div>';
					} else {
						$result = $this->get_location($ip_address, false);

						if (empty($result['country_code'])) {
							$ip_query_status = '
							<div id="message" class="error">
								<p><strong>ERROR</strong>: Unable to lookup IP address <strong>' . $ip_address . '</strong>.</p>
							</div>';
						} else {
							$ip_query_status = '
							<div id="message" class="updated">
								<p>IP address <code>' . $ip_address . '</code> belongs to <strong>' . $result['country_name'] . ' (' . $result['country_code'] . ')</strong>.</p>
							</div>';

							if (isset($result['is_proxy'])) {
								$ip_query_status .= '
								<div id="message" class="updated">
									<p>Proxy: ' . (($result['is_proxy'] == 1) ? 'Yes' : 'No') . '</p>
								</div>';
							}
						}
					}
				}

				echo '
				<div class="wrap">
					<h1>IP2Location Country Blocker</h1>
					<p>Blocks unwanted visitors from accessing your frontend (blog pages) or backend (admin area) by countries or proxy servers.</p>
					' . $this->admin_tabs() . '

					' . $ip_query_status . '

					<form method="post" novalidate="novalidate">
						<table class="form-table">
							<tr>
								<th scope="row"><label for="ip_address">IP Address</label></th>
								<td>
									<input name="ip_address" type="text" id="ip_address" value="' . $ip_address . '" class="regular-text" />
									<p class="description">Enter an IP address for lookup.</p>
								</td>
							</tr>
						</table>

						<p class="submit">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="Lookup" />
						</p>
					</form>

					<div class="clear"></div>
				</div>';
				break;

			// Settings
			case 'settings':
				$settings_status = '';
				$web_service_status = '';

				$lookup_mode = (isset($_POST['lookup_mode'])) ? $_POST['lookup_mode'] : get_option('ip2location_country_blocker_lookup_mode');
				$px_lookup_mode = (isset($_POST['px_lookup_mode'])) ? $_POST['px_lookup_mode'] : get_option('ip2location_country_blocker_px_lookup_mode');
				$api_key = (isset($_POST['api_key'])) ? $_POST['api_key'] : get_option('ip2location_country_blocker_api_key');
				$px_api_key = (isset($_POST['px_api_key'])) ? $_POST['px_api_key'] : get_option('ip2location_country_blocker_px_api_key');
				$email_notification = (isset($_POST['email_notification'])) ? $_POST['email_notification'] : get_option('ip2location_country_blocker_email_notification');
				$detect_forwarder_ip = (isset($_POST['submit']) && isset($_POST['detect_forwarder_ip'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['detect_forwarder_ip']))) ? 0 : get_option('ip2location_country_blocker_detect_forwarder_ip'));
				$enable_log = (isset($_POST['submit']) && isset($_POST['enable_log'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['enable_log']))) ? 0 : get_option('ip2location_country_blocker_log_enabled'));
				$enable_debug_log = (isset($_POST['submit']) && isset($_POST['enable_debug_log'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['enable_debug_log']))) ? 0 : get_option('ip2location_country_blocker_debug_log_enabled'));

				if (isset($_POST['lookup_mode'])) {
					update_option('ip2location_country_blocker_lookup_mode', $lookup_mode);
					update_option('ip2location_country_blocker_px_lookup_mode', $px_lookup_mode);
					update_option('ip2location_country_blocker_email_notification', $email_notification);
					update_option('ip2location_country_blocker_detect_forwarder_ip', $detect_forwarder_ip);
					update_option('ip2location_country_blocker_log_enabled', $enable_log);
					update_option('ip2location_country_blocker_debug_log_enabled', $enable_debug_log);

					if ($enable_debug_log) {
						$this->write_debug_log('Debug log enabled.');
					} else {
						$this->write_debug_log('Debug log disabled.');
					}

					$settings_status .= '
					<div id="message" class="updated">
						<p>Changes saved.</p>
					</div>';
				}

				if (isset($_POST['api_key'])) {
					if (!class_exists('WP_Http')) {
						include_once ABSPATH . WPINC . '/class-http.php';
					}

					$request = new WP_Http();

					$response = $request->request('http://api.ip2location.com/?' . http_build_query([
						'key'   => $api_key,
						'check' => 1,
					]), ['timeout' => 3]);

					if ((isset($response->errors)) || (!(in_array('200', $response['response'])))) {
						$web_service_status .= '
						<div id="message" class="error">
							<p><strong>ERROR</strong>: Error when accessing IP2Location web service gateway.</p>
						</div>';
					} elseif (!preg_match('/^[0-9]+$/', $response['body'])) {
						$web_service_status .= '
						<div id="message" class="error">
							<p><strong>ERROR</strong>: Invalid API key.</p>
						</div>';
					} else {
						update_option('ip2location_country_blocker_api_key', $api_key);

						$web_service_status = '
						<div id="message" class="updated">
							<p>IP2Location Web Service API key saved.</p>
						</div>';
					}
				}

				if (isset($_POST['px_api_key'])) {
					if (!class_exists('WP_Http')) {
						include_once ABSPATH . WPINC . '/class-http.php';
					}

					$request = new WP_Http();

					$response = $request->request('http://api.ip2proxy.com/?' . http_build_query([
						'key'   => $px_api_key,
						'check' => 1,
					]), ['timeout' => 3]);

					if ((isset($response->errors)) || (!(in_array('200', $response['response'])))) {
						$px_web_service_status .= '
						<div id="message" class="error">
							<p><strong>ERROR</strong>: Error when accessing IP2Proxy web service gateway.</p>
						</div>';
					} else {
						$data = json_decode($response['body']);

						if (!preg_match('/^\d+$/', $data->response)) {
							$px_web_service_status .= '
							<div id="message" class="error">
								<p><strong>ERROR</strong>: Invalid API key.</p>
							</div>';
						} else {
							update_option('ip2location_country_blocker_px_api_key', $px_api_key);

							$px_web_service_status = '
							<div id="message" class="updated">
								<p>IP2Proxy Web Service API key saved.</p>
							</div>';
						}
					}
				}

				$date = $this->get_database_date();
				$px_date = $this->get_px_database_date();

				if (!file_exists(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_database'))) {
					$settings_status .= '
					<div id="message" class="error">
						<p><strong>ERROR</strong>: Unable to find the IP2Location BIN database! Please download the database at at <a href="http://www.ip2location.com/?r=wordpress" target="_blank">IP2Location commercial database</a> | <a href="http://lite.ip2location.com/?r=wordpress" target="_blank">IP2Location LITE database (free edition)</a>.</p>
					</div>';
				}

				echo '
				<div class="wrap">
					<h1>IP2Location Country Blocker</h1>
					<p>Blocks unwanted visitors from accessing your frontend (blog pages) or backend (admin area) by countries or proxy servers.</p>
					' . $this->admin_tabs() . '

					<h2 class="title">General Settings</h2>

					' . $settings_status . '

					<form method="post" novalidate="novalidate">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="lookup_mode">IP2Location Lookup Mode</label>
								</th>
								<td>
									<fieldset>
										<legend class="screen-reader-text"><span>Lookup Mode</span></legend>
										<label><input type="radio" name="lookup_mode" id="lookup_mode_bin" value="bin"' . (($lookup_mode == 'bin') ? ' checked' : '') . ' /> Binary Database</label><br />
										<label><input type="radio" name="lookup_mode" id="lookup_mode_ws" value="ws"' . (($lookup_mode == 'ws') ? ' checked' : '') . ' /> Web Service</label><br />
									</fieldset>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="px_lookup_mode">IP2Proxy Lookup Mode</label>
								</th>
								<td>
									<fieldset>
										<legend class="screen-reader-text"><span>IP2Peoxy Lookup Mode</span></legend>
										<label><input type="radio" name="px_lookup_mode" id="px_lookup_mode_disabled" value=""' . (($px_lookup_mode == '') ? ' checked' : '') . ' />  Disabled</label><br />
										<label><input type="radio" name="px_lookup_mode" id="px_lookup_mode_bin" value="px_bin"' . (($px_lookup_mode == 'px_bin') ? ' checked' : '') . ' />  Binary Database</label><br />
										<label><input type="radio" name="px_lookup_mode" id="px_lookup_mode_ws" value="px_ws"' . (($px_lookup_mode == 'px_ws') ? ' checked' : '') . ' /> Web Service</label><br />
									</fieldset>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="email_notification">Send Email Notification To</label>
								</th>
								<td>
									<select name="email_notification">
										<option value="none"> None</option>';

										$users = get_users(['role' => 'administrator']);

										foreach ($users as $user) {
											echo '
											<option value="' . $user->user_email . '"' . (($user->user_email == $email_notification) ? ' selected' : '') . '>' . $user->display_name . '</option>';
										}

										echo '
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="detect_forwarder_ip">Detect Forwarder IP</label>
								</th>
								<td>
									<label for="detect_forwarder_ip">
										<input type="checkbox" name="detect_forwarder_ip" id="detect_forwarder_ip" value="1"' . (($detect_forwarder_ip == 1) ? ' checked' : '') . ' /> Enable
										<p class="description">
											Enable this option if visitor IP detected is incorrect.
										</p>
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="enable_log">Visitor Logs</label>
								</th>
								<td>
									<label for="enable_log">
										<input type="checkbox" name="enable_log" id="enable_log" value="1"' . (($enable_log == 1) ? ' checked' : '') . ' /> Enable Logging
										<p class="description">
											No statistic will be collected and displayed if this option is disabled.
										</p>
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="enable_debug_log">Debugging Logs</label>
								</th>
								<td>
									<label for="enable_debug_log">
										<input type="checkbox" name="enable_debug_log" id="enable_debug_log" value="1"' . (($enable_debug_log == 1) ? ' checked' : '') . ' /> Enable Debug Message Logging
									</label>
								</td>
							</tr>
						</table>

						<p class="submit">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
						</p>
					</form>

					<div id="bin_database">
						<h2 class="title">IP2Location BIN Database Information</h2>

						<table class="form-table">
							<tr>
								<th scope="row">
									<label>File Name</label>
								</th>
								<td>
									<div>' . ((!file_exists(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_database'))) ? '<span class="dashicons dashicons-warning" title="Database file not found."></span>' : '') . get_option('ip2location_country_blocker_database') . '
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label>Database Date</label>
								</th>
								<td>
									' . (($date) ? $date : '-') . '
								</td>
							</tr>
						</table>
					</div>
					<div id="ws_access">
						<h2 class="title">IP2Location Web Service</h2>

						' . $web_service_status . '
						<form method="post" novalidate="novalidate">
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="api_key">API Key</label>
									</th>
									<td>
										<input name="api_key" type="text" id="api_key" value="' . $api_key . '" class="regular-text" />
										<p class="description">Your IP2Location <a href="http://www.ip2location.com/web-service" target="_blank">Web service</a> API key.</p>
									</td>
								</tr>';

								if (!empty($api_key)) {
									if (!class_exists('WP_Http')) {
										include_once ABSPATH . WPINC . '/class-http.php';
									}

									$request = new WP_Http();

									$response = $request->request('http://api.ip2location.com/?' . http_build_query([
										'key'   => $api_key,
										'check' => 1,
									]), ['timeout' => 3]);

									if ((!isset($response->errors)) && ((in_array('200', $response['response'])))) {
										if (preg_match('/^[0-9]+$/', $response['body'])) {
											echo '
											<tr>
												<th scope="row">
													<label for="available_credit">Available Credit</label>
												</th>
												<td>
													' . number_format($response['body'], 0, '', ',') . '
												</td>
											</tr>';
										}
									}
								}

							echo '
							</table>

							<p class="submit">
								<input type="submit" name="submit" id="submit" class="button button-primary" value="Save API Key" />
							</p>
						</form>
					</div>

					<div id="px_bin_database">
						<h2 class="title">IP2Proxy Database Information</h2>

						<table class="form-table">
							<tr>
								<th scope="row">
									<label>File Name</label>
								</th>
								<td>
									<div>' . ((!file_exists(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_px_database'))) ? '<span class="dashicons dashicons-warning" title="Database file not found."></span>' : '') . get_option('ip2location_country_blocker_px_database') . '
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label>Database Date</label>
								</th>
								<td>
									' . (($px_date) ? $px_date : '-') . '
								</td>
							</tr>
						</table>
					</div>
					<div id="bin_download">
						<h2 class="title">Download & Update IP2Location BIN Database</h2>

						<div id="download_status"></div>

						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="database_name">Database Name</label>
								</th>
								<td>
									<select name="database_name" id="database_name">
										<option value=""></option>
										<option value="DB1LITEBIN"> IP2Location Lite DB1</option>
										<option value="DB1BIN"> IP2Location DB1</option>
										<option value="DB1LITEBINIPV6">IP2Location Lite DB1 (IPv6)</option>
										<option value="DB1BINIPV6">IP2Location DB1 (IPv6)</option>
										<option value="PX1LITEBIN"> IP2Proxy Lite PX1</option>
										<option value="PX1BIN"> IP2Proxy PX1</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="token">Download Token</label></th>
								<td>
									<input name="token" type="text" id="token" value="' . get_option('ip2location_country_blocker_token') . '" class="regular-text" />
									<p class="description">
										Get your download token from <a href="https://lite.ip2location.com/file-download" target="_blank">https://lite.ip2location.com/file-download</a> or <a href="https://www.ip2location.com/file-download" target="_blank">https://www.ip2location.com/file-download</a>.
										<br><br>
										If you failed to download the BIN database using this automated downloading tool, please follow the procedures below to update the BIN database manually.

										<ol>
											<li>
												Download the BIN database at <a href="http://www.ip2location.com/?r=wordpress" target="_blank">IP2Location commercial database</a> | <a href="http://lite.ip2location.com/?r=wordpress" target="_blank">IP2Location LITE database (free edition)</a>.</li>
											<li>
												Decompress the zip file and update the BIN database to <code>' . __DIR__ . '</code>.
											</li>
											<li>
												Once completed, please refresh the information by reloading the setting page.
											</li>
										</ol>
									</p>
									<p class="description">
										You may implement automated monthly database update as well. <a href="https://www.ip2location.com/tutorials/how-to-automate-ip2location-bin-database-download" target="_balnk">Learn more...</a>
									</p>
								</td>
							</tr>
						</table>

						<div id="ip2location-download-progress">
							<div class="loading-admin-ip2location"></div> Downloading...
						</div>

						<p class="submit">
							<input type="submit" name="download" id="download" class="button button-primary" value="Download/Update Now" />
						</p>
					</div>
					<div id="px_ws_access">
						<h2 class="title">IP2Proxy Web Service</h2>

						' . $px_web_service_status . '
						<form method="post" novalidate="novalidate">
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="px_api_key">API Key</label>
									</th>
									<td>
										<input name="px_api_key" type="text" id="px_api_key" value="' . $px_api_key . '" class="regular-text" />
										<p class="description">Your IP2Proxy <a href="https://www.ip2location.com/ip2proxy-web-service" target="_blank">Web service</a> API key.</p>
									</td>
								</tr>';

								if (!empty($px_api_key)) {
									if (!class_exists('WP_Http')) {
										include_once ABSPATH . WPINC . '/class-http.php';
									}

									$request = new WP_Http();

									$response = $request->request('http://api.ip2proxy.com/?' . http_build_query([
										'key'   => $px_api_key,
										'check' => 1,
									]), ['timeout' => 3]);

									if ((!isset($response->errors)) && ((in_array('200', $response['response'])))) {
										if (preg_match('/^[0-9]+$/', $response['body'])) {
											echo '
											<tr>
												<th scope="row">
													<label for="available_credit">Available Credit</label>
												</th>
												<td>
													' . number_format($response['body'], 0, '', ',') . '
												</td>
											</tr>';
										}
									}
								}

							echo '
							</table>

							<p class="submit">
								<input type="submit" name="submit" id="submit" class="button button-primary" value="Save API Key" />
							</p>
						</form>
					</div>

					<div class="clear"></div>
				</div>';
				break;

			// Frontend
			case 'frontend':
			default:
				$this->perform_upgrade();

				$frontend_status = '';

				$enable_frontend = (isset($_POST['submit']) && isset($_POST['enable_frontend'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['enable_frontend']))) ? 0 : get_option('ip2location_country_blocker_frontend_enabled'));
				$frontend_block_mode = (isset($_POST['frontend_block_mode'])) ? $_POST['frontend_block_mode'] : get_option('ip2location_country_blocker_frontend_block_mode');
				$frontend_banlist = (isset($_POST['frontend_ban_list'])) ? $_POST['frontend_ban_list'] : (!isset($_POST['submit']) ? get_option('ip2location_country_blocker_frontend_banlist') : '');
				$frontend_banlist = (!is_array($frontend_banlist)) ? [$frontend_banlist] : $frontend_banlist;
				$frontend_option = (isset($_POST['frontend_option'])) ? $_POST['frontend_option'] : get_option('ip2location_country_blocker_frontend_option');
				$frontend_error_page = (isset($_POST['frontend_error_page'])) ? $_POST['frontend_error_page'] : get_option('ip2location_country_blocker_frontend_error_page');
				$frontend_redirect_url = (isset($_POST['frontend_redirect_url'])) ? $_POST['frontend_redirect_url'] : get_option('ip2location_country_blocker_frontend_redirect_url');
				$frontend_ip_blacklist = (isset($_POST['frontend_ip_blacklist'])) ? $_POST['frontend_ip_blacklist'] : get_option('ip2location_country_blocker_frontend_ip_blacklist');
				$frontend_ip_whitelist = (isset($_POST['frontend_ip_whitelist'])) ? $_POST['frontend_ip_whitelist'] : get_option('ip2location_country_blocker_frontend_ip_whitelist');
				$enable_frontend_logged_user_whitelist = (isset($_POST['submit']) && isset($_POST['enable_frontend_logged_user_whitelist'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['enable_frontend_logged_user_whitelist']))) ? 0 : ((get_option('ip2location_country_blocker_frontend_whitelist_logged_user') !== false) ? get_option('ip2location_country_blocker_frontend_whitelist_logged_user') : 1));
				$frontend_skip_bots = (isset($_POST['submit']) && isset($_POST['frontend_skip_bots'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['frontend_skip_bots']))) ? 0 : get_option('ip2location_country_blocker_frontend_skip_bots'));
				$frontend_bots_list = (isset($_POST['frontend_bots_list'])) ? $_POST['frontend_bots_list'] : (!isset($_POST['submit']) ? get_option('ip2location_country_blocker_frontend_bots_list') : '');
				$frontend_bots_list = (!is_array($frontend_bots_list)) ? [$frontend_bots_list] : $frontend_bots_list;
				$frontend_block_proxy = (isset($_POST['submit']) && isset($_POST['frontend_block_proxy'])) ? 1 : (((isset($_POST['submit']) && !isset($_POST['frontend_block_proxy']))) ? 0 : get_option('ip2location_country_blocker_frontend_block_proxy'));

				if (isset($_POST['submit'])) {
					if ($frontend_option == 2 && !filter_var($frontend_error_page, FILTER_VALIDATE_URL)) {
						$frontend_status = '
						<div id="message" class="error">
							<p><strong>ERROR</strong>: Please choose a custom error page.</p>
						</div>';
					} elseif ($frontend_option == 3 && !filter_var($frontend_redirect_url, FILTER_VALIDATE_URL)) {
						$frontend_status = '
						<div id="message" class="error">
							<p><strong>ERROR</strong>: Please provide a valid URL for redirection.</p>
						</div>';
					} else {
						update_option('ip2location_country_blocker_frontend_enabled', $enable_frontend);
						update_option('ip2location_country_blocker_frontend_block_mode', $frontend_block_mode);
						update_option('ip2location_country_blocker_frontend_banlist', $frontend_banlist);
						update_option('ip2location_country_blocker_frontend_option', $frontend_option);
						update_option('ip2location_country_blocker_frontend_redirect_url', $frontend_redirect_url);
						update_option('ip2location_country_blocker_frontend_error_page', $frontend_error_page);
						update_option('ip2location_country_blocker_frontend_ip_blacklist', $frontend_ip_blacklist);
						update_option('ip2location_country_blocker_frontend_ip_whitelist', $frontend_ip_whitelist);
						update_option('ip2location_country_blocker_frontend_whitelist_logged_user', $enable_frontend_logged_user_whitelist);
						update_option('ip2location_country_blocker_frontend_skip_bots', $frontend_skip_bots);
						update_option('ip2location_country_blocker_frontend_bots_list', $frontend_bots_list);
						update_option('ip2location_country_blocker_frontend_block_proxy', $frontend_block_proxy);

						$frontend_status = '
						<div id="message" class="updated">
							<p>Changes saved.</p>
						</div>';
					}
				}

				echo '
				<div class="wrap">
					<h1>IP2Location Country Blocker</h1>
					<p>Blocks unwanted visitors from accessing your frontend (blog pages) or backend (admin area) by countries or proxy servers.</p>
					' . $this->admin_tabs() . '

					' . $frontend_status . '

					<form method="post" novalidate="novalidate">
						<div style="margin-top:20px">
							<label for="enable_frontend">
								<input type="checkbox" name="enable_frontend" id="enable_frontend"' . (($enable_frontend) ? ' checked' : '') . '>
								Enable Frontend Blocking
							</label>
						</div>

						<table class="form-table" style="margin-left:20px;">
							<tr>
								<td>
									<fieldset>
										<legend class="screen-reader-text"><span>Blocking Mode</span></legend>
										<label><input type="radio" name="frontend_block_mode" value="1"' . (($frontend_block_mode == 1) ? ' checked' : '') . ' class="input-field" /> Block countries listed below.</label><br />
										<label><input type="radio" name="frontend_block_mode" value="2"' . (($frontend_block_mode == 2) ? ' checked' : '') . ' class="input-field" /> Block all countries <strong>except</strong> countries listed below.</label>
									</fieldset>
									<select name="frontend_ban_list[]" id="frontend_ban_list" data-placeholder="Choose Country..." multiple="true" class="chosen input-field">';

									foreach ($this->countries as $country_code => $country_name) {
										echo '
											<option value="' . $country_code . '"' . (($this->is_in_array($country_code, $frontend_banlist)) ? ' selected' : '') . '> ' . $country_name . '</option>';
									}

				echo '
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="frontend_skip_bots">
										<input type="checkbox" name="frontend_skip_bots" id="frontend_skip_bots"' . (($frontend_skip_bots) ? ' checked' : '') . ' class="input-field">
										Do not block the below bots and crawlers.
									</label>

									<div style="margin-top:10px;">
										<select name="frontend_bots_list[]" id="frontend_bots_list" data-placeholder="Choose Robot..." multiple="true" class="chosen input-field">';

										foreach ($this->robots as $robot_code => $robot_name) {
											echo '
												<option value="' . $robot_code . '"' . (($this->is_in_array($robot_code, $frontend_bots_list)) ? ' selected' : '') . '> ' . $robot_name . '</option>';
										}

					echo '
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label for="frontend_block_proxy">
										<input type="checkbox" name="frontend_block_proxy" id="frontend_block_proxy"' . (($frontend_block_proxy) ? ' checked' : '') . ' class="input-field' . (get_option('ip2location_country_blocker_px_lookup_mode') ? '' : ' disabled') . '">
										Block proxy IP.
										<p class="description">
											IP2Proxy Lookup Mode is required for this option. You can enable/disable the IP2Proxy Lookup Mode at the Settings tab.
										</p>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<div style="margin-bottom:10px;">
										<strong>Show the following page when visitor is blocked.</strong>
									</div>

									<fieldset>
										<legend class="screen-reader-text"><span>Error Option</span></legend>

										<label>
											<input type="radio" name="frontend_option" id="frontend_option_1" value="1"' . (($frontend_option == 1) ? ' checked' : '') . ' class="input-field">
											Default Error 403 Page
										</label>
										<br />
										<label>
											<input type="radio" name="frontend_option" id="frontend_option_2" value="2"' . (($frontend_option == 2) ? ' checked' : '') . ' class="input-field">
											Custom Error Page :
											<select name="frontend_error_page" id="frontend_error_page" class="input-field">';

											$pages = get_pages(['post_status' => 'publish,private']);

											foreach ($pages as $page) {
												echo '
												<option value="' . $page->guid . '"' . (($frontend_error_page == $page->guid) ? ' selected' : '') . '>' . $page->post_title . '</option>';
											}

					echo '
											</select>
										</label>
										<br />
										<label>
											<input type="radio" name="frontend_option" id="frontend_option_3" value="3"' . (($frontend_option == 3) ? ' checked' : '') . ' class="input-field" />
											URL :
											<input type="text" name="frontend_redirect_url" id="frontend_redirect_url" value="' . $frontend_redirect_url . '" class="regular-text code input-field" />
										</label>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td>
									<div style="margin-bottom:10px;">
										<strong>Blacklist the below IP addresses:</strong>
									</div>

									<fieldset>
										<legend class="screen-reader-text"><span>Blacklist</span></legend>
										<input type="text" name="frontend_ip_blacklist" id="frontend_ip_blacklist" value="' . $frontend_ip_blacklist . '" class="regular-text ip-address-list" />
										<p class="description">Use asterisk (*) for wildcard matching. E.g.: 8.8.8.* will match IP from 8.8.8.0 to 8.8.8.255.</p>
									</fieldset>
								</td>
							</tr>

							<tr>
								<td>
									<div style="margin-bottom:10px;">
										<strong>Whitelist the below IP addresses:</strong>
									</div>

									<fieldset>
										<legend class="screen-reader-text"><span>Blacklist</span></legend>
										<input type="text" name="frontend_ip_whitelist" id="frontend_ip_whitelist" value="' . $frontend_ip_whitelist . '" class="regular-text ip-address-list" />
										<p class="description">Use asterisk (*) for wildcard matching. E.g.: 8.8.8.* will match IP from 8.8.8.0 to 8.8.8.255.</p>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td>
									<label for="enable_frontend_logged_user_whitelist">
										<input type="checkbox" name="enable_frontend_logged_user_whitelist" id="enable_frontend_logged_user_whitelist"' . (($enable_frontend_logged_user_whitelist) ? ' checked' : '') . ' class="input-field">
										Bypass blocking for logged in user.
									</label>
								</td>
							</tr>
						</table>

						<p class="submit">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
						</p>
					</form>

					<div class="clear"></div>
				</div>';
		}

		echo '
		<p>If you like this plugin, please leave us a <a href="https://wordpress.org/support/plugin/ip2location-country-blocker/reviews/">rating</a>. A huge thanks in advance!</p>';
	}

	public function check_block()
	{
		$this->start_session();

		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');
		header('Pragma: no-cache');

		$this->write_debug_log('Visitor [' . $this->get_ip() . '] accessed ' . basename(home_url(add_query_arg(null, null))) . '.');

		if (is_admin()) {
			$this->write_debug_log('Aborted. User is administrator.');
			unset($_SESSION['ip2location_country_blocker_secret_code']);
			return;
		}

		if (preg_match('/facebookexternalhit/', $this->get_user_agent())) {
			return;
		}

		// Backend
		if ($this->is_backend_page()) {
			if (!get_option('ip2location_country_blocker_backend_enabled')) {
				$this->write_debug_log('Aborted. Backend blocking is not enabled.');
				return;
			}

			if ($this->is_in_list($this->get_ip(), 'backend_ip_whitelist')) {
				$this->write_debug_log('Aborted. IP [' . $this->get_ip() . '] is in whitelist.');
				return;
			}

			if (get_option('ip2location_country_blocker_backend_skip_bots') && $this->is_bot('backend')) {
				$this->write_debug_log('Aborted. Visitor is a web crawler.');
				return;
			}

			$secret_code = (isset($_GET['secret_code'])) ? $_GET['secret_code'] : ((isset($_SESSION['ip2location_country_blocker_secret_code'])) ? $_SESSION['ip2location_country_blocker_secret_code'] : md5(microtime()));

			$_SESSION['ip2location_country_blocker_secret_code'] = $secret_code;

			$bypass_code = (get_option('ip2location_country_blocker_bypass_code')) ? get_option('ip2location_country_blocker_bypass_code') : md5(microtime());

			// Stop validation if bypass code is provided.
			if ($bypass_code == $secret_code) {
				$this->write_debug_log('Aborted. Bypass code found.');
				return;
			}

			$result = $this->get_location($this->get_ip());

			if ($this->is_in_list($this->get_ip(), 'backend_ip_blacklist')) {
				$this->write_debug_log('IP [' . $this->get_ip() . '] is in blacklist.');
				$this->block_backend($result['country_code'], $result['country_name']);
			}

			$banlist = get_option('ip2location_country_blocker_backend_banlist');

			if (is_array($banlist) && $this->check_list($result['country_code'], $banlist, get_option('ip2location_country_blocker_backend_block_mode'))) {
				$this->write_debug_log('Country [' . $result['country_code'] . '] is in block list.');
				$this->block_backend($result['country_code'], $result['country_name']);
			}

			if (get_option('ip2location_country_blocker_backend_block_proxy') && $result['is_proxy']) {
				$this->write_debug_log('IP [' . $this->get_ip() . '] is a proxy server.');
				$this->block_backend($result['country_code'], $result['country_name']);
			}
		}

		// Frontend
		else {
			if (!get_option('ip2location_country_blocker_frontend_enabled')) {
				$this->write_debug_log('Aborted. Frontend blocking is not enabled.');
				return;
			}

			if ($this->is_in_list($this->get_ip(), 'frontend_ip_whitelist')) {
				$this->write_debug_log('Aborted. IP [' . $this->get_ip() . '] is in whitelist.');
				return;
			}

			if (is_user_logged_in()) {
				if (get_option('ip2location_country_blocker_frontend_whitelist_logged_user') == false || get_option('ip2location_country_blocker_frontend_whitelist_logged_user') == 1) {
					$this->write_debug_log('Aborted. User is logged in.');
					return;
				}
			}

			if (get_option('ip2location_country_blocker_frontend_skip_bots') && $this->is_bot('frontend')) {
				$this->write_debug_log('Aborted. Backend blocking is not enabled.');
				return;
			}

			$result = $this->get_location($this->get_ip());

			if ($this->is_in_list($this->get_ip(), 'frontend_ip_blacklist')) {
				$this->write_debug_log('IP [' . $this->get_ip() . '] is in blacklist.');
				$this->block_frontend($result['country_code'], $result['country_name']);
			}

			$banlist = get_option('ip2location_country_blocker_frontend_banlist');

			if (is_array($banlist) && $this->check_list($result['country_code'], $banlist, get_option('ip2location_country_blocker_frontend_block_mode'))) {
				$this->write_debug_log('Country [' . $result['country_code'] . '] is in block list.');
				$this->block_frontend($result['country_code'], $result['country_name']);
			}

			if (get_option('ip2location_country_blocker_frontend_block_proxy') && $result['is_proxy']) {
				$this->write_debug_log('IP [' . $this->get_ip() . '] is a proxy server.');
				$this->block_frontend($result['country_code'], $result['country_name']);
			}
		}
	}

	public function add_admin_menu()
	{
		add_menu_page('Country Blocker', 'Country Blocker', 'manage_options', 'ip2location-country-blocker', [$this, 'admin_page'], 'dashicons-admin-ip2location', 30);
	}

	public function set_defaults()
	{
		global $wpdb;

		$this->perform_upgrade();

		if (get_option('ip2location_country_blocker_lookup_mode') !== false) {
			return;
		}

		update_option('ip2location_country_blocker_lookup_mode', 'bin');
		update_option('ip2location_country_blocker_px_lookup_mode', '');
		update_option('ip2location_country_blocker_api_key', '');
		update_option('ip2location_country_blocker_px_api_key', '');
		update_option('ip2location_country_blocker_frontend_enabled', 1);
		update_option('ip2location_country_blocker_frontend_block_mode', 1);
		update_option('ip2location_country_blocker_frontend_banlist', '');
		update_option('ip2location_country_blocker_frontend_error_page', '');
		update_option('ip2location_country_blocker_frontend_redirect_url', '');
		update_option('ip2location_country_blocker_frontend_option', 1);
		update_option('ip2location_country_blocker_backend_enabled', 1);
		update_option('ip2location_country_blocker_backend_block_mode', 1);
		update_option('ip2location_country_blocker_backend_banlist', '');
		update_option('ip2location_country_blocker_backend_error_page', '');
		update_option('ip2location_country_blocker_backend_redirect_url', '');
		update_option('ip2location_country_blocker_backend_option', 1);
		update_option('ip2location_country_blocker_email_notification', 'none');
		update_option('ip2location_country_blocker_bypass_code', '');
		update_option('ip2location_country_blocker_log_enabled', 1);
		update_option('ip2location_country_blocker_debug_log_enabled', 0);
		update_option('ip2location_country_blocker_frontend_ip_blacklist', '');
		update_option('ip2location_country_blocker_frontend_ip_whitelist', '');
		update_option('ip2location_country_blocker_backend_ip_blacklist', '');
		update_option('ip2location_country_blocker_backend_ip_whitelist', '');
		update_option('ip2location_country_blocker_frontend_whitelist_logged_user', 1);
		update_option('ip2location_country_blocker_frontend_block_proxy', '');
		update_option('ip2location_country_blocker_backend_block_proxy', '');

		$wpdb->query('
		CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'ip2location_country_blocker_log (
			`log_id` INT(11) NOT NULL AUTO_INCREMENT,
			`ip_address` VARCHAR(50) NOT NULL COLLATE \'utf8_bin\',
			`country_code` CHAR(2) NOT NULL COLLATE \'utf8_bin\',
			`side` CHAR(1) NOT NULL COLLATE \'utf8_bin\',
			`page` VARCHAR(100) NOT NULL COLLATE \'utf8_bin\',
			`date_created` DATETIME NOT NULL,
			PRIMARY KEY (`log_id`),
			INDEX `idx_country_code` (`country_code`),
			INDEX `idx_side` (`side`),
			INDEX `idx_date_created` (`date_created`),
			INDEX `idx_ip_address` (`ip_address`)
		) COLLATE=\'utf8_bin\'');

		// Get BIN database
		if (($database = $this->get_database_file()) !== null) {
			update_option('ip2location_country_blocker_database', $database);
		}
	}

	public function download_database()
	{
		try {
			$code = (isset($_POST['database'])) ? $_POST['database'] : '';
			$token = (isset($_POST['token'])) ? $_POST['token'] : '';

			if (!class_exists('WP_Http')) {
				include_once ABSPATH . WPINC . '/class-http.php';
			}

			// Remove existing database.zip.
			if (file_exists(IP2LOCATION_COUNTRY_BLOCKER_ROOT . 'database.zip')) {
				@unlink(IP2LOCATION_COUNTRY_BLOCKER_ROOT . 'database.zip');
			}

			// Start downloading BIN database from IP2Location website.
			$request = new WP_Http();
			$response = $request->request('http://www.ip2location.com/download?' . http_build_query([
				'file'  => $code,
				'token' => $token,
			]), ['timeout' => 120]);

			if ((isset($response->errors)) || (!(in_array('200', $response['response'])))) {
				die('Connection error.');
			}

			// Save downloaded package into plugin directory.
			$fp = fopen(IP2LOCATION_COUNTRY_BLOCKER_ROOT . 'database.zip', 'w');

			fwrite($fp, $response['body']);
			fclose($fp);

			// Decompress the package.
			$zip = zip_open(IP2LOCATION_COUNTRY_BLOCKER_ROOT . 'database.zip');

			if (!is_resource($zip)) {
				die('Downloaded file is corrupted.');
			}

			while ($entries = zip_read($zip)) {
				// Extract the BIN file only.
				$file_name = zip_entry_name($entries);

				if (substr($file_name, -4) != '.BIN') {
					continue;
				}

				/*// Remove existing BIN files before extract the latest BIN file.
				$files = scandir( IP2LOCATION_COUNTRY_BLOCKER_ROOT );

				foreach ( $files as $file ) {
					if ( strtoupper( substr( $file, -4 ) ) == '.BIN' ) {
						@unlink( IP2LOCATION_COUNTRY_BLOCKER_ROOT . $file );
					}
				}*/

				$handle = fopen(IP2LOCATION_COUNTRY_BLOCKER_ROOT . $file_name, 'w+');
				fwrite($handle, zip_entry_read($entries, zip_entry_filesize($entries)));
				fclose($handle);

				if (!file_exists(IP2LOCATION_COUNTRY_BLOCKER_ROOT . $file_name)) {
					die('ERROR');
				}

				zip_close($zip);

				@unlink(IP2LOCATION_COUNTRY_BLOCKER_ROOT . 'database.zip');

				if (preg_match('/IP2PROXY/', $file_name)) {
					update_option('ip2location_country_blocker_px_database', $file_name);
				} else {
					update_option('ip2location_country_blocker_database', $file_name);
				}

				update_option('ip2location_country_blocker_token', $token);

				die('SUCCESS');
			}
		} catch (Exception $e) {
			die('ERROR');
		}

		die('ERROR');
	}

	// Add notice in plugin page.
	public function plugin_admin_notices()
	{
		if (get_user_meta(get_current_user_id(), 'ip2location_country_blocker_admin_notice', true) === 'dismissed') {
			return;
		}

		$current_screen = get_current_screen();

		if ($current_screen->parent_base == 'plugins') {
			if (is_plugin_active('ip2location-country-blocker/ip2location-country-blocker.php')) {
				echo '
					<div id="ip2location-country-blocker-notice" class="updated notice is-dismissible">
						<h2>IP2Location Country Blocker is almost ready!</h2>
						<p>Download and update IP2Location BIN database for accurate result.</p>
						<p>
							<a href="' . get_admin_url() . 'admin.php?page=ip2location-country-blocker&tab=settings" class="button button-primary"> Download Now </a>
							<a href="http://www.ip2location.com/?r=wordpress" class="button"> Learn more </a>
						</p>
					</div>
				';
			}
		}
	}

	// Enqueue the script.
	public function plugin_enqueues($hook)
	{
		wp_enqueue_style('ip2location_country_blocker_admin_menu_styles', untrailingslashit(plugins_url('/', __FILE__)) . '/assets/css/style.css', []);

		if ($hook == 'toplevel_page_ip2location-country-blocker') {
			wp_enqueue_script('ip2location_country_blocker_admin_script', plugins_url('/assets/js/script.js', __FILE__), ['jquery'], null, true);
		} elseif (is_admin() && get_user_meta(get_current_user_id(), 'ip2location_country_blocker_admin_notice', true) !== 'dismissed') {
			wp_enqueue_script('ip2location_country_blocker_admin_script', plugins_url('/assets/js/notice-update.js', __FILE__), ['jquery'], '1.0', true);
			wp_localize_script('ip2location_country_blocker_admin_script', 'ip2location_country_blocker_admin', ['ip2location_country_blocker_admin_nonce' => wp_create_nonce('ip2location_country_blocker_admin_nonce')]);
		}
	}

	// Dismiss the admin notice.
	public function plugin_dismiss_admin_notice()
	{
		if (!isset($_POST['ip2location_country_blocker_admin_nonce'])) {
			wp_die();
		}

		update_user_meta(get_current_user_id(), 'ip2location_country_blocker_admin_notice', 'dismissed');
	}

	public function footer()
	{
		echo "<!--\n";
		echo "The IP2Location Country Blocker is using IP2Location LITE geolocation database. Please visit http://lite.ip2location.com for more information.\n";
		echo "-->\n";
	}

	public function write_debug_log($message)
	{
		if (!get_option('ip2location_country_blocker_debug_log_enabled')) {
			return;
		}

		file_put_contents(IP2LOCATION_COUNTRY_BLOCKER_ROOT . 'debug.log', gmdate('Y-m-d H:i:s') . "\t" . $message . "\n", FILE_APPEND);
	}

	private function is_backend_page()
	{
		if (preg_match('/wp-admin/', $_SERVER['SCRIPT_NAME'])) {
			return true;
		}

		return  $GLOBALS['pagenow'] == 'wp-login.php';
	}

	private function block_backend($country_code = '', $country_name = '')
	{
		global $wpdb;

		if (get_option('ip2location_country_blocker_log_enabled')) {
			$this->write_debug_log('Writing visitor log.');

			$wpdb->query('INSERT INTO ' . $wpdb->prefix . 'ip2location_country_blocker_log (ip_address, country_code, side, page, date_created) VALUES ("' . $this->get_ip() . '", "' . $country_code . '", 2, "' . basename(home_url(add_query_arg(null, null))) . '", "' . date('Y-m-d H:i:s') . '")');
		}

		if (filter_var(get_option('ip2location_country_blocker_email_notification'), FILTER_VALIDATE_EMAIL)) {
			$message = [];

			$message[] = 'Hi,';

			if ($country_code && $country_name) {
				$message[] = 'IP2Location Country Blocker has successfully blocked visitor from accessing your admin page. The visitor\'s details:';
				$message[] = '';
				$message[] = 'IP Address : ' . $this->get_ip();
				$message[] = 'Country    : ' . $country_code . ' (' . $country_name . ')';
			} else {
				$message[] = 'IP2Location Country Blocker has successfully blocked visitor from accessing your admin page.';
				$message[] = 'The visitor IP (' . $this->get_ip() . ') is listed in your blacklist.';
			}

			$message[] = '';
			$message[] = str_repeat('-', 100);
			$message[] = 'Get a free IP2Location LITE database at http://lite.ip2location.com.';
			$message[] = 'Get an accurate IP2Location commercial database at http://www.ip2location.com.';
			$message[] = str_repeat('-', 100);
			$message[] = '';
			$message[] = '';
			$message[] = 'Regards,';
			$message[] = 'IP2Location Country Blocker';
			$message[] = 'www.ip2location.com';

			$this->write_debug_log('Send notification email.');

			wp_mail(get_option('ip2location_country_blocker_email_notification'), 'IP2Location Country Blocker Alert', implode("\n", $message));
		}

		if (get_option('ip2location_country_blocker_backend_option') == 1) {
			$this->deny();
		} elseif (get_option('ip2location_country_blocker_backend_option') == 2) {
			$this->deny(get_option('ip2location_country_blocker_backend_error_page'));
		} else {
			$this->redirect(get_option('ip2location_country_blocker_backend_redirect_url'));
		}
	}

	private function block_frontend($country_code, $country_name)
	{
		global $wpdb;

		if (get_option('ip2location_country_blocker_log_enabled')) {
			$wpdb->query('INSERT INTO ' . $wpdb->prefix . 'ip2location_country_blocker_log (ip_address, country_code, side, page, date_created) VALUES ("' . $this->get_ip() . '", "' . $country_code . '", 1, "' . basename(home_url(add_query_arg(null, null))) . '", "' . date('Y-m-d H:i:s') . '")');
		}

		if (get_option('ip2location_country_blocker_frontend_option') == 1) {
			$this->deny();
		} elseif (get_option('ip2location_country_blocker_frontend_option') == 2) {
			$this->deny(get_option('ip2location_country_blocker_frontend_error_page'));
		} else {
			$this->redirect(get_option('ip2location_country_blocker_frontend_redirect_url'));
		}
	}

	private function get_ip()
	{
		// For development usage
		if (isset($_SERVER['DEV_MODE'])) {
			$_SERVER['REMOTE_ADDR'] = '80.239.243.251';
		}

		if (get_option('ip2location_country_blocker_detect_forwarder_ip') == '1') {
			// If website is hosted behind CloudFlare protection.
			if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
				return $_SERVER['HTTP_CF_CONNECTING_IP'];
			}

			if (isset($_SERVER['X-Real-IP']) && filter_var($_SERVER['X-Real-IP'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
				return $_SERVER['X-Real-IP'];
			}

			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = trim(current(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));

				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
					return $ip;
				}
			}
		}

		return $_SERVER['REMOTE_ADDR'];
	}

	private function is_bot($interface = 'frontend')
	{
		$is_bot = preg_match('/baidu|bingbot|googlebot|-google|ia_archiver|msnbot|naverbot|pingdom|seznambot|slurp|teoma|twitter|yandex|yeti/i', $this->get_user_agent());

		$list = get_option('ip2location_country_blocker_' . (($interface == 'frontend') ? 'frontend' : 'backend') . '_bots_list');

		if (is_array($list)) {
			foreach ($list as $bot) {
				if (preg_match('/' . $bot . '/i', $this->get_user_agent())) {
					return true;
				}
			}
		}

		return $is_bot;
	}

	private function get_user_agent()
	{
		return (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : null;
	}

	private function admin_tabs()
	{
		$tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'frontend';

		return '
		' . $this->global_status . '
		<h2 class="nav-tab-wrapper">
			<a href="' . admin_url('admin.php?page=ip2location-country-blocker&tab=frontend') . '" class="nav-tab' . (($tab == 'frontend') ? ' nav-tab-active' : '') . '">Frontend</a>
			<a href="' . admin_url('admin.php?page=ip2location-country-blocker&tab=backend') . '" class="nav-tab' . (($tab == 'backend') ? ' nav-tab-active' : '') . '">Backend</a>
			<a href="' . admin_url('admin.php?page=ip2location-country-blocker&tab=statistic') . '" class="nav-tab' . (($tab == 'statistic') ? ' nav-tab-active' : '') . '">Statistic</a>
			<a href="' . admin_url('admin.php?page=ip2location-country-blocker&tab=ip-query') . '" class="nav-tab' . (($tab == 'ip-query') ? ' nav-tab-active' : '') . '">IP Query</a>
			<a href="' . admin_url('admin.php?page=ip2location-country-blocker&tab=settings') . '" class="nav-tab' . (($tab == 'settings') ? ' nav-tab-active' : '') . '">Settings</a>
		</h2>';
	}

	private function redirect($url)
	{
		$current_url = preg_replace('/^https?:\/\//', '', home_url(add_query_arg(null, null)));
		$new_url = preg_replace('/^https?:\/\//', '', $url);

		// Prevent infinite redirection.
		if ($new_url == $current_url) {
			return;
		}

		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $url);
		die;
	}

	private function build_url($scheme, $host, $path, $queries)
	{
		return $scheme . '://' . $host . (($path) ? $path : '/') . (($queries) ? ('?' . http_build_query($queries)) : '');
	}

	private function get_current_url()
	{
		global $wp;

		$current_url = add_query_arg($_SERVER['QUERY_STRING'], '', home_url($wp->request));

		$data = parse_url($current_url);

		$queries = [];

		if (isset($data['query'])) {
			parse_str($data['query'], $queries);
		}

		return $this->build_url($data['scheme'], $data['host'], ((isset($data['path'])) ? $data['path'] : ''), $queries);
	}

	private function deny($url = '')
	{
		if (empty($url)) {
			header('HTTP/1.1 403 Forbidden');

			echo '
			<html>
				<head>
					<meta http-equiv="content-type" content="text/html;charset=utf-8">
					<title>Error 403: Access Denied</title>
					<style>
						body{font-family:arial,sans-serif}
					</style>
				</head>
				<body>
					<div style="margin:30px;padding:0 30px 30px;border:2px solid #f00;background-color:#fcc">
						<h2>Access Denied</h2>
						<div>You do not have permission to access the page on this server.</div>
					</div>
				</body>
			</html>';

			die;
		}

		$this->redirect($url);
	}

	private function start_session()
	{
		if (!session_id()) {
			session_start();
		}
	}

	private function check_list($country_code, $banlist, $mode = 1)
	{
		return ($mode == 1) ? $this->is_in_array($country_code, $banlist) : !$this->is_in_array($country_code, $banlist);
	}

	private function is_in_array($needle, $array)
	{
		foreach (array_values($array) as $key) {
			$return[$key] = 1;
		}

		return isset($return[$needle]);
	}

	private function get_location($ip, $use_cache = false)
	{
		$this->write_debug_log('Visitor IP: ' . $ip);

		// Read result from session to prevent duplicate lookup.
		if (isset($_SESSION[$ip . '_country_code']) && !empty($_SESSION[$ip . '_country_code']) && isset($_SESSION[$ip . '_is_proxy']) && $use_cache) {
			$this->write_debug_log('Country Code: ' . $_SESSION[$ip . '_country_code'] . ' (Session)');
			$this->write_debug_log('Country Name: ' . $_SESSION[$ip . '_country_name'] . ' (Session)');

			return [
				'country_code' => $_SESSION[$ip . '_country_code'],
				'country_name' => $_SESSION[$ip . '_country_name'],
				'is_proxy'     => $_SESSION[$ip . '_is_proxy'],
			];
		}

		$result = [
			'country_code' => '',
			'country_name' => '',
			'is_proxy'     => '',
		];

		switch (get_option('ip2location_country_blocker_lookup_mode')) {
			// IP2Location Web Service
			case 'ws':
				if (!class_exists('WP_Http')) {
					include_once ABSPATH . WPINC . '/class-http.php';
				}

				$this->write_debug_log('Lookup by Web service.');

				$request = new WP_Http();
				$response = $request->request('http://api.ip2location.com/?' . http_build_query([
					'key' => get_option('ip2location_country_blocker_api_key'),
					'ip'  => $ip,
				]), ['timeout' => 3]);

				if ((isset($response->errors)) || (!(in_array('200', $response['response'])))) {
					$this->write_debug_log('Web service connection error.');
					return $result;
				}

				// Store result into session for later use.
				$_SESSION[$ip . '_country_code'] = $response['body'];
				$_SESSION[$ip . '_country_name'] = $this->get_country_name($response['body']);

				$this->write_debug_log('Country Code: ' . $_SESSION[$ip . '_country_code']);
				$this->write_debug_log('Country Name: ' . $_SESSION[$ip . '_country_name']);

				$result = [
					'country_code' => $_SESSION[$ip . '_country_code'],
					'country_name' => $_SESSION[$ip . '_country_name'],
				];
			break;

			// Local BIN database
			default:
			case 'bin':
				// Make sure IP2Location database is exist.
				if (!is_file(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_database'))) {
					return;
				}

				if (!class_exists('IP2Location\\Database')) {
					require_once IP2LOCATION_COUNTRY_BLOCKER_ROOT . 'class.IP2Location.php';
				}

				$this->write_debug_log('Lookup by BIN database.');

				// Create IP2Location object.
				$db = new \IP2Location\Database(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_database'), \IP2Location\Database::FILE_IO);

				// Get geolocation by IP address.
				$response = $db->lookup($ip, \IP2Location\Database::ALL);

				// Store result into session for later use.
				$_SESSION[$ip . '_country_code'] = $response['countryCode'];
				$_SESSION[$ip . '_country_name'] = $response['countryName'];

				$this->write_debug_log('Country Code: ' . $_SESSION[$ip . '_country_code']);
				$this->write_debug_log('Country Name: ' . $_SESSION[$ip . '_country_name']);

				$result = [
					'country_code' => $_SESSION[$ip . '_country_code'],
					'country_name' => $_SESSION[$ip . '_country_name'],
				];
			break;
		}

		if (get_option('ip2location_country_blocker_px_lookup_mode')) {
			switch (get_option('ip2location_country_blocker_px_lookup_mode')) {
				// Local PX BIN database
				case 'px_bin':
					// Make sure IP2Proxy database is exist.
					if (!is_file(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_px_database'))) {
						return;
					}

					if (!class_exists('IP2Proxy\\Database')) {
						require_once IP2LOCATION_COUNTRY_BLOCKER_ROOT . 'class.IP2Proxy.php';
					}

					$this->write_debug_log('Lookup by PX BIN database.');

					// Create IP2Proxy object.
					$db = new \IP2Proxy\Database(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_px_database'), \IP2Proxy\Database::FILE_IO);

					// Get geolocation by IP address.
					$response = $db->lookup($ip, \IP2Proxy\Database::ALL);

					// Store result into session for later use.
					$_SESSION[$ip . '_is_proxy'] = $response['isProxy'];

					$this->write_debug_log('Is Proxy: ' . $response['isProxy']);

					$result['is_proxy'] = $_SESSION[$ip . '_is_proxy'];
				break;

				// IP2Proxy Web Service
				case 'px_ws':
					if (!class_exists('WP_Http')) {
						include_once ABSPATH . WPINC . '/class-http.php';
					}

					$this->write_debug_log('Lookup by Web service.');

					$request = new WP_Http();
					$response = $request->request('http://api.ip2proxy.com/?' . http_build_query([
						'key' => get_option('ip2location_country_blocker_px_api_key'),
						'ip'  => $ip,
					]), ['timeout' => 3]);

					if ((isset($response->errors)) || (!(in_array('200', $response['response'])))) {
						$this->write_debug_log('Web service connection error.');
						return $result;
					}

					$data = json_decode($response['body']);

					// Store result into session for later use.
					$_SESSION[$ip . '_is_proxy'] = ($data->isProxy == 'YES') ? 1 : 0;

					$result['is_proxy'] = $_SESSION[$ip . '_is_proxy'];

					$this->write_debug_log('Is Proxy: ' . $result['is_proxy']);
				break;
			}
		}

		return $result;
	}

	private function get_country_name($code)
	{
		return (isset($this->countries[$code])) ? $this->countries[$code] : '';
	}

	private function is_in_list($ip, $list_name)
	{
		// IPv6
		if (strpos($ip, ':') !== false) {
			$ip = inet_pton($ip);
		}

		$rows = explode(';', get_option('ip2location_country_blocker_' . $list_name));

		if (count($rows) > 0) {
			foreach ($rows as $row) {
				if ($row == $ip) {
					return true;
				}

				if (preg_match('/^' . str_replace(['.', '*'], ['\\.', '.+'], $row) . '$/', $ip)) {
					return true;
				}
			}
		}

		return false;
	}

	private function get_database_file()
	{
		// Find any .BIN files in current directory.
		$files = scandir(IP2LOCATION_COUNTRY_BLOCKER_ROOT);

		foreach ($files as $file) {
			if (strtoupper(substr($file, -4)) == '.BIN' && preg_match('/IP2LOCATION/', $file)) {
				return $file;
			}
		}
	}

	private function get_px_database_file()
	{
		// Find any .BIN files in current directory.
		$files = scandir(IP2LOCATION_COUNTRY_BLOCKER_ROOT);

		foreach ($files as $file) {
			if (strtoupper(substr($file, -4)) == '.BIN' && preg_match('/IP2PROXY/', $file)) {
				return $file;
			}
		}
	}

	private function get_database_date()
	{
		if (!class_exists('IP2Location\\Database')) {
			require_once IP2LOCATION_COUNTRY_BLOCKER_ROOT . 'class.IP2Location.php';
		}

		if (!is_file(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_database'))) {
			return;
		}

		$obj = new \IP2Location\Database(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_database'), \IP2Location\Database::FILE_IO);

		return str_replace('.', '-', $obj->getDatabaseVersion());
	}

	private function get_px_database_date()
	{
		if (!class_exists('IP2Proxy\\Database')) {
			require_once IP2LOCATION_COUNTRY_BLOCKER_ROOT . 'class.IP2Proxy.php';
		}

		if (!is_file(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_px_database'))) {
			return;
		}

		$obj = new \IP2Proxy\Database(IP2LOCATION_COUNTRY_BLOCKER_ROOT . get_option('ip2location_country_blocker_px_database'), \IP2Proxy\Database::FILE_IO);

		return str_replace('.', '-', $obj->getDatabaseVersion());
	}

	private function perform_upgrade()
	{
		// Frontend Changes
		if (get_option('ip2location_country_blocker_frontend_reditected_url') !== false) {
			// [2.6.0] Option ID to redirect page to external URL has been has been changed from 2 to 3.
			if (get_option('ip2location_country_blocker_frontend_option') == 2) {
				update_option('ip2location_country_blocker_frontend_option', 3);
			}

			if (get_option('ip2location_country_blocker_frontend_option') == 1 && get_option('ip2location_country_blocker_frontend_reditected_url') != 'default') {
				// [2.6.0] Option ID to use custom error page has been has been changed from 1 to 2.
				update_option('ip2location_country_blocker_frontend_option', 2);

				// [2.6.0] Option ip2location_country_blocker_frontend_reditected_url has been renamed to ip2location_country_blocker_frontend_error_page.
				update_option('ip2location_country_blocker_frontend_error_page', get_option('ip2location_country_blocker_frontend_reditected_url'));
			}

			delete_option('ip2location_country_blocker_frontend_reditected_url');
		}

		if (get_option('ip2location_country_blocker_frontend_target') !== false) {
			// [2.6.0] Option ip2location_country_blocker_frontend_target has been renamed to ip2location_country_blocker_frontend_redirect_url.
			update_option('ip2location_country_blocker_frontend_redirect_url', get_option('ip2location_country_blocker_frontend_target'));
			delete_option('ip2location_country_blocker_frontend_target');
		}

		// [2.6.0] New option field
		if (get_option('ip2location_country_blocker_frontend_block_mode') === false) {
			update_option('ip2location_country_blocker_frontend_block_mode', 1);
		}

		// Backend Changes
		if (get_option('ip2location_country_blocker_backend_reditected_url') !== false) {
			// [2.6.0] Option ID to redirect page to external URL has been has been changed from 2 to 3.
			if (get_option('ip2location_country_blocker_backend_option') == 2) {
				update_option('ip2location_country_blocker_backend_option', 3);
			}

			if (get_option('ip2location_country_blocker_backend_option') == 1 && get_option('ip2location_country_blocker_backend_reditected_url') != 'default') {
				// [2.6.0] Option ID to use custom error page has been has been changed from 1 to 2.
				update_option('ip2location_country_blocker_backend_option', 2);

				// [2.6.0] Option ip2location_country_blocker_backend_reditected_url has been renamed to ip2location_country_blocker_backend_error_page.
				update_option('ip2location_country_blocker_backend_error_page', get_option('ip2location_country_blocker_backend_reditected_url'));
			}

			delete_option('ip2location_country_blocker_backend_reditected_url');
		}

		if (get_option('ip2location_country_blocker_backend_target') !== false) {
			// [2.6.0] Option ip2location_country_blocker_backend_target has been renamed to ip2location_country_blocker_backend_redirect_url.
			update_option('ip2location_country_blocker_backend_redirect_url', get_option('ip2location_country_blocker_backend_target'));
			delete_option('ip2location_country_blocker_backend_target');
		}

		// [2.6.0] New option field
		if (get_option('ip2location_country_blocker_backend_block_mode') === false) {
			update_option('ip2location_country_blocker_backend_block_mode', 1);
		}
	}
}
