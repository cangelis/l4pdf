<?php
namespace CanGelis\L4pdf;

class PDFException extends \Exception {

	public function __construct($message)
	{
		parent::__construct("PDF could not be created: " . json_encode($message));
	}

}