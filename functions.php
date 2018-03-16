<?php


// Retirer la barre d'action Admin du FrontOffice
function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');


// Augmenter la taille d'upload vidéo :
@ini_set( 'upload_max_size' , '10M' );
@ini_set( 'post_max_size', '10M');
@ini_set( 'max_execution_time', '300' );


//  Css and Javascript load --------------------------------------------
function startwordpress_scripts() {
    wp_enqueue_style( 'css', get_template_directory_uri() . '/style.css' );

    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js', array());
} add_action( 'wp_enqueue_scripts', 'startwordpress_scripts' );


// Autoriser les miniatures
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );


// Création zones de menus
add_action('init', 'theme_menus');
function theme_menus() {
    register_nav_menu('main_menu', 'Menu Principal');
    register_nav_menu('footer_menu', 'Menu du pied de page');
}

// Création des zones de widgets
add_action('widgets_init', 'theme_widgets_zones');
function theme_widgets_zones() {
    register_sidebar();
    register_sidebar(array(
        'id' => 'footer_widgets',
        'name' => 'Pied de page',
        'description' => 'Ces widgets vont dans le pied de page'
    ));
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

    register_post_type(
        'Formules',
        array(
            'label' => 'Formules',
            'labels' => array(
                'name' => 'Formules',
                'singular_name' => 'Formules',
                'all_items' => 'Tous les formules',
                'add_new_item' => 'Ajouter une formule',
                'edit_item' => "Éditer la formule",
                'new_item' => 'Nouvelle formule',
                'view_item' => "Voir la formule",
                'search_items' => 'Rechercher parmi les formules',
                'not_found' => "Pas de formules trouvées",
                'not_found_in_trash'=> "Pas de formule dans la corbeille"
            ),
            'menu_position' => 26,
            'public' => true,
            'capability_type' => 'post',
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
            ),
            'has_archive' => true,
            'taxonomies' => array('type de formules'),
        )
    );

    register_post_type(
        'breads',
        array(
            'label' => 'Nos pains',
            'labels' => array(
                'name' => 'Pains',
                'singular_name' => 'pain',
                'all_items' => 'Tous les pains',
                'add_newitem' => 'Ajouter un pain',
                'edit_item' => 'Editer un pain',
                'new_item' => 'Nouveau pain',
                'view_item' => 'Voir le pain',
                'search_items' => 'Rechercher parmi les pains',
                'not_found' => 'Pas de pain trouvé',
                'not_found_in_trash' => 'Pas de pain trouvvé dans la corbeille'
            ),
            'menu_position' => 27,
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


// Metabox prix pour type pain
function init_formulePrice() {
    add_meta_box( 'formulePrice', __( 'Prix de la formule', 'textdomain' ), 'display_formulePrice', 'formules');
} add_action( 'add_meta_boxes', 'init_formulePrice' );

function display_formulePrice($post) {

    $dataRecup = get_post_meta($post->ID,'price',true);
?>
<input style="width:10%;" id="price" name="price"/><?php echo $dataRecup ?>€
<br><label style="cursor: default;">Prix de la formule :</label>
<?php

}

function save_formulePrice( $post_id ) {
    if(isset($_POST['price'])) {
        update_post_meta($post_id, 'price', $_POST['price']);
    }
}
add_action( 'save_post', 'save_formulePrice' );







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


    // SECTION HOME -------------------------------------------------
    $wp_customize->add_section( 'homeSection' , array(
        'title'      => __( 'Section Home', 'Ad4Games' ),
        'priority'   => 1,
    ) );

    $wp_customize->add_setting( 'gameLogo' );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'gameLogo', array(
        'label'    => __( 'Logo du jeu', 'themeslug' ),
        'description' => 'Image affichée par dessus la vidéo de la homepage. Une image à fond transparent est donc conseillée.',
        'section'  => 'homeSection',
        'settings' => 'gameLogo',
    ) ) );


    // Gestion de la vidéo
    $wp_customize->add_setting( 'homePageVideo', array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'absint',
        'type' => 'theme_mod',
    ) );

    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'homePageVideo', array(
        'label' => __( 'Vidéo en arrière plan' ),
        'description' => esc_html__( 'La vidéo sera jouée en boucle et la bande-son sera désactivée.' ),
        'section' => 'homeSection',
        'mime_type' => 'video',  // Required. Can be image, audio, video, application, text
        'button_labels' => array( // Optional
            'select' => __( 'Choisir une vidéo' ),
            'change' => __( 'Changer de vidéo' ),
            'default' => __( 'Default' ),
            'remove' => __( 'Retirer' ),
            'placeholder' => __( 'Aucune vidéo séléctionnée' ),
            'frame_title' => __( 'Choisir une vidéo' ),
            'frame_button' => __( 'Choisir une vidéo' ),

        ) ) ) );
} add_action( 'customize_register', 'mytheme_customize_register' );


// Catégorie / Taxonomy pour les Formules
register_taxonomy(
    'Type de formule',
    'formules',
    array(
        'label' => 'Type de formule',
        'labels' => array(
            'name' => 'Type de formule',
            'singular_name' => 'Type de formule',
            'all_items' => 'Touts les Types de formule',
            'edit_item' => 'Éditer le Type de formule',
            'view_item' => 'Voir le Type de formule',
            'update_item' => 'Mettre à jour le Type de formule',
            'add_new_item' => 'Ajouter le Type de formule',
            'new_item_name' => 'Nouveau Type de formule',
            'search_items' => 'Rechercher parmi les Types de formule',
            'popular_items' => 'Types de formule les plus utilisées'
        ),
        'hierarchical' => true
    )
);
register_taxonomy_for_object_type( 'Type de formule', 'formules' );

// Customiser les champs taxonomy
add_filter('manage_edit-Plateformes_columns', function ( $columns ) {
    if( isset( $columns['description'] ) )
        unset( $columns['description'] );   

    if( isset( $columns['slug'] ) )
        unset( $columns['slug'] );     
?>

<style>
    .term-desription-wrap{
        display:none;
    }
    .term-slug-wrap{
        display:none;
    }
    .term-parent-wrap{
        display:none;
    }
</style><?php

    return $columns;
} );

// On limite le nombre de type de formules possible pour une formule à 1.
function allowOnlyOneTaxonomyPerPost( $args ) {

    if ( ! empty( $args['taxonomy'] ) && $args['taxonomy'] === 'Type de formule' ) {
        if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) { 
            if ( ! class_exists( 'WPSE_139269_Walker_Category_Radio_Checklist' ) ) {

                class WPSE_139269_Walker_Category_Radio_Checklist extends Walker_Category_Checklist {
                    function walk( $elements, $max_depth, $args = array() ) {
                        $output = parent::walk( $elements, $max_depth, $args );
                        $output = str_replace(
                            array( 'type="checkbox"', "type='checkbox'" ),
                            array( 'type="radio"', "type='radio'" ),
                            $output
                        );
                        return $output;
                    }
                }
            }

            $args['walker'] = new WPSE_139269_Walker_Category_Radio_Checklist;
        }
    }

    return $args;
} add_filter( 'wp_terms_checklist_args', 'allowOnlyOneTaxonomyPerPost' );
?>