<?php
/**
 * Wzorowany na walidatorze sfValidatorFile
 */
class twCoreBaseUploader
{
	protected $upload_name = "Filedata";

	protected $max_file_size_in_bytes = 2147483647;

	protected $valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';

	protected $max_filename_length = 260;

	protected $uploadErrors = array(
			0 => "There is no error, the file uploaded with success",
			1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
			2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
			3 => "The uploaded file was only partially uploaded",
			4 => "No file was uploaded",
			6 => "Missing a temporary folder"
		);

	protected $resource = false;

	protected $file_size = false;

	protected $file_name = false;

	protected $path_info = false;

	protected $mime_type = false;

	public function __construct() {
		$this->mime_type_guessers = array(
			array($this, 'guessFromFileinfo'),
			array($this, 'guessFromMimeContentType'),
			array($this, 'guessFromFileBinary'),
		);
	}

	public function checkMaxPostSize($response) {
		// Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
		$POST_MAX_SIZE = ini_get('post_max_size');
		$unit = strtoupper(substr($POST_MAX_SIZE, -1));
		$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

		if (isset($_SERVER['CONTENT_LENGTH']) and (int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
			$response->setStatusCode(500, 'POST exceeded maximum allowed size.');
			return sfView::HEADER_ONLY;
		}
		return true;
	}

	public function validateAll() {
		$this->validateUpload();
		$this->validateSize();
		$this->checkFileName();
		$this->checkMimeType();
	}

	public function validateUpload() {
		if (!isset($_FILES[$this->upload_name])) {
			throw new Exception("No upload found in \$_FILES for " . $this->upload_name);
		} else if (isset($_FILES[$this->upload_name]["error"]) && $_FILES[$this->upload_name]["error"] != 0) {
			throw new Exception($this->uploadErrors[$_FILES[$this->upload_name]["error"]]);
		} else if (!isset($_FILES[$this->upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$this->upload_name]["tmp_name"])) {
			throw new Exception("Upload failed is_uploaded_file test.");
		} else if (!isset($_FILES[$this->upload_name]['name'])) {
			throw new Exception("File has no name.");
		}
		$this->resource = $_FILES[$this->upload_name]["tmp_name"];
		$this->path_info = pathinfo($_FILES[$this->upload_name]['name']);
		return true;
	}

	public function validateSize() {
		$file_size = @filesize($_FILES[$this->upload_name]["tmp_name"]);
		if (!$file_size || $file_size > $this->max_file_size_in_bytes) {
			throw new Exception("File exceeds the maximum allowed size");
		}

		if ($file_size <= 0) {
			throw new Exception("File size outside allowed lower bound");
		}
		$this->file_size = $file_size;
		return true;
	}

	public function checkFileName() {
		$file_name = preg_replace('/[^'.$this->valid_chars_regex.']|\.+$/i', "", basename($_FILES[$this->upload_name]['name']));
		if (strlen($file_name) == 0 || strlen($file_name) > $this->max_filename_length) {
			throw new Exception("Invalid file name");
		}
		$this->file_name = $file_name;
	}

	public function checkMimeType($type = null) {
		if ($this->resource === false) {
			throw new Exception('Upload nie został jeszcze zwalidowany');
		}
		if (!isset($type)) {
			$type = 'application/octet-stream';
		}
		$this->mime_type = $this->analyzeMimeType((string) $this->resource, (string) $type);
	}

	public function getFileName() {
		if ($this->file_name === false) {
			throw new Exception('Upload nie został jeszcze zwalidowany');
		}
		return $this->file_name;
	}

	public function getPathInfo() {
		if ($this->path_info === false) {
			throw new Exception('Upload nie został jeszcze zwalidowany');
		}
		return $this->path_info;
	}

	public function getFileSize() {
		if ($this->file_size === false) {
			throw new Exception('Wielkość pliku nie został jeszcze zwalidowana');
		}
		return $this->file_size;
	}

	public function getMimeType() {
		if ($this->mime_type === false) {
			throw new Exception('Typ mime pliku nie został jeszcze określony');
		}
		return $this->mime_type;
	}

	public function getUploadDataArray(sfWebRequest $request) {
		try {
			$token = $request->getParameter('token');
			$field = $request->getParameter('field');
			if (empty($token) or empty($field)) {
				throw new Exception('Brak tokena');
			}
			$value = md5(sfConfig::get('sf_csrf_secret').session_id().$field);
			if ($value != $token) {
				throw new Exception('Błędny token');
			}

			$code = $this->getFileCode();
			$temp_file = tempnam(sys_get_temp_dir(), $this->getFileCode());
			if (!move_uploaded_file($_FILES[$this->upload_name]["tmp_name"], $temp_file)) {
				throw new Exception('Nie udało się pobrać plik');
			}

			$is_image = @getimagesize($temp_file);
			if (!$is_image) {
				$res = array('code' => $code, 'file_code' => $temp_file, 'field' => $field, 'file_name' => $this->getFileName(), 'file_size' => $this->getFileSize(), 'mime_type' => $this->getMimeType(), 'url' => '/twCorePlugin/images/mimetypes/unknown.png');
				$_SESSION[$_GET['field']][$code] = $res;
				return json_encode($res);
			}

			$thumbnail = new sfThumbnail(64, 64);
			$thumbnail->loadFile($temp_file);
			$buff = $thumbnail->toString('image/png');

			$res = array('code' => $code, 'file_code' => $temp_file, 'field' => $field, 'file_name' => $this->getFileName(), 'file_size' => $this->getFileSize(), 'mime_type' => $this->getMimeType(), 'url' => 'data:image/png;base64,'.base64_encode($buff));
			$_SESSION[$field][$code] = $res;
			return json_encode($res);
		} catch (Exception $e) {
			return json_encode(array('error' =>  $e->getMessage()));
		}
	}

	public function deleteUploadedData(sfWebRequest $request) {
		try {
			$token = $request->getParameter('token');
			$field = $request->getParameter('field');
			if (empty($token) or empty($field)) {
				throw new Exception('Brak tokena');
			}
			$value = md5(sfConfig::get('sf_csrf_secret').session_id().$field);
			if ($value != $token) {
				throw new Exception('Błędny token');
			}
			$err = 0;
			$msg = 'OK';

			$object = $request->getParameter('object');
			if (!empty($object) and !empty($_SESSION[$field][$object])) {
				@unlink($_SESSION[$field][$object]['file_code']);
				$_SESSION[$field.'_deleted'][$object] = $_SESSION[$field][$object];
				unset($_SESSION[$field][$object]);
			}
			return json_encode(array('msg' => 'OK', 'field' => $field));
		} catch (Exception $e) {
			return json_encode(array('error' =>  $e->getMessage()));
		}
	}

	protected function getFileCode() {
		return md5($this->file_name . '.' . $this->file_size . '.' . microtime());
	}

	/**
	 * Returns the mime type of a file.
	 *
	 * This methods call each mime_type_guessers option callables to
	 * guess the mime type.
	 *
	 * @param  string $file      The absolute path of a file
	 * @param  string $fallback  The default mime type to return if not guessable
	 *
	 * @return string The mime type of the file (fallback is returned if not guessable)
	 */
	protected function analyzeMimeType($file, $fallback)
	{
		foreach ($this->mime_type_guessers as $method) {
			$type = call_user_func($method, $file);

			if (!is_null($type) && $type !== false) {
				return $type;
			}
		}

		return $fallback;
	}

	/**
	 * Guess the file mime type with PECL Fileinfo extension
	 *
	 * @param  string $file  The absolute path of a file
	 *
	 * @return string The mime type of the file (null if not guessable)
	 */
	protected function guessFromFileinfo($file)
	{
		if (!function_exists('finfo_open') || !is_readable($file)) {
			return null;
		}

		if (!$finfo = new finfo(FILEINFO_MIME)) {
			return null;
		}

		$type = $finfo->file($file);

		return $type;
	}

	/**
	 * Guess the file mime type with mime_content_type function (deprecated)
	 *
	 * @param  string $file  The absolute path of a file
	 *
	 * @return string The mime type of the file (null if not guessable)
	 */
	protected function guessFromMimeContentType($file)
	{
		if (!function_exists('mime_content_type') || !is_readable($file)) {
			return null;
		}

		return mime_content_type($file);
	}

	/**
	 * Guess the file mime type with the file binary (only available on *nix)
	 *
	 * @param  string $file  The absolute path of a file
	 *
	 * @return string The mime type of the file (null if not guessable)
	 */
	protected function guessFromFileBinary($file)
	{
		ob_start();
		passthru(sprintf('file -bi %s 2>/dev/null', escapeshellarg($file)), $return);
		if ($return > 0) {
			ob_end_clean();

			return null;
		}
		$type = trim(ob_get_clean());

		if (!preg_match('#^([a-z0-9\-]+/[a-z0-9\-]+)#i', $type, $match)) {
			// it's not a type, but an error message
			return null;
		}

		return $match[1];
	}

}
?>