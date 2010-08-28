<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
sfApplicationConfiguration::getActive()->loadHelpers('Url');
/**
 * Description of sfWidgetFormInputSWFUpload
 *
 * @author joshi
 */
class sfWidgetGalleryUpload extends sfWidgetFormInput
{
	/**
	 * Instance counter
	 *
	 * @var integer
	 */
	protected static $INSTANCE_COUNT = 0;

	protected function iniSize2Bytes($ini_size)
	{
		if (preg_match('#^([0-9]+?)([gmk])$#i', $ini_size, $tokens)) {
			$unit=null; $size_val=null;
			isset($tokens[1])&&$size_val  = $tokens[1];
			isset($tokens[2])&&$unit      = $tokens[2];
			if ($size_val && $unit) {
				switch (strtolower($unit)) {
					case 'k':
						return $size_val * 1024 . 'B';
					case 'm':
						return $size_val * 1024 * 1024 . 'B';
					case 'g':
						return $size_val * 1024 * 1024 * 1024 . 'B';
				}
			}
		} else {
			return $ini_size . 'B';
		}
	}

	/**
	 * @param array $options     An array of options
	 * @param array $attributes  An array of default HTML attributes
	 *
	 * @see sfWidgetFormInput
	 */
	protected function configure($options = array(), $attributes = array())
	{
		parent::configure($options, $attributes);

		$this->addOption('custom_javascripts', array());

		$this->addOption('require_yui', false);

		// Backend Settings
		$this->addOption('flash_url',     public_path('/twCorePlugin/js/swfupload/Flash/swfupload.swf'));
		$this->addOption('upload_url',    url_for('twUpload/index'));
		$this->addOption('delete_url',    url_for('twUpload/delete'));
		$this->addOption('post_params',   '"symfony" : "'.session_id().'"');
		$this->addOption('debug',         'false');

		// File Upload Settings
		$this->addOption('file_types', '*.jpeg;*.jpg;*.png;*.gif; *.JPG; *.GIF');
		$this->addOption('file_types_description', 'Web images');

		$this->addOption('file_size_limit', ini_get('upload_max_filesize'));
		$this->addOption('file_upload_limit', 0);
		$this->addOption('file_queue_limit', 0);

		$this->addOption('css_path',          public_path('/twCorePlugin/css/swfupload.css'));
		$this->addOption('js_path',           public_path('/twCorePlugin/js/swfupload/swfupload.js'));
		$this->addOption('handler_path',      public_path('/twCorePlugin/js/swfupload_handlers.js'));
		$this->addOption('filepogress_path',  public_path('/twCorePlugin/js/swfupload_fileprogress.js'));
		$this->addOption('json_path',         public_path('/twCorePlugin/js/jquery.json-2.2.min.js'));
		$this->addOption('plugins_dir',       public_path('/twCorePlugin/js/swfupload/plugins'));
		$this->addOption('button_image_url',  null);

		$this->addOption('minimum_flash_version', '9.0.28');

//		$this->setOption('is_hidden', true);
		$this->setOption('type', 'hidden');
	}

	/**
	 * Gets the stylesheet paths associated with the widget.
	 *
	 * The array keys are files and values are the media names (separated by a ,):
	 *
	 *   array('/path/to/file.css' => 'all', '/another/file.css' => 'screen,print')
	 *
	 * @return array An array of stylesheet paths
	 */
	public function getStylesheets()
	{
		return array(
			$this->getOption('css_path') => 'all'
		);
	}

	/**
	 * Gets the JavaScript paths associated with the widget.
	 *
	 * @return array An array of JavaScript paths
	 */
	public function getJavaScripts()
	{
		$js = array(
			$this->getOption('js_path'),
			$this->getOption('plugins_dir') . '/swfupload.swfobject.js',
			$this->getOption('plugins_dir') . '/swfupload.queue.js',
//			$this->getOption('plugins_dir') . '/swfupload.cookies.js',
//			$this->getOption('plugins_dir') . '/swfupload.speed.js',
			$this->getOption('filepogress_path'),
			$this->getOption('handler_path'),
			$this->getOption('json_path'),
		);
//		if ($this->getOption('require_yui')) {
//			$js[] = "http://yui.yahooapis.com/combo?2.7.0/build/yahoo-dom-event/yahoo-dom-event.js&2.7.0/build/animation/animation-min.js";
//		}
		return array_merge($js, $this->getOption('custom_javascripts'));
	}

