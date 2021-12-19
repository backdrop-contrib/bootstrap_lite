<?php
/**
 * @file
 * template.php
 */

/**
 * Implements hook_css_alter().
 */
function bootstrap_lite_css_alter(&$css) {
  $theme_path = backdrop_get_path('theme', 'bootstrap_lite');

  // Bootstrap

  $cdn_version = theme_get_setting('bootstrap_lite_cdn');
  if ($cdn_version) {
    $bootswatch = theme_get_setting('bootstrap_lite_bootswatch');
    if ($cdn_version == 'module') {
      // Use bundled library
      $bootstrap_src = '/' . $theme_path;
      if ($bootswatch) {
        $bootstrap_src .= '/bootswatch/' . $bootswatch . '/bootstrap.min.css';
      }
      else {
        $bootstrap_src .= '/bootstrap/css/bootstrap.min.css';
      }
    }
    else {
      // Use CDN
      $bootstrap_src = 'https://stackpath.bootstrapcdn.com';
      if ($bootswatch) {
        $bootstrap_src .= '/bootswatch/' . $cdn_version . '/' . $bootswatch . '/bootstrap.min.css';
      }
      else {
        $bootstrap_src .= '/bootstrap/' . $cdn_version . '/css/bootstrap.min.css';
      }
    }
    $css[$bootstrap_src] = array(
      'data' => $bootstrap_src,
      'type' => 'external',
      'every_page' => TRUE,
      'every_page_weight' => -1,
      'media' => 'all',
      'preprocess' => FALSE,
      'group' => CSS_THEME,
      'browsers' => array('IE' => TRUE, '!IE' => TRUE),
      'weight' => -2,
    );

    // Add overrides to Bootstrap CSS.
    $override = $theme_path . '/css/overrides.css';
    $css[$override] = array(
      'data' => $override,
      'type' => 'file',
      'every_page' => TRUE,
      'every_page_weight' => -1,
      'media' => 'all',
      'preprocess' => TRUE,
      'group' => CSS_THEME,
      'browsers' => array('IE' => TRUE, '!IE' => TRUE),
      'weight' => -1,
    );
  }

  // Font Awesome

  if ($font_awesome = theme_get_setting('bootstrap_lite_font_awesome')) {
    if ($font_awesome == 'module') {
      // Use bundled library
      $font_awesome_src = '/' . $theme_path . '/font-awesome/css/font-awesome.min.css';
    }
    else {
      // Use CDN.
      $font_awesome_src = 'https://stackpath.bootstrapcdn.com/font-awesome/' . $font_awesome . '/css/font-awesome.min.css';
    }
    $css[$font_awesome_src] = array(
      'data' => $font_awesome_src,
      'type' => 'external',
      'every_page' => TRUE,
      'every_page_weight' => -1,
      'media' => 'all',
      'preprocess' => FALSE,
      'group' => CSS_THEME,
      'browsers' => array('IE' => TRUE, '!IE' => TRUE),
      'weight' => -2,
    );
  }
}

/**
 * Implements hook_js_alter().
 */
function bootstrap_lite_js_alter(&$js) {
  $cdn_version = theme_get_setting('bootstrap_lite_cdn');
  if ($cdn_version) {
    if ($cdn_version == 'module') {
      // Use bundled library
      $js_src = '/' . backdrop_get_path('theme', 'bootstrap_lite') . '/bootstrap/js/bootstrap.min.js';
    }
    else {
      // Use CDN
      $js_src = 'https://stackpath.bootstrapcdn.com/bootstrap/' .$cdn_version  . '/js/bootstrap.min.js';
    }
    $js[$js_src] = array(
      'data' => $js_src,
      'type' => 'external',
      'every_page' => TRUE,
      'every_page_weight' => -1,
      'weight' => -100,
    ) + backdrop_js_defaults();
  }
}

/**
 * Internal function to make sure Header block is rendered.
 */
