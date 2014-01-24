<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=advanced_search_result.<br />
 * Displays results of advanced search
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_advanced_search_result_default.php 2786 2006-01-05 01:52:38Z birdbrain $
 */
?>
<div id="advSearchResultsDefault">
<h1><?php echo HEADING_TITLE; ?></h1>
<!--
<table cellpadding="0" cellspacing="0">
                <tbody><tr><td height=2></td></tr><tr><td><img src="images/m22.gif" height="29" width="9"></td><td class="fe" bgcolor="#c6ed4e" width="506"> &nbsp; &nbsp; <?php echo HEADING_TITLE; ?></td><td><img src="images/m23.gif" height="29" width="10"></td></tr>
               </tbody>
</table>
-->
<?php
/**
 * Used to collate and display products from advanced search results
 */
 require($template->get_template_dir('tpl_modules_product_listing.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_product_listing.php');
?>

<div class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_ADVANCED_SEARCH, zen_get_all_get_params(array('sort', 'page', 'x', 'y')), 'NONSSL', true, false) . '">' . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>

</div>