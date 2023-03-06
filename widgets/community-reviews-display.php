<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Community_Reviews_Display extends ElementorPro\Modules\Posts\Widgets\Posts {
    public function get_name() {
		return 'community_reviews_display';
	}

	public function get_title() {
		return esc_html__( 'Community Reviews Display', 'blister-community-reviews' );
	}
}
?>