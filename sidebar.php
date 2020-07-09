<?php
/**
 * Created by IntelliJ IDEA.
 * User: macbookpro
 * Date: 22/02/2018
 * Time: 13:20
 */
?>


<div class="uk-child-width-1-2@l" uk-grid uk-height-match=".uk-card">
    <div>
        <div class="uk-card uk-card-jess uk-card-small uk-card-default uk-margin-bottom uk-flex uk-flex-middle">
            <div class="uk-card-body">
                <div class="uk-text-center">
                    <span class="uk-h4 uk-display-block uk-text-jess-inverse-2 uk-text-bold"><?= tr_options_field('pc_options.message1')?></span>

                    <span class="uk-h5 uk-display-block uk-text-jess-inverse-2 uk-text-bold uk-margin-small-top"><?= tr_options_field('pc_options.message2')?></span>

                    <span class="uk-h6 uk-display-block uk-text-jess uk-text-bold uk-margin-small-top"><?= tr_options_field('pc_options.message3')?></span>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="uk-card uk-card-jess uk-card-small ">
            <div class="uk-card-header">
                <h3 class="uk-card-title uk-margin-remove-bottom">Ils nous font confiance</h3>

            </div>
            <div class="uk-card-body uk-padding-remove-horizontal">
			    <?php
			    $sliderpartenaire = tr_options_field('pc_options.partenaireslide');

			    if($sliderpartenaire):
				    ?>
                    <div class="owl-carousel owl-partenaire owl-theme">
					    <?php foreach ($sliderpartenaire as $item): ?>
                            <div class="item">
                                <img src="<?php echo wp_get_attachment_image_src($item['partenaireslider'], 'meidum')[0] ?>" alt="">
                            </div>
						    <?php
					    endforeach;
					    ?>
                    </div>
			    <?php endif; ?>
            </div>
        </div>
    </div>
</div>