function bootstrap_lite_is_header($set) {
  static $is_header;
  if (0 == strcmp($set, 'get')) {
    return $is_header;
  }
  else {
    $is_header = $set;
  }
}

/**
 * Implements hook_preprocess_layout().
 */
function bootstrap_lite_preprocess_layout(&$variables) {
  $layout = $variables['layout'];
  $layout_name = $layout->layout;

  foreach($layout->content as $key => $block){
    if ($block->module == 'system' && $block->delta == 'header') {
      bootstrap_lite_is_header(TRUE);
    }
  }

  // Default Backdrop layouts contain both .container and .container-fluid.
  // Remove the one we're not using.
  if (theme_get_setting('bootstrap_lite_container') == 'container') {
    backdrop_add_js('(function($){ $(".container.container-fluid").removeClass("container-fluid");})(jQuery);', array('type' => 'inline', 'scope' => 'footer'));
  }
  else {
    backdrop_add_js('(function($){ $(".container.container-fluid").removeClass("container");})(jQuery);', array('type' => 'inline', 'scope' => 'footer'));
  }
}

/**
 * Implements hook_preprocess_page().
 */
function bootstrap_lite_preprocess_page(&$variables){
  $no_old_ie_compatibility_modes = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'http-equiv' => 'X-UA-Compatible',
      'content' => 'IE=edge',
    ),
  );

  // Adding class for bootswatch theme to help with css overrides
  if ($bootswatch = theme_get_setting('bootstrap_lite_bootswatch')) {
    $variables['classes'][] = $bootswatch;
  }

  backdrop_add_html_head($no_old_ie_compatibility_modes, 'no_old_ie_compatibility_modes');

  if (bootstrap_lite_is_header('get')) {

    if (function_exists('admin_bar_suppress') && user_access('access administration bar') && !admin_bar_suppress(FALSE)) {
      $variables['classes'][] = 'navbar-admin-bar';
    }
    if ($navbar_position = theme_get_setting('bootstrap_lite_navbar_position'))
    {
      $variables['classes'][] = 'navbar-is-' . $navbar_position;

       $config = config('admin_bar.settings');

      if (function_exists('admin_bar_suppress') &&  $navbar_position == 'fixed-top' && user_access('access administration bar') && !admin_bar_suppress(FALSE) && !$config->get('position_fixed') ){
        backdrop_add_js(backdrop_get_path('theme', 'bootstrap_lite') . '/js/navbar-fixed-top.js');
      }
      if ($navbar_position == 'static-top'){
        backdrop_add_js(backdrop_get_path('theme', 'bootstrap_lite') . '/js/navbar-static-top.js');
      }
    }
  }

  // Add 'not-front' if we're not front.
  if (!$variables['is_front']) {
    $variables['classes'][] = 'not-front';
  }

  // Add classes based on normal path parts.
  $normal_path = strtolower(backdrop_get_normal_path(request_path()));
  $normal_path = str_replace('_', '-', $normal_path);
  $path_parts = $normal_path ? explode('/', $normal_path) : array();
  if (!empty($path_parts)) {
    $path_classes = array('page-' . $path_parts[0]);
    for ($i = 1; $i < count($path_parts); $i++) {
      $path_classes[] = $path_classes[$i - 1] . '-' . $path_parts[$i];
    }
    $variables['classes'] = array_merge($variables['classes'], $path_classes);
  }

  // Add classes based on user roles.
  global $user;
  foreach ($user->roles as $role) {
    $variables['classes'][] = 'role-' . $role;
  }
}

/**
 * Implements hook_preprocess_header().
 */
