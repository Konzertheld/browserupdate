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
		
	}
	
	/**
	 * Add the Javascript
	 **/
	public function action_template_header()
	{
		Stack::add('template_header_javascript', $this->get_url(true) . 'browserupdate.js', 'browserupdate');
	}
}
?>