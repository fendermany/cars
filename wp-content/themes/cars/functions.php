<?php

/**
 * cars functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cars
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

if (!function_exists('cars_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function cars_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on cars, use a find and replace
		 * to change 'cars' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('cars', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		add_theme_support('menus');

		add_filter('nav_menu_link_attributes', 'filter_nav_menu_link_attributes', 10, 3);

		function filter_nav_menu_link_attributes($atts, $item, $args)
		{
			if ($args->theme_location === 'header') {
				$atts['class'] = 'header__menu-link';

				if ($item->current) {
					$atts['class'] = 'header__menu-link_active';
				}
			};
			if ($args->theme_location === 'left') {
				$atts['class'] = 'leftmenu__link';

				if ($item->current) {
					$atts['class'] = 'active';
				}
			};
			if ($args->theme_location === 'bottom') {
				$atts['class'] = 'footer__link';

				if ($item->current) {
					$atts['class'] = 'footer__link_active';
				}
			};
			return $atts;
		}

		add_filter('nav_menu_css_class', 'change_menu_item_css_classes', 10, 4);

		function change_menu_item_css_classes($classes, $item, $args, $depth)
		{
			if ($args->theme_location === 'header') {
				$classes[] = 'header__menu-item';

				if ($item->current) {
					$classes[] = 'header__menu-item_active';
				}
			};
			if ($args->theme_location === 'left') {
				$classes[] = 'leftmenu__item';

				if ($item->current) {
					$classes[] = 'leftmenu__item_active';
				}
			};
			if ($args->theme_location === 'left') {
				$classes[] = 'footer__item';

				if ($item->current) {
					$classes[] = 'footer__item_active';
				}
			};

			return $classes;
		}



		add_action('after_setup_theme', 'theme_register_nav_menu');
		function theme_register_nav_menu()
		{
			register_nav_menu('header', 'Header Menu');
			register_nav_menu('left', 'Left Menu');
			register_nav_menu('bottom', 'Bottom Menu');
		}

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'cars_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action('after_setup_theme', 'cars_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cars_content_width()
{
	$GLOBALS['content_width'] = apply_filters('cars_content_width', 640);
}
add_action('after_setup_theme', 'cars_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cars_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'cars'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'cars'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'cars_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function cars_scripts()
{
	wp_enqueue_style('cars-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('cars-style', 'rtl', 'replace');
	wp_enqueue_style('editor-style', get_template_directory_uri() . '/assets/css/editor-style.css');
	wp_enqueue_script('cars-scripts-vendors', get_template_directory_uri() . '/assets/js/vendors.min.js', array(), null, true);
	wp_enqueue_script('cars-scripts-app', get_template_directory_uri() . '/assets/js/app.min.js', array(), null, true);
	wp_enqueue_script('ajax-search-app', get_template_directory_uri() . '/assets/js/ajax-search.js', array('jquery'), null, true);
	wp_deregister_script('jquery');
	wp_register_script('jquery', get_template_directory_uri() . '/assets/js/jquery-3.6.0.min.js', array(), null, true);
	wp_enqueue_script('jquery');

	wp_enqueue_script('cars-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'cars_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}



// SVG

add_filter('upload_mimes', 'svg_upload_allow');

# Добавляет SVG в список разрешенных для загрузки файлов.
function svg_upload_allow($mimes)
{
	$mimes['svg']  = 'image/svg+xml';

	return $mimes;
}

add_filter('wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5);

# Исправление MIME типа для SVG файлов.
function fix_svg_mime_type($data, $file, $filename, $mimes, $real_mime = '')
{

	// WP 5.1 +
	if (version_compare($GLOBALS['wp_version'], '5.1.0', '>='))
		$dosvg = in_array($real_mime, ['image/svg', 'image/svg+xml']);
	else
		$dosvg = ('.svg' === strtolower(substr($filename, -4)));

	// mime тип был обнулен, поправим его
	// а также проверим право пользователя
	if ($dosvg) {

		// разрешим
		if (current_user_can('manage_options')) {

			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		// запретим
		else {
			$data['ext'] = $type_and_ext['type'] = false;
		}
	}

	return $data;
}

add_filter('wp_prepare_attachment_for_js', 'show_svg_in_media_library');

# Формирует данные для отображения SVG как изображения в медиабиблиотеке.
function show_svg_in_media_library($response)
{

	if ($response['mime'] === 'image/svg+xml') {

		// С выводом названия файла
		$response['image'] = [
			'src' => $response['url'],
		];
	}

	return $response;
}


// Хлебные крошки

// function get_breadcrumb()
// {
// 	echo '<a href="' . home_url() . '" rel="nofollow" class="breadcrumbs__link breadcrumbs__item">Главная</a>';
// 	if (is_category() || is_single()) {
// 		echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
// 		the_category(' &bull; ');
// 		if (is_single()) {
// 			echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
// 			the_title();
// 		}
// 	} elseif (is_page()) {
// 		echo the_title('<span class="breadcrumbs__title breadcrumbs__item">', '</span>', true);
// 	} elseif (is_search()) {
// 		echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Search Results for... ";
// 		echo '"<em>';
// 		echo the_search_query();
// 		echo '</em>"';
// 	}
// }


//remove body class

add_filter('body_class', function (array $classes) {
	if (in_array('page', $classes)) {
		unset($classes[array_search('page', $classes)]);
	}
	return $classes;
});

// Widgets PHP

function php_in_widgets($widget_content)
{
	if (strpos($widget_content, '<' . '?') !== false) {
		ob_start();
		eval('?' . '>' . $widget_content);
		$widget_content = ob_get_contents();
		ob_end_clean();
	}
	return $widget_content;
}

add_filter('widget_text', 'php_in_widgets', 99);

// Enable shortcodes in text widgets
add_filter('widget_text', 'do_shortcode');


// Регистрация типа записи

add_action('init', 'true_register_post_type_init'); // Использовать функцию только внутри хука init

function true_register_post_type_init()
{
	$labels = array(
		'name' => 'Все товары/услуги',
		'singular_name' => 'Товар/услугу', // админ панель Добавить->Функцию
		'add_new' => 'Добавить товар/услугу',
		'add_new_item' => 'Добавить новый товар/услугу', // заголовок тега <title>
		'edit_item' => 'Редактировать товар/услугу',
		'new_item' => 'Новый товар/услуга',
		'all_items' => 'Все товары/услуги',
		'view_item' => 'Просмотр товара/услуги на сайте',
		'search_items' => 'Искать товары/услуги',
		'not_found' =>  'Товаров/услуг не найдено.',
		'not_found_in_trash' => 'В корзине нет товаров/услуг.',
		'menu_name' => 'Товары и услуги' // ссылка в меню в админке
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true, // показывать интерфейс в админке
		'has_archive' => true,
		'menu_icon' => 'dashicons-dashboard', // иконка в меню
		'menu_position' => 20, // порядок в меню
		'supports' => array('title', 'editor', 'comments', 'author', 'thumbnail', 'custom-fields', 'page-attributes', 'excerpt'),
		'show_in_rest' => true,
		'exclude_from_search' => false,
	);
	register_post_type('autoproducts', $args);
}

// Регистрация таксономий

add_action('init', 'true_register_taxonomy', 0);

function true_register_taxonomy()
{
	register_taxonomy('transport_type', array('autoproducts'), array(
		'label' => _x('Разделы', 'taxonomy general name'),
		'labels' => array(
			'name' => _x('Разделы', 'taxonomy general name'),
			'singular_name'            => 'Раздел', // название единичного элемента таксономии
			'menu_name'                => 'Разделы', // Название в меню. По умолчанию: name.
			'all_items'                => 'Все разделы',
			'edit_item'                => 'Изменить раздел',
			'view_item'                => 'Просмотр раздела', // текст кнопки просмотра записи на сайте (если поддерживается типом)
			'update_item'              => 'Обновить раздел',
			'add_new_item'             => 'Добавить новый раздел',
			'new_item_name'            => 'Название нового раздела',
			'search_items'             => 'Искать разделы',
			'popular_items'            => 'Популярные разделы', // для таксономий без иерархий
			'separate_items_with_commas' => 'Разделяйте разделы запятыми',
			'add_or_remove_items'      => 'Добавить или удалить раздел',
			'choose_from_most_used'    => 'Выбрать из часто используемых разделов',
			'not_found'                => 'Разделов не найдено',
			'back_to_items'            => '← Назад к разделам',
		),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => true,
		'show_in_quick_edit' => true,
		'show_in_rest' => true,
		'meta_box_cb' => null,
		'show_admin_column' => true,
		'description' => '',
		'hierarchical' => true,
		'update_count_callback' => '',
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'transport',
			'with_front' => true,
			'hierarchical' => true,
			'ep_mask' => EP_NONE,
		),
		'sort' => null,
		'_builtin' => false,
	));

	register_taxonomy('transport_tags', array('autoproducts'), array(
		'label' => _x('Метки облака', 'taxonomy general name'),
		'labels' => array(
			'name' => _x('Метки облака', 'taxonomy general name'),
			'singular_name'            => 'Метка', // название единичного элемента таксономии
			'menu_name'                => 'Метки', // Название в меню. По умолчанию: name.
			'all_items'                => 'Все метки',
			'edit_item'                => 'Изменить метку',
			'view_item'                => 'Просмотр меток', // текст кнопки просмотра записи на сайте (если поддерживается типом)
			'update_item'              => 'Обновить метку',
			'add_new_item'             => 'Добавить новую метку',
			'new_item_name'            => 'Название новой метки',
			'search_items'             => 'Искать метки',
			'popular_items'            => 'Популярные метки', // для таксономий без иерархий
			'separate_items_with_commas' => 'Разделяйте метки запятыми',
			'add_or_remove_items'      => 'Добавить или удалить метку',
			'choose_from_most_used'    => 'Выбрать из часто используемых меток',
			'not_found'                => 'Меток не найдено',
			'back_to_items'            => '← Назад к меткам',
		),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'show_tagcloud' => true,
		'show_in_quick_edit' => true,
		'show_in_rest' => true,
		'meta_box_cb' => null,
		'show_admin_column' => true,
		'description' => '',
		'hierarchical' => false,
		'update_count_callback' => '',
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'autotags',
			'with_front' => true,
			'hierarchical' => true,
			'ep_mask' => EP_NONE,
		),
		'sort' => null,
		'_builtin' => false,
	));
}

/*
ajax поиск по сайту  */
add_action('wp_ajax_nopriv_ajax_search', 'ajax_search');
add_action('wp_ajax_ajax_search', 'ajax_search');
function ajax_search()
{
	$args = array(
		'post_type'      => array('autoproducts', 'post'), // Тип записи: post, page, кастомный тип записи 
		'post_status'    => 'publish',
		'order'          => 'DESC',
		'orderby'        => 'date',
		's'              => $_POST['term'],
		'posts_per_page' => -1
	);
	$query = new WP_Query($args);
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post(); ?>
			<li class="ajax-search__item">
				<?php if (has_post_thumbnail()) { ?>
					<div class="ajax-search__img"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></div>
				<?php } ?>
				<a href="<?php the_permalink(); ?>" class="ajax-search__link"><?php the_title(); ?></a>
				<div class="ajax-search__excerpt"><?php the_excerpt(); ?></div>
			</li>
		<?php }
	} else { ?>
		<li class="ajax-search__item">
			<div class="ajax-search__not-found">Ничего не найдено</div>
		</li>
<?php }
	exit;
}



