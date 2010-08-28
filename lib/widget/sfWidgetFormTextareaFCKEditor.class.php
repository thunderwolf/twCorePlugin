<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormTextareaFCKEditor represents a FCKEditor widget.
 *
 * You must include the FCKEditor JavaScript file by yourself.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Denny Reeh <denny.reeh@gmail.com>
 * @version    SVN: $Id: sfWidgetFormTextareaFCKEditor.class.php 2949 2009-11-07 20:35:23Z ldath $
 */
class sfWidgetFormTextareaFCKEditor extends sfWidgetFormTextarea
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * width:                     width of FCKEditor frame
   *  * height:                    height of FCKEditor frame
   *  * base_path:                 path to FCKEditor
   *  * CustomConfigurationsPath:  CustomConfigurationsPath for FCKEditor
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('width', '');
    $this->addOption('height', '');
    $this->addOption('BasePath', '');
    $this->addOption('CustomConfigurationsPath', '');
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
public function render($name, $value = null, $attributes = array(), $errors = array())
      {

      $path1=sfContext::getInstance()->getRequest()->getRelativeUrlRoot().'/'.sfConfig::get('sf_rich_text_fck_js_dir').'/';
      $path2=sfContext::getInstance()->getRequest()->getRelativeUrlRoot().'/js/fckeditor/fckconfig.js';
    $textarea = parent::render($name, $value, $attributes, $errors);

        $js = sprintf(<<<EOF
<script type="text/javascript">
   var oFCKeditor = new FCKeditor('%s','%s','%s');
   oFCKeditor.BasePath = '%s' ;
   oFCKeditor.Config['CustomConfigurationsPath'] = '%s';
   oFCKeditor.ReplaceTextarea();
</script>
EOF
        ,
          $name,
          $this->getOption('width')                     ? $this->getOption('width')                    : '100%',
          $this->getOption('height')                    ? $this->getOption('height')                   : '200',
          $this->getOption('BasePath')                  ? $this->getOption('BasePath')                 : $path1,
          $this->getOption('CustomConfigurationsPath')  ? $this->getOption('CustomConfigurationsPath') : $path2
        );

        return $textarea.$js;
      }





  public function old_render($name, $value = null, $attributes = array(), $errors = array())
  {
    $textarea = parent::render($name, $value, $attributes, $errors);

    $js = sprintf(<<<EOF
<script type="text/javascript">
window.onload = function()
{
  var oFCKeditor = new FCKeditor( '%s' , '%s', '%s');
  oFCKeditor.BasePath = "%s" ;
  oFCKeditor.Config["CustomConfigurationsPath"] = "%s";
  oFCKeditor.ReplaceTextarea() ;
}

</script>
EOF
    ,
      $name,
      $this->getOption('width')                     ? $this->getOption('width')                    : '100%',
      $this->getOption('height')                    ? $this->getOption('height')                   : '200',
      $this->getOption('BasePath')                  ? $this->getOption('BasePath')                 : '/fckeditor/',
      $this->getOption('CustomConfigurationsPath')  ? $this->getOption('CustomConfigurationsPath') : '/fckconfig.js'
    );

    return $textarea.$js;
  }
}
