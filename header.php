<!DOCTYPE html>
<html>
    <head>
        <?php wp_head(); ?>
    </head>
    <body>
       
        <?php 
        //Affichage du menu
        wp_nav_menu(array(
            'theme_location' => 'main_menu'
        ));
        ?>