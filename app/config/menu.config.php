<?php


// Menus de navigation
register_nav_menus(array(
	'header-nav' => 'Header navigation',
	'login-nav' => 'navigation',
	'profil-nav' => 'Menu du profil'
));


if ( ! class_exists( 'Menu_Item_Custom_Fields' ) ) :
	/**
	 * Menu Item Custom Fields Loader
	 */
	class Menu_Item_Custom_Fields {
		/**
		 * Add filter
		 *
		 * @wp_hook action wp_loaded
		 */
		public static function load() {
			add_filter( 'wp_edit_nav_menu_walker', array( __CLASS__, '_filter_walker' ), 99 );
		}
		/**
		 * Replace default menu editor walker with ours
		 *
		 * We don't actually replace the default walker. We're still using it and
		 * only injecting some HTMLs.
		 *
		 * @since   0.1.0
		 * @access  private
		 * @wp_hook filter wp_edit_nav_menu_walker
		 * @param   string $walker Walker class name
		 * @return  string Walker class name
		 */
		public static function _filter_walker( $walker ) {
			$walker = 'Menu_Item_Custom_Fields_Walker';
			if ( ! class_exists( $walker ) ) {
				require_once dirname( __FILE__ ) . '/walker-nav-menu-edit.php';
			}
			return $walker;
		}
	}
	add_action( 'wp_loaded', array( 'Menu_Item_Custom_Fields', 'load' ), 9 );
endif;




//
//class CSS_Menu_Maker_Walker_Mobile extends Walker_Nav_Menu {
//
//	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
//
//		$id_field = $this->db_fields['id'];
//
//		if ( isset( $args[0] ) && is_object( $args[0] ) )
//		{
//			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
//
//		}
//		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
//	}
//	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
//
//		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
//
//		$class_names = $value = '';
//
//		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
//		$classes[] = 'menu-item-' . $item->ID;
//
//		// permet d'activer automatiquement le menu courant et ses sous caterorie correspondant
//		if($item->type == "taxonomy"){
//			$cat = get_the_category()[0];
//			if($cat->category_parent == $item->object_id){
//				$classes[] = 'uk-active';
//				unset($classes['current-menu-item']);
//			}
//		}
//
//		/* Add active class */
//		if(in_array('current-menu-item', $classes)) {
//			$classes[] = 'uk-active';
//			unset($classes['current-menu-item']);
//		}
//
//		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
//		$class_names = $class_names ? ' class="uk-menu' . esc_attr( $class_names ) . '"' : '';
//
//		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
//		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
//
//		$output .= $indent . '<li' . $id . $value . $class_names .'>';
//
//
//		$atts = array();
//		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
//		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
//		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
//		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
//
//		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
//
//		$attributes = '';
//		foreach ( $atts as $attr => $value ) {
//			if ( ! empty( $value ) ) {
//				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
//				$attributes .= ' ' . $attr . '="' . $value . '"';
//			}
//		}
//
//
//		$item_output = '<a' . $attributes . '>';//
//		$item_output .= apply_filters('the_title', $item->title, $item->ID);
//		$item_output .= '</a>';
//		$item_output .= '</li>';
//
//
//		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
//
//
//
//	}
//
//
//	function start_lvl( &$output, $depth = 0, $args = array() ) {
//
//		$indent = str_repeat("\t", $depth);
//		$output .= "\n$indent\n";
//	}
//
//	public function end_lvl( &$output, $depth = 0, $args = array() ) {
//
//		$indent = str_repeat("\t", $depth);
//		$output .= "\n$indent</ul>";
//
//
//	}
//
//}
//
