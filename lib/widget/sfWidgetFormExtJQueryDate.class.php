<?php
/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * sfWidgetFormJQueryDate represents a date widget rendered by JQuery UI.
 *
 * This widget needs JQuery and JQuery UI to work.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormExtJQueryDate.class.php 2949 2009-11-07 20:35:23Z ldath $
 */
class sfWidgetFormExtJQueryDate extends sfWidgetFormDate
{

	/**
	 * Configures the current widget.
	 *
	 * Available options:
	 *
	 *  * image:   The image path to represent the widget (false by default)
	 *  * config:  A JavaScript array that configures the JQuery date widget
	 *  * culture: The user culture
	 *
	 * @param array $options     An array of options
	 * @param array $attributes  An array of default HTML attributes
	 *
	 * @see sfWidgetForm
	 */
	protected function configure($options = array(), $attributes = array()) {
		$this->addOption('image', false);
		$this->addOption('config', '{}');
		$this->addOption('culture', '');
		$this->addOption('changeMonth', false);
		$this->addOption('changeYear', false);
		parent::configure($options, $attributes);
		if ('en' == $this->getOption('culture')) {
			$this->setOption('culture', 'en');
		}
	}

	/**
	 * @param  string $name        The element name
	 * @param  string $value       The date displayed in this widget
	 * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
	 * @param  array  $errors      An array of errors for the field
	 *
	 * @return string An HTML tag string
	 *
	 * @see sfWidgetForm
	 */
	public function render($name, $value = null, $attributes = array(), $errors = array()) {
		$prefix = $this->generateId($name);
		$image = '';
		if (false !== $this->getOption('image')) {
			$image = sprintf(', buttonImage: "%s", buttonImageOnly: true', $this->getOption('image'));
		}
		return parent::render($name, $value, $attributes, $errors) . $this->renderTag('input', array('type' => 'hidden', 'size' => 10, 'id' => $id = $this->generateId($name) . '_jquery_control', 'disabled' => 'disabled')) . sprintf(<<<EOF
<script type="text/javascript">
jQuery(function($){
	$.datepicker.regional['pl'] = {
		closeText: 'Zamknij',
		prevText: '&#x3c;Poprzedni',
		nextText: 'Następny&#x3e;',
		currentText: 'Dziś',
		monthNames: ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec',
		'Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'],
		monthNamesShort: ['Sty','Lu','Mar','Kw','Maj','Cze',
		'Lip','Sie','Wrz','Pa','Lis','Gru'],
		dayNames: ['Niedziela','Poniedzialek','Wtorek','Środa','Czwartek','Piątek','Sobota'],
		dayNamesShort: ['Nie','Pn','Wt','Śr','Czw','Pt','So'],
		dayNamesMin: ['N','Pn','Wt','Śr','Cz','Pt','So'],
		dateFormat: 'yy-mm-dd', firstDay: 1,
		isRTL: false};
});

  function wfd_%s_read_linked()
  {
    jQuery("#%s").val(jQuery("#%s").val() + "-" + jQuery("#%s").val() + "-" + jQuery("#%s").val());

    return {};
  }

  function wfd_%s_update_linked(date)
  {
    jQuery("#%s").val(date.substring(0, 4));
    jQuery("#%s").val(date.substring(5, 7));
    jQuery("#%s").val(date.substring(8));
  }

  function wfd_%s_check_linked_days()
  {
    var daysInMonth = 32 - new Date(jQuery("#%s").val(), jQuery("#%s").val() - 1, 32).getDate();
    jQuery("#%s option").attr("disabled", "");
    jQuery("#%s option:gt(" + (%s) +")").attr("disabled", "disabled");

    if (jQuery("#%s").val() > daysInMonth)
    {
      jQuery("#%s").val(daysInMonth);
    }
  }

  jQuery(document).ready(function() {
    jQuery("#%s").datepicker(jQuery.extend({}, {
      minDate:    new Date(%s, 1 - 1, 1),
      maxDate:    new Date(%s, 12 - 1, 31),
      beforeShow: wfd_%s_read_linked,
      onSelect:   wfd_%s_update_linked,
      changeMonth: false,
      changeYear: false,
      showOn:     "button"
      %s
    }, jQuery.datepicker.regional["%s"], %s, {dateFormat: "yy-mm-dd"}));
  });

  jQuery("#%s, #%s, #%s").change(wfd_%s_check_linked_days);
</script>
EOF
,
		$prefix, $id, $this->generateId($name . '[year]'), $this->generateId($name . '[month]'), $this->generateId($name . '[day]'),
		$prefix, $this->generateId($name . '[year]'), $this->generateId($name . '[month]'), $this->generateId($name . '[day]'),
		$prefix, $this->generateId($name . '[year]'), $this->generateId($name . '[month]'), $this->generateId($name . '[day]'),
		$this->generateId($name . '[day]'),
		($this->getOption('can_be_empty') ? 'daysInMonth' : 'daysInMonth - 1'),
		$this->generateId($name . '[day]'), $this->generateId($name . '[day]'), $id,
		min($this->getOption('years')), max($this->getOption('years')),
		$prefix, $prefix, $image,
		$this->getOption('culture'), $this->getOption('config'),
		$this->generateId($name . '[day]'), $this->generateId($name . '[month]'), $this->generateId($name . '[year]'), $prefix);
	}
}
