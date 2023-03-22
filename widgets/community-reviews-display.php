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
			<div class="community-reviews-display-mobile-only">
				<button id="community-reviews-display-mobile-button"></button>
			</div>
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
							<input class="community-reviews-display-number-box" type="text" id="min_length" value="50 cm" readonly/>
						</div>

						<div class="community-reviews-number-box-right">
							<input class="community-reviews-display-number-box" type="text" id="max_length" value="250 cm" readonly/>
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
				var sport = $( '#community-reviews-display-sport' ).val();
				var category = $( '#community-reviews-display-category' ).val();
				var brand = $( '#community-reviews-display-brand' ).val();
				var product = $( '#community-reviews-display-product' ).val();

				var min_length = $( '#community-reviews-display-slider-min-length' ).val();
				var max_length = $( '#community-reviews-display-slider-max-length' ).val();

				var min_year = $( '#community-reviews-display-slider-min-year' ).val();
				var max_year = $( '#community-reviews-display-slider-max-year' ).val();

				var ski_ability = $( '#community-reviews-display-ski-ability' ).val();
				
				var min_weight = $( '#community-reviews-display-slider-min-weight' ).val();
				var max_weight = $( '#community-reviews-display-slider-max-weight' ).val();
				
				var min_height = $( '#community-reviews-display-slider-min-height' ).val();
				var max_height = $( '#community-reviews-display-slider-max-height' ).val();

				$.ajax( {
					url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
					method: 'POST',
					data: {
						action: 'bcr_filter_posts',
						sport: sport,
						category: category,
						brand: brand,
						product: product,
						min_length: min_length,
						max_length: max_length,
						min_year: min_year,
						max_year: max_year,
						ski_ability: ski_ability,
						min_height: min_height,
						max_height: max_height,
						min_weight: min_weight,
						max_weight: max_weight
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
?>