function bootstrap_lite_preprocess_header(&$variables){
  $variables['navigation'] = '';

  if ($navbar_position = theme_get_setting('bootstrap_lite_navbar_user_menu'))
  {
    $user_menu = menu_tree('user-menu');
    $variables['navigation'] = render($user_menu);
  }

  $variables['navbar_classes_array'] = array('navbar');
  if ($navbar_position = theme_get_setting('bootstrap_lite_navbar_position'))
  {
    $variables['navbar_classes_array'][] = 'navbar-' . $navbar_position;
  }

  $variables['container_class'] = theme_get_setting('bootstrap_lite_container');

  if (theme_get_setting('bootstrap_lite_navbar_inverse')) {
    $variables['navbar_classes_array'][] = 'navbar-inverse';
  }
  else {
    $variables['navbar_classes_array'][] = 'navbar-default';
  }
}

/**
 * Implements hook_links().
 */
function bootstrap_lite_links__header_menu($menu){
  $menu['attributes']['class'] = array('menu','nav','navbar-nav');
  if ($navbar_menu_position = theme_get_setting('bootstrap_lite_navbar_menu_position')){
    $menu['attributes']['class'][] = $navbar_menu_position;
  }
  return theme_links($menu);
}

/**
 * Implements hook_menu_tree().
 */
function bootstrap_lite_menu_tree__user_menu($variables){
  if ($navbar_position = theme_get_setting('bootstrap_lite_navbar_user_menu')){
    return '
<ul class="menu nav navbar-nav navbar-right">
  <li class="dropdown">
    <a href="#" class="user-cog-link dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-cog"></span></a>
    <ul class="dropdown-menu">
    ' . $variables['tree'] . '
    </ul>
  </li>
</ul>';
  }
  return theme_menu_tree($variables);
}

/**
 * Returns HTML for a fieldset form element and its children.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #attributes, #children, #collapsed, #collapsible,
 *     #description, #id, #title, #value.
 *
 * @ingroup themeable
 */
function bootstrap_lite_fieldset($variables) {
  if (isset($variables['element']['#group_fieldset']) && !empty($variables['element']['#group_fieldset'])){
    return theme_fieldset($variables);
  }
  $element = $variables['element'];
  element_set_attributes($element, array('id'));
  _form_set_class($element, array('form-wrapper'));
  $element['#attributes']['class'][] = 'panel';
  $element['#attributes']['class'][] = 'panel-default';
  $output = '<fieldset' . backdrop_attributes($element['#attributes']) . '>';
  if (!empty($element['#title'])) {
    // Always wrap fieldset legends in a SPAN for CSS positioning.
    $output .= '<legend class="panel-heading"><span class="fieldset-legend">' . $element['#title'] . '</span></legend>';
  }
  $output .= '<div class="fieldset-wrapper panel-body">';
  if (!empty($element['#description'])) {
    $output .= '<div class="fieldset-description">' . $element['#description'] . '</div>';
  }
  $output .= $element['#children'];
  if (isset($element['#value'])) {
    $output .= $element['#value'];
  }
  $output .= '</div>';
  $output .= "</fieldset>\n";
  return $output;
}

/**
 * Returns HTML for a button form element.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #attributes, #button_type, #name, #value.
 *
 * @ingroup themeable
 */
function bootstrap_lite_button($variables) {

  if (isset($variables['element']['#attributes']['class'])){
    $default = TRUE;
    foreach($variables['element']['#attributes']['class'] as $key => $class){
      if (FALSE !== strpos($class, 'secondary')){
        if ($variables['element']['#id'] == 'edit-delete'){
          $variables['element']['#attributes']['class'][$key] = 'btn-danger';
          $default = FALSE;
        }else{
          $class = $variables['element']['#attributes']['class'][$key] = str_replace('secondary', 'default', $class);
        }
      }
      if (FALSE !== strpos($class, 'button')){
        $variables['element']['#attributes']['class'][$key] = str_replace('button', 'btn', $class);
        $default = FALSE;
      }
    }
    if ($default){
      $variables['element']['#attributes']['class'][] = 'btn-default';
    }
  } else{
    $variables['element']['#attributes']['class'][] = 'btn-default';
  }

  $variables['element']['#attributes']['class'][] = 'btn';
  return theme_button($variables);
}

