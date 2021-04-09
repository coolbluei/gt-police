(function ($, Drupal) {

    /**
     * Use this behavior as a template for custom Javascript.
     */
    Drupal.behaviors.slidesBehavior = {
      attach: function (context, settings) {
        $('.field--name-field-slides').flexslider({
            animation: "slide"
          });
      }
    };
})(jQuery, Drupal);