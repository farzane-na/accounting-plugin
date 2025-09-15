<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class Filtering_Card_Widget extends \Elementor\Widget_Base{
    public function get_name() {
        return 'filtering-card-widget';
    }
    public function get_title() {
        return esc_html__( 'Filtering Card','accounting' );
    }
    public function get_icon() {
        return 'eicon-checkbox';
    }
    public function get_categories() {
        return [ 'basic' ];
    }
    public function get_style_depends() {
        return [ 'filtering-card-style' ];
    }
    public function get_script_depends() {
        return [ 'filtering-card-script' ];
    }
    protected function get_available_post_types() {
        $post_types = get_post_types( [ 'public' => true ], 'objects' );
        $options = [];

        foreach ( $post_types as $post_type ) {
            $options[$post_type->name] = $post_type->labels->singular_name;
        }

        return $options;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'filtering_card_post_type',
            [
                'label'=>esc_html__( 'post type','accounting' ),
                'tab'=>\Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'select_post_type',
            [
                'label' => esc_html__( 'Select Post Type', 'accounting' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_available_post_types(),
                'default' => 'post',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'card-style',
            [
                'label'=>esc_html__('Card Style','accounting'),
                'tab'=> \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'card_typography',
            'label' => __('Typography', 'accounting'),
            'selector' => '{{WRAPPER}} .filtering-card--typography',
        ]);
        $this->end_controls_section();
    }
    public function render() {
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $settings = $this->get_settings_for_display();
        $selected_cats = isset($_POST['categories']) ? array_map('intval', $_POST['categories']) : [];

        // درست گرفتن post_type از تنظیمات (fix)
        $post_type = ! empty( $settings['select_post_type'] ) ? $settings['select_post_type'] : 'education-video';

        // گرفتن دسته‌بندی‌ها (اگر taxonomy متفاوتی داری اینجا تغییر بده)
        $categories = get_terms([
            'taxonomy'   => 'video-category',
            'hide_empty' => true,
        ]);

        // کوئری ویدیوها — وضع پیشنهادی: فقط منتشر شده‌ها
        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            's'              => $search,
        ];
        if ( !empty($selected_cats) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'video-category',
                    'field'    => 'term_id',
                    'terms'    => $selected_cats,
                ]
            ];
        }
        $query = new WP_Query($args);
        ?>

        <div class="filtering-card">
            <!-- مودال ویدیو -->
            <div class="filtering-card__modal-video" style="display:none;">
                <video src="" controls></video>
            </div>

            <!-- ستون فرم (سمت راست) -->
            <div class="filtering-card__form-column">
                <div class="filtering-card__open-filter-btn">
                    <span>
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.75 3H10.5" stroke="#2A2A2A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.5 3H2.25" stroke="#2A2A2A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15.75 9H9" stroke="#2A2A2A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 9H2.25" stroke="#2A2A2A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15.75 15H12" stroke="#2A2A2A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 15H2.25" stroke="#2A2A2A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10.5 1.5V4.5" stroke="#2A2A2A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 7.5V10.5" stroke="#2A2A2A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 13.5V16.5" stroke="#2A2A2A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>فیلتر</span>
                </div>
                <form action="" method="post" class="filtering-card__form" id="filtering-form">
                    <div class='filtering-card__form-heading'>
                        <h3 class="filtering-card__form-title">فیلتر</h3>
                        <span class="filtering-card__close-filter-form">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="32" height="32" rx="16" fill="#F3F4F6"/>
                                <path d="M21 11L11 21" stroke="black" stroke-width="1.5"                                stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M11 11L21 21" stroke="black" stroke-width="1.5"                                stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                    <input
                            type="text"
                            name="search"
                            placeholder="<?php esc_attr_e('جستجو...', 'accounting'); ?>"
                            class="filtering-card__search-box"
                            value="<?php echo esc_attr($search); ?>"
                    />
                    <h3 class="filtering-card__category-title">دسته بندی</h3>
                    <div class="filtering-card__category">
                        <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                            <?php foreach ( $categories as $cat ) : ?>
                                <label class="filtering-card__category-container">
                                    <input
                                            type="checkbox"
                                            name="categories[]"
                                            value="<?php echo esc_attr( $cat->term_id ); ?>"
                                            class="filtering-card__category-checkbox"
                                            <?php checked( in_array( $cat->term_id, $selected_cats ) ); ?>
                                    />
                                    <div class="filtering-card__checked"></div>
                                    <span class="filtering-card__category-name"><?php echo esc_html( $cat->name ); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <input type="submit" value="<?php esc_attr_e('فیلتر', 'accounting'); ?>" class="filtering-card__submit-btn" />
                </form>
            </div>

            <!-- ستون نتایج (سمت چپ) -->
            <div class="filtering-card__result-column">
                <?php if ( !empty($search) || !empty($selected_cats) ) : ?>
                    <div class="filtering-card__active-filters">
                       <div>
                           <strong><?php esc_html_e('فیلترهای اعمال شده:', 'accounting'); ?></strong>

                           <?php if ( !empty($search) ) : ?>
                               <span class="filtering-card__active-filter">
                    <?php echo sprintf( __('جستجو: %s', 'accounting'), esc_html($search) ); ?>
                </span>
                           <?php endif; ?>

                           <?php if ( !empty($selected_cats) ) : ?>
                               <?php foreach ( $selected_cats as $cat_id ) :
                                   $term = get_term( $cat_id );
                                   if ( !is_wp_error($term) && $term ) :
                                       ?>
                                       <span class="filtering-card__active-filter">
                        <?php echo esc_html( $term->name ); ?>
                    </span>
                                   <?php endif; endforeach; ?>
                           <?php endif; ?>
                       </div>

                        <!-- دکمه پاک کردن -->
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="filtering-card__clear-filters">
                            <?php esc_html_e('پاک کردن همه', 'accounting'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="filtering-card__results">
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : $query->the_post();
                        // ACF fields — ممکنه URL یا آرایه یا attachment ID باشه
                        // $video_field = get_field('video-file'); // ممکنه url یا آرایه یا id
                        // $video_url = '';

                        // if ( is_array( $video_field ) && ! empty( $video_field['url'] ) ) {
                        //     $video_url = $video_field['url'];
                        // } elseif ( is_numeric( $video_field ) ) {
                        //     $video_url = wp_get_attachment_url( $video_field );
                        // } else {
                        //     $video_url = $video_field; // فرض می‌کنیم رشته (url) هست
                        // }

                        // $cover_field = get_field('video-cover');
                        // $cover_url = '';

                        // if ( is_array( $cover_field ) && ! empty( $cover_field['url'] ) ) {
                        //     $cover_url = $cover_field['url'];
                        // } elseif ( is_numeric( $cover_field ) ) {
                        //     $cover_url = wp_get_attachment_url( $cover_field );
                        // } else {
                        //     $cover_url = $cover_field;
                        // }
                        $video_url = '';
                        $cover_url = '';

                        if ( function_exists('get_field') ) {
                            $video_field = get_field('video-file');
                            $cover_field = get_field('video-cover');
                        
                            if ( is_array($video_field) && !empty($video_field['url']) ) $video_url = esc_url($video_field['url']);
                            elseif ( is_numeric($video_field) ) $video_url = esc_url(wp_get_attachment_url($video_field));
                            else $video_url = esc_url($video_field);
                        
                            if ( is_array($cover_field) && !empty($cover_field['url']) ) $cover_url = esc_url($cover_field['url']);
                            elseif ( is_numeric($cover_field) ) $cover_url = esc_url(wp_get_attachment_url($cover_field));
                            else $cover_url = esc_url($cover_field);
                        
                        } else {
                            $video_field = get_post_meta(get_the_ID(), 'video-file', true);
                            $cover_field = get_post_meta(get_the_ID(), 'video-cover', true);

                            $video_url = '';
                            $cover_url = '';
                            if (is_numeric($video_field)) {
                                $video_url = wp_get_attachment_url($video_field);
                            } else {
                                $video_url = esc_url($video_field);
                            }

                            if (is_numeric($cover_field)) {
                                $cover_url = wp_get_attachment_url($cover_field);
                            } else {
                                $cover_url = esc_url($cover_field);
                            }
                            
                        }

                        ?>
                        <article class="filtering-card__result" data-video="<?php echo esc_url( $video_url ); ?>">
                            <div class="filtering-card__video-wrapper">
                                <?php if ( $cover_url ) : ?>
                                    <div class="filtering-card__video-cover">
                                        <img src="<?php echo esc_url( $cover_url ); ?>" alt="<?php the_title_attribute(); ?>" class="filtering-card__video-image" />
                                        <div class="filtering-card__play-icon">
                                            <svg width="49" height="48" viewBox="0 0 49 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0.128906" width="48" height="48" rx="24" fill="white" fill-opacity="0.8"/>
                                                <path d="M33.1289 22.2679C34.4622 23.0378 34.4622 24.9623 33.1289 25.7321L21.1289 32.6603C19.7956 33.4301 18.1289 32.4678 18.1289 30.9282L18.1289 17.0718C18.1289 15.5322 19.7956 14.5699 21.1289 15.3397L33.1289 22.2679Z" fill="#FF6B00"/>
                                            </svg>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <video class="filtering-card__video" controls style="display:none;">
                                    <source src="<?php echo $video_url ; ?>" type="video/mp4">
                                </video>
                            </div>
                            <h3 class="filtering-card__title"><?php the_title(); ?></h3>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else: ?>
                    <p><?php esc_html_e('ویدیویی یافت نشد.', 'accounting'); ?></p>
                <?php endif; ?>
                </div
            </div>
        </div>

        <?php
    }


};