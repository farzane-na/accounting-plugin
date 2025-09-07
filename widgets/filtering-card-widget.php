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

    public function register_controls() {
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
        $this->add_controls_section(
            'card-style',
            [
                'label'=>esc_html__('Card Style','accounting'),
                'tab'=> \Elementor|Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'card_typography',
            'label' => __('Typography', 'accounting'),
            'selector' => '{{WRAPPER}} .filtering-card--typography',
        ]);
    }
};