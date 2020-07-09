/**
 * Created by macbookpro on 16/02/2018.
 */

jQuery("#js-rotating").Morphext({
    animation: "zoomIn", // Overrides default "bounceIn"
    separator: ";", // Overrides default ","
    speed: 5000, // Overrides default 2000
    complete: function () {
        // Overrides default empty function
    }
});

jQuery(document).ready(function() {
    jQuery(".dotdot").dotdotdot({
        //	configuration goes here
    });
});


jQuery('.owl-partenaire').owlCarousel({
    loop:true,
    margin:10,
    autoplay: true,
    nav:false,
    responsive:{
        0:{
            items:1
        },
        // 600:{
        //     items:3
        // },
        1000:{
            items:2
        }
    }
})
