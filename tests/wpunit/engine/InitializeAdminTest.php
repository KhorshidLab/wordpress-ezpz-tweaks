<?php

namespace WP2X\Tests\WPUnit;

use Codeception\TestCase\WPTestCase;

class InitializeAdminTest extends WPTestCase
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

		$user_id = $this->factory->user->create(array('role' => 'administrator'));
		wp_set_current_user($user_id);
		set_current_screen('edit.php');
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be admin
	 */
	public function it_should_be_admin()
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
		$classes[] = 'WP2X\Backend\ActDeact';
		$classes[] = 'WP2X\Backend\Enqueue';
		$classes[] = 'WP2X\Backend\ImpExp';
		$classes[] = 'WP2X\Backend\Notices';
		$classes[] = 'WP2X\Backend\Pointers';
		$classes[] = 'WP2X\Backend\Settings_Page';

		$this->assertTrue(is_admin());
		foreach ($classes as $class) {
			$this->assertTrue(class_exists($class));
		}
	}

}
