<?php get_header(); ?>

<main class='container my-5'>
    <h1 class='my-3'>Probar Donde Imprime<?php the_title(); ?></h1>
    <?php if(have_posts()){ ?>
        <?php while(have_posts()) { ?>
            <?php the_post(); ?>
            <?php the_content(); ?>
         <?php } ?>
    <?php }?>
</main>

<?php get_footer(); ?>