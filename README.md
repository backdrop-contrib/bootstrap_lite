Bootstrap Lite
==============

This is a clean and minimal Backdrop-oriented Bootstrap 3-based theme inspired by the Drupal [Bootstrap](https://www.drupal.org/project/bootstrap) theme. It is a totally separate project from the Drupal [Bootstrap](https://www.drupal.org/project/bootstrap) theme with no guaranteed compatibility between the two themes.

This theme is still in beta and is subject to changes that may break your theme during upgrade. We recommend that you only upgrade if necessary or if you are prepared to fix minor changes. With the change in maintainership on Feb 5, 2021, we are hoping to cut a stable release ASAP.

Features
--------

1. [BootstrapCDN](http://bootstrapcdn.com/) based.
2. [Bootswatch](http://bootswatch.com) support (via BootstrapCDN) included. Easy to pick a Bootswatch free theme.
3. [FontAwesome](https://fortawesome.github.io/Font-Awesome/) support included.
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

Additional documentation is located in [the Wiki](https://github.com/backdrop-contrib/bootstrap_lite/wiki/Documentation).

Differences from Drupal 7
-------------------------

These features were dropped in the port from the Drupal Bootstrap theme:

  - Starter kit. But you still can create a sub theme. See [Developing themes](https://api.backdropcms.org/developing-themes).
  - Tooltip. The feature is there, but you need to follow the [Bootstrap 3 documentation](https://getbootstrap.com/docs/3.4/javascript/#tooltips) to make it work.
  - Popovers. The feature is there, but you need to follow the [Bootstrap 3 documentation](https://getbootstrap.com/docs/3.4/javascript/#popovers) to make it work.
  - Anchors settings. I believe this one needs to be done via a module.
  - Well settings.

Issues
------

Bugs and feature requests should be reported in [the Issue Queue](https://github.com/backdrop-contrib/bootstrap_lite/issues).

Current Maintainers
-------------------

- [Tim Erickson](https://github.com/stpaultim),  [https://www.simplo.site](https://www.simplo.site)
- [Robert J. Lang](https://github.com/bugfolder)

Credits
-------

Thanks to the Drupal [Bootstrap theme authors](http://drupal.org/node/259843/committers).

Thanks to [Gormartsen](https://github.com/Gormartsen) for the original port to Backdrop CMS. 


License
-------

This project is GPL v2 software. See the LICENSE.txt file in this directory for complete text.

