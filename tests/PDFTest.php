<?php

class PDFTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Cangelis\L4pdf\PDF
	 */
	protected $pdf;

	/**
	 * Call private and protected methods
	 *
	 * @param string $methodName
	 * @param array  $args
	 *
	 * @return mixed
	 */
	protected function call($methodName, $args = array())
	{
		$method = new \ReflectionMethod($this->pdf, $methodName);
		$method->setAccessible(true);
		return $method->invokeArgs($this->pdf, $args);
	}

	public function setUp()
	{
		$this->pdf = new \CanGelis\L4pdf\PDF();
	}

	public function testGetParamsReturnsParameterNamesCorrectly()
	{
		$this->call('addParam',array('foo'));
		$this->call('addParam',array('bar', 'baz'));
		$this->call('addParam',array('bazzer'));
		$this->assertEquals('--foo --bar "baz" --bazzer ', $this->call('getParams'));
	}

	public function testMethodNameConvertedToSnakeCaseParameter()
	{
		$this->assertEquals('foo-bar', $this->call('methodToParam', array('fooBar')));
		$this->assertEquals('foo', $this->call('methodToParam', array('foo')));
	}

	public function testInputSourceIsUrlWhenLoadUrlIsCalled()
	{
		$this->pdf->loadUrl('http://www.foo.bar');
		$this->assertEquals('http://www.foo.bar', $this->call('getInputSource'));
	}

	public function testInputSourceReturnsHTMLFileWhenLoadHtmlIsCalled()
	{
		$this->pdf->loadHTML('<b>foo bar</b>');
		$this->assertContains('.html', $this->call('getInputSource'));
	}

}