	public function render($name, $value = null, $attributes = array(), $errors = array())
	{
		self::$INSTANCE_COUNT++;

		$token = md5(sfConfig::get('sf_csrf_secret').session_id().$name);

		$extensions = is_array($this->getOption('swf_upload_file_types')) ? implode(';', $this->getOption('swf_upload_file_types')): $this->getOption('swf_upload_file_types');

		$output = '';
//		$output = parent::render($name, $value, $attributes, $errors);

		$widget_id  = $this->getAttribute('id') ? $this->getAttribute('id') : $this->generateId($name);
		$button_id  = $widget_id . "_swfupload_target";

		$swfupload_button_image_url = $this->getOption('button_image_url') === null ? '' : public_path($this->getOption('button_image_url'));

		$max_size = $this->iniSize2Bytes($this->getOption('file_size_limit'));

		$values = explode(',', $value);

		$gallery = '';
		if (!empty($_SESSION[$name])) {
			foreach($_SESSION[$name] as $gitem) {
				$gallery .= '<li id="li'.$gitem['code'].'" class="added"><div id="fileToUploadArea' . $gitem['code'] . 'Image" class="info" style="">';
				$gallery .= '<img height="64" width="64" src="'.$gitem['url'].'" alt="" />';
				$gallery .= '<span class="delete" onclick="deleteFile(\'' . $this->getOption('delete_url').'?token='.$token.'&field='. $name . '\', \'' . $gitem['code'] . '\');" title="Usuń">Usuń</span>';
				$gallery .= '<div class="upload-image-title">Plik: ' . $gitem['file_name'] . '<br />Rozmiar: ' . $gitem['file_size'] . '</div>';
				$gallery .= '</li>';
				if (!in_array($gitem['code'], $values)) {
					array_push($values, $gitem['code']);
				}
			}
		}
		foreach($values as $k => $v) {
			if ($v == '') {
				unset($values[$k]);
			}
		}
		$value = implode(',', $values);
		if ($value != '') {
			$value .= ',';
		}

		$input = $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));

		$output .= <<<EOF
<script type="text/javascript">
//<![CDATA[

  var swfu;

  SWFUpload.onload = function()
  {

    var settings = {
      // Backend Settings
      flash_url : "{$this->getOption('flash_url')}",
      upload_url: "{$this->getOption('upload_url')}?token={$token}&field={$name}",	// Relative to the SWF file
      post_params:
      {
        {$this->getOption('post_params')}
      },
      debug: {$this->getOption('debug')},

      // File Upload Settings
      file_size_limit : "100 MB",
      file_types : "{$extensions}",
      file_types_description : "{$this->getOption('file_types_description')}",
      file_upload_limit : 50,
      file_queue_limit : 50,

      // The event handler functions are defined in handlers.js
      swfupload_loaded_handler : TwAdminSwfHandlers.swfUploadLoaded,
      swfupload_pre_load_handler : TwAdminSwfHandlers.swfUploadPreLoad,
      swfupload_load_failed_handler : TwAdminSwfHandlers.swfUploadLoadFailed,

      file_dialog_start_handler : TwAdminSwfHandlers.fileDialogStart,
      file_queued_handler : TwAdminSwfHandlers.fileQueued,
      file_queue_error_handler : TwAdminSwfHandlers.fileQueueError,
      file_dialog_complete_handler : TwAdminSwfHandlers.fileDialogComplete,

      upload_start_handler : TwAdminSwfHandlers.uploadStart,
      upload_progress_handler : TwAdminSwfHandlers.uploadProgress,
      upload_error_handler : TwAdminSwfHandlers.uploadError,
      upload_success_handler : TwAdminSwfHandlers.uploadSuccess,
      upload_complete_handler : TwAdminSwfHandlers.uploadComplete,

      queue_complete_handler : TwAdminSwfHandlers.queueComplete,	// Queue plugin event

      debug_handler : TwAdminSwfHandlers.debug,

      // SWFObject settings
      minimum_flash_version : "9.0.28",

      // Button
      button_image_url: "/twCorePlugin/images/dodaj.png",	// Relative to the Flash file
      button_width: "100",
      button_height: "27",
      button_placeholder_id: "{$button_id}",
      button_text: '',
      button_text_style: ".buttonAddPhoto { cursor: pointer; font-size: 18px; color: #007DF2;}",
      button_text_left_padding: 3,
      button_text_top_padding: 3,
      button_cursor : SWFUpload.CURSOR.HAND,
      button_cursor : SWFUpload.CURSOR.HAND, button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,

      moving_average_history_size: 40,

      custom_settings : {
        progressTarget : "fsUploadProgress",
        deleteUrl :  "{$this->getOption('delete_url')}?token={$token}&field={$name}",
        cancelButtonId : "btnCancel"
      }

    };

    swfu = new SWFUpload(settings);
  }
//]]>
</script>
<div id="uploaded-list">
  <ul id="fileToUploadUl">{$gallery}</ul>
  <div class="fieldset flash" id="fsUploadProgress"></div>
  <div id="divStatus"></div>
  <div id="upload-button"><input id="btnCancel" type="button" value="Przerwij ładowanie" class="cancel-button" /><span id="{$button_id}"></span></div>
  <div id="divLoadingContent"><p class="loading">Trwa ładowanie aplikacji. Proszę czekać...</p></div>
  <div id="divLongLoading" style="display: none;">Wystąpił błąd. Spróbuj ponownie</div>
  <div id="divAlternateContent" style="display: none;">Musisz zainstalować nowszą wersję wtyczki Flash.<br />Wejdź na stronę <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" target="_blank">Adobe</a>.</div>
  {$input}
</div>
EOF;

		return $output;
	}
}