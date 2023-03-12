<?php
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

				<div class="community-reviews-display-product-controls">
					<label for="community-reviews-display-product">Product:</label>
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
				
				<div class="community-reviews-display-brand-controls">
				<label for="community-reviews-display-brand">Brand:</label>
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
				
				<div class="community-reviews-display-category-controls">
					<label for="community-reviews-display-category">Category:</label>
					<select id="community-reviews-display-category">
						<option value="">--No Category Filter--</option>
						<?php
							global $wpdb;

							$categories_table_name = $wpdb->prefix . "bcr_categories";

							$sql = $wpdb->prepare("SELECT categoryName FROM $categories_table_name;");
					
							$results  = $wpdb->get_results($sql);

							foreach ($results as $id => $category_obj) {
								$category_name = $category_obj->categoryName;

								echo '<option value="' . esc_html($category_name) . '">' . esc_html($category_name) . '</option>';
							}
						?>
					</select>
				</div>

				<div class="community-reviews-display-weight-controls">
					<label for="community-reviews-display-min-weight">Weight</label>
					<input id="community-reviews-display-min-weight" type="range" value="100" min="50" max="350"/>
					<input id="community-reviews-display-max-weight" type="range" value="200" min="50" max="350"/>
				</div>

				<div class="community-reviews-display-ski-ability-controls">
					<label for="community-reviews-display-ski-ability">Ski Ability</label>
					<select id="community-reviews-display-ski-ability">
						<option value="">--No Ability Filter--</option>
						<option value="Beginner">Beginner</option>
						<option value="Novice">Novice</option>
						<option value="Intermediate">Intermediate</option>
						<option value="Advanced">Advanced</option>
						<option value="Expert">Expert</option>
					</select>
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
					
					global $post;
					if ( $query->have_posts() ) {
						echo '<ul>';
						while ( $query->have_posts() ) {
							$query->the_post();
							echo '<li>' . get_the_title() . get_the_excerpt() . '</li>';
						}
						echo '</ul>';
						wp_reset_postdata();
					}
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
				var ski_ability = $( '#community-reviews-display-ski-ability' ).val();

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
						ski_ability: ski_ability
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
		</script>
		<?php
    }
}
?>