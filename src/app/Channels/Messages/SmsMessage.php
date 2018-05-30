<?php
namespace App\Channels\Messages;

/**
* 短信
*/
class SmsMessage
{
	public $view;

	public $data;
	
	function __construct($view='', $data=[])
	{
		$this->view($view);
		$this->data($data);
	}

	public function view($view) {
		$this->view = $view;

		return $this;
	}

	public function data($data) {
		$this->data = $data;

		return $this;
	}

	public function __get($name)
	{
		switch (strtolower($name)) {
			case 'content':
				return $this->getContent();
		}
	}

	public function getContent()
	{
		return view($this->view, $this->data)->render();
	}
}