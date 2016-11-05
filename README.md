#Bootstrap Lite: a Backdrop Bootstrap-based theme 
It's clean and minimal Backdrop-oriented Bootstrap-based theme inspired by the Drupal [Bootstrap](https://www.drupal.org/project/bootstrap) theme. It is a totally separate project from the Drupal [Bootstrap](https://www.drupal.org/project/bootstrap) theme with no guarantied compatibility between the two themes.

[Demo site](http://bootstrap.backdrop.expert).

##Features
1. [BootstrapCDN](http://bootstrapcdn.com/) based.
2. [Bootswatch](http://bootswatch.com) support (via BootstrapCDN) included. Easy to pick a Bootswatch free theme.
3. [Font awesome](https://fortawesome.github.io/Font-Awesome/) support included.
4. Other tweaks:
  - Navbar settings (fixed, static, top, bottom). 
  - Navbar user menu with cog icon.
  - Breadcrumbs tweaks.
  - Ability to use fluid or fixed width.
  - "XX time ago" for nodes and comments instead of regular time.
  
## Excluded from original Drupal Bootstrap code
  - Starter kit. But you still can create a sub theme. See [Developing themes](https://api.backdropcms.org/developing-themes)
  - Tooltip. The feature is there, but you need to follow the [documentation](http://getbootstrap.com/javascript/#tooltips) to make it work.
  - Popovers. The feature is there, but you need to follow the [documentation](http://getbootstrap.com/javascript/#popovers) to make it work.
  - Anchors settings. I believe this one needs to be done via a module.
  - Well settings.

##Installation
  - Install this theme using the official [Backdrop CMS instructions](https://backdropcms.org/guide/themes).

##HOWTO
  - The navbar content is controlled by a layout block - "Header block". By changing settings for the "Header block", you can control the visibility of the menu, logo, sitename and site slogan.

##Roadmap
  1.x-1.3.5.3
    - Tooltops, Popovers implementation in a better way to integrate with Backdrop.
    - Add the ability to have different container settings for different layouts. For example: fluid for Default Layout and fixed for Default Administrative Layout.

##License
This project is GPL v2 software. See the LICENSE.txt file in this directory for complete text.

##Credits
Thanks to the Drupal [Bootstrap theme Authors](http://drupal.org/node/259843/committers)

## Drupal Bootstrap
Drupal [Bootstrap](https://www.drupal.org/project/bootstrap) theme
If you are interested in the Drupal Bootstrap theme port to Backdrop, please see: [Backdrop port issue](https://www.drupal.org/node/2483391)
