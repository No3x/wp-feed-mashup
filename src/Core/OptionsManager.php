<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14.09.15
 * Time: 19:41
 */

namespace No3x\WPFM\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class OptionsManager {

	public function getOptionNamePrefix() {
		return $this->getPluginSlug() . '_';
	}
	/**
	 * Define your options meta data here as an array, where each element in the array
	 * @return array of key=>display-name and/or key=>array(display-name, choice1, choice2, ...)
	 * key: an option name for the key (this name will be given a prefix when stored in
	 * the database to ensure it does not conflict with other plugin options)
	 * value: can be one of two things:
	 *   (1) string display name for displaying the name of the option to the user on a web page
	 *   (2) array where the first element is a display name (as above) and the rest of
	 *       the elements are choices of values that the user can select
	 * e.g.
	 * array(
	 *   'item' => 'Item:',             // key => display-name
	 *   'rating' => array(             // key => array ( display-name, choice1, choice2, ...)
	 *       'CanDoOperationX' => array('Can do Operation X', 'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber'),
	 *       'Rating:', 'Excellent', 'Good', 'Fair', 'Poor')
	 */
	public function getOptionMetaData() {
		return array();
	}
	/**
	 * @return array of string name of options
	 */
	public function getOptionNames() {
		return array_keys($this->getOptionMetaData());
	}
	/**
	 * Override this method to initialize options to default values and save to the database with add_option
	 * @return void
	 */
	protected function initOptions() {
	}
	/**
	 * Cleanup: remove all options from the DB
	 * @return void
	 */
	protected function deleteSavedOptions() {
		$optionMetaData = $this->getOptionMetaData();
		if (is_array($optionMetaData)) {
			foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
				$prefixedOptionName = $this->prefix($aOptionKey); // how it is stored in DB
				delete_option($prefixedOptionName);
			}
		}
	}
	/**
	 * Cleanup: remove version option
	 * @since 1.6.0
	 * @return void
	 */
	protected function deleteVersionOption() {
		delete_option( $this->prefix( Plugin::optionVersion ) );
	}
	/**
	 * @return string display name of the plugin to show as a name/title in HTML.
	 * Just returns the class name. Override this method to return something more readable
	 */
	public function getPluginDisplayName() {
		return get_class($this);
	}
	/**
	 * @return string slug of the plugin to use as identifier.
	 * Just returns the class name in lowercase.
	 */
	public function getPluginSlug() {
		return strtolower( "WPFM" );
	}
	/**
	 * Get the prefixed version input $name suitable for storing in WP options
	 * Idempotent: if $optionName is already prefixed, it is not prefixed again, it is returned without change
	 * @param  $name string option name to prefix. Defined in settings.php and set as keys of $this->optionMetaData
	 * @return string
	 */
	public function prefix($name) {
		$optionNamePrefix = $this->getOptionNamePrefix();
		if (strpos($name, $optionNamePrefix) === 0) { // 0 but not false
			return $name; // already prefixed
		}
		return $optionNamePrefix . $name;
	}
	/**
	 * Remove the prefix from the input $name.
	 * Idempotent: If no prefix found, just returns what was input.
	 * @param  $name string
	 * @return string $optionName without the prefix.
	 */
	public function &unPrefix($name) {
		$optionNamePrefix = $this->getOptionNamePrefix();
		if (strpos($name, $optionNamePrefix) === 0) {
			return substr($name, strlen($optionNamePrefix));
		}
		return $name;
	}
	/**
	 * A wrapper function delegating to WP get_option() but it prefixes the input $optionName
	 * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
	 * @param $optionName string defined in settings.php and set as keys of $this->optionMetaData
	 * @param $default string default value to return if the option is not set
	 * @return string the value from delegated call to get_option(), or optional default value
	 * if option is not set.
	 */
	public function getOption($optionName, $default = null) {
		$prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
		$retVal = get_option($prefixedOptionName);
		if (!$retVal && $default) {
			$retVal = $default;
		}
		return $retVal;
	}
	/**
	 * A wrapper function delegating to WP delete_option() but it prefixes the input $optionName
	 * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
	 * @param  $optionName string defined in settings.php and set as keys of $this->optionMetaData
	 * @return bool from delegated call to delete_option()
	 */
	public function deleteOption($optionName) {
		$prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
		return delete_option($prefixedOptionName);
	}
	/**
	 * A wrapper function delegating to WP add_option() but it prefixes the input $optionName
	 * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
	 * @param  $optionName string defined in settings.php and set as keys of $this->optionMetaData
	 * @param  $value mixed the new value
	 * @return null from delegated call to delete_option()
	 */
	public function addOption($optionName, $value) {
		$prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
		return add_option($prefixedOptionName, $value);
	}
	/**
	 * A wrapper function delegating to WP add_option() but it prefixes the input $optionName
	 * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
	 * @param  $optionName string defined in settings.php and set as keys of $this->optionMetaData
	 * @param  $value mixed the new value
	 * @return null from delegated call to delete_option()
	 */
	public function updateOption($optionName, $value) {
		$prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
		return update_option($prefixedOptionName, $value);
	}
	/**
	 * A Role Option is an option defined in getOptionMetaData() as a choice of WP standard roles, e.g.
	 * 'CanDoOperationX' => array('Can do Operation X', 'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber')
	 * The idea is use an option to indicate what role level a user must minimally have in order to do some operation.
	 * So if a Role Option 'CanDoOperationX' is set to 'Editor' then users which role 'Editor' or above should be
	 * able to do Operation X.
	 * Also see: canUserDoRoleOption()
	 * @param  $optionName
	 * @return string role name
	 */
	public function getRoleOption($optionName) {
		$roleAllowed = $this->getOption($optionName);
		if (!$roleAllowed || $roleAllowed == '') {
			$roleAllowed = 'Administrator';
		}
		return $roleAllowed;
	}
	/**
	 * Given a WP role name (case insensitive), return a WP capability which only that role and roles above it have.
	 * http://codex.wordpress.org/Roles_and_Capabilities
	 * @param  $roleName
	 * @return string a WP capability or '' if unknown input role
	 */
	protected function roleToCapability($roleName) {
		switch ( ucfirst( $roleName ) ) {
			case 'Super Admin':
				return 'manage_options';
			case 'Administrator':
				return 'manage_options';
			case 'Editor':
				return 'publish_pages';
			case 'Author':
				return 'publish_posts';
			case 'Contributor':
				return 'edit_posts';
			case 'Subscriber':
				return 'read';
			case 'Anyone':
				return 'read';
		}
		return '';
	}
	/**
	 * @param $roleName string a standard WP role name like 'Administrator'
	 * @return bool
	 */
	public function isUserRoleEqualOrBetterThan($roleName) {
		if ('Anyone' == $roleName) {
			return true;
		}
		$capability = $this->roleToCapability($roleName);
		return current_user_can($capability);
	}
	/**
	 * @param  $optionName string name of a Role option (see comments in getRoleOption())
	 * @return bool indicates if the user has adequate permissions
	 */
	public function canUserDoRoleOption($optionName) {
		$roleAllowed = $this->getRoleOption($optionName);
		if ('Anyone' == $roleAllowed) {
			return true;
		}
		return $this->isUserRoleEqualOrBetterThan($roleAllowed);
	}

	public function load_assets() {
		global $wp_logging_list_page;
		$screen = get_current_screen();
		if ( $screen->id != $wp_logging_list_page )
			return;
	}

	/**
	 * Save Screen option
	 * @since 1.3
	 */
	function save_screen_options( $status, $option, $value ) {
		if ( 'per_page' == $option ) return $value;
		return $status;
	}

	/**
	 * Override this method and follow its format.
	 * The purpose of this method is to provide i18n display strings for the values of options.
	 * For example, you may create a options with values 'true' or 'false'.
	 * In the options page, this will show as a drop down list with these choices.
	 * But when the the language is not English, you would like to display different strings
	 * for 'true' and 'false' while still keeping the value of that option that is actually saved in
	 * the DB as 'true' or 'false'.
	 * To do this, follow the convention of defining option values in getOptionMetaData() as canonical names
	 * (what you want them to literally be, like 'true') and then add each one to the switch statement in this
	 * function, returning the "__()" i18n name of that string.
	 * @param  $optionValue string
	 * @return string __($optionValue) if it is listed in this method, otherwise just returns $optionValue
	 */
	protected function getOptionValueI18nString($optionValue) {
		switch ($optionValue) {
			case 'true':
				return __('true', 'wpml');
			case 'false':
				return __('false', 'wpml');
			case 'Administrator':
				return __('Administrator', 'wpml');
			case 'Editor':
				return __('Editor', 'wpml');
			case 'Author':
				return __('Author', 'wpml');
			case 'Contributor':
				return __('Contributor', 'wpml');
			case 'Subscriber':
				return __('Subscriber', 'wpml');
			case 'Anyone':
				return __('Anyone', 'wpml');
		}
		return $optionValue;
	}
	/**
	 * Query MySQL DB for its version
	 * @return string|false
	 */
	protected function getMySqlVersion() {
		global $wpdb;
		$rows = $wpdb->get_results('select version() as mysqlversion');
		if (!empty($rows)) {
			return $rows[0]->mysqlversion;
		}
		return false;
	}
	/**
	 * If you want to generate an email address like "no-reply@your-site.com" then
	 * you can use this to get the domain name part.
	 * E.g.  'no-reply@' . $this->getEmailDomain();
	 * This code was stolen from the wp_mail function, where it generates a default
	 * from "wordpress@your-site.com"
	 * @return string domain name
	 */
	public function getEmailDomain() {
		// Get the site domain and get rid of www.
		$sitename = strtolower($_SERVER['SERVER_NAME']);
		if (substr($sitename, 0, 4) == 'www.') {
			$sitename = substr($sitename, 4);
		}
		return $sitename;
	}
}