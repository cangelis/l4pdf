<?php namespace CanGelis\L4pdf;

use Response;
use View;
use File;

class PDF extends \CanGelis\PDF\PDF {

	/**
	 * Loads the Input Content from the view
	 *
	 * @param string $viewName
	 * @param array  $data
	 * @param array  $mergeData
	 *
	 * @return $this
	 */
	public function loadView($viewName, $data = array(), $mergeData = array())
	{
		$this->htmlContent = View::make($viewName, $data, $mergeData);

		return $this;
	}

	/**
	 * Get the PDF Content as an attachment
	 *
	 * @param string $as
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function download($as = null)
	{
		return $this->createResponse()->header('Content-Disposition', 'attachment; ' . $this->getAs($as));
	}

	/**
	 * Display the PDF Document in the browser window
	 *
	 * @param string $as
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function stream($as = null)
	{
		return $this->createResponse()->header('Content-Disposition', 'inline; ' . $this->getAs($as));
	}

	/**
	 * Creates a response object with proper Content-type for PDF Doc.
	 *
	 * @return \Illuminate\Http\Response
	 */
	protected function createResponse()
	{
		return Response::make($this->generatePDF(), 200)->header('Content-type', 'application/pdf');
	}

	/**
	 * Gets the attachment name for the Response
	 *
	 * @param string $as
	 *
	 * @return string
	 */
	protected function getAs($as = null)
	{
		if (!is_null($as))
		{
			return 'filename="' . $as . '"';
		}

		return "";
	}

}