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

    protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_filters',
			[
				'label' => esc_html__( 'Show Filters', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'textdomain' ),
				'label_off' => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'type' => \Elementor\Controls_Manager::NUMBER,
				'label' => esc_html__( 'Number of reviews per page', 'textdomain' ),
				'placeholder' => '4',
				'min' => 1,
				'max' => 50,
				'step' => 1,
				'default' => 4,
			]
		);

		$this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();

		?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

		<?php
			if('yes' === $settings['show_filters']) {
				echo '<div class="community-reviews-display">';
				echo '<div class="community-reviews-display-mobile-only">';
			} else {
				echo '<div class="community-reviews-display" style="display: grid; grid-template-columns: 100%;">';
				echo '<div class="community-reviews-display-mobile-only" style="display: none;">';
			}
		?>
				<button id="community-reviews-display-mobile-button">Filters</button>
			</div>
			<?php
				if('yes' === $settings['show_filters']) {
					echo '<div class="community-reviews-display-filter" id="community-reviews-display-filter">';
				} else {
					echo '<div class="community-reviews-display-filter" id="community-reviews-display-filter" style="display: none;">';
				}
			?>

				<strong>Product Filters</strong>

				<div class="community-reviews-display-keyword-controls">
					<div class="community-reviews-display-title">Keyword Search</div>
					<input type="text" id="community-reviews-keyword-field" placeholder="- Enter some keyword -">
				</div>

				<div class="community-reviews-display-sport-controls">
					<div class="community-reviews-display-title">Sport</div>
					<select id="community-reviews-display-sport">
						<option value="No Sport Filter">- No Sport Filter -</option>
						<?php
							global $wpdb;

							$categories_table_name = $wpdb->prefix . "bcr_categories";

							$sql = "SELECT categoryName FROM $categories_table_name WHERE (parentID=0);";
					
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
						<option value="No Category Filter">- No Category Filter -</option>
						<?php
							global $wpdb;

							$categories_table_name = $wpdb->prefix . "bcr_categories";

							$sql = "SELECT categoryName FROM $categories_table_name WHERE (parentID!=0);";
					
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
						<option value="No Brand Filter">- No Brand Filter -</option>
						<?php
							global $wpdb;

							$brands_table_name = $wpdb->prefix . "bcr_brands";

							$sql = "SELECT brandName FROM $brands_table_name;";
					
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
						<option value="No Product Filter">- No Product Filter -</option>
						<?php
							global $wpdb;

							$products_table_name = $wpdb->prefix . "bcr_products";

							$sql = "SELECT productName FROM $products_table_name;";
					
							$results  = $wpdb->get_results($sql);

							foreach ($results as $id => $product_obj) {
								$product_name = $product_obj->productName;

								echo '<option value="' . esc_html($product_name) . '">' . esc_html($product_name) . '</option>';
							}
						?>
					</select>
				</div>

				<div id="community-reviews-display-length-controls" class="community-reviews-display-length-controls">
					<div class="community-reviews-display-title">Length</div>
					<div class="community-reviews-display-slider">
						<input id="community-reviews-display-slider-min-length" type="range" value="50" min="50" max="250"/>
						<input id="community-reviews-display-slider-max-length" type="range" value="250" min="50" max="250"/>
					</div>
					
					<div class="community-reviews-number-boxes">
						<div class="community-reviews-number-box-left">
							<input class="community-reviews-display-number-box" type="text" id="min_length" value="50 cm" readonly/>
						</div>

						<div class="community-reviews-number-box-right">
							<input class="community-reviews-display-number-box" type="text" id="max_length" value="250 cm" readonly/>
						</div>
					</div>
				</div>

				<div id="community-reviews-display-boot-size-controls" class="community-reviews-display-boot-size-controls">
					<div class="community-reviews-display-title">Ski Boot Size</div>
					<div class="community-reviews-display-slider">
						<input id="community-reviews-display-slider-min-boot-size" type="range" value="15" min="15" max="34" step="0.5"/>
						<input id="community-reviews-display-slider-max-boot-size" type="range" value="34" min="15" max="34" step="0.5"/>
					</div>
					
					<div class="community-reviews-number-boxes">
						<div class="community-reviews-number-box-left">
							<input class="community-reviews-display-number-box" type="text" id="min_boot-size" value="15" readonly/>
						</div>

						<div class="community-reviews-number-box-right">
							<input class="community-reviews-display-number-box" type="text" id="max_boot-size" value="34" readonly/>
						</div>
					</div>
				</div>

				<div id="community-reviews-display-year-controls" class="community-reviews-display-year-controls">
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
						<option value="No Ability Filter">- No Ability Filter -</option>
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

					<div class="community-reviews-number-boxes community-reviews-display-number-boxes-with-toggle">
						<div class="community-reviews-number-box-left">
							<input class="community-reviews-display-number-box" type="text" id="min_height" value="3'0&quot;" readonly/>
						</div>
						
						<div class="community-reviews-number-box-center">
							<div class ="community-reviews-unit-label">in</div>
						
							<div class="community-reviews-unit-toggle">
								<input class="community-reviews-toggle" id="community-reviews-toggle-height" type="checkbox">
								<label class="community-reviews-toggle-label" for="community-reviews-toggle-height"></label>
							</div>

							<div class ="community-reviews-unit-label">cm</div>
						</div>

						<div class="community-reviews-number-box-right">
							<input class="community-reviews-display-number-box" type="text" id="max_height" value="7'0&quot;" readonly/>
						</div>
					</div>

					<div class="community-reviews-units-option-mobile">
						<div class ="community-reviews-unit-label community-reviews-unit-label-left">in</div>
					
						<div class="community-reviews-unit-toggle">
							<input class="community-reviews-toggle" id="community-reviews-toggle-height-mobile" type="checkbox">
							<label class="community-reviews-toggle-label" for="community-reviews-toggle-height-mobile"></label>
						</div>

						<div class ="community-reviews-unit-label community-reviews-unit-label-right">cm</div>
					</div>
				</div>

				<div class="community-reviews-display-weight-controls">
					<div class="community-reviews-display-title">Weight</div>
					<div class="community-reviews-display-slider">
						<input id="community-reviews-display-slider-min-weight" type="range" value="50" min="50" max= "400"/>
						<input id="community-reviews-display-slider-max-weight" type="range" value="400" min="50" max= "400"/>
					</div>

					<div class="community-reviews-number-boxes community-reviews-display-number-boxes-with-toggle">
						<div class="community-reviews-number-box-left">
							<input class="community-reviews-display-number-box" type="text" id="min_weight" value="<?php echo 0 ?> lbs" readonly/>
						</div>
						
						<div class="community-reviews-number-box-center">
							<div class ="community-reviews-unit-label">lbs</div>

							<div class="community-reviews-unit-toggle">
								<input class="community-reviews-toggle" id="community-reviews-toggle-weight" type="checkbox">
								<label class="community-reviews-toggle-label" for="community-reviews-toggle-weight"></label>
							</div>

							<div class ="community-reviews-unit-label">kg</div>
						</div>

						<div class="community-reviews-number-box-right">
							<input class="community-reviews-display-number-box" type="text" id="max_weight" value="<?php echo 400 ?> lbs" readonly/>
						</div>
					</div>

					<div class="community-reviews-units-option-mobile">
						<div class ="community-reviews-unit-label community-reviews-unit-label-left">lbs</div>

						<div class="community-reviews-unit-toggle">
							<input class="community-reviews-toggle" id="community-reviews-toggle-weight-mobile" type="checkbox">
							<label class="community-reviews-toggle-label" for="community-reviews-toggle-weight-mobile"></label>
						</div>

						<div class ="community-reviews-unit-label community-reviews-unit-label-right">kg</div>
					</div>
				</div>
				<button id="community-reviews-display-submit">Filter</button>
			</div>

			<div class="community-reviews-display-show-posts">
				<?php
					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

					$args = array(
						'post_type' 	 => 'Community Reviews',
						'posts_per_page' => $settings['posts_per_page'],
						'paged'          => $paged,
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
				var sport = $( '#community-reviews-display-sport' ).val();
				var category = $( '#community-reviews-display-category' ).val();
				var brand = $( '#community-reviews-display-brand' ).val();
				var product = $( '#community-reviews-display-product' ).val();

				var keyword = $( '#community-reviews-keyword-field' ).val();

				var min_length = $( '#community-reviews-display-slider-min-length' ).val();
				var abs_min_length = $( '#community-reviews-display-slider-min-length' ).prop('min');
				var max_length = $( '#community-reviews-display-slider-max-length' ).val();
				var abs_max_length = $( '#community-reviews-display-slider-max-length' ).prop('max');
				if(min_length == abs_min_length && max_length == abs_max_length) {
					var min_length = "";
					var max_length = "";
				}

				var min_year = $( '#community-reviews-display-slider-min-year' ).val();
				var abs_min_year = $( '#community-reviews-display-slider-min-year' ).prop('min');
				var max_year = $( '#community-reviews-display-slider-max-year' ).val();
				var abs_max_year = $( '#community-reviews-display-slider-max-year' ).prop('max');
				if(min_year == abs_min_year && max_year == abs_max_year) {
					var min_year = "";
					var max_year = "";
				}

				var ski_ability = $( '#community-reviews-display-ski-ability' ).val();
				
				var min_weight = $( '#community-reviews-display-slider-min-weight' ).val();
				var abs_min_weight = $( '#community-reviews-display-slider-min-weight' ).prop('min');
				var max_weight = $( '#community-reviews-display-slider-max-weight' ).val();
				var abs_max_weight = $( '#community-reviews-display-slider-max-weight' ).prop('max');
				if(min_weight == abs_min_weight && max_weight == abs_max_weight) {
					var min_weight = "";
					var max_weight = "";
				}
				
				var min_height = $( '#community-reviews-display-slider-min-height' ).val();
				var abs_min_height = $( '#community-reviews-display-slider-min-height' ).prop('min');
				var max_height = $( '#community-reviews-display-slider-max-height' ).val();
				var abs_max_height = $( '#community-reviews-display-slider-max-height' ).prop('max');
				if(min_height == abs_min_height && max_height == abs_max_height) {
					var min_height = "";
					var max_height = "";
				}

				var min_boot_size = $( '#community-reviews-display-slider-min-boot-size' ).val();
				var abs_min_boot_size = $( '#community-reviews-display-slider-min-boot-size' ).prop('min');
				var max_boot_size = $( '#community-reviews-display-slider-max-boot-size' ).val();
				var abs_max_boot_size = $( '#community-reviews-display-slider-max-boot-size' ).prop('max');
				if(min_boot_size == abs_min_boot_size && max_boot_size == abs_max_boot_size) {
					var min_boot_size = "";
					var max_boot_size = "";
				}

				$.ajax( {
					url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
					method: 'POST',
					data: {
						action: 'bcr_filter_posts',
						posts_per_page: <?php echo $settings['posts_per_page'];?>,
						sport: sport,
						category: category,
						brand: brand,
						product: product,
						keyword: keyword,
						min_length: min_length,
						max_length: max_length,
						min_year: min_year,
						max_year: max_year,
						ski_ability: ski_ability,
						min_height: min_height,
						max_height: max_height,
						min_weight: min_weight,
						max_weight: max_weight,
						min_boot_size: min_boot_size,
						max_boot_size: max_boot_size
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
						$('select').selectize({
							sortField: 'text'
						});
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
						$('select').selectize({
							sortField: 'text'
						});
						$( '#community-reviews-display-category').on('change', function() {
							bcr_hide_selectors();
						});
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
?>