/**
 * Returns HTML for an email form element.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #value, #description, #size, #maxlength,
 *     #placeholder, #required, #attributes, #autocomplete_path.
 *
 * @ingroup themeable
 */
function bootstrap_lite_email($variables) {
  $variables['element']['#attributes']['class'][] = 'form-control';
  return theme_email($variables);
}

/**
 * Returns HTML for an email form element in a webform.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #value, #description, #size, #maxlength,
 *     #placeholder, #required, #attributes, #autocomplete_path.
 *
 * @ingroup themeable
 */
function bootstrap_lite_webform_email($variables) {
  $variables['element']['#attributes']['class'][] = 'form-control';
  return theme_webform_email($variables);
}

/**
 * Returns HTML for a textfield form element.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #value, #description, #size, #maxlength,
 *     #placeholder, #required, #attributes, #autocomplete_path.
 *
 * @ingroup themeable
 */
function bootstrap_lite_textfield($variables) {
  $variables['element']['#attributes']['class'][] = 'form-control';
  return theme_textfield($variables);
}

/**
 * Returns HTML for a textarea form element.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #value, #description, #rows, #cols,
 *     #placeholder, #required, #attributes
 *
 * @ingroup themeable
 */
function bootstrap_lite_textarea($variables) {
  $variables['element']['#attributes']['class'][] = 'form-control';
  return theme_textarea($variables);
}

/**
 * Returns HTML for a form element.
 *
 * Each form element is wrapped in a DIV container having the following CSS
 * classes:
 * - form-item: Generic for all form elements.
 * - form-type-#type: The internal element #type.
 * - form-item-#name: The internal form element #name (usually derived from the
 *   $form structure and set via form_builder()).
 * - form-disabled: Only set if the form element is #disabled.
 *
 * In addition to the element itself, the DIV contains a label for the element
 * based on the optional #title_display property, and an optional #description.
 *
 * The optional #title_display property can have these values:
 * - before: The label is output before the element. This is the default.
 *   The label includes the #title and the required marker, if #required.
 * - after: The label is output after the element. For example, this is used
 *   for radio and checkbox #type elements as set in system_element_info().
 *   If the #title is empty but the field is #required, the label will
 *   contain only the required marker.
 * - invisible: Labels are critical for screen readers to enable them to
 *   properly navigate through forms but can be visually distracting. This
 *   property hides the label for everyone except screen readers.
 * - attribute: Set the title attribute on the element to create a tooltip
 *   but output no label element. This is supported only for checkboxes
 *   and radios in form_pre_render_conditional_form_element(). It is used
 *   where a visual label is not needed, such as a table of checkboxes where
 *   the row and column provide the context. The tooltip will include the
 *   title and required marker.
 *
 * If the #title property is not set, then the label and any required marker
 * will not be output, regardless of the #title_display or #required values.
 * This can be useful in cases such as the password_confirm element, which
 * creates children elements that have their own labels and required markers,
 * but the parent element should have neither. Use this carefully because a
 * field without an associated label can cause accessibility challenges.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #title_display, #description, #id, #required,
 *     #children, #type, #name.
 *
 * @ingroup themeable
 */
function bootstrap_lite_form_element($variables){
  if (isset($variables['element']['#type'])){
    if ($variables['element']['#type'] == 'checkbox'){
      $variables['element']['#wrapper_attributes']['class'][] = 'checkbox';
    }
    if ($variables['element']['#type'] == 'radio'){
      $variables['element']['#wrapper_attributes']['class'][] = 'radio';
    }
  }
  $description = FALSE;
  if (isset($variables['element']['#description'])){
    $description = $variables['element']['#description'];
    unset($variables['element']['#description']);
  }
  $output = theme_form_element($variables);
  if ($description){
    $output .= '<div class="description help-block">' . $description . "</div>\n";
  }
  return $output;
}

