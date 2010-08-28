<?php

/**
 * twUpload actions.
 *
 * @package    zoolandia
 * @subpackage twUpload
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2949 2009-11-07 20:35:23Z ldath $
 */
class twUploadActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$response = $this->getResponse();
		$uploader = new twCoreBaseUploader();
		$res = $uploader->checkMaxPostSize($response);
		if ($res !== true) {
			return $res;
		}
		$response->setContentType('text/plain');

		try {
			$uploader->validateAll();
			$file_data = $uploader->getUploadDataArray($request);
			return $this->renderText($file_data);
		} catch (Exception $e) {
			return $this->renderText($e->getMessage());
		}
	}

	public function executeDelete(sfWebRequest $request) {
		$response = $this->getResponse();
		$response->setContentType('text/plain');
		$uploader = new twCoreBaseUploader();
		$out = $uploader->deleteUploadedData($request);

		return $this->renderText($out);
	}
}
