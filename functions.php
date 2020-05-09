<?php 

function init_template(){
    add_theme_support('post-thumbnails');
    add_theme_support( 'title-tag');

    register_nav_menus( 
        array(
            'top_menu' => 'Menú Principal'   
        )
    );
}


//ad_action('after_setup_theme','init-template');

if ( function_exists( 'add_theme_support' ) )
add_theme_support( 'post-thumbnails' );

function assets(){
    wp_register_style('bootstrap','https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', '', '4.4.1','all');
    wp_register_style('montserrat', 'https://fonts.googleapis.com/css?family=Montserrat&display=swap','','1.0', 'all');
    wp_enqueue_style('estilos', get_stylesheet_uri(), array('bootstrap','montserrat'),'1.0', 'all');
   
    wp_register_script('popper','https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js','','1.16.0', true);
    wp_enqueue_script('boostraps', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', array('jquery','popper'),'4.4.1', true);
    wp_enqueue_script('custom', get_template_directory_uri().'/assets/js/custom.js', '', '1.0', true);
    wp_localize_script( 'custom', 'pg', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'apiurl' => home_url( 'wp-json/pg/v1/'),
    ) );
}

add_action('wp_enqueue_scripts','assets');


function sidebar(){
    register_sidebar(
        array(
            'name' => 'Pie de página',
            'id'   => 'footer',
            'description' => 'ZONna de Widgets para PIE de página',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p>',
            'after_title'   => '</p>',
        ));
}

add_action('widgets_init', 'register_sidebar');

function productos_type(){
    $labels = array(
        'name' => 'Productos',
        'singular_name' => 'Producto',
        'manu_name' => 'Productos',
    );

    $args = array(
        'label'  => 'Productos', 
        'description' => 'Productos de Platzi',
        'labels'       => $labels,
        'supports'   => array('title','editor','thumbnail', 'revisions'),
        'public'    => true,
        'show_ui'   => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon'     => 'dashicons-cart',
    'show_in_nav_menus'     => false,
        'show_in_rest'          => true,
        'can_export' => true,
        'publicly_queryable' => true,
        'rewrite'       => true, 

    );    
    register_post_type('producto', $args);
}

add_action('init', 'productos_type');
add_action('init', 'pgRegisterTax');
function pgRegisterTax() {
    $args = array(
    'hierarchical' => true,
    'labels' => array('name' => 'Categorías de Productos', 
    'singular_name' => 'Categoría de Productos'),

    'show_in_nav_menu' => true,
    'show_admin_column' => true,
    'rewrite' => array('slug' => 'categoria-productos')
    );
    register_taxonomy('categoria-productos', array('producto'), $args);
}
add_action( "wp_ajax_nopriv_pgFiltroProductos", "pgFiltroProductos" );
add_action( "wp_ajax_pgFiltroProductos", "pgFiltroProductos" );
function pgFiltroProductos() {
    $args = array(
        'post_type' => 'producto',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'title',
    );
    if ($_POST['categoria']) {
    $args['tax_query'] = array( 
        array(
            'taxonomy' => 'categoria-productos',
            'field' => 'slug',
            'terms' => $_POST['categoria']
        )
        );
        }
    $productos = new WP_Query($args);

    if ($productos->have_posts()) {
        $return = array();
        while ($productos->have_posts()) {
            $productos->the_post();
            $return[] = array(
                'imagen' => get_the_post_thumbnail(get_the_id(), 'large' ),
                'link' => get_tje_permalink(),
                'titulo' => get_the_title()
            );
        }
        wp_send_json($return);
    }
}



add_action('res_api_init', 'novedadesAPI');
function novedadesAPI(){
    register_rest_route( 
        'pg/v1', 
        '/novedades/(?P<cantidad>\d+)', 
        array(
            'methods' => 'GET',
            'callback' => 'pedidoNovedades'
        )
    );
}

function pedidoNovedades($data){
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $data['cantidad'],
        'order' => 'ASC',
        'orderby' => 'title',
    );
    $novedades = new WP_Query($args);

    if ($novedades->have_posts()) {
        $return = array();
        while ($novedades->have_posts()) {
            $novedades->the_post();
            $return[] = array(
                'imagen' => get_the_post_thumbnail(get_the_id(), 'large' ),
                'link' => get_tje_permalink(),
                'titulo' => get_the_title()
            );
        }
        return $return;
    }
}