/**
 * Returns HTML for a password form element.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #value, #description, #size, #maxlength,
 *     #placeholder, #required, #attributes.
 *
 * @ingroup themeable
 */
function bootstrap_lite_password($variables) {
  $variables['element']['#attributes']['class'][] = 'form-control';
  return theme_password($variables);
}

/**
 * Returns HTML for a search form element.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #value, #description, #size, #maxlength,
 *     #placeholder, #required, #attributes, #autocomplete_path.
 *
 * @ingroup themeable
 */
function bootstrap_lite_search($variables) {

  if (isset($variables['element']['#attributes']['placeholder']) && $variables['element']['#attributes']['placeholder'] == t('Menu search')){
    return theme_search($variables);
  }

  $variables['element']['#attributes']['class'][] = 'form-control';
  return theme_search($variables);
}

/**
 * Returns HTML for a select form element.
 *
 * It is possible to group options together; to do this, change the format of
 * $options to an associative array in which the keys are group labels, and the
 * values are associative arrays in the normal $options format.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #value, #options, #description, #extra,
 *     #multiple, #required, #name, #attributes, #size.
 *
 * @ingroup themeable
 */
function bootstrap_lite_select($variables) {
  if (isset($variables['element']['#size'])){
    unset($variables['element']['#size']);
  }
  $variables['element']['#attributes']['class'][] = 'form-control';
  return theme_select($variables);
}

/**
 * Implements hook_preprocess_table().
 */
function bootstrap_lite_preprocess_table(&$variables) {
  $variables['attributes']['class'][] = 'table';
  $variables['attributes']['class'][] = 'table-hover';
  if (!in_array('table-no-striping', $variables['attributes']['class'])) {
    $variables['attributes']['class'][] = 'table-striped';
  }
}

/**
 * Returns HTML for an individual permission description.
 *
 * @param $variables
 *   An associative array containing:
 *   - permission_item: An associative array representing the permission whose
 *     description is being themed. Useful keys include:
 *     - description: The text of the permission description.
 *     - warning: A security-related warning message about the permission (if
 *       there is one).
 *
 * @ingroup themeable
 */
function bootstrap_lite_user_permission_description($variables) {
  $description = array();
  $permission_item = $variables['permission_item'];
  if (!empty($permission_item['description'])) {
    $description[] = $permission_item['description'];
  }
  if (!empty($permission_item['warning'])) {
    $description[] = '<em class="permission-warning text-danger">' . $permission_item['warning'] . '</em>';
  }
  if (!empty($description)) {
    return implode(' ', $description);
  }
}

/**
 * Returns HTML for an administrative block for display.
 *
 * @param $variables
 *   An associative array containing:
 *   - block: An array containing information about the block:
 *     - show: A Boolean whether to output the block. Defaults to FALSE.
 *     - title: The block's title.
 *     - content: (optional) Formatted content for the block.
 *     - description: (optional) Description of the block. Only output if
 *       'content' is not set.
 *
 * @ingroup themeable
 */
function bootstrap_lite_admin_block($variables) {
  $block = $variables['block'];
  $output = '';

  // Don't display the block if it has no content to display.
  if (empty($block['show'])) {
    return $output;
  }

  $output .= '<div class="panel panel-default">';
  if (!empty($block['title'])) {
    $output .= '<div class="panel-heading"><h3 class="panel-title">' . $block['title'] . '</h3></div>';
  }
  if (!empty($block['content'])) {
    $output .= $block['content'];
  }
  else {
    $output .= '<div class="description panel-body">' . $block['description'] . '</div>';
  }
  $output .= '</div>';

  return $output;
}

/**
 * Returns HTML for the output of the dashboard page.
 *
 * @param $variables
 *   An associative array containing:
 *   - menu_items: An array of modules to be displayed.
 *
 * @ingroup themeable
 */
