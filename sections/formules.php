<?php

$query = new WP_Query( array( 'post_type' => 'Formules' ) );

/* On vérifie si on a de réultats */
if($query->have_posts()):
// Le nombre de résultat
$numberOfNews = $query->found_posts;
// On récupère la limite de slide si elle est définie et valide

?>

<section id="formules">
    <div class="content">
        <div class="sectionTitle">
            <h1>FORMULES</h1>
        </div>

        <ul>
            <?php
            $i = 1;
            while ( $query->have_posts() ) : $query->the_post(); 
                if($i <= 3):?>
            <li><a href="<?php the_permalink(); ?>">
                <img class="img-formules-home" src="<?php echo get_the_post_thumbnail_url() ?>">
                <p><?php echo get_post_meta($post->post_title,'summary',true); ?></p>
                <?php the_title(); ?>
                </a>
            </li>
            <?php
            $i++;
            endif;
            endwhile;
            wp_reset_postdata();
            ?>

        </ul>

        <button onClick="window.open('<?php echo get_post_type_archive_link('formules'); ?>', '_blank')"><h3>Toutes nos formules</h3></button>
    </div>
</section>
<?php endif; ?>
