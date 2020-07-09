<?php
/**
 * Created by IntelliJ IDEA.
 * User: macbookpro
 * Date: 21/02/2018
 * Time: 12:27
 */
?>

<div class="uk-header">

    <div class="uk-width-1-1 uk-section uk-section-small">
        <div class="uk-container">
            <div class="uk-padding uk-background-default">
                <div class=" uk-child-width-1-2@l" uk-grid>
                    <div class="">
                        <ul class="uk-list">
                            <li><span uk-icon="icon: home" class="uk-margin-small-right"></span><?= tr_posts_field('nomcompany', tr_options_field("pc_options.lien_page_contact")) ?></li>
                            <li><span uk-icon="icon: location" class="uk-margin-small-right"></span><?= tr_posts_field('adressecompany', tr_options_field("pc_options.lien_page_contact")) ?></li>
                            <li><span uk-icon="icon: receiver" class="uk-margin-small-right"></span><?= tr_posts_field('phonecompany', tr_options_field("pc_options.lien_page_contact")) ?></li>
                            <li><span uk-icon="icon: mail" class="uk-margin-small-right"></span><?= tr_posts_field('emailcompany', tr_options_field("pc_options.lien_page_contact")) ?></li>
                        </ul>
                    </div>
                    <div class="uk-flex uk-flex-right@l uk-flex-center@s uk-position-relative">

                        <div class="uk-bottom-nav uk-padding uk-padding-remove-vertical">
                            <ul class="uk-subnav uk-subnav-pill" uk-margin>
                                <?php if(tr_options_field('pc_options.facebook')): ?><li><a href="<?= tr_options_field('pc_options.facebook') ?>" target="_blank"  uk-icon="icon: facebook" class="uk-border-circle"></a></li><?php endif; ?>
                                <?php if(tr_options_field('pc_options.tweeter')): ?><li><a href="<?= tr_options_field('pc_options.tweeter') ?>" target="_blank"  uk-icon="icon: twitter" class="uk-border-circle"></a></li> <?php endif; ?>
                                <?php if(tr_options_field('pc_options.linkedin')): ?><li><a href="<?= tr_options_field('pc_options.linkedin') ?>" target="_blank"  uk-icon="icon: linkedin" class="uk-border-circle"></a></li> <?php endif; ?>
                            </ul>

                        </div>

                        <div class="uk-position-bottom-left uk-margin uk-text-right uk-padding uk-padding-remove-vertical uk-width-1-1 uk-text-jess">
                            <?= tr_options_field('pc_options.message3'); ?>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="uk-width-1-1">
        <div class="uk-container">
            <div class="uk-padding uk-padding-remove-bottom">
                <div class="uk-width-1-1 uk-flex uk-flex-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/LosangeHandicap.png" style="height: 100px;" alt="">
                </div>
                <div class="uk-width-1-1 uk-text-center uk-padding-small uk-padding-remove-horizontal uk-padding-remove-bottom" style="color: #000000">
                    <p>Toutes nos offres sont ouvertes aux personnes en situation de handicap</p>
                    <p>©<?= date('Y') ?> Jess Assistance - Tous droits réservés. Design by <a href="http://aligodu.cm">Aligodu</a></p>
                </div>
            </div>
        </div>
    </div>
</div>


</div>

<?php wp_footer(); ?>
</body>
</html>
