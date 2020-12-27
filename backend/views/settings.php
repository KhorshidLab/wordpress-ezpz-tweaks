<div id="tabs-1" class="wrap">
	<?php
	$cmb = new_cmb2_box(
		array(
			'id'         => W_TEXTDOMAIN . '_options',
			'hookup'     => false,
			'show_on'    => array( 'key' => 'options-page', 'value' => array( W_TEXTDOMAIN ) ),
			'show_names' => true,
		)
	);

	$cmb->add_field(
		array(
			'name'             => __( 'Admin Font', W_TEXTDOMAIN ),
			'desc'             => __( 'Change WordPress admin font', W_TEXTDOMAIN ),
			'id'               => 'admin-font',
			'type'             => 'select',
			'show_option_none' => false,
			'options'          => array(
				'wp-default'   => __( 'WordPress Default', W_TEXTDOMAIN ),
				'vazir'        => __( 'Vazir', W_TEXTDOMAIN ),
				'estedad'      => __( 'Estedad', W_TEXTDOMAIN ),
				'iranian'      => __( 'Iranian', W_TEXTDOMAIN ),
				'noto'         => __( 'Noto', W_TEXTDOMAIN ),
			),
		)
	);

	$cmb->add_field( array(
			'name'    => __( 'WordPress Login Logo', W_TEXTDOMAIN ),
			'desc'    => __( 'Upload an image or enter an URL.', W_TEXTDOMAIN ),
			'id'      => 'custom_logo',
			'type'    => 'file',
			'options' => array(
					'url' => true, // Hide the text input for the url
			),
			'text'    => array(
					'add_upload_file_text' => __( 'Add File', W_TEXTDOMAIN )
			),
			'query_args' => array(
				 'type' => array(
				     'image/jpeg',
				     'image/png',
				 ),
			),
			'preview_size' => 'large',
	) );

	cmb2_metabox_form( W_TEXTDOMAIN . '_options', W_TEXTDOMAIN . '-settings' );
	?>
</div>
