<?php
/**
 * t2018 functions and definitions
 * t2018 функции и определения
 *
 * @link       https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package    WordPress
 * @subpackage t2018
 * @since 1.0
 * @version 1.0 */

if ( ! function_exists( 't2018_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function t2018_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on t2018, use a find and replace
		 * to change 't2018' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 't2018', get_stylesheet_directory() . '/languages' );
		
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'mainmenu' => esc_html__( 'Primary', 't2018' ),
	) );
		
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		
		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 't2018_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
		
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
		
		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 't2018_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function t2018_content_width() {
	$GLOBALS['content_width'] = apply_filters( 't2018_content_width', 640 );
}

add_action( 'after_setup_theme', 't2018_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function t2018_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 't2018' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 't2018' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', 't2018_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function t2018_scripts() {
	wp_enqueue_style( 't2018-style', get_stylesheet_uri() );
	wp_enqueue_style( 't2018', get_stylesheet_directory_uri().'/less/t2018.css' );
	
	wp_enqueue_script( 't2018-navigation', get_stylesheet_directory_uri() . '/js/navigation.js', array(), '20180107', true );
	
	wp_enqueue_script( 't2018-skip-link-focus-fix', get_stylesheet_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20180107', true );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 't2018_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_stylesheet_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_stylesheet_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_stylesheet_directory() . '/inc/template-functions.php';

/**
 * SVG icons functions and filters.
 */
require get_stylesheet_directory() . '/inc/icon-functions.php';


/**
 * Customizer additions.
 */
require get_stylesheet_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_stylesheet_directory() . '/inc/jetpack.php';
}


/*--------------------------------------------*/
define('DISABLE_WP_CRON', true);

add_shortcode('members_only', 'members_only_shortcode');

function members_only_shortcode($atts, $content=null){
	if (is_user_logged_in() && ! empty($content) && ! is_feed()){
		return $content;
	}
	
	return 'Текст для зарегистрированых и авторизованых.';
}

remove_action('wp_head', 'wp_generator');


function my_theme_add_editor_styles(){
	add_editor_style('/editor-styles.css');
}

add_action('current_screen', 'my_theme_add_editor_styles');


//=============================== php ===============================
/* php в постах или страницах WordPress: [exec]код[/exec]
----------------------------------------------------------------- */
function exec_php($matches){
	$inline_execute_output='';
	eval('ob_start();'.$matches[1].'$inline_execute_output = ob_get_contents();ob_end_clean();');
	
	return $inline_execute_output;
}

/**Создать перелинковку текста
 *
 * @param $content
 *
 * @return mixed
 */
function inline_php($content){
	$content=preg_replace_callback('/\[exec\]((.|\n)*?)\[\/exec\]/', 'exec_php', $content);
	$content=preg_replace('/\[exec off\]((.|\n)*?)\[\/exec\]/', '$1', $content);
	
	return $content;
}

add_filter('the_content', 'inline_php', 0);
//убираем ненужные авто кусочки
remove_filter('get_the_excerpt', 'wp_trim_excerpt');

/*--------------------------------------------*/
/**
 *
 * Загрузить содержимое папки commons (аналог mu-plugins).
 */
$dir = get_stylesheet_directory() .'/commons/';
//пропускаем точки
$files = scandir($dir);
include_once $dir .'.servis.php';
foreach($files as $file) {
	if($file[0] != '.')
		include_once $dir .$file;
}
/*--------------------------------------------*/


/** Автоматическая микроразметка в комментариях
 * @link https://1zaicev.ru/avtomaticheskaya-mikrorazmetka-kommentariev-v-wordpress/
 * @param $comment
 * @param $args
 * @param $depth
 */
function micro_comment($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
	?>
	<<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
	<?php endif; ?>
	<div class="comment-author">
		<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	</div>
	<div >
		<?php printf( __('<div itemprop="creator">%s:</div>' ), get_comment_author_link() ); ?>
		<?php if ( '0' == $comment->comment_approved ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em>
			<br />
		<?php endif; ?>
		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
<span itemprop="datePublished" style="display: none"><?php
	/* translators: 1: date, 2: time */
	printf( __( '%1$s' ), get_comment_date('Y-m-d')); ?></span><?=get_comment_date();?> в <?php echo (get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '&nbsp;&nbsp;', '' );
			?>
		</div>
		<span itemprop="text"><?php comment_text( get_comment_id(), array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
		</div>
	<?php endif; ?>
	<?php
}

function input_link(){
	?>
  <form action="" name="inplink" method="post">
    <label for="name">
      <p style="margin-left: 30px;">URL:
        <input name="myurl" type="text" placeholder="Вставь сюда URL" style="width: 70%">
        <input name="sendurl" type="submit" value="Отправить">
        <br>
        Статус:
        <input name="mystatus" type="radio" value="draft">
        <input name="mystatus" type="radio" value="publish" checked>
        <br>
        Оценка:
        <input name="mymark" type="radio" value="1">
        <input name="mymark" type="radio" value="2">
        <input name="mymark" type="radio" value="3">
        <input name="mymark" type="radio" value="4">
        <input name="mymark" type="radio" value="5">
        <br>
      
      </p>
    </label>
  </form>
	<?php
	global $link,$post;
	if(isset($_POST['sendurl'])){
		$myurl=$_POST['myurl'];
//	  echoln("$myurl",'$myurl');
		
		$html = @file_get_contents($myurl);
		$doc = new DOMDocument();
		@$doc->loadHTML($html);
//	  echoln($doc->encoding);
		
		$title=preg_match( "|<title>([^<]+)</title>|is", $html, $matches )
			? $matches[1] : '';
		$desc=preg_match( "|<meta name=\"description\" content=\"([^<]+)\" />|is",
			$html, $matches ) ? $matches[1] : '';
		
		$mydata=array();
		foreach( $doc->getElementsByTagName('meta') as $meta ) {
			if($meta->getAttribute('name')=='generator')
				$mydata['generator']=$meta->getAttribute('content');
			if($meta->getAttribute('property')=='og:title')
				$mydata['og:title']=$meta->getAttribute('content');
			if($meta->getAttribute('property')=='og:description')
				$mydata['og:description']=$meta->getAttribute('content');
			if($meta->getAttribute('property')=='og:image')
				$mydata['og:image']=$meta->getAttribute('content');
			
		}
		$tags = get_meta_tags($myurl);
		if('windows-1251'==$doc->encoding){
			foreach ( $tags as $key=>$value ){
				$tags[$key]=iconv( "CP1251//IGNORE", "UTF-8", $value );
			}
			$title=iconv( "CP1251//IGNORE", "UTF-8", $title );
			$desc=iconv( "CP1251//IGNORE", "UTF-8", $desc );
		}
		$mydata=array_merge($tags,$mydata);
		
		
		if(!empty($mydata['twitter:title']))$mydata['title']=$mydata['twitter:title'];
        elseif(!empty($mydata['og:title']))$mydata['title']=$mydata['og:title'];
		else $mydata['title']=$title;
		
		if(!empty($mydata['twitter:description']))
			$mydata['description']=$mydata['twitter:description'];
        elseif(empty($mydata['description']))$mydata['description']=$desc;
//    else $mydata['description']=$desc;
//	  $mydata['description']=$desc;
		
		$ctid=array();
		$cat=get_the_terms($post->ID,'link_tax');
		foreach ( $cat as $item ){
			$ctid[]=$item->term_id;
		}
		// Создаем массив
		$post_data=array(
			'post_title'  =>wp_strip_all_tags( $mydata['title'] ),
			'post_content'=>wp_strip_all_tags( $mydata['description'] ),
			'post_type'   => 'links',
			'post_status' =>$_POST['mystatus'],
			'tags_input'  =>array(
				'link_tags'=> explode(',',$mydata['keywords'] )),// Метки поста(указываем ярлыки).
			'meta_input'  =>array(
				'gr0_linkref'=>$_POST['myurl'],
				'gr0_mark'=>$_POST['mymark'],
			),  // добавит указанные мета поля. По умолчанию: ''. с версии 4.4.
			'tax_input' => array( 'link_tax'=> $ctid ),
			'post_author'=>1,
//		  'post_category' => array(8,39)
		);

// Вставляем данные в БД
		$post_id = wp_insert_post( wp_slash($post_data) );
	  wp_set_post_terms( $post_id, $mydata['keywords'], 'link_tags');
		echoln($mydata['keywords'],'keywords');
		dump( $post_data ,'$post_data');
//	  dump($cat,'$cat');
	}
}

function cat_list(){
	$args = array(
		'orderby'      => 'name',  // сортируем по названиям
	  'hide_empty'   => 0,       // не прячем пустые группы
		'show_count'   => 1,       // показываем количество записей
		'pad_counts'   => 1,       // показываем количество записей у родителей
		'hierarchical' => 1,       // древовидное представление
		'taxonomy' => 'link_tax',  // какая категория
		'title_li'     => ''       // список без заголовка
	);
	echo '<ul>';
	wp_list_categories( $args );
	echo '</ul>';
}

function google_font_styles() {
	if (!is_admin()) {
		wp_register_style('googlefont',
        'http://fonts.googleapis.com/css?family=family=Istok+Web:400,400i,700,700i|Open+Sans:400,700&amp;subset=cyrillic,cyrillic-ext,latin');
		wp_enqueue_style('font', get_stylesheet_uri(), array('googlefont') );
	}
}
add_action('wp_enqueue_scripts', 'google_font_styles');



