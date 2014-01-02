<?php namespace CanGelis\L4pdf;

use Config;
use Response;
use View;

class PDF {

	/**
	 * Random name that will be the name of the temporary files
	 *
	 * @var string
	 */
	protected $fileName;

	/**
	 * Folder in which temporary files will be saved
	 *
	 * @var string
	 */
	protected $folder;

	/**
	 * PDF's binary content
	 *
	 * @var binary
	 */
	protected $content = null;

	/**
	 * HTML content that will be converted to PDF
	 *
	 * @var string
	 */
	protected $htmlContent = null;

	/**
	 * Params to be executed by wkhtmltopdf
	 *
	 * @var array
	 */
	protected $params = array();

	/**
	 * Input URL that will be generated to PDF Doc.
	 *
	 * @var string
	 */
	protected $url = null;

	/**
	 * Available command parameters for wkhtmltopdf
	 *
	 * @var array
	 */
	protected $availableParams = array(
		'grayscale', 'orientation', 'page-size',
		'lowquality', 'dpi', 'image-dpi', 'image-quality',
		'margin-bottom', 'margin-left', 'margin-right', 'margin-top',
		'page-height', 'page-width', 'no-background', 'encoding', 'enable-forms',
		'no-images', 'disable-internal-links', 'disable-javascript',
		'password', 'username', 'footer-center', 'footer-font-name',
		'footer-font-size', 'footer-html', 'footer-left', 'footer-line',
		'footer-right', 'footer-spacing', 'header-center', 'header-font-name',
		'header-font-size', 'header-html', 'header-left', 'header-line', 'header-right',
		'header-spacing', 'print-media-type', 'zoom'
	);

	/**
	 * wkhtmltopdf executable path
	 *
	 * @var string
	 */
	protected $cmd;

	/**
	 * Initialize temporary file names and folders
	 */
	public function __construct($cmd ,$tmpFolder)
	{
		$this->cmd = $cmd;

		$this->fileName = uniqid(rand(0, 99999));

		$this->folder = $tmpFolder;
	}

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
	 * Loads the HTML Content from plain text
	 *
	 * @param string $html
	 *
	 * @return $this
	 */
	public function loadHTML($html)
	{
		$this->htmlContent = $html;

		return $this;
	}

	/**
	 * Loads the input source as a URL
	 *
	 * @param string $url
	 *
	 * @return $this
	 */
	public function loadUrl($url)
	{
		$this->url = $url;

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
	 * Gets the parameters defined by user
	 *
	 * @return string
	 */
	protected function getParams()
	{
		$result = "";
		foreach ($this->params as $key => $value)
		{
			if (is_numeric($key))
			{
				$result .= '--' . $value;
			} else
			{
				$result .= '--' . $key . ' ' . '"' . $value . '"';
			}
			$result .= ' ';
		}
		return $result;
	}

	/**
	 * Adds a wkhtmltopdf parameter
	 *
	 * @param string $key
	 * @param string $value
	 */
	protected function addParam($key, $value = null)
	{
		if (is_null($value))
		{
			$this->params[] = $key;
		} else
		{
			$this->params[$key] = $value;
		}

	}

	/**
	 * Converts a method name to a wkhtmltopdf parameter name
	 *
	 * @param string $method
	 *
	 * @return string
	 */
	protected function methodToParam($method)
	{
		return snake_case($method, "-");
	}

	/**
	 * Gets the Input source which can be an HTML file or a URL
	 *
	 * @return string
	 */
	protected function getInputSource()
	{
		if (!is_null($this->url))
		{
			return $this->url;
		}

		file_put_contents($this->getHTMLPath(), $this->htmlContent);

		return $this->getHTMLPath();
	}

	/**
	 * Generates the PDF and save the PDF content for the further use
	 *
	 * @throws PDFException
	 */
	protected function generatePDF()
	{
		$return_var = $this->executeCommand($output);

		if ($return_var == 0)
		{
			$this->content = file_get_contents($this->getPDFPath());
		} else
		{
			throw new PDFException($output);
		}

		$this->removeTmpFiles();

	}

	/**
	 * Execute wkhtmltopdf command
	 *
	 * @param array &$output
	 *
	 * @return integer
	 */
	protected function executeCommand(&$output)
	{
		exec($this->cmd . ' ' . $this->getParams() . ' ' . $this->getInputSource() . ' ' . $this->getPDFPath(), $output, $return_var);

		return $return_var;
	}

	/**
	 * Creates a response object with proper Content-type for PDF Doc.
	 *
	 * @return \Illuminate\Http\Response
	 */
	protected function createResponse()
	{
		$this->generatePDF();

		return Response::make($this->content, 200)->header('Content-type', 'application/pdf');
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

	/**
	 * Remove temporary HTML and PDF files
	 */
	protected function removeTmpFiles()
	{
		@unlink($this->getHTMLPath());
		@unlink($this->getPDFPath());
	}

	/**
	 * Gets the temporary saved PDF file path
	 *
	 * @return string
	 */
	protected function getPDFPath()
	{
		return $this->folder . '/' . $this->fileName . '.pdf';
	}

	/**
	 * Gets the temporary save HTML file path
	 *
	 * @return string
	 */
	protected function getHTMLPath()
	{
		return $this->folder . '/' . $this->fileName . '.html';
	}

	/**
	 * Handle method<->parameter conventions
	 *
	 * @param string $method
	 * @param string $args
	 *
	 * @return $this
	 * @throws PDFException
	 */
	public function __call($method, $args)
	{
		$param = $this->methodToParam($method);
		if (in_array($param, $this->availableParams))
		{
			if (isset($args[0]))
			{
				$this->addParam($param, $args[0]);
			} else
			{
				$this->addParam($param);
			}
			return $this;
		} else
		{
			throw new PDFException('Undefined method: ' . $method);
		}
	}

}