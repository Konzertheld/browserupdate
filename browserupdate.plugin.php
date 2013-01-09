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
		$ui->append('checkbox', 'use_userbrowserversions', __CLASS__ . '__use_userbrowserversions', _t('Use own values. Browser this version or lower will be notified:', __CLASS__));
		$i = $ui->append('text', 'userbrowserversion_i', __CLASS__ . '__userbrowserversions__i', _t('Internet Explorer', __CLASS__));
		$f = $ui->append('text', 'userbrowserversion_f', __CLASS__ . '__userbrowserversions__f', _t('Firefox', __CLASS__));
		$o = $ui->append('text', 'userbrowserversion_o', __CLASS__ . '__userbrowserversions__o', _t('Opera', __CLASS__));
		$s = $ui->append('text', 'userbrowserversion_s', __CLASS__ . '__userbrowserversions__s', _t('Safari', __CLASS__));
		$n = $ui->append('text', 'userbrowserversion_n', __CLASS__ . '__userbrowserversions__n', _t('Netscape', __CLASS__));
		$i->add_validator(array($this, 'validate_versions'));
		$f->add_validator(array($this, 'validate_versions'));
		$o->add_validator(array($this, 'validate_versions'));
		$s->add_validator(array($this, 'validate_versions'));
		$n->add_validator(array($this, 'validate_versions'));
		$ui->set_option('success_message', _t('Options saved', __CLASS__));
		$ui->append('submit', 'save', _t('Save', __CLASS__ ));
		$ui->out();
	}
	
	/**
	 * Validate that the version is either numerical or the user decided not to use user versions.
	 **/
	function validate_versions( $value, $control, $form )
	{
		if(!$form->use_userbrowserversions->value || preg_match('/[0-9\.]+/', $value))
		{
			return array();
		}
		else
		{
			return array(_t('Only numerical values are allowed. Use dots for subversions.', __CLASS__));
		}
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
			Stack::add('template_header_javascript', "var \$buoop = {vs:{i:" . $opts['userbrowserversions__i'] . ",f:" . $opts['userbrowserversions__f'] . ",o:" . $opts['userbrowserversions__o'] . ".6,s:" . $opts['userbrowserversions__s'] . ",n:" . $opts['userbrowserversions__n'] . "}};", 'browserupdate_browsers');
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