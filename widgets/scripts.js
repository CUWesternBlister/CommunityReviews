function bcr_alter_slider(cur_slider, other_slider, is_min_slider, box, prefix) {
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

    bcr_update_display_box(box, cur_slider, prefix);
    if(!is_min_slider) {
        bcr_set_max_slider_z_val(other_slider, cur_slider);
    }

    bcr_color_slider(cur_slider, other_slider);  
}

function bcr_update_display_box(box, slider, prefix) {
    if(prefix === "year") {
        year_1 = parseInt(slider.value, 10);
        year_2 = year_1 + 1;

        box.value = year_1.toString() + "-" + year_2.toString();
    } else if(prefix ==="height") {
        height = parseInt(slider.value, 10);

        var is_mobile = window.matchMedia("(max-width: 1500px)");

        var checked = false;

        if(is_mobile.matches) {
            checked = document.querySelector('#community-reviews-toggle-height-mobile:checked') !== null;
        } else {
            checked = document.querySelector('#community-reviews-toggle-height:checked') !== null;
        }

        if(checked) {
            const height_cm = Math.floor(height * 2.54);

            box.value = height_cm.toString() + " cm";
        } else {
            feet = Math.floor(height/12);
            inches = height % 12;

            box.value = feet.toString() + "'" + inches.toString() + '"';
        }
    } else if(prefix === "lbs") {
        weight = parseInt(slider.value, 10);

        var is_mobile = window.matchMedia("(max-width: 1500px)");

        var checked = false;

        if(is_mobile.matches) {
            checked = document.querySelector('#community-reviews-toggle-weight-mobile:checked') !== null;
        } else {
            checked = document.querySelector('#community-reviews-toggle-weight:checked') !== null;
        }

        if(checked) {
            const weight_kg = Math.floor(weight * 0.453592);

            box.value = weight_kg.toString() + " kg";
        } else {
            box.value = slider.value + " " + prefix;
        }
    } else {
        box.value = slider.value + " " + prefix;
    }
}

function bcr_set_max_slider_z_val(min_slider, max_slider) {
    min = Number(min_slider.min); 
    if (Number(max_slider.value) <= min ) {
      max_slider.style.zIndex = 2;
    } else {
      max_slider.style.zIndex = 0;
    }
}

function bcr_color_slider(slider_1, slider_2) {
    const total_range = slider_1.max-slider_1.min;
    const slider_1_val = Number(slider_1.value);
    const sldier_2_val = Number(slider_2.value);

    const low_range = Math.min(slider_1_val, sldier_2_val) - slider_1.min;
    const high_range = Math.max(slider_1_val, sldier_2_val) - slider_1.min;

    slider_1.style.background = `linear-gradient(
        to right,
        ${'#C6C6C6'} 0%,
        ${'#C6C6C6'} ${(low_range)/(total_range)*100}%,
        ${'#D82129'} ${((low_range)/(total_range))*100}%,
        ${'#D82129'} ${(high_range)/(total_range)*100}%, 
        ${'#C6C6C6'} ${(high_range)/(total_range)*100}%, 
        ${'#C6C6C6'} 100%)`;

    slider_2.style.background = `linear-gradient(
        to right,
        ${'#C6C6C6'} 0%,
        ${'#C6C6C6'} ${(low_range)/(total_range)*100}%,
        ${'#D82129'} ${((low_range)/(total_range))*100}%,
        ${'#D82129'} ${(high_range)/(total_range)*100}%, 
        ${'#C6C6C6'} ${(high_range)/(total_range)*100}%, 
        ${'#C6C6C6'} 100%)`;
}

function bcr_update_units(min_slider, max_slider, min_box, max_box, prefix) {
    bcr_update_display_box(min_box, min_slider, prefix);
    bcr_update_display_box(max_box, max_slider, prefix);
}

const length_min_slider = document.querySelector('#community-reviews-display-slider-min-length');
const length_max_slider = document.querySelector('#community-reviews-display-slider-max-length');

const year_min_slider = document.querySelector('#community-reviews-display-slider-min-year');
const year_max_slider = document.querySelector('#community-reviews-display-slider-max-year');

const height_min_slider = document.querySelector('#community-reviews-display-slider-min-height');
const height_max_slider = document.querySelector('#community-reviews-display-slider-max-height');

const weight_min_slider = document.querySelector('#community-reviews-display-slider-min-weight');
const weight_max_slider = document.querySelector('#community-reviews-display-slider-max-weight');


const length_min_box = document.querySelector('#min_length');
const length_max_box = document.querySelector('#max_length');

const year_min_box = document.querySelector('#min_year');
const year_max_box = document.querySelector('#max_year');

