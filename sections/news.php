<!-- Génération automatique des "News" dans le Slider-->
<?php
$query = new WP_Query( array( 'post_type' => 'News' ) );

if($query->have_posts()):

// On enregistre le nombre total de News
$numberOfNews = $query->found_posts;

// On récupère la limite de slide si elle est définie et valide
if( (ctype_digit(get_theme_mod('newsSliderLimitNumber')) == '1') && (get_theme_mod('newsSliderLimitNumber') > 0) && ( get_theme_mod('newsSliderLimitNumber') < $numberOfNews) ) {
    $numberOfSlides = ltrim(get_theme_mod('newsSliderLimitNumber'), '0');
} else {
    $numberOfSlides = $numberOfNews;
}
?>

<section id="news">

    <div class="sectionTitle">
        <h1>NEWS</h1>
    </div>

    <div class="content">
        <button id="newsSliderLeftButton" class="buttonDisable" onClick="newsSliderLeftButton();">
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="50px" height="50px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;"
                 xml:space="preserve">
                <g>
                    <path d="M97.141,225.92c0-8.095,3.091-16.192,9.259-22.366L300.689,9.27c12.359-12.359,32.397-12.359,44.751,0c12.354,12.354,12.354,32.388,0,44.748L173.525,225.92l171.903,171.909c12.354,12.354,12.354,32.391,0,44.744c-12.354,12.365-32.386,12.365-44.745,0l-194.29-194.281C100.226,242.115,97.141,234.018,97.141,225.92z"/>
                </g>
            </svg>
        </button>

        <div class="newsSlider">
            <div class="newsSliderWrapper">

                <?php        
                $i = 1;
                while ( $query->have_posts() ) : $query->the_post(); ?>   

                <?php if($i <= $numberOfSlides): ?>


                <div class="newsSlide" <?php if($numberOfSlides == "1") {echo 'style="margin-left:220px;"';}?> onClick="redirectToSingleNews('<?php the_permalink(); ?>');">

                    <div class="newsImage">
                        <img class="imgHeightInherit" src="<?php echo get_the_post_thumbnail_url() ?>">
                    </div>

                    <div class="newsText">
                        <p><?php echo get_post_meta($post->ID,'summary',true); ?></p>
                    </div>

                </div>

                <?php 
                $i++;
                endif;
                endwhile; 
                wp_reset_postdata();
                ?>

            </div>
        </div>

        <button id="newsSliderRightButton" class="<?php if($numberOfSlides <= 2) {echo "buttonDisable";}?>" onClick="newsSliderRightButton();">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 451.846 451.847" style="enable-background:new 0 0 451.846 451.847;" xml:space="preserve">
                <g>
                    <path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744   L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0l194.287,194.284   c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"/>
                </g>
            </svg>
        </button>

        <?php 
        if( ($numberOfSlides > 2) && ($numberOfSlides < 11)):
        ?>
        <div class="newsDotsButtons">
            <?php for($i = 1; $i < $numberOfSlides; $i++): ?>
            <button <?php if($i == 1) {echo "class=\"active\"";}?> id="newsDotButton<?php echo $i;?>" onClick="newsDotButton(<?php echo $i;?>);"></button>

            <?php endfor; ?>
        </div>
        <?php endif; ?>

    </div>

    <?php if($numberOfSlides > 2): ?>
    <button class="buttonType1" onClick="window.open('<?php echo get_post_type_archive_link('news'); ?>', '_blank')"><p>Voir toutes les News</p></button>
    <?php endif; ?>

    <?php
    ?>

</section>
<?php endif; ?>


