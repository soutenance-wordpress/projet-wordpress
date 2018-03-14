<?php


get_header(); ?>

<div id="singleNewsContainer">
    <div class="sectionTitle">
        <h1>NEWS</h1>
    </div>

    <div class="NewsImage">
        <img class="imgHeightInherit" src="<?php echo get_the_post_thumbnail_url() ?>">
    </div>

    <div class="newsText">
        <h2><?php echo get_the_title(); ?></h2>
        <div class="theContent">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
            <?php endwhile; endif; ?>
        </div>

    </div>

</div>

<?php get_footer(); ?>