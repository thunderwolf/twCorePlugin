<?php
/**
 * sfWidgetFormTextareaCKEditor represents a CKEditor widget.
 *
 * You must include the CKEditor JavaScript file by yourself.
 *
 * @package    twCorePlugin
 * @subpackage widget
 * @author     Arkadiusz Tułodziecki <ldath@thunderwolf.net>
 */
class sfWidgetFormTextareaCKEditor extends sfWidgetFormTextarea
{

	/**
	 * Constructor.
	 *
	 * Available options:
	 *
	 *  * customConfig: Custom configuration file link
	 *  * phptal:       Is phptal template
	 *  * width:        Width
	 *  * height:       Height
	 *  * config:       The javascript configuration
	 *  * browser:      Browser link
	 *
	 * @param array $options     An array of options
	 * @param array $attributes  An array of default HTML attributes
	 *
	 * @see sfWidgetForm
	 */
	protected function configure ($options = array(), $attributes = array()) {
		$this->addOption('customConfig', '/ckeditor/config.js');
		$this->addOption('phptal', false);
		$this->addOption('width');
		$this->addOption('height');
		$this->addOption('config', false);
		$this->addOption('browser', false);
	}

	/**
	 * @param  string $name        The element name
	 * @param  string $value       The value selected in this widget
	 * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
	 * @param  array  $errors      An array of errors for the field
	 *
	 * @return string An HTML tag string
	 *
	 * @see sfWidgetForm
	 */
	public function render ($name, $value = null, $attributes = array(), $errors = array()) {
		if ($this->getOption('phptal')) {
			$value = str_replace('tal:omit-tag', 'tal:omittag', $value);
		}
		$browserconfig = '';
		if ($this->getOption('browser')) {
			$browser = sfContext::getInstance()->getController()->genUrl($this->getOption('browser'));
//			$browserconfig = ", filebrowserBrowseUrl: '".$browser."', filebrowserUploadUrl: '".$browser."', filebrowserImageBrowseUrl: '".$browser."', filebrowserImageUploadUrl: '".$browser."', filebrowserWindowWidth : '640', filebrowserWindowHeight : '480'";
			$browserconfig = ", filebrowserBrowseUrl: '".$browser."', filebrowserImageBrowseUrl: '".$browser."', filebrowserWindowWidth : '640', filebrowserWindowHeight : '480', filebrowserWindowScrollbars: true";
		}
		$textarea = parent::render($name, $value, $attributes, $errors);
		$js = sprintf(<<<EOF
<script type="text/javascript">
CKEDITOR.replace( '%s',
    {
        customConfig : '%s'
        %s
        %s
    });
</script>
EOF
			,
			$this->generateId($name),
			$this->getOption('customConfig'),
			$this->getOption('config') ? ",".implode(",", $this->getOption('config')) : '',
			$browserconfig
		);
		return $textarea . $js;
	}
}