<?php
namespace ElementorPro\Modules\Posts\Widgets;

use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Module_Query;
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;
use ElementorPro\Modules\Posts\Skins;

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly
}

class Community_Reviews_Display extends \Elementor\Widget_Base {
	public function get_name() {
        return 'community-reviews-display';
    }

    public function get_title() {
        return __( 'Community Reviews', 'community-reviews' );
    }

	public function get_categories() {
		return [ 'community_reviews', 'basic' ];
	}

    public function get_icon() {
		return 'eicon-post-list';
	}

    protected function _register_controls() {

    }

    protected function render() {
        $settings = $this->get_settings();

		?>

		<div class="community-reviews-display">
			<div class="community-reviews-display-filter">

				<strong>Product Filters</strong>

				<div class="community-reviews-display-sport-controls">
					<div class="community-reviews-display-title">Sport</div>
					<select id="community-reviews-display-sport">
						<option value="">--No Sport Filter--</option>
						<?php
							global $wpdb;

							$categories_table_name = $wpdb->prefix . "bcr_categories";

							$sql = $wpdb->prepare("SELECT categoryName FROM $categories_table_name WHERE (parentID=0);");
					
							$results  = $wpdb->get_results($sql);

							foreach ($results as $id => $category_obj) {
								$category_name = $category_obj->categoryName;

								echo '<option value="' . esc_html($category_name) . '">' . esc_html($category_name) . '</option>';
							}
						?>
					</select>
				</div>

				<div class="community-reviews-display-category-controls">
					<div class="community-reviews-display-title">Category</div>
					<select id="community-reviews-display-category">
						<option value="">--No Category Filter--</option>
						<?php
							global $wpdb;

							$categories_table_name = $wpdb->prefix . "bcr_categories";

							$sql = $wpdb->prepare("SELECT categoryName FROM $categories_table_name WHERE (parentID!=0);");
					
							$results  = $wpdb->get_results($sql);

							foreach ($results as $id => $category_obj) {
								$category_name = $category_obj->categoryName;

								echo '<option value="' . esc_html($category_name) . '">' . esc_html($category_name) . '</option>';
							}
						?>
					</select>
				</div>

				<div class="community-reviews-display-brand-controls">
					<div class="community-reviews-display-title">Brand</div>
					<select id="community-reviews-display-brand">
						<option value="">--No Brand Filter--</option>
						<?php
							global $wpdb;

							$brands_table_name = $wpdb->prefix . "bcr_brands";

							$sql = $wpdb->prepare("SELECT brandName FROM $brands_table_name;");
					
							$results  = $wpdb->get_results($sql);

							foreach ($results as $id => $brand_obj) {
								$brand_name = $brand_obj->brandName;

								echo '<option value="' . esc_html($brand_name) . '">' . esc_html($brand_name) . '</option>';
							}
						?>
					</select>
				</div>

				<div class="community-reviews-display-product-controls">
					<div class="community-reviews-display-title">Product</div>
					<select id="community-reviews-display-product">
						<option value="">--No Product Filter--</option>
						<?php
							global $wpdb;

							$products_table_name = $wpdb->prefix . "bcr_products";

							$sql = $wpdb->prepare("SELECT productName FROM $products_table_name;");
					
							$results  = $wpdb->get_results($sql);

							foreach ($results as $id => $product_obj) {
								$product_name = $product_obj->productName;

								echo '<option value="' . esc_html($product_name) . '">' . esc_html($product_name) . '</option>';
							}
						?>
					</select>
				</div>

				<div class="community-reviews-display-length-controls">
					<div class="community-reviews-display-title">Length</div>
					<div class="community-reviews-display-slider">
						<input id="community-reviews-display-slider-min-length" type="range" value="50" min="50" max="250"/>
						<input id="community-reviews-display-slider-max-length" type="range" value="250" min="50" max="250"/>
					</div>
					
					<div class="community-reviews-number-boxes">
						<div class="community-reviews-number-box-left">
							<input class="community-reviews-display-number-box" type="text" id="min_length" value="100 cm" readonly/>
						</div>

						<div class="community-reviews-number-box-right">
							<input class="community-reviews-display-number-box" type="text" id="max_length" value="200 cm" readonly/>
						</div>
					</div>
				</div>

				<div class="community-reviews-display-year-controls">
					<div class="community-reviews-display-title">Year</div>
					<div class="community-reviews-display-slider">
						<input id="community-reviews-display-slider-min-year" type="range" value="2000" min="2000" max="2023"/>
						<input id="community-reviews-display-slider-max-year" type="range" value="2023" min="2000" max="2023"/>
					</div>

					<div class="community-reviews-number-boxes">
						<div class="community-reviews-number-box-left">
							<input class="community-reviews-display-number-box" type="text" id="min_year" value="2000-2001" readonly/>
						</div>

						<div class="community-reviews-number-box-right">
							<input class="community-reviews-display-number-box" type="text" id="max_year" value="2023-2024" readonly/>
						</div>
					</div>
				</div>

				<strong>Reviewer Filters</strong>

				<div class="community-reviews-display-ski-ability-controls">
					<div class="community-reviews-display-title">Ski Ability</div>
					<select id="community-reviews-display-ski-ability">
						<option value="">--No Ability Filter--</option>
						<option value="Beginner">Beginner</option>
						<option value="Novice">Novice</option>
						<option value="Intermediate">Intermediate</option>
						<option value="Advanced">Advanced</option>
						<option value="Expert">Expert</option>
					</select>
				</div>

				<div class="community-reviews-display-height-controls">
					<div class="community-reviews-display-title">Height</div>
					<div class="community-reviews-display-slider">
						<input id="community-reviews-display-slider-min-height" type="range" value="36" min="36" max="84"/>
						<input id="community-reviews-display-slider-max-height" type="range" value="84" min="36" max="84"/>
					</div>

					<div class="community-reviews-number-boxes">
						<div class="community-reviews-number-box-left">
							<input class="community-reviews-display-number-box" type="text" id="min_height" value="3'0&quot;" readonly/>
						</div>

						<div class="community-reviews-number-box-right">
							<input class="community-reviews-display-number-box" type="text" id="max_height" value="7'0&quot;" readonly/>
						</div>
					</div>
				</div>

				<div class="community-reviews-display-weight-controls">
					<div class="community-reviews-display-title">Weight</div>
					<div class="community-reviews-display-slider">
						<?php
							global $wpdb;
							$user_table_name = $wpdb->prefix . "bcr_users";
							$sql = $wpdb->prepare("SELECT MAX(weight) FROM $user_table_name;");
							$max_weight  = $wpdb->get_var($sql);
							$sql = $wpdb->prepare("SELECT MIN(weight) FROM $user_table_name;");
							$min_weight  = $wpdb->get_var($sql);
							$avg_weight = ($max_weight+$min_weight)/2;
						?>
						<input id="community-reviews-display-slider-min-weight" type="range" value="<?php echo $min_weight ?>" min="<?php echo $min_weight ?>" max= "<?php echo $max_weight ?>"/>
						<input id="community-reviews-display-slider-max-weight" type="range" value="<?php echo $max_weight ?>" min="<?php echo $min_weight ?>" max= "<?php echo $max_weight ?>"/>
					</div>

					<div class="community-reviews-number-boxes">
						<div class="community-reviews-number-box-left">
							<input class="community-reviews-display-number-box" type="text" id="min_weight" value="<?php echo $min_weight ?> lbs" readonly/>
						</div>

						<div class="community-reviews-number-box-right">
							<input class="community-reviews-display-number-box" type="text" id="max_weight" value="<?php echo $max_weight ?> lbs" readonly/>
						</div>
					</div>
				</div>
				<button id="community-reviews-display-submit">Filter</button>
			</div>

			<div class="community-reviews-display-show-posts">
				<?php
					$args = array(
						'post_type' 	 => 'Community Reviews',
						'posts_per_page' => -1,
					);

					$query = new \WP_Query( $args );
					bcr_display_posts( $query );
				?>
			</div>
		</div>

		<script>
		jQuery( document ).ready( function( $ ) {
			$( '#community-reviews-display-submit' ).on( 'click', function( event ) {
				event.preventDefault();

				var product = $( '#community-reviews-display-product' ).val();
				var brand = $( '#community-reviews-display-brand' ).val();
				var category = $( '#community-reviews-display-category' ).val();
				var min_weight = $( '#community-reviews-display-min-weight' ).val();
				var max_weight = $( '#community-reviews-display-max-weight' ).val();
				var height = $( '#community-reviews-display-height' ).val();
				var ski_ability = $( '#community-reviews-display-ski-ability' ).val();
				var sport = $( '#community-reviews-display-sport' ).val();
				var min_height = $( '#community-reviews-display-min-height' ).val();
				var max_height = $( '#community-reviews-display-max-height' ).val();

				$.ajax( {
					url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
					method: 'POST',
					data: {
						action: 'bcr_filter_posts',
						product: product,
						brand: brand,
						category: category,
						min_weight: min_weight,
						max_weight: max_weight,
						ski_ability: ski_ability,
						sport: sport,
						min_height: min_height,
						max_height: max_height
					},
					success: function( data ) {
						$( '.community-reviews-display-show-posts' ).html( data );
					},
					error: function( xhr, status, error ) {
						console.error( xhr, status, error );
					},
				} );
			} );
		} );

		jQuery( document ).ready( function( $ ) {
			$( '#community-reviews-display-brand' ).on( 'change', function() {

				var brand_selected = $( '#community-reviews-display-brand' ).val();

				$.ajax( {
					url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
					method: 'POST',
					data: {
						action: 'bcr_filter_products',
						brand_selected: brand_selected
					},
					success: function( data ) {
						$( '.community-reviews-display-product-controls' ).html( data );
					},
					error: function( xhr, status, error ) {
						console.error( xhr, status, error );
					},
				} );
			} );
		} );

		jQuery( document ).ready( function( $ ) {
			$( '#community-reviews-display-sport' ).on( 'change', function() {

				var sport_selected = $( '#community-reviews-display-sport' ).val();

				$.ajax( {
					url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
					method: 'POST',
					data: {
						action: 'bcr_filter_categories',
						sport_selected: sport_selected
					},
					success: function( data ) {
						$( '.community-reviews-display-category-controls' ).html( data );
					},
					error: function( xhr, status, error ) {
						console.error( xhr, status, error );
					},
				} );
			} );
		} );
		</script>
		<?php
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
