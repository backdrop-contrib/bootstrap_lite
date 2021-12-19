Bootstrap Lite
==============

This is a clean and minimal Backdrop-oriented Bootstrap 3-based theme inspired by the Drupal [Bootstrap](https://www.drupal.org/project/bootstrap) theme. It is a totally separate project from the Drupal [Bootstrap](https://www.drupal.org/project/bootstrap) theme with no guaranteed compatibility between the two themes.

Features
--------

1. Load Bootstrap from [BootstrapCDN](http://bootstrapcdn.com/) or from the bundled library.
2. [Bootswatch](http://bootswatch.com) support included. Easy to pick a Bootswatch free theme.
3. [FontAwesome 4](https://fontawesome.com/v4.7/) support included.
4. Other tweaks:
  - Navbar settings (fixed, static, top, bottom).
  - Navbar user menu with cog icon.
  - Breadcrumbs tweaks.
  - Ability to use fluid or fixed width.
  - "XX time ago" for nodes and comments instead of regular time.

Installation
------------

  - Install this theme using the official [Backdrop CMS instructions](https://backdropcms.org/guide/themes).

  - The navbar content is controlled by a layout block - "Header block". By changing settings for the "Header block", you can control the visibility of the menu, logo, sitename and site slogan.

Documentation
-------------

Beginning with version 1.x-1.4.0, the main `body` tag in the page HTML includes classes based on the user's role(s) and the normal path (not URL alias) of the page.

See the official [Bootstrap 3 documentation](https://getbootstrap.com/docs/3.4/) for a general description of Bootstrap CSS, components, and JS resources supported by the module.

See the official [Font Awesome 4 documentation](https://fontawesome.com/v4.7/) for a general description of Font Awesome 4.

Bootstrap 3 comes with its own [set of icons](https://getbootstrap.com/docs/3.4/components/#glyphicons) and an icon font, "Glyphicon Halflings". Bootstrap 3 icons use HTML like this (for a cart): `<i class="glyphicon glyphicon-shopping-cart"></i>`.

Font Awesome 4 (which is optional) provides its own [set of icons](https://fontawesome.com/v4.7/icons/), which use HTML like this (also for a cart): `<i class="fa fa-shopping-cart"></i>`.

Differences from Drupal 7
-------------------------

These features were dropped in the port from the Drupal Bootstrap theme:

  - Starter kit. But you still can create a sub theme. See [Developing themes](https://docs.backdropcms.org/documentation/developing-themes).
  - Tooltip. The feature is there, but you need to follow the [Bootstrap 3 documentation](https://getbootstrap.com/docs/3.4/javascript/#tooltips) to make it work.
  - Popovers. The feature is there, but you need to follow the [Bootstrap 3 documentation](https://getbootstrap.com/docs/3.4/javascript/#popovers) to make it work.
  - Anchors settings.
  - Well settings.

Issues
------

Bugs and feature requests should be reported in [the issue queue](https://github.com/backdrop-contrib/bootstrap_lite/issues).

Current Maintainers
-------------------

- [Tim Erickson](https://github.com/stpaultim), [https://www.simplo.site](https://www.simplo.site)
- [Robert J. Lang](https://github.com/bugfolder)

Credits
-------

Thanks to the Drupal [Bootstrap theme authors](http://drupal.org/node/259843/committers).

Thanks to [Gormartsen](https://github.com/Gormartsen) for the original port to Backdrop CMS.


License
-------

This project is GPL v2 software. See the LICENSE.txt file in this directory for complete text.

Bootstrap, the Bootswatch themes, and Font Awesome are provided under the [MIT License](https://getbootstrap.com/docs/4.0/about/license/).
