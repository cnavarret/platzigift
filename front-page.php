<?php get_header(); ?>

<main class='container my-5     '>
    <?php if(have_posts()){
            while(have_posts()){
                the_post(); ?>
            <h1 class='my-3'><?php the_title(); ?>!!</h1>
            <?php the_content(); ?>

        <?php    }
    }?>


    <div class="lista-productos my-5">
        <h2 class='text-center'>PRODUCTOS</h2>
                <div class="row">
                    <div class="col-12"></div>
                        <select class="form-control" name="categorias-productos" id="categorias-productos">
                            <option value="">Todas las categorías</option>
                            <?php $terms = get_terms('catagoria-productos', array('hide_empty' => true)); ?>
                            <?php foreach ($terms as $term) {
                                echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
                            }?>
                        </select>
                </div>
    </div>
        <div id="resultado-productos" class="row justify-content-center">
            <?php $args = array(
                'post_type' => 'producto',
                'post_per_page' => -1, 
                'order'         => 'ASC',
                'orderby'       => 'title'
            );

            $productos = new WP_Query($args);?>
            <?php if($productos->have_posts()){ ?>
                <?php while($productos->have_posts()){ ?>
                    <?php $productos->the_post(); ?>
                        <div class="col-md-4 col-12 my-3 text center">
                            <a href="<?php the_permalink(); ?>">
                                <figure>
                                    <?php the_post_thumbnail('large'); ?>
                                </figure>
                                <h4 class='my-2 text-center'>
                                    <?php the_title();?>
                            </h4>
                            </a>
                        </div>
            <?php } ?>
            <?php }?>
        </div>
    
    <div class="row">
        <div class="col-12"><h1>Novedades</h1></div>

        
    <div id="resultado-novedades" class="row"></div>

    </div>
</main>

<?php get_footer(); ?>