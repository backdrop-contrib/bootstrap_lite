(function ($, Backdrop) {
  /*global jQuery:false */
  /*global Backdrop:false */
  "use strict";

  /**
   * Provide vertical tab summaries for Bootstrap settings.
   */
  Backdrop.behaviors.bootstrapLiteSettingSummaries = {
    attach: function (context) {
      var $context = $(context);

      // Version and CDN [TODO]

      // Bootswatch theme [TODO]

      // Navbar [TODO]

      // Breadcrumbs [TODO]

      // Tweaks

      $context.find('#edit-tweaks').backdropSetSummary(function () {
        var summary = [];

        var container_type = 'Container type: ' + $context.find('select[name="bootstrap_lite_container"] :selected').text();
        summary.push(container_type);

        if ($context.find(':input[name="bootstrap_lite_datetime"]').is(':checked')) {
          summary.push(Backdrop.t('Show "XX time ago"'));
        }

        if ($context.find(':input[name="bootstrap_lite_image_responsive"]').is(':checked')) {
          summary.push(Backdrop.t('Responsive images'));
        }

        return summary.join('<br>');
      });

    }
  };

})(jQuery, Backdrop);
