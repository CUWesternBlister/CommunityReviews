<?php
namespace ElementorPro\Modules\Posts\Widgets;

use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Module_Query;
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;
use ElementorPro\Modules\Posts\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Community_Reviews_Display extends Posts_Base {

	public function get_name() {
		return 'community_reviews_display';
	}

	public function get_title() {
		return esc_html__( 'Community Reviews Display', 'blister-community-reviews' );
	}

	protected function register_controls() {
		parent::register_controls();

		$this->register_query_section_controls();
		$this->register_pagination_section_controls();
	}

// ------------------ from posts -------------------
	public function get_keywords() {
		return [ 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' ];
	}

	public function on_import( $element ) {
		if ( isset( $element['settings']['posts_post_type'] ) && ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}

	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Classic( $this ) );
		$this->add_skin( new Skins\Skin_Cards( $this ) );
		$this->add_skin( new Skins\Skin_Full_Content( $this ) );
	}

	public function query_posts() {
		$query_args = [
			'posts_per_page' => $this->get_posts_per_page_value(),
			'paged' => $this->get_current_page(),
		];

		/** @var Module_Query $elementor_query */
		$elementor_query = Module_Query::instance();
		$this->query = $elementor_query->get_query( $this, $this->get_query_name(), $query_args, [] );
	}

	public function get_query_name() {
		return $this->get_name();
	}

	protected function get_posts_per_page_value() {
		return $this->get_current_skin()->get_instance_value( 'posts_per_page' );
	}

	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			[
				'name' => $this->get_name(),
				'presets' => [ 'full' ],
				'exclude' => [
					'posts_per_page', //use the one from Layout section
				],
			]
		);

		$this->end_controls_section();
	}
}


/*



// 	public function get_keywords() {
// 		return [ 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' ];
// 	}

// 	public function on_import( $element ) {
// 		if ( isset( $element['settings']['posts_post_type'] ) && ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
// 			$element['settings']['posts_post_type'] = 'post';
// 		}

// 		return $element;
// 	}

// 	protected function register_skins() {
// 		$this->add_skin( new Skins\Skin_Classic( $this ) );
// 		$this->add_skin( new Skins\Skin_Cards( $this ) );
// 		$this->add_skin( new Skins\Skin_Full_Content( $this ) );
// 	}

// 	public function register_controls() {
// 		parent::register_controls();

// 		$this->register_query_section_controls();
// 		$this->register_pagination_section_controls();

// 		$this->input_controlls();		
// 	}

// 	/*public function render() {
//         $settings = $this->get_settings_for_display();

//         // Render the parent class's content
//         parent::render();

//         // Add your own content here
//         $my_text_field = $settings['my_text_field'];
//         $my_input_field = $settings['my_input_field'];
//         $my_button_text = $settings['my_button']['text'];

//         ?>
//         <div>
//             <label for="<?php echo esc_attr( $this->get_field_id( 'my_input_field' ) ); ?>"><?php esc_html_e( 'My Input Field:', 'my-plugin' ); ?></label>
//             <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'my_input_field' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'my_input_field' ) ); ?>" value="<?php echo esc_attr( $my_input_field ); ?>">

//             <button type="button" id="<?php echo esc_attr( $this->get_field_id( 'my_button' ) ); ?>"><?php echo esc_html( $my_button_text ); ?></button>
//         </div>
//         <?php
//     }*/

// 	/**
// 	 * Get Query Name
// 	 *
// 	 * Returns the query control name used in the widget's main query.
// 	 *
// 	 * @since 3.8.0
// 	 *
// 	 * @return string
// 	 */
// 	public function get_query_name() {
// 		return $this->get_name();
// 	}

// 	public function query_posts() {
// 		$query_args = [
// 			'posts_per_page' => $this->get_posts_per_page_value(),
// 			'paged' => $this->get_current_page(),
// 		];

// 		/** @var Module_Query $elementor_query */
// 		$elementor_query = Module_Query::instance();
// 		$this->query = $elementor_query->get_query( $this, $this->get_query_name(), $query_args, [] );
// 	}

// 	/**
// 	 * Get Posts Per Page Value
// 	 *
// 	 * Returns the value of the Posts Per Page control of the widget. This method was created because in some cases,
// 	 * the control is registered in the widget, and in some cases, it is registered in a widget skin.
// 	 *
// 	 * @since 3.8.0
// 	 * @access protected
// 	 *
// 	 * @return mixed
// 	 */
// 	protected function get_posts_per_page_value() {
// 		return $this->get_current_skin()->get_instance_value( 'posts_per_page' );
// 	}

// 	protected function input_controlls(){
// 		$this->start_controls_section(
// 			'my_section',
// 			[
// 				'label' => __( 'My Section', 'my-plugin' ),
// 				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
// 			]
// 		);

// 		$this->add_control(
// 			'my_input_field',
// 			[
// 				'label' => __( 'My Input Field', 'my-plugin' ),
// 				'type' => \Elementor\Controls_Manager::TEXT,
// 				'placeholder' => __( 'Enter something here', 'my-plugin' ),
// 			]
// 		);
	
// 		$this->add_control(
// 			'my_button',
// 			[
// 				'label' => __( 'My Button', 'my-plugin' ),
// 				'type' => \Elementor\Controls_Manager::BUTTON,
// 				'text' => __( 'Click me', 'my-plugin' ),
// 				'separator' => 'before',
// 			]
// 		);
	
// 		$this->end_controls_section();
// 	}

// 	protected function register_query_section_controls() {
// 		$this->start_controls_section(
// 			'section_query',
// 			[
// 				'label' => esc_html__( 'Query', 'elementor-pro' ),
// 				'tab' => Controls_Manager::TAB_CONTENT,
// 			]
// 		);

// 		$this->add_group_control(
// 			Group_Control_Related::get_type(),
// 			[
// 				'name' => $this->get_name(),
// 				'presets' => [ 'full' ],
// 				'exclude' => [
// 					'posts_per_page', //use the one from Layout section
// 				],
// 			]
// 		);

// 		$this->end_controls_section();
// 	}



?>
