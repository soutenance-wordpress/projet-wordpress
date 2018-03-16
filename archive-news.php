<?php get_header(); ?>

<div id="listTemplateContainer">
    <div class="sectionTitle">
        <h1><?php echo strtoupper(post_type_archive_title('', false));?></h1>
    </div>

    <div class="customPostsList">
        <?php if(have_posts()) : ?>
        <div class="titles">
            <h3>Titre des <?php post_type_archive_title()?></h3>
            <p>Date de publication</p>
        </div>
        <?php while(have_posts()) : the_post(); ?>
        <div class="customPostContent">
            <a href="<?php the_permalink(); ?>" target="_blank"><h3><?php the_title(); ?></h3></a>
            <p><?php echo get_the_date(); ?></p>
        </div>

        <?php 
        endwhile; 
        endif; 
        ?>
    </div>

</div>