function bootstrap_lite_system_admin_index($variables) {
  $menu_items = $variables['menu_items'];

  $stripe = 0;
  $container = array('left' => '', 'right' => '');
  $flip = array('left' => 'right', 'right' => 'left');
  $position = 'left';

  // Iterate over all modules.
  foreach ($menu_items as $module => $block) {
    list($description, $items) = $block;

    // Output links.
    if (count($items)) {
      $block = array();
      $block['title'] = $module;
      $block['content'] = theme('admin_block_content', array('content' => $items));
      $block['description'] = t($description);
      $block['show'] = TRUE;

      if ($block_output = theme('admin_block', array('block' => $block))) {
        if (!isset($block['position'])) {
          // Perform automatic striping.
          $block['position'] = $position;
          $position = $flip[$position];
        }
        $container[$block['position']] .= $block_output;
      }
    }
  }

  $output = '<div class="admin clearfix">';
  foreach ($container as $id => $data) {
    $output .= '<div class=" col-md-6 col-sm-12 clearfix">';
    $output .= $data;
    $output .= '</div>';
  }
  $output .= '</div>';

  return $output;
}

/**
 * Returns HTML for an administrative page.
 *
 * @param $variables
 *   An associative array containing:
 *   - blocks: An array of blocks to display. Each array should include a
 *     'title', a 'description', a formatted 'content' and a 'position' which
 *     will control which container it will be in. This is usually 'left' or
 *     'right'.
 *
 * @ingroup themeable
 */
function bootstrap_lite_admin_page($variables) {
  $blocks = $variables['blocks'];

  $stripe = 0;
  $container = array();

  foreach ($blocks as $block) {
    if ($block_output = theme('admin_block', array('block' => $block))) {
      if (empty($block['position'])) {
        // perform automatic striping.
        $block['position'] = ++$stripe % 2 ? 'left' : 'right';
      }
      if (!isset($container[$block['position']])) {
        $container[$block['position']] = '';
      }
      $container[$block['position']] .= $block_output;
    }
  }

  $output = '<div class="admin clearfix">';

  foreach ($container as $id => $data) {
    $output .= '<div class="clearfix  col-md-6 col-sm-12 ">';
    $output .= $data;
    $output .= '</div>';
  }
  $output .= '</div>';
  return $output;
}

/**
 * Returns HTML for primary and secondary local tasks.
 *
 * @param $variables
 *   An associative array containing:
 *     - primary: (optional) An array of local tasks (tabs).
 *     - secondary: (optional) An array of local tasks (tabs).
 *
 * @ingroup themeable
 * @see menu_local_tasks()
 */
function bootstrap_lite_menu_local_tasks(&$variables) {
  $output = '';

  if (!empty($variables['primary'])) {
    $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
    $variables['primary']['#prefix'] .= '<ul class="nav nav-tabs tabs-primary">';
    $variables['primary']['#suffix'] = '</ul>';
    $output .= backdrop_render($variables['primary']);
  }
  if (!empty($variables['secondary'])) {
    $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
    $variables['secondary']['#prefix'] .= '<ul class="nav nav-pills secondary">';
    $variables['secondary']['#suffix'] = '</ul>';
    $output .= backdrop_render($variables['secondary']);
  }

  return $output;
}

/**
 * Implements hook_links().
 */
function bootstrap_lite_links__dropbutton($menu){
  foreach($menu['links'] as $name => $settings){
    $menu['links'][$name]['attributes']['class'][] = 'btn';
    $menu['links'][$name]['attributes']['class'][] = 'btn-default';
  }
  return theme_links($menu);
}

/**
 * Returns rendered HTML for the local actions.
 */
