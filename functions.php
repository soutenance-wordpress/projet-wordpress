<?php
//  Css and Javascript load --------------------------------------------
function startwordpress_scripts() {
    wp_enqueue_style( 'css', get_template_directory_uri() . '/style.css' );

    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js', array());
} add_action( 'wp_enqueue_scripts', 'startwordpress_scripts' );


// Autoriser les miniatures
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );


//Création zones de menus
add_action('init', 'theme_menus');
function theme_menus() {
    register_nav_menu('main_menu', 'Menu Principal');
    register_nav_menu('footer_menu', 'Menu du pied de page');
}

//Création des zones de widgets
add_action('widgets_init', 'theme_widgets_zones');
function theme_widgets_zones() {
    register_sidebar();
    register_sidebar(array(
        'id' => 'footer_widgets',
        'name' => 'Pied de page',
        'description' => 'Ces widgets vont dans le pied de page'
    ));
}

// Déclare le widget
class CustomWidget extends WP_Widget {

    public function __construct() {

        parent::__construct(false, "Widget Custom Link");
        $options = array(
            'classname' => 'custom-link-widget',
            'description' => 'Mon Widget Perso :) !'
        );
        $this->WP_Widget('custom-link-widget', 'Widget Custom Link', $options);

    }

    // Méthode d'affichage en front
    public function widget($args, $d) {
        echo "Proc";
        echo '<a href="'.$d['url'].'">' .$d['name'].'</a>';
    }

    public function form($d) {

        $default = array(
            'name' => 'Google',
            'url' => 'http://google.com'
        );
        $d = wp_parse_args($d, $default);
        
        echo '
        <p>
            <label for="'.$this->get_field_name('name').'"> Texte du lien <label/>
            <input id="'.$this->get_field_id('name').'" name="'.$this->get_field_id('name').'" value="'.$d['name'].'" type="text" style="margin-left: 5px;" />
        </p> 
        
        <p>
            <label for="'.$this->get_field_name('url').'"> URL du lien <label/>
            <input id="'.$this->get_field_id('url').'" name="'.$this->get_field_id('url').'" value="'.$d['url'].'" type="text" style="margin-left: 5px;" />
        </p> 
        '; 

    }

    public function update($new, $old) {
        return $new;
    }
    
} add_action( 'widgets_init', function(){
    register_widget( 'CustomWidget' );
});


//Créer un shortcode [hello]
add_shortcode('hello', 'hello_function');
function hello_function() {
    return "<label> Hello </label>";
}

// Création d'un nouveau type de contenu
function register_post_type_function() {
    register_post_type(
        'News',
        array(
            'label' => 'News',
            'labels' => array(
                'name' => 'News',
                'singular_name' => 'News',
                'all_items' => 'Tous les News',
                'add_new_item' => 'Ajouter une News',
                'edit_item' => "Éditer la News",
                'new_item' => 'Nouvelle News',
                'view_item' => "Voir la News",
                'search_items' => 'Rechercher parmi les News',
                'not_found' => "Pas de News trouvées",
                'not_found_in_trash'=> "Pas de News dans la corbeille"
            ),
            'menu_position' => 26,
            'public' => true,
            'capability_type' => 'post',
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
            ),
            'has_archive' => true
        )
    );
} add_action('init', 'register_post_type_function');


// News - Metabox Résumé ------------------------------------------------
function init_newsSummary() {
    add_meta_box( 'NewsSummary', __( 'Description de la News', 'textdomain' ), 'display_newsSummary', 'news');
} add_action( 'add_meta_boxes', 'init_newsSummary' );


function display_newsSummary($post) {

    $dataRecup = get_post_meta($post->ID,'summary',true);
?>
<textarea style="width:100%;" id="summary" name="summary"><?php echo $dataRecup ?></textarea>
<br><label style="cursor: default;">Texte affiché dans la partie droite de la slide correspondante.</label>
<?php

}


function save_newsSummary( $post_id ) {
    if(isset($_POST['summary'])) {
        update_post_meta($post_id, 'summary', $_POST['summary']);
    }
}
add_action( 'save_post', 'save_newsSummary' );




function remove_titles_from_editor( $settings ) {

    global $post_type;

    // News settings
    if ( $post_type == 'news' ) {
        $settings['block_formats'] = 'Paragraph=p;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;';
    } 

    return $settings;

} add_filter( 'tiny_mce_before_init', 'remove_titles_from_editor' );


// Désactivation du menu "texte" de l'éditeur
function remove_textMode_from_editor($settings) {

    $settings['quicktags'] = false;
    return $settings;
} add_filter('wp_editor_settings', 'remove_textMode_from_editor');


// Editer le menu apparence=>customize  --------------------------------

function mytheme_customize_register($wp_customize) {

    // Retirer les menus inutiles du Customizer
    $wp_customize->remove_section( 'title_tagline');
    $wp_customize->remove_section( 'colors');
    $wp_customize->remove_section( 'background_image');
    $wp_customize->remove_section( 'static_front_page');
    $wp_customize->remove_section( 'custom_css');
    remove_action( 'customize_controls_enqueue_scripts', array( $wp_customize->nav_menus, 'enqueue_scripts' ) );
    remove_action( 'customize_register', array( $wp_customize->nav_menus, 'customize_register' ), 11 );
    remove_filter( 'customize_dynamic_setting_args', array( $wp_customize->nav_menus, 'filter_dynamic_setting_args' ) );
    remove_filter( 'customize_dynamic_setting_class', array( $wp_customize->nav_menus, 'filter_dynamic_setting_class' ) );
    remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->nav_menus, 'print_templates' ) );
    remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->nav_menus, 'available_items_template' ) );
    remove_action( 'customize_preview_init', array( $wp_customize->nav_menus, 'customize_preview_init' ) );


    // SECTION NEWS ----------------------------------------------------------------------------------------
    $wp_customize->add_section( 'newsSection' , array(
        'title'      => __( 'Section News', 'Ad4Games' ),
        'priority'   => 2,
    ) );

    $query = new WP_Query( array( 'post_type' => 'News' ) );

    if($query->have_posts()) {
        // On enregistre le nombre total de News
        $numberOfNews = $query->found_posts;    
    } else {
        $numberOfNews = 0;
    }

    if( (ctype_digit(get_theme_mod('newsSliderLimitNumber')) == '1') && (get_theme_mod('newsSliderLimitNumber') > 0) && ( get_theme_mod('newsSliderLimitNumber') < $numberOfNews) ) {
        $newsSliderLimitNumberDefaultValue = ltrim(get_theme_mod('newsSliderLimitNumber'), '0');
    } else {
        $newsSliderLimitNumberDefaultValue = 0;
    }

    $wp_customize->add_setting( 'newsSliderLimitNumber', array(
        'capability' => 'edit_theme_options',
        'default' => $newsSliderLimitNumberDefaultValue,
    ) );

    if($newsSliderLimitNumberDefaultValue == 0) {
        $newsSliderLimitNumberDefaultValue = $numberOfNews;
    }
    
    $wp_customize->add_control( 'newsSliderLimitNumber', array(
        'type' => 'text',
        'section' => 'newsSection',
        'label' => __( 'Nombre de slide maximum du slider' ),
        'description' => __( 'Nombre de news actuelle(s) : '.$newsSliderLimitNumberDefaultValue.'.' ),
        'input_attrs' => ['size' => 2, 'maxlength' => strlen($numberOfNews), 'style' => 'width:auto'],
    ) );
    
} add_action( 'customize_register', 'mytheme_customize_register' );
?>