<script>

    // Variables utilisées pour le Slider :
    var canUseSliderButtons = true;
    var currentNewsSliderPosition = 1;
    var maxNewsSliderPosition = $('#news .newsSlider .newsSlide').length - 1;
    var newsSliderAnimationDuration = 1200;


    function newsSliderLeftButton() {

        if( !(currentNewsSliderPosition === 1) && (canUseSliderButtons === true) ) {

            canUseSliderButtons = false;
            // On augmente la valeur de currentNewsSliderPosition de 1
            currentNewsSliderPosition -= 1;

            // On décalle le slider d'un rang vers la gauche
            $(".newsSliderWrapper").animate({marginLeft:'+=440px'}, newsSliderAnimationDuration);

            // Retrait de l'ancien DotButton actif, et ajout du nouveau
            $(".newsDotsButtons .active").removeClass("active");
            $("#newsDotButton"+(currentNewsSliderPosition)).addClass("active");

            // Désactive le dotButton actuel, et réactive les autres
            document.querySelectorAll(".newsDotsButtons button").forEach(function(element){
                element.removeAttribute("disabled");
            }); document.querySelector("#newsDotButton"+currentNewsSliderPosition).setAttribute("disabled", "");

            // On désactive le bouton gauche si jamais on est sur la première slide
            if(currentNewsSliderPosition === 1) {
                $("#newsSliderLeftButton").addClass('buttonDisable');
            }

            // On réactive le bouton droit
            $("#newsSliderRightButton").removeClass('buttonDisable');

            // On réactive le clic sur les boutons après une durée définie
            window.setTimeout(function() {
                canUseSliderButtons = true;
            }, newsSliderAnimationDuration);

        }
    }


    function newsSliderRightButton() {

        if( ( !(currentNewsSliderPosition === maxNewsSliderPosition) ) && (canUseSliderButtons === true) ) {

            canUseSliderButtons = false;
            // On augmente la valeur de currentNewsSliderPosition de 1
            currentNewsSliderPosition += 1;

            // On décalle le slider d'un rang vers la gauche
            $(".newsSliderWrapper").animate({marginLeft:'-=440px'}, newsSliderAnimationDuration);

            // Retrait de l'ancien DotButton actif, et ajout du nouveau
            $(".newsDotsButtons .active").removeClass("active");
            $("#newsDotButton"+currentNewsSliderPosition).addClass("active");

            // Désactive le DotButton actuel, et réactive les autres
            document.querySelectorAll(".newsDotsButtons button").forEach(function(element){
                element.removeAttribute("disabled");
            }); document.querySelector("#newsDotButton"+currentNewsSliderPosition).setAttribute("disabled", "");

            // On désactive le bouton droit si jamais on est sur la dernière slide
            if(currentNewsSliderPosition === maxNewsSliderPosition) {
                $("#newsSliderRightButton").addClass('buttonDisable');
            }

            // On réactive le bouton gauche
            $("#newsSliderLeftButton").removeClass('buttonDisable');

            // On réactive le clic sur les boutons après une durée définie
            window.setTimeout(function() {
                canUseSliderButtons = true;
            }, newsSliderAnimationDuration);

        }
    }


    function newsDotButton(number) {

        if( (canUseSliderButtons === true) && !(number === currentNewsSliderPosition) ) {

            canUseSliderButtons = false;

            // Retrait de l'ancien Dotbutton actif, et ajout du nouveau
            $(".newsDotsButtons .active").removeClass("active");
            $("#newsDotButton"+number).addClass("active");

            // Désactive le dotButton actuel, et réactive les autres
            document.querySelectorAll(".newsDotsButtons button").forEach(function(element){
                element.removeAttribute("disabled");
            });
            document.querySelector("#newsDotButton"+number).setAttribute("disabled", "");

            // On décalle le slider d'autant de rang que nécessaire vers la droite ou la gauche.
            var moveLenght;
            if(number > currentNewsSliderPosition) {
                moveLenght = '-=' +(440*(number - currentNewsSliderPosition)) +'px';
            } else {
                moveLenght = '+=' +(440*(currentNewsSliderPosition - number)) +'px';
            }
            $(".newsSliderWrapper").animate({marginLeft:moveLenght}, newsSliderAnimationDuration);

            // On set la valeur de currentNewsSliderPosition à number
            currentNewsSliderPosition = number;

            // On désactive le bouton droit si jamais on est sur la dernière slide
            if(currentNewsSliderPosition === maxNewsSliderPosition) {
                $("#newsSliderRightButton").addClass('buttonDisable');
                $("#newsSliderLeftButton").removeClass('buttonDisable');
            } 
            // On désactive le bouton gauche si jamais on est sur la première slide
            if(currentNewsSliderPosition === 1) {
                $("#newsSliderLeftButton").addClass('buttonDisable');
                $("#newsSliderRightButton").removeClass('buttonDisable');
            } 
            // Sinon, on réactive les deux boutons
            if( !(currentNewsSliderPosition === maxNewsSliderPosition) && !(currentNewsSliderPosition === 1)){
                $("#newsSliderRightButton").removeClass('buttonDisable');
                $("#newsSliderLeftButton").removeClass('buttonDisable');
            }

            // On réactive le clic sur les boutons après une durée définie
            window.setTimeout(function() {
                canUseSliderButtons = true;
            }, newsSliderAnimationDuration);
        }
    }


    function newsSliderAutoLoop() {

    }


    $( document ).ready(function() {

        console.log("Fichier news.php chargé");
    });

    // Redirection vers une single page
    function redirectToSingleNews(permaLink) {
        window.open(permaLink, '_blank');
    }

</script>