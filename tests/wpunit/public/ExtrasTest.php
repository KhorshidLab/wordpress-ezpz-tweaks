<?php

use Codeception\TestCase\WPTestCase;
use Plugin_name\Frontend\Extras\Body_Class;
use tad\FunctionMocker\FunctionMocker;

class ExtrasTest extends WPTestCase
{
	/**
	 * @var string
	 */
	protected $root_dir;

	public function setUp()
	{
		parent::setUp();
		// your set up methods here
		$this->root_dir = dirname(dirname(dirname(__FILE__)));

		// https://github.com/lucatume/function-mocker
		// FunctionMocker::setUp();
	}

	public function tearDown()
	{
		parent::tearDown();
		// FunctionMocker::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_add_body_class()
	{
		// FunctionMocker::replace('get_option', 'another_function');
		$sut = $this->make_instance();
		$list = array('test', 'another-class');
		$this->assertEquals(array_merge($list, array(W_TEXTDOMAIN)), $sut->add_w_class($list));
	}

	private function make_instance()
	{
		return new Body_Class();
	}
}
