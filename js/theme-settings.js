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

      // Version and CDN

      $context.find('#edit-bootstrap-lite-cdn').backdropSetSummary(function () {
        var summary = [];
        var bootstrap_version = 'Bootstrap: ' + $context.find('select[name="bootstrap_lite_cdn"] :selected').text();
        summary.push(bootstrap_version);
        var fontawesome_version = 'FontAwesome: ' + $context.find('select[name="bootstrap_lite_font_awesome"] :selected').text();
        summary.push(fontawesome_version);
        return summary.join('<br>');
      });

      // Bootswatch theme

      $context.find('#edit-bootswatch').backdropSetSummary(function () {
        var bootswatch = $context.find('#edit-bootstrap-lite-bootswatch input[checked="checked"]').parent().find('h3').text();
        if (bootswatch == '') {
          bootswatch = Backdrop.t('Default');
        }
        return bootswatch;
      });

      // Navbar

      $context.find('#edit-navbar').backdropSetSummary(function () {
        var summary = [];
        var navbar_position = 'Position: ' + $context.find('select[name="bootstrap_lite_navbar_position"] :selected').text();
        summary.push(navbar_position);
        var menu_position = 'Menu position: ' + $context.find('select[name="bootstrap_lite_navbar_menu_position"] :selected').text();
        summary.push(menu_position);
        if ($context.find(':input[name="bootstrap_lite_navbar_inverse"]').is(':checked')) {
          summary.push(Backdrop.t('Inverse navbar'));
        }
        if ($context.find(':input[name="bootstrap_lite_navbar_user_menu"]').is(':checked')) {
          summary.push(Backdrop.t('Cog style'));
        }
        return summary.join('<br>');
      });

      // Breadcrumbs

      $context.find('#edit-breadcrumbs').backdropSetSummary(function () {
        var summary = [];
        if ($context.find(':input[name="bootstrap_lite_breadcrumb_home"]').is(':checked')) {
          summary.push(Backdrop.t('Show home'));
        }
        if ($context.find(':input[name="bootstrap_lite_breadcrumb_title"]').is(':checked')) {
          summary.push(Backdrop.t('Show current page'));
        }
        return summary.join('<br>');
      });

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
