<?php
class AjaxObject {
	private $content;
	private $status_code;
	private $reload;

	function __construct($content = array(), $status_code = 200, $reload = false) {
		$this->content = $content;
		$this->status_code = $status_code;
		$this->reload = $reload;
	}

	/**
	 * @return mixed
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param mixed $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * @return mixed
	 */
	public function getStatusCode() {
		return $this->status_code;
	}

	/**
	 * @param mixed $status_code
	 */
	public function setStatusCode($status_code) {
		$this->status_code = $status_code;
	}

	/**
	 * @return mixed
	 */
	public function getReload() {
		return $this->reload;
	}

	/**
	 * @param mixed $reload
	 */
	public function setReload($reload) {
		$this->reload = $reload;
	}

	public function encode() {
		header('Content-Type: application/json');
		echo json_encode(get_object_vars($this));
		exit();
	}

}