function bootstrap_lite_menu_local_actions(&$variables) {
  foreach($variables['actions'] as $key => $link){
    switch($link['#link']['path']){
      case 'admin/people/create':
          $variables['actions'][$key]['#link']['title'] =  '<i class="fa fa-user-plus"></i>' . $link['#link']['title'];
          $variables['actions'][$key]['#link']['options']['html'] = TRUE;
          $variables['actions'][$key]['#link']['localized_options']['html'] = TRUE;
        break;
      default:
          $variables['actions'][$key]['#link']['title'] =  '<i class="fa fa-plus"></i>' . $link['#link']['title'];
          $variables['actions'][$key]['#link']['options']['html'] = TRUE;
          $variables['actions'][$key]['#link']['localized_options']['html'] = TRUE;
    }
  }

  $output = backdrop_render($variables['actions']);
  if ($output) {
    $output = '<ul class="nav nav-pills action-links">' . $output . '</ul>';
  }
  return $output;
}

/**
 * Returns HTML for a breadcrumb trail.
 *
 * @param $variables
 *   An associative array containing:
 *   - breadcrumb: An array containing the breadcrumb links.
 */
function bootstrap_lite_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  $output = '';
  if (!empty($breadcrumb)) {
    $output .= '<nav role="navigation">';
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output .= '<h2 class="element-invisible">' . t('You are here') . '</h2>';
    $output .= '<ol  class="breadcrumb" >';
    $count = 1;
    foreach($breadcrumb as $item){
      if ($count == count($breadcrumb)){
        $output .= '<li class="active">' . $item . '</li>';
      }else{
        $output .= '<li>' . $item . '</li>';
      }
      $count ++;
    }
    $output .= '</ol></nav>';
  }
  return $output;
}


/**
 * Implements hook_preprocess_breadcrumb().
 */
function bootstrap_lite_preprocess_breadcrumb(&$variables) {
  $breadcrumb = &$variables['breadcrumb'];

  // Optionally get rid of the homepage link.
  $show_breadcrumb_home = theme_get_setting('bootstrap_lite_breadcrumb_home');
  if (!$show_breadcrumb_home) {
    array_shift($breadcrumb);
  }
  if (theme_get_setting('bootstrap_lite_breadcrumb_title') && !empty($breadcrumb)) {
    $item = menu_get_item();
    $breadcrumb[] = !empty($item['tab_parent']) ? check_plain($item['title']) : backdrop_get_title();
  }
}

/**
 * Returns HTML to wrap child elements in a container.
 *
 * Used for grouped form items. Can also be used as a #theme_wrapper for any
 * renderable element, to surround it with a <div> and add attributes such as
 * classes or an HTML id.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #id, #attributes, #children.
 *
 * @ingroup themeable
 */
function bootstrap_lite_container($variables) {
  if (isset($variables['element']['#attributes']['class'][0]) && $variables['element']['#attributes']['class'][0] == 'views-display-column'){
    $variables['element']['#attributes']['class'] = array('col-xs-12','cols-sm-12', 'col-md-4');
  }
  return theme_container($variables);
}

/**
 * Display a view as a table style.
 */
function bootstrap_lite_preprocess_views_view_table(&$variables) {
  $variables['classes'][] = 'table';
}

/**
 * Implements hook_form_alter().
 */
function bootstrap_lite_form_alter(array &$form, array &$form_state = array(), $form_id = NULL) {
  if ($form_id) {
    if (isset($form['actions']['cancel']) && isset($form['actions']['cancel']['#type']) && $form['actions']['cancel']['#type'] == 'link'){
       $form['actions']['cancel']['#options']['attributes']['class'][] = 'btn';
       $form['actions']['cancel']['#options']['attributes']['class'][] = 'btn-default';
    }
    if (isset($form['actions']['cancel_form']) && $form['actions']['cancel_form']['#type'] == 'link'){
       $form['actions']['cancel']['#options']['attributes']['class'][] = 'btn';
       $form['actions']['cancel']['#options']['attributes']['class'][] = 'btn-default';
    }

  }
}

/**
 * Overrides theme_node_add_list().
 *
 * Display the list of available node types for node creation.
 */
function bootstrap_lite_node_add_list($variables) {
  $content = $variables['content'];
  $output = '';
  if ($content) {
    $output = '<ul class="list-group">';
    foreach ($content as $item) {
      $title = '<h4 class="list-group-item-heading">' . $item['title'] . '</h4>';
      if (isset($item['description'])){
        $title .= '<p class="list-group-item-text">' . filter_xss_admin($item['description']) . '</p>';
      }
      $item['localized_options']['attributes']['class'][] = 'list-group-item';
      $item['localized_options']['html'] = TRUE;
      $output .= l($title, $item['href'], $item['localized_options']);
    }
    $output .= '</ul>';
  }
  else {
    $output = '<p>' . t('You have not created any content types yet. Go to the <a href="@create-content">content type creation page</a> to add a new content type.', array('@create-content' => url('admin/structure/types/add'))) . '</p>';
  }
  return $output;
}

/**
 * Overrides theme_admin_block_content().
 *
 * Use unordered list markup in both compact and extended mode.
 */
function bootstrap_lite_admin_block_content($variables) {
  return bootstrap_lite_node_add_list($variables);
}

/**
 * Process variables for user-picture.tpl.php.
 *
 * The $variables array contains the following arguments:
 * - $account: A user, node or comment object with 'name', 'uid' and 'picture'
 *   fields.
 *
 * @see user-picture.tpl.php
 */
function bootstrap_lite_preprocess_user_picture(&$variables) {
  $variables['user_picture'] = '';
  if (config_get('system.core', 'user_pictures')) {
    $account = $variables['account'];
    if (!empty($account->picture)) {
      // @TODO: Ideally this function would only be passed file entities, but
      // since there's a lot of legacy code that JOINs the {users} table to
      // {node} or {comments} and passes the results into this function if we
      // a numeric value in the picture field we'll assume it's a file id
      // and load it for them. Once we've got user_load_multiple() and
      // comment_load_multiple() functions the user module will be able to load
      // the picture files in mass during the object's load process.
      if (is_numeric($account->picture)) {
        $account->picture = file_load($account->picture);
      }
      if (!empty($account->picture->uri)) {
        $filepath = $account->picture->uri;
      }
    }
    elseif (config_get('system.core', 'user_picture_default')) {
      $filepath = config_get('system.core', 'user_picture_default');
    }
    if (isset($filepath)) {
      $alt = t("@user's picture", array('@user' => user_format_name($account)));
      // If the image does not have a valid Backdrop scheme (for eg. HTTP),
      // don't load image styles.
      if (module_exists('image') && file_valid_uri($filepath) && $style = config_get('system.core', 'user_picture_style')) {
        $variables['user_picture'] = theme('image_style', array('style_name' => $style, 'uri' => $filepath, 'alt' => $alt, 'title' => $alt, 'attributes' => array('class' => 'img-circle')));
      }
      else {
        $variables['user_picture'] = theme('image', array('uri' => $filepath, 'alt' => $alt, 'title' => $alt, 'attributes' => array('class' => 'img-circle')));
      }
      if (!empty($account->uid) && user_access('access user profiles')) {
        $attributes = array('attributes' => array('title' => t('View user profile.')), 'html' => TRUE);
        $variables['user_picture'] = l($variables['user_picture'], "user/$account->uid", $attributes);
      }
    }
  }
}

/**
 * Implements hook_preprocess_comment().
 */
function bootstrap_lite_preprocess_comment(&$variables){
  if (theme_get_setting('bootstrap_lite_datetime')) {
    $comment = $variables['elements']['#comment'];
    $variables['timeago'] = t('@time ago', array('@time' => format_interval(time() - $comment->changed)));
  }
}

/**
 * Implements hook_preprocess_node().
 */
function bootstrap_lite_preprocess_node(&$variables){
  if (theme_get_setting('bootstrap_lite_datetime')) {
    $node = $variables['elements']['#node'];
    $variables['timeago'] = t('@time ago', array('@time' => format_interval(time() - $node->created)));
  }
}
