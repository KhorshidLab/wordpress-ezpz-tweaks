<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WP2X
 * @author    Khorshid <info@khorshidlab.com>
 * @copyright 2020 Khorshid
 * @license   GPL 2.0+
 * @link      https://khorshidlab.com
 */
?>

<div class="wrap">

	<h2 class="setting-title"><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<div class="right-column-settings-page metabox-holder">
		<div class="postbox">
			<h3 class="hndle"><span><?php esc_html_e( 'About', W_TEXTDOMAIN ); ?></span></h3>
			<div class="inside">
				<p><a href="https://khorshidlab.com"><img src="<?php echo W_PLUGIN_ROOT_URL ?>assets/img/khorshid-logo.svg" width="120" ></a></p>
				<br>
				<h4>Our History</h4>
				<p>
					Two of us began our adventure about three years ago. We tried on and off working on our dream besides being an employee somewhere else, now we are a team that continues to grow. <br>
					We use all related experiences and new technologies to ensure the best possible solutions for our clients.
				</p>

				<h4>Our Team</h4>
				<p>
					We are a small but exceedingly effective and experienced team. We believe that success is a team effort, and we work shoulder to shoulder to enable all that is possible. <br>
					Our team brings together different kinds of skills, backgrounds, interests, and education which, enables us to overcome the challenges we face to create the best user experiences. <br>
					We are a team of individuals that want to do great work and grow the business alongside their careers.
				</p>

				<h4>Our Values</h4>
				<p>
					<ul>
						<li>Family</li>
						<li>Independence</li>
						<li>Creativity</li>
						<li>Innovation</li>
						<li>Equality</li>
						<li>Purpose</li>
						<li>Enjoyment</li>
					</ul>
				</p>
			</div>
		</div>
	</div>

</div>
