<?php
  // 翻訳ファイル読み込み
  function hamburger_theme_setup() {
    load_theme_textdomain('hamburger', get_template_directory().'/languages');
  }
  add_action('after_setup_theme', 'hamburger_theme_setup');

  // テーマサポート
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('automatic-feed-links');
  add_theme_support('custom-header');
  add_theme_support('wp-block-styles');
  add_theme_support('responsive-embeds');
  add_theme_support('html5', array('comment-form', 'comment-list'));
  add_theme_support('custom-logo');
  add_theme_support('align-wide');
  add_theme_support('custom-background');


  // メニュー登録
  function my_menus() {
    register_nav_menus( array(
      'sidebar_menu' => 'Sidebar Menu',
      'footer_menu' => 'Footer Menu',
    ));
  }
  add_action('after_setup_theme', 'my_menus');

  // タイトル出力
  function hamburger_title($title) {
    if ( is_front_page() && is_home()) {
      $title = get_bloginfo('name', 'display');
    } elseif ( is_singular()) {
      $title = single_post_title( get_bloginfo('name', 'display').' | ', false);
    }
    return $title;
  }
  add_filter('pre_get_document_title', 'hamburger_title');


  function hamburger_script() {
    // css読み込み
    wp_enqueue_style('reset', get_template_directory_uri().'/css/ress.css',array());
    wp_enqueue_style('my-style', get_template_directory_uri().'/css/style.css',array('reset'));
    // googlefonts
    wp_enqueue_style('m-plus1p', 'https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@400;700&display=swap',array());
    wp_enqueue_style('roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap',array());
    //adobefonts
    wp_enqueue_script('m-plus1m', get_theme_file_uri('/js/font.js'), array());
    // jQuery読み込み
    wp_enqueue_script('jquery');
    // js読み込み
    wp_enqueue_script('my-script', get_template_directory_uri().'/js/main.js', array('jquery'), false, true);
  }
  add_action('wp_enqueue_scripts', 'hamburger_script');

  // ウィジェット追加
  function hamburger_widgets_init() {
    register_sidebar (
      array (
        'name' => 'メニューウィジェット',
        'id' => 'menu_widget',
        'description' => 'メニュー用ウィジェットです',
        'class' => 'p-menuList',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title' => '</h2>',
      )
    );
  }
  add_action('widgets_init', 'hamburger_widgets_init');

  // 小見出しを取得
  function get_post_subtitle() {
    global $post;
    // 投稿文を取得
    $content = apply_filters('the_content', $post -> post_content);
    // 本文からh2見出しを抽出
    $pattern = '/<h2[^>]*>(.*?)<\/h2>/';
    preg_match($pattern, $content, $matches);
    // 最初のh2タグのタイトルを返す
    if (!empty($matches[0])) {
      return strip_tags($matches[0]); // タイトルを返す
    } else {
      return ''; // h2タグが見つからない場合は空文字を返す
    }
  }

  // 投稿のアーカイブを古い順に変更
  function custom_archive_order($query) {
    if ( is_admin() || ! $query -> is_main_query()) {
      return;
    }
    if ( is_archive()) {
      $query -> set('post_type', 'post');
      $query -> set('orderby', 'date');
      $query -> set('order', 'ASC');
    }
  }
  add_action('pre_get_posts', 'custom_archive_order');

  // editor-style追加
  function hamburger_theme_add_editor_styles() {
    add_theme_support('editor-styles');
    add_editor_style(array("/css/editor-style.css"));
  }
  add_action('after_setup_theme', 'hamburger_theme_add_editor_styles');

  // ブロックスタイル追加
  register_block_style( 'core/heading',
    array(
      'name' => 'heading',
      'label' => '縦ライン',
      'inline_style' => '.is-style-heading {
        padding-left: 8px;
        border-left: 4px solid #4A6C9B;
      }'
    )
  );

  // ブロックパターン追加
  function my_block_pattern() {
    register_block_pattern ('my-plugin/my-custom-pattern',
    array(
      'title' => 'grid gallery',
      'content' => '<div class="p-article__gallery"><img src="' . get_theme_file_uri('/images/single_article.jpg') . '" alt="ハンバーガーセット"><img src="' . get_theme_file_uri('/images/single_article.jpg') . '" alt="ハンバーガーセット"><img src="' . get_theme_file_uri('/images/single_article.jpg') . '" alt="ハンバーガーセット"><img src="' . get_theme_file_uri('/images/single_article.jpg') . '" alt="ハンバーガーセット"><img src="' . get_theme_file_uri('/images/single_article.jpg') . '" alt="ハンバーガーセット"><img src="' . get_theme_file_uri('/images/single_article.jpg') . '" alt="ハンバーガーセット"><img src="' . get_theme_file_uri('/images/single_article.jpg') . '" alt="ハンバーガーセット"><img src="' . get_theme_file_uri('/images/single_article.jpg') . '" alt="ハンバーガーセット"><img src="' . get_theme_file_uri('/images/single_article.jpg') . '" alt="ハンバーガーセット"></div>'
    ));
  }
  add_action( 'init', 'my_block_pattern' );