add_filter('pre_get_posts', 'include_search_filter');
function include_search_filter($query)
{
	if (!is_admin() && $query->is_main_query() && $query->is_search) {
		$query->set('post_type', array('post', 'autoproducts'));
	}
	return $query;
}

add_filter( 'posts_results', 'cody_search_cir_lat', 10, 2 );
function cody_search_cir_lat( $posts, $query ) {
	
	if ( is_admin() || !$query->is_search ) return $posts;
	
	global $wp_query;

	if ( $wp_query->found_posts == 0 ) {
		
		// замена латиницы на кириллицу
		$letters = array( 'f' => 'а', ',' => 'б', 'd' => 'в', 'u' => 'г', 'l' => 'д', 't' => 'е', '`' => 'ё', ';' => 'ж', 'p' => 'з', 'b' => 'и', 'q' => 'й', 'r' => 'к', 'k' => 'л', 'v' => 'м', 'y' => 'н', 'j' => 'о', 'g' => 'п', 'h' => 'р', 'c' => 'с', 'n' => 'т', 'e' => 'у', 'a' => 'ф', '[' => 'х', 'w' => 'ц', 'x' => 'ч', 'i' => 'ш', 'o' => 'щ', ']' => 'ъ', 's' => 'ы', 'm' => 'ь', '\'' => 'э', '.' => 'ю', 'z' => 'я' );
		
		$cir = array( 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я' );
		$lat = array( 'f', ',', 'd', 'u', 'l', 't', '`', ';', 'p', 'b', 'q', 'r', 'k', 'v', 'y', 'j', 'g', 'h', 'c', 'n', 'e', 'a', '[', 'w', 'x', 'i', 'o', ']', 's', 'm', '\'', '.', 'z', 'F', ',', 'D', 'U', 'L', 'T', '`', ';', 'P', 'B', 'Q', 'R', 'K', 'V', 'Y', 'J', 'G', 'H', 'C', 'N', 'E', 'A', '[', 'W', 'X', 'I', 'O', ']', 'S', 'M', '\'', '.', 'Z' );
		$new_search = str_replace( $lat, $cir, $wp_query->query_vars['s'] );
		
		// производим выборку из базы данных
		global $wpdb;
		$request = $wpdb->get_results( str_replace( $wp_query->query_vars['s'], $new_search, $query->request ) );
		
		if ( $request ) {
			$new_posts = array();
			foreach ( $request as $post ) {
				$new_posts[] = get_post( $post->ID );
			}
			if ( count( $new_posts ) > 0 ) {
				$posts = $new_posts;
			} 
		}
	}
	// возвращаем массив найденных постов
	return $posts;
}


// Облако меток

function true_no_callback_count($real_count)
{
	return;
	// return $real_count - отобразить в подсказках количество постов
}

// Изменение длины отрывка

add_filter('excerpt_length', function () {
	return 20;
});

// Хлебные крошки

function adjust_single_breadcrumb( $link_output) {
	if( is_single() ) {
		 if(strpos( $link_output, 'breadcrumb_last' ) !== false )  {
		 $link_output = '';
		 }
	}
		return $link_output;
	}
	add_filter('wpseo_breadcrumb_single_link', 'adjust_single_breadcrumb' );
