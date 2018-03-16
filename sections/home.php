<section id="accueil">

    <div class="videoSlot">

        <video class="video" loop muted autoplay>
            <source src="<?php echo wp_get_attachment_url(get_theme_mod('homePageVideo'));?>" type="video/mp4">
            Votre navigateur ne supporte pas le tag "video".
        </video>

        <div class="container">

            <div id="gameLogo">
                <?php if(get_theme_mod('gameLogo') != null): ?>
                <img height="100%" src="<?php echo get_theme_mod('gameLogo');?>">
                <?php endif; ?>
            </div>

        </div>

    </div>

</section>