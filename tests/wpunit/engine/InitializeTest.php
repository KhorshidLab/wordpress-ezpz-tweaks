<?php

namespace WP2X\Tests\WPUnit;

use Codeception\TestCase\WPTestCase;

class InitializeTest extends WPTestCase
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

		wp_set_current_user(0);
		wp_logout();
		wp_safe_redirect(wp_login_url());
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be front
	 */
	public function it_should_be_front()
	{
		$classes = array();
		$classes[] = 'WP2X\Internals\PostTypes';
		$classes[] = 'WP2X\Internals\Shortcode';
		$classes[] = 'WP2X\Internals\Transient';
		$classes[] = 'WP2X\Integrations\CMB';
		$classes[] = 'WP2X\Integrations\Cron';
		$classes[] = 'WP2X\Integrations\FakePage';
		$classes[] = 'WP2X\Integrations\Template';
		$classes[] = 'WP2X\Integrations\Widgets';
		$classes[] = 'WP2X\Ajax\Ajax';
		$classes[] = 'WP2X\Ajax\Ajax_Admin';
		$classes[] = 'WP2X\Frontend\Enqueue';
		$classes[] = 'WP2X\Frontend\extras\Body_Class';

		foreach ($classes as $class) {
			$this->assertTrue(class_exists($class));
		}
	}

}