const height_min_box = document.querySelector('#min_height');
const height_max_box = document.querySelector('#max_height');

const weight_min_box = document.querySelector('#min_weight');
const weight_max_box = document.querySelector('#max_weight');


bcr_color_slider(length_max_slider, length_min_slider);
bcr_color_slider(year_max_slider, year_min_slider);
bcr_color_slider(height_max_slider, height_min_slider);
bcr_color_slider(weight_max_slider, weight_min_slider);

bcr_update_units(height_min_slider, height_max_slider, height_min_box, height_max_box, "height");
bcr_update_units(weight_min_slider, weight_max_slider, weight_min_box, weight_max_box, "lbs");

const weight_unit_toggle = document.querySelector('#community-reviews-toggle-weight');
const height_unit_toggle = document.querySelector('#community-reviews-toggle-height');

weight_unit_toggle.oninput = () => bcr_update_units(weight_min_slider, weight_max_slider, weight_min_box, weight_max_box, "lbs");
height_unit_toggle.oninput = () => bcr_update_units(height_min_slider, height_max_slider, height_min_box, height_max_box, "height");

const weight_unit_toggle_mobile = document.querySelector('#community-reviews-toggle-weight-mobile');
const height_unit_toggle_mobile = document.querySelector('#community-reviews-toggle-height-mobile');

weight_unit_toggle_mobile.oninput = () => bcr_update_units(weight_min_slider, weight_max_slider, weight_min_box, weight_max_box, "lbs");
height_unit_toggle_mobile.oninput = () => bcr_update_units(height_min_slider, height_max_slider, height_min_box, height_max_box, "height");

length_min_slider.oninput = () => bcr_alter_slider(length_min_slider, length_max_slider, true, length_min_box, "cm");
length_max_slider.oninput = () => bcr_alter_slider(length_max_slider, length_min_slider, false, length_max_box, "cm");

year_min_slider.oninput = () => bcr_alter_slider(year_min_slider, year_max_slider, true, year_min_box, "year");
year_max_slider.oninput = () => bcr_alter_slider(year_max_slider, year_min_slider, false, year_max_box, "year");

height_min_slider.oninput = () => bcr_alter_slider(height_min_slider, height_max_slider, true, height_min_box, "height");
height_max_slider.oninput = () => bcr_alter_slider(height_max_slider, height_min_slider, false, height_max_box, "height");

weight_min_slider.oninput = () => bcr_alter_slider(weight_min_slider, weight_max_slider, true, weight_min_box, "lbs");
weight_max_slider.oninput = () => bcr_alter_slider(weight_max_slider, weight_min_slider, false, weight_max_box, "lbs");

function bcr_show_mobile_filters() {
    const all_filters = document.getElementById('community-reviews-display-filter');
    all_filters.style.display = 'initial';
}

function bcr_hide_mobile_filters() {
    var is_mobile = window.matchMedia("(max-width: 1500px)");
    if(is_mobile.matches) {
        const all_filters = document.getElementById('community-reviews-display-filter');
        all_filters.style.display = 'none';
    }
}

const mobile_button = document.getElementById('community-reviews-display-mobile-button');
const filter_button = document.getElementById('community-reviews-display-submit');

mobile_button.onclick = () => bcr_show_mobile_filters();

filter_button.onclick = () => bcr_hide_mobile_filters();

function bcr_hide_length_selector(category_dropdown) {
    const length_div = document.getElementById('community-reviews-display-length-controls');
    if(category_dropdown.value == "Skis" || category_dropdown.value == "Snowboards") {
        length_div.style.display = 'initial';
    } else {
        length_div.style.display = 'none';
    }
}

function bcr_hide_year_selector(category_dropdown) {
    const year_div = document.getElementById('community-reviews-display-year-controls');
    if(category_dropdown.value == "Skis" || category_dropdown.value == "Snowboards") {
        year_div.style.display = 'initial';
    } else {
        year_div.style.display = 'none';
    }
}

function bcr_hide_selectors() {
    const category_dropdown = document.getElementById('community-reviews-display-category');
    bcr_hide_length_selector(category_dropdown);
    bcr_hide_year_selector(category_dropdown);
}

const categories_dropdown = document.getElementById('community-reviews-display-category');

function bcr_set_categories_dropdown() {
    const categories_dropdown = document.getElementById('community-reviews-display-category');
}

const sport_dropdown = document.getElementById('community-reviews-display-sport');

sport_dropdown.onchange = () => bcr_set_categories_dropdown();

categories_dropdown.onchange = () => bcr_hide_selectors();

bcr_hide_selectors(categories_dropdown);

jQuery(document).ready(function ( $ ) {
    $('select').selectize({
        sortField: 'text'
    });
});