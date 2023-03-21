function bcr_alter_slider(cur_slider, other_slider, is_min_slider) {
    cur_val = parseInt(cur_slider.value, 10);
    other_val = parseInt(other_slider.value, 10);

    if(is_min_slider) {
        if(cur_val >= other_val) {
            cur_slider.value = other_val;
        }
    } else {
        if(cur_val <= other_val) {
            cur_slider.value = other_val;
        }
    }
}

const length_min_slider = document.querySelector('#community-reviews-display-slider-min-length');
const length_max_slider = document.querySelector('#community-reviews-display-slider-max-length');

const year_min_slider = document.querySelector('#community-reviews-display-slider-min-year');
const year_max_slider = document.querySelector('#community-reviews-display-slider-max-year');

const height_min_slider = document.querySelector('#community-reviews-display-slider-min-height');
const height_max_slider = document.querySelector('#community-reviews-display-slider-max-height');

const weight_min_slider = document.querySelector('#community-reviews-display-slider-min-weight');
const weight_max_slider = document.querySelector('#community-reviews-display-slider-max-weight');

length_min_slider.oninput = () => bcr_alter_slider(length_min_slider, length_max_slider, true);
length_max_slider.oninput = () => bcr_alter_slider(length_max_slider, length_min_slider, false);

year_min_slider.oninput = () => bcr_alter_slider(year_min_slider, year_max_slider, true);
year_max_slider.oninput = () => bcr_alter_slider(year_max_slider, year_min_slider, false);

height_min_slider.oninput = () => bcr_alter_slider(height_min_slider, height_max_slider, true);
height_max_slider.oninput = () => bcr_alter_slider(height_max_slider, height_min_slider, false);

weight_min_slider.oninput = () => bcr_alter_slider(weight_min_slider, weight_max_slider, true);
weight_max_slider.oninput = () => bcr_alter_slider(weight_max_slider, weight_min_slider, false);