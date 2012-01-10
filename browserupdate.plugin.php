<?php
/**
 * Inserts the Javascript from http://browser-update.org.
 * Following versions should be able to configure for which browsers the notification appears.
 **/
class Browserupdate extends Plugin
{
	/**
	 * Create plugin configuration menu entry
	 **/
	public function filter_plugin_config( $actions, $plugin_id )
	{
		if ( $plugin_id == $this->plugin_id() ) {
			$actions['configure'] = _t('Configure');
		}
		return $actions;
	}

	/**
	 * Create plugin configuration
	 **/
	public function action_plugin_ui_configure($plugin_id, $action)
	{
		$ui = new FormUI(strtolower(get_class($this)));
		$ui->append('checkbox', 'use_userbrowserversions', __CLASS__ . '__use_userbrowserversions', _t('Do not automatically set browser version depending on what the publisher still supports', __CLASS__));
		$ui->append('hidden', 'userbrowserversions', __CLASS__ . '__userbrowserversions');
		$ui->set_option('success_message', _t('Options saved'));
		$ui->append('submit', 'save', _t('Save', __CLASS__ ));
		$ui->out();
	}
	
	/**
	 * Add the Javascript
	 **/
	public function action_template_header()
	{
		$opts = Options::get_group( __CLASS__ );
		if($opts['use_userbrowserversions'])
		{
			// Use the browser versions the user wanted
			Stack::add('template_header_javascript', "var \$buoop = {" . $opts['userbrowserversions'] . "};", 'browserupdate_browsers');
		}
		else
		{
			// Just use the automatic way
			Stack::add('template_header_javascript', "var \$buoop = {};", 'browserupdate_browsers');
		}
		Stack::add('template_header_javascript', $this->get_url(true) . 'browserupdate.js', 'browserupdate', 'browserupdate_browsers');
	}
}
?>