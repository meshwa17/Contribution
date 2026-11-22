<?php
/**
 * Simple Vertical Timeline with Slider - Elementor widget.
 *
 * @package Simple_Vertical_Timeline_With_Slider
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

/**
 * Widget class.
 */
class VTWS_Widget extends Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'vtws_vertical_timeline';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Vertical Timeline with Slider', 'simple-vertical-timeline-with-slider-for-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-time-line';
	}

	/**
	 * Widget categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'vtws-elements' );
	}

	/**
	 * Search keywords.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'timeline', 'vertical', 'slider', 'history', 'milestones', 'journey', 'company' );
	}

	/**
	 * Style dependencies.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( 'vtws-vertical-timeline' );
	}

	/**
	 * Script dependencies.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( 'vtws-vertical-timeline' );
	}

	/**
	 * Color definitions shared by the Light and Dark color tabs.
	 *
	 * Format: key => array( label, light default, dark default ).
	 *
	 * @return array
	 */
	private function color_defs() {
		return array(
			'accent'      => array( esc_html__( 'Accent', 'simple-vertical-timeline-with-slider-for-elementor' ), '#0aa5e8', '#0aa5e8' ),
			'accent_text' => array( esc_html__( 'Active Text', 'simple-vertical-timeline-with-slider-for-elementor' ), '#0786bd', '#0aa5e8' ),
			'bg'          => array( esc_html__( 'Section Background', 'simple-vertical-timeline-with-slider-for-elementor' ), '#ffffff', '#131c2c' ),
			'heading'     => array( esc_html__( 'Headings', 'simple-vertical-timeline-with-slider-for-elementor' ), '#0f1b2d', '#f1f5f9' ),
			'text'        => array( esc_html__( 'Body Text', 'simple-vertical-timeline-with-slider-for-elementor' ), '#475569', '#b4c0d0' ),
			'inactive'    => array( esc_html__( 'Inactive Year / Label', 'simple-vertical-timeline-with-slider-for-elementor' ), '#8493a6', '#7d8ba0' ),
			'rail'        => array( esc_html__( 'Rail & Inactive Dot', 'simple-vertical-timeline-with-slider-for-elementor' ), '#d5dde6', '#2b384d' ),
			'dot_bg'      => array( esc_html__( 'Dot & Button Background', 'simple-vertical-timeline-with-slider-for-elementor' ), '#ffffff', '#131c2c' ),
			'panel_bg'    => array( esc_html__( 'Panel Background', 'simple-vertical-timeline-with-slider-for-elementor' ), '#f1f5f9', '#192438' ),
			'stat_bg'     => array( esc_html__( 'Stat Box Background', 'simple-vertical-timeline-with-slider-for-elementor' ), '#ffffff', '#131c2c' ),
			'line'        => array( esc_html__( 'Divider & Button Border', 'simple-vertical-timeline-with-slider-for-elementor' ), '#d5dde6', '#2b384d' ),
		);
	}

	/**
	 * Default timeline items.
	 *
	 * @return array
	 */
	private function default_items() {
		$text = __( 'Describe this milestone: what happened, why it mattered and what came next.', 'simple-vertical-timeline-with-slider-for-elementor' );

		return array(
			array(
				'year'        => '2006',
				'label'       => __( 'Founded', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'chip'        => '2006',
				'title'       => __( 'Where it all began', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'description' => $text,
				'stat_number' => '15+',
				'stat_label'  => __( 'years of experience', 'simple-vertical-timeline-with-slider-for-elementor' ),
			),
			array(
				'year'        => '2012',
				'label'       => __( 'First milestone', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'chip'        => '2012',
				'title'       => __( 'Growing the team', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'description' => $text,
				'stat_number' => '25',
				'stat_label'  => __( 'team members', 'simple-vertical-timeline-with-slider-for-elementor' ),
			),
			array(
				'year'        => '2018',
				'label'       => __( 'Expansion', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'chip'        => '2018',
				'title'       => __( 'Reaching new markets', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'description' => $text,
				'stat_number' => '',
				'stat_label'  => '',
			),
			array(
				'year'        => '2026',
				'label'       => __( 'New horizons', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'chip'        => '2026',
				'title'       => __( 'What comes next', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'description' => $text,
				'stat_number' => '',
				'stat_label'  => '',
			),
		);
	}

	/**
	 * Register all controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_settings_controls();
		$this->register_style_container_controls();
		$this->register_style_color_controls();
		$this->register_style_header_controls();
		$this->register_style_timeline_controls();
		$this->register_style_panel_controls();
		$this->register_style_stat_controls();
		$this->register_style_nav_controls();
	}

	/* ------------------------------------------------------------------
	 * CONTENT TAB
	 * ---------------------------------------------------------------- */

	/**
	 * Content: header + timeline items.
	 *
	 * @return void
	 */
	private function register_content_controls() {

		// Header.
		$this->start_controls_section(
			'section_header',
			array(
				'label' => esc_html__( 'Header', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_header',
			array(
				'label'        => esc_html__( 'Show Header', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'eyebrow_text',
			array(
				'label'       => esc_html__( 'Eyebrow Text', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Since 2006', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'placeholder' => esc_html__( 'Since 2006', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array( 'show_header' => 'yes' ),
			)
		);

		$this->add_control(
			'heading_text',
			array(
				'label'       => esc_html__( 'Heading', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Our Journey', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'placeholder' => esc_html__( 'Our Journey', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array( 'show_header' => 'yes' ),
			)
		);

		$this->add_control(
			'heading_tag',
			array(
				'label'     => esc_html__( 'Heading HTML Tag', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'condition' => array( 'show_header' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'header_align',
			array(
				'label'     => esc_html__( 'Alignment', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'simple-vertical-timeline-with-slider-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'simple-vertical-timeline-with-slider-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'simple-vertical-timeline-with-slider-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .vtws__head' => 'text-align: {{VALUE}};',
				),
				'condition' => array( 'show_header' => 'yes' ),
			)
		);

		$this->end_controls_section();

		// Timeline items.
		$this->start_controls_section(
			'section_items',
			array(
				'label' => esc_html__( 'Timeline Items', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'year',
			array(
				'label'       => esc_html__( 'Year (on timeline)', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '2020',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'       => esc_html__( 'Short Label (on timeline)', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Milestone', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'chip',
			array(
				'label'       => esc_html__( 'Date Chip (in panel)', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '1 January 2020',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Panel Title', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Milestone title', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 6,
				'default' => esc_html__( 'Describe this milestone.', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'stat_number',
			array(
				'label'       => esc_html__( 'Highlight Number', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'e.g. 15+ (leave empty to hide)', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'label_block' => true,
				'separator'   => 'before',
				'dynamic'     => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'stat_label',
			array(
				'label'       => esc_html__( 'Highlight Label', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'e.g. years of experience', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Milestones', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $this->default_items(),
				'title_field' => '{{{ year }}} — {{{ label }}}',
				'prevent_empty' => true,
			)
		);

		$this->end_controls_section();
	}

	/* ------------------------------------------------------------------
	 * CONTENT TAB: BEHAVIOUR SETTINGS
	 * ---------------------------------------------------------------- */

	/**
	 * Content: behaviour settings.
	 *
	 * @return void
	 */
	private function register_settings_controls() {

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'start_index',
			array(
				'label'       => esc_html__( 'Start At Item #', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'step'        => 1,
				'default'     => 1,
				'description' => esc_html__( 'Which milestone is open when the page loads.', 'simple-vertical-timeline-with-slider-for-elementor' ),
			)
		);

		$this->add_control(
			'show_counter',
			array(
				'label'        => esc_html__( 'Show Counter', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'counter_format',
			array(
				'label'       => esc_html__( 'Counter Format', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '{current} / {total}',
				'description' => esc_html__( 'Use {current} and {total} as placeholders.', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'condition'   => array( 'show_counter' => 'yes' ),
			)
		);

		$this->add_control(
			'show_ghost',
			array(
				'label'        => esc_html__( 'Show Large Background Year', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_nav',
			array(
				'label'        => esc_html__( 'Show Prev / Next Buttons', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'prev_label',
			array(
				'label'     => esc_html__( 'Previous Button (Screen Reader Text)', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Previous milestone', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'condition' => array( 'show_nav' => 'yes' ),
			)
		);

		$this->add_control(
			'next_label',
			array(
				'label'     => esc_html__( 'Next Button (Screen Reader Text)', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Next milestone', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'condition' => array( 'show_nav' => 'yes' ),
			)
		);

		$this->add_control(
			'keyboard',
			array(
				'label'        => esc_html__( 'Keyboard Arrow Navigation', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'animate_panel',
			array(
				'label'        => esc_html__( 'Panel Entrance Animation', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'description'  => esc_html__( 'Autoplay is disabled inside the Elementor editor and stops after the visitor interacts.', 'simple-vertical-timeline-with-slider-for-elementor' ),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed (ms)', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1500,
				'step'      => 100,
				'default'   => 5000,
				'condition' => array( 'autoplay' => 'yes' ),
			)
		);

		$this->add_control(
			'pause_hover',
			array(
				'label'        => esc_html__( 'Pause On Hover', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'autoplay' => 'yes' ),
			)
		);

		$this->add_control(
			'heading_responsive',
			array(
				'label'     => esc_html__( 'Responsive Behaviour', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'tablet_breakpoint',
			array(
				'label'       => esc_html__( 'Tablet Layout Up To (px)', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 320,
				'max'         => 1600,
				'default'     => 860,
				'description' => esc_html__( 'At or below this width the timeline and panel stack in a single column.', 'simple-vertical-timeline-with-slider-for-elementor' ),
			)
		);

		$this->add_control(
			'mobile_breakpoint',
			array(
				'label'       => esc_html__( 'Mobile Accordion Up To (px)', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 320,
				'max'         => 1200,
				'default'     => 640,
				'description' => esc_html__( 'At or below this width the timeline becomes a vertical accordion.', 'simple-vertical-timeline-with-slider-for-elementor' ),
			)
		);

		$this->add_control(
			'mobile_collapsible',
			array(
				'label'        => esc_html__( 'Mobile: Tap Open Item To Collapse', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'mobile_scroll',
			array(
				'label'        => esc_html__( 'Mobile: Scroll Item Into View', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'auto_dark',
			array(
				'label'        => esc_html__( 'Auto Dark Mode', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'description'  => esc_html__( 'Use the Dark colors (Style tab) when the visitor’s device prefers a dark color scheme.', 'simple-vertical-timeline-with-slider-for-elementor' ),
			)
		);

		$this->end_controls_section();
	}

	/* ------------------------------------------------------------------
	 * STYLE TAB
	 * ---------------------------------------------------------------- */

	/**
	 * Style: container.
	 *
	 * @return void
	 */
	private function register_style_container_controls() {

		$this->start_controls_section(
			'style_container',
			array(
				'label' => esc_html__( 'Container', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'container_width',
			array(
				'label'      => esc_html__( 'Max Width', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 320,
						'max' => 1800,
					),
					'%'  => array(
						'min' => 30,
						'max' => 100,
					),
					'vw' => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .vtws' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vtws' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .vtws',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_shadow',
				'selector' => '{{WRAPPER}} .vtws',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style: colors (Light / Dark tabs).
	 *
	 * @return void
	 */
	private function register_style_color_controls() {

		$this->start_controls_section(
			'style_colors',
			array(
				'label' => esc_html__( 'Colors', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'color_tabs' );

		foreach ( array(
			'l' => esc_html__( 'Light', 'simple-vertical-timeline-with-slider-for-elementor' ),
			'd' => esc_html__( 'Dark', 'simple-vertical-timeline-with-slider-for-elementor' ),
		) as $mode => $mode_label ) {

			$this->start_controls_tab(
				'color_tab_' . $mode,
				array( 'label' => $mode_label )
			);

			foreach ( $this->color_defs() as $key => $def ) {
				$this->add_control(
					$mode . '_' . $key,
					array(
						'label'     => $def[0],
						'type'      => Controls_Manager::COLOR,
						'default'   => ( 'l' === $mode ) ? $def[1] : $def[2],
						'selectors' => array(
							'{{WRAPPER}} .vtws' => '--vtws-' . $mode . '-' . str_replace( '_', '-', $key ) . ': {{VALUE}};',
						),
					)
				);
			}

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Style: header.
	 *
	 * @return void
	 */
	private function register_style_header_controls() {

		$this->start_controls_section(
			'style_header',
			array(
				'label'     => esc_html__( 'Header', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_header' => 'yes' ),
			)
		);

		$this->add_control(
			'heading_eyebrow_style',
			array(
				'label' => esc_html__( 'Eyebrow', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eyebrow_typography',
				'selector' => '{{WRAPPER}} .vtws__eyebrow',
			)
		);

		$this->add_responsive_control(
			'eyebrow_padding',
			array(
				'label'      => esc_html__( 'Padding', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__eyebrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'eyebrow_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__eyebrow' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => esc_html__( 'Heading', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .vtws__heading',
			)
		);

		$this->add_responsive_control(
			'header_spacing',
			array(
				'label'      => esc_html__( 'Space Below Header', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 120,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__head' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style: timeline track.
	 *
	 * @return void
	 */
	private function register_style_timeline_controls() {

		$this->start_controls_section(
			'style_timeline',
			array(
				'label' => esc_html__( 'Timeline', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'dot_size',
			array(
				'label'      => esc_html__( 'Dot Size', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 14,
						'max' => 56,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws' => '--vtws-dot: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rail_thickness',
			array(
				'label'      => esc_html__( 'Rail Thickness', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 14,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws' => '--vtws-rail: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'active_scale',
			array(
				'label'     => esc_html__( 'Active Dot Scale', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 1,
						'max'  => 2,
						'step' => 0.05,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .vtws' => '--vtws-scale: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'year_typography',
				'label'     => esc_html__( 'Year Typography', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'selector'  => '{{WRAPPER}} .vtws__yr',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Label Typography', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'selector' => '{{WRAPPER}} .vtws__nm',
			)
		);

		$this->add_responsive_control(
			'track_width',
			array(
				'label'      => esc_html__( 'Timeline Column Width', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 160,
						'max' => 480,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws' => '--vtws-track-w: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'track_spacing',
			array(
				'label'      => esc_html__( 'Gap Between Timeline & Panel', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__layout' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style: content panel.
	 *
	 * @return void
	 */
	private function register_style_panel_controls() {

		$this->start_controls_section(
			'style_panel',
			array(
				'label' => esc_html__( 'Content Panel', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'panel_padding',
			array(
				'label'      => esc_html__( 'Padding', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'panel_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'panel_border',
				'selector' => '{{WRAPPER}} .vtws__panel',
			)
		);

		$this->add_responsive_control(
			'panel_gap',
			array(
				'label'      => esc_html__( 'Gap Between Text & Highlight', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__panel' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_chip_style',
			array(
				'label'     => esc_html__( 'Date Chip', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'chip_typography',
				'selector' => '{{WRAPPER}} .vtws__chip',
			)
		);

		$this->add_control(
			'heading_ptitle_style',
			array(
				'label'     => esc_html__( 'Title', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'panel_title_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ptitle_typography',
				'selector' => '{{WRAPPER}} .vtws__ttl',
			)
		);

		$this->add_control(
			'heading_text_style',
			array(
				'label'     => esc_html__( 'Description', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .vtws__txt',
			)
		);

		$this->add_responsive_control(
			'text_max_width',
			array(
				'label'      => esc_html__( 'Text Max Width', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'ch', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 200,
						'max' => 1400,
					),
					'ch' => array(
						'min' => 20,
						'max' => 140,
					),
					'%'  => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__txt' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_ghost_style',
			array(
				'label'     => esc_html__( 'Background Year', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'show_ghost' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'ghost_size',
			array(
				'label'      => esc_html__( 'Font Size', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 30,
						'max' => 400,
					),
					'vw' => array(
						'min' => 4,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__ghost' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'show_ghost' => 'yes' ),
			)
		);

		$this->add_control(
			'ghost_opacity',
			array(
				'label'     => esc_html__( 'Opacity', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 0.4,
						'step' => 0.005,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .vtws__ghost' => 'opacity: {{SIZE}};',
				),
				'condition' => array( 'show_ghost' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style: highlight (stat) box.
	 *
	 * @return void
	 */
	private function register_style_stat_controls() {

		$this->start_controls_section(
			'style_stat',
			array(
				'label' => esc_html__( 'Highlight Box', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'stat_min_width',
			array(
				'label'      => esc_html__( 'Min Width', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 80,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__stat' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'stat_padding',
			array(
				'label'      => esc_html__( 'Padding', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__stat' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'stat_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__stat' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'stat_border',
				'selector' => '{{WRAPPER}} .vtws__stat',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'stat_shadow',
				'selector' => '{{WRAPPER}} .vtws__stat',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'stat_num_typography',
				'label'     => esc_html__( 'Number Typography', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'selector'  => '{{WRAPPER}} .vtws__stat-num',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stat_lbl_typography',
				'label'    => esc_html__( 'Label Typography', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'selector' => '{{WRAPPER}} .vtws__stat-lbl',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style: footer (counter + navigation buttons).
	 *
	 * @return void
	 */
	private function register_style_nav_controls() {

		$this->start_controls_section(
			'style_nav',
			array(
				'label' => esc_html__( 'Counter & Buttons', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'counter_typography',
				'label'     => esc_html__( 'Counter Typography', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'selector'  => '{{WRAPPER}} .vtws__count',
				'condition' => array( 'show_counter' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'btn_size',
			array(
				'label'      => esc_html__( 'Button Size', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 24,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws' => '--vtws-btn: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'show_nav' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'btn_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws' => '--vtws-btn-icon: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'show_nav' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'btn_radius',
			array(
				'label'      => esc_html__( 'Button Border Radius', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
					'%'  => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws' => '--vtws-btn-radius: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'show_nav' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'foot_padding_top',
			array(
				'label'      => esc_html__( 'Footer Top Padding', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .vtws__foot' => 'padding-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* ------------------------------------------------------------------
	 * RENDER
	 * ---------------------------------------------------------------- */

	/**
	 * Print an inline SVG arrow.
	 *
	 * @param string $dir Either "prev" or "next".
	 * @return void
	 */
	private function render_arrow( $dir ) {
		$path = ( 'prev' === $dir )
			? 'M19 12H5M12 19l-7-7 7-7'
			: 'M5 12h14M12 5l7 7-7 7';

		echo '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="' . esc_attr( $path ) . '"/></svg>';
	}

	/**
	 * Render the widget output on the front end.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = ! empty( $settings['items'] ) ? array_values( $settings['items'] ) : array();

		if ( empty( $items ) ) {
			return;
		}

		$count = count( $items );
		$start = max( 1, (int) $settings['start_index'] );
		$start = min( $start, $count ) - 1;
		$uid   = 'vtws-' . $this->get_id();

		$show_counter = 'yes' === $settings['show_counter'];
		$show_nav     = 'yes' === $settings['show_nav'];
		$show_ghost   = 'yes' === $settings['show_ghost'];
		$show_foot    = $show_counter || $show_nav;
		$autoplay     = 'yes' === $settings['autoplay'];
		$counter_fmt  = '' !== $settings['counter_format'] ? $settings['counter_format'] : '{current} / {total}';

		$show_header = 'yes' === $settings['show_header'];
		$has_heading = $show_header && '' !== $settings['heading_text'];
		$heading_tag = Utils::validate_html_tag( $settings['heading_tag'] );
		$title_tag   = Utils::validate_html_tag( $settings['panel_title_tag'] );

		$config = array(
			'start'        => $start,
			'autoplay'     => $autoplay,
			'speed'        => max( 1500, (int) $settings['autoplay_speed'] ),
			'pauseHover'   => 'yes' === $settings['pause_hover'],
			'keyboard'     => 'yes' === $settings['keyboard'],
			'animate'      => 'yes' === $settings['animate_panel'],
			'tabletBp'     => max( 320, (int) $settings['tablet_breakpoint'] ),
			'mobileBp'     => max( 320, (int) $settings['mobile_breakpoint'] ),
			'collapsible'  => 'yes' === $settings['mobile_collapsible'],
			'mobileScroll' => 'yes' === $settings['mobile_scroll'],
		);

		$classes = array( 'vtws' );
		if ( 'yes' === $settings['auto_dark'] ) {
			$classes[] = 'vtws--auto-dark';
		}

		$wrap_attributes = array(
			'class'     => $classes,
			'id'        => $uid,
			'data-vtws' => wp_json_encode( $config ),
		);

		if ( $has_heading ) {
			$wrap_attributes['aria-labelledby'] = $uid . '-title';
		} else {
			$wrap_attributes['aria-label'] = __( 'Vertical timeline', 'simple-vertical-timeline-with-slider-for-elementor' );
		}

		$this->add_render_attribute( 'wrap', $wrap_attributes );
		?>
		<section <?php $this->print_render_attribute_string( 'wrap' ); ?>>

			<?php if ( $show_header && ( '' !== $settings['eyebrow_text'] || '' !== $settings['heading_text'] ) ) : ?>
				<div class="vtws__head">
					<?php if ( '' !== $settings['eyebrow_text'] ) : ?>
						<span class="vtws__eyebrow"><?php echo esc_html( $settings['eyebrow_text'] ); ?></span>
					<?php endif; ?>
					<?php if ( '' !== $settings['heading_text'] ) : ?>
						<<?php echo esc_attr( $heading_tag ); ?> class="vtws__heading" id="<?php echo esc_attr( $uid ); ?>-title"><?php echo esc_html( $settings['heading_text'] ); ?></<?php echo esc_attr( $heading_tag ); ?>>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="vtws__layout">

				<div class="vtws__track">
					<?php foreach ( $items as $i => $item ) : ?>
						<?php
						$item_class = 'vtws__item' . ( $i < $start ? ' is-done' : '' );
						$node_class = 'vtws__node';
						if ( $i < $start ) {
							$node_class .= ' is-done';
						} elseif ( $i === $start ) {
							$node_class .= ' is-on';
						}
						?>
						<div class="<?php echo esc_attr( $item_class ); ?>" data-index="<?php echo esc_attr( $i ); ?>">
							<button type="button" class="<?php echo esc_attr( $node_class ); ?>" aria-controls="<?php echo esc_attr( $uid . '-panel-' . $i ); ?>" aria-expanded="<?php echo ( $i === $start ) ? 'true' : 'false'; ?>"<?php echo ( $i === $start ) ? ' aria-current="step"' : ''; ?>>
								<span class="vtws__dot"></span>
								<span class="vtws__text">
									<span class="vtws__yr"><?php echo esc_html( $item['year'] ); ?></span>
									<span class="vtws__nm"><?php echo esc_html( $item['label'] ); ?></span>
								</span>
							</button>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="vtws__panels">
					<?php foreach ( $items as $i => $item ) : ?>
						<?php
						$has_stat    = '' !== trim( (string) $item['stat_number'] );
						$panel_class = 'vtws__panel' . ( $has_stat ? '' : ' vtws__panel--no-stat' );
						$counter     = str_replace(
							array( '{current}', '{total}' ),
							array( (string) ( $i + 1 ), (string) $count ),
							$counter_fmt
						);
						?>
						<article class="<?php echo esc_attr( $panel_class ); ?>" id="<?php echo esc_attr( $uid . '-panel-' . $i ); ?>" data-index="<?php echo esc_attr( $i ); ?>" aria-live="<?php echo $autoplay ? 'off' : 'polite'; ?>"<?php echo ( $i !== $start ) ? ' hidden' : ''; ?>>

							<?php if ( $show_ghost ) : ?>
								<div class="vtws__ghost" aria-hidden="true"><?php echo esc_html( $item['year'] ); ?></div>
							<?php endif; ?>

							<div class="vtws__body">
								<?php if ( '' !== $item['chip'] ) : ?>
									<span class="vtws__chip"><?php echo esc_html( $item['chip'] ); ?></span>
								<?php endif; ?>
								<?php if ( '' !== $item['title'] ) : ?>
									<<?php echo esc_attr( $title_tag ); ?> class="vtws__ttl"><?php echo esc_html( $item['title'] ); ?></<?php echo esc_attr( $title_tag ); ?>>
								<?php endif; ?>
								<?php if ( '' !== $item['description'] ) : ?>
									<div class="vtws__txt"><?php echo wp_kses_post( $item['description'] ); ?></div>
								<?php endif; ?>
							</div>

							<?php if ( $has_stat ) : ?>
								<div class="vtws__stat">
									<b class="vtws__stat-num"><?php echo esc_html( $item['stat_number'] ); ?></b>
									<span class="vtws__stat-lbl"><?php echo esc_html( $item['stat_label'] ); ?></span>
								</div>
							<?php endif; ?>

							<?php if ( $show_foot ) : ?>
								<div class="vtws__foot">
									<?php if ( $show_counter ) : ?>
										<span class="vtws__count"><?php echo esc_html( $counter ); ?></span>
									<?php endif; ?>
									<?php if ( $show_nav ) : ?>
										<div class="vtws__nav">
											<button type="button" class="vtws__btn vtws__prev" aria-label="<?php echo esc_attr( $settings['prev_label'] ); ?>"<?php echo ( 0 === $i ) ? ' disabled' : ''; ?>>
												<?php $this->render_arrow( 'prev' ); ?>
											</button>
											<button type="button" class="vtws__btn vtws__next" aria-label="<?php echo esc_attr( $settings['next_label'] ); ?>"<?php echo ( $i === $count - 1 ) ? ' disabled' : ''; ?>>
												<?php $this->render_arrow( 'next' ); ?>
											</button>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>

			</div>
		</section>
		<?php
	}
}
