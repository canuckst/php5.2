<?php
/**
 * Common Template - tpl_main_page.php
 *
 * Governs the overall layout of an entire page<br />
 * Normally consisting of a header, left side column. center column. right side column and footer<br />
 * For customizing, this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * - make a directory /templates/my_template/privacy<br />
 * - copy /templates/templates_defaults/common/tpl_main_page.php to /templates/my_template/privacy/tpl_main_page.php<br />
 * <br />
 * to override the global settings and turn off columns un-comment the lines below for the correct column to turn off<br />
 * to turn off the header and/or footer uncomment the lines below<br />
 * Note: header can be disabled in the tpl_header.php<br />
 * Note: footer can be disabled in the tpl_footer.php<br />
 * <br />
 * $flag_disable_header = true;<br />
 * $flag_disable_left = true;<br />
 * $flag_disable_right = true;<br />
 * $flag_disable_footer = true;<br />
 * <br />
 * // example to not display right column on main page when Always Show Categories is OFF<br />
 * <br />
 * if ($current_page_base == 'index' and $cPath == '') {<br />
 *  $flag_disable_right = true;<br />
 * }<br />
 * <br />
 * example to not display right column on main page when Always Show Categories is ON and set to categories_id 3<br />
 * <br />
 * if ($current_page_base == 'index' and $cPath == '' or $cPath == '3') {<br />
 *  $flag_disable_right = true;<br />
 * }<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_main_page.php 3183 2006-03-14 07:58:59Z birdbrain $
 */

// the following IF statement can be duplicated/modified as needed to set additional flags
  if (in_array($current_page_base,explode(",",'list_pages_to_skip_all_right_sideboxes_on_here,separated_by_commas,and_no_spaces')) ) {
    $flag_disable_right = true;
  }


  $header_template = 'tpl_header.php';
  $footer_template = 'tpl_footer.php';
  $left_column_file = 'column_left.php';
  $right_column_file = 'column_right.php';
  $body_id = str_replace('_', '', $_GET['main_page']);
?>
<body id="<?php echo $body_id . 'Body'; ?>"<?php if($zv_onload !='') echo ' onload="'.$zv_onload.'"'; ?>>
<?php
  if (SHOW_BANNERS_GROUP_SET1 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET1)) {
    if ($banner->RecordCount() > 0) {
?>
<div id="bannerOne" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
<?php
    }
  }
?>

<div id="mainWrapper">
<?php
 /**
  * prepares and displays header output
  *
  */
  require($template->get_template_dir('tpl_header.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_header.php');?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" id="contentMainWrapper">
  <tr>
<?php
if (COLUMN_LEFT_STATUS == 0 or (CUSTOMERS_APPROVAL == '1' and $_SESSION['customer_id'] == '')) {
  // global disable of column_left
  $flag_disable_left = true;
}
if (!isset($flag_disable_left) || !$flag_disable_left) {
?>

 <td VALIGN=top>
<?php
 /**
  * prepares and displays left column sideboxes
  *
  */
?>
<div id="navColumnOneWrapper"><?php require(DIR_WS_MODULES . zen_get_module_directory('column_left.php')); ?></div>


<!--// bof: find out more //-->
<TABLE CELLSPACING=0 CELLPADDING=0 class="sidebox_left">
  <TR><TD HEIGHT=5></TD></TR>
  <TR><TD WIDTH="280" VALIGN=top>
<table cellpadding="0" cellspacing="0" align="center" class="box_header_category">
<tbody>
<tr><td class="hd_left"></td>
<td class="hd_center" >Find Out More</td>
<td class="hd_right"></td></tr>
</tbody></table>
  <TABLE CELLSPACING=0 CELLPADDING=0 class="box_body_category">
    <tr><td>
      <div class="box_wrapper">
      <a href="http://www.canuckstuff.com/store/index.php?main_page=index&cPath=65">Volleyball Uniforms</a>
      <a href="http://www.canuckbadminton.com/store/index.php?main_page=index&cPath=65">Badminton Uniforms</a>
      <a href="http://uniforms.canuckstuff.com/store/index.php?main_page=index&cPath=299">Baseball Uniforms</a>
      
      </div></td></tr>
    <TR class="box_bottom_category"><TD HEIGHT=17></TD></TR>
  </TABLE>
              
  </TD></TR>
  <TR><TD HEIGHT=5></TD></TR>        
</TABLE>
<!--// eof: find out more //-->

<!-- seal and visa -->
<TABLE CELLSPACING=0 CELLPADDING=0 width="280">
  <TR valign="middle"><TD align="center" height="46">
 
	    <!-- ssl seal and visa logo -->
        <span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=JW8yj6S3ZOnlBbHJIGKfY0Gy3BRkFcG9eYINcSwqMuQOuNZFGXa"></script></span> 
        <img src="./images/ico_visa.jpg"/>
  </TD></TR>
</TABLE>
</td>
<?php
}
?>
    <td valign="top">
<!-- bof  breadcrumb -->
<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
    <div id="navBreadCrumb"><?php echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?></div>
<?php } ?>
<!-- eof breadcrumb -->

<?php
  if (SHOW_BANNERS_GROUP_SET3 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET3)) {
    if ($banner->RecordCount() > 0) {
?>
<div id="bannerThree" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
<?php
    }
  }
?>

<table id="main_center_content" VALIGN="top" border="0" cellspacing="0" cellpadding="0" >
<tr><td>
<?php
 /**
  * prepares and displays center column
  *
  */
  
require($body_code); ?>
</td></tr>
</table>
<?php
  if (SHOW_BANNERS_GROUP_SET4 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET4)) {
    if ($banner->RecordCount() > 0) {
?>
<div id="bannerFour" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
<?php
    }
  }
?></td>

<?php
if (COLUMN_RIGHT_STATUS == 0 or (CUSTOMERS_APPROVAL == '1' and $_SESSION['customer_id'] == '')) {
  // global disable of column_right
  $flag_disable_right = true;
}
if (!isset($flag_disable_right) || !$flag_disable_right) {
?>
<td id="navColumnTwo" class="columnRight" style="width: <?php echo COLUMN_WIDTH_RIGHT; ?>">
<?php
 /**
  * prepares and displays right column sideboxes
  *
  */
?>
<div id="navColumnTwoWrapper" style="width: <?php echo BOX_WIDTH_RIGHT; ?>"><?php require(DIR_WS_MODULES . zen_get_module_directory('column_right.php')); ?></div></td>
<?php
}
?>
  </tr>
</table>

<?php
 /**
  * prepares and displays footer output
  *
  */
  require($template->get_template_dir('tpl_footer.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_footer.php');?>
</div>
<!--bof- parse time display -->
<?php
  if (DISPLAY_PAGE_PARSE_TIME == 'true') {
?>
<div class="smallText center">Parse Time: <?php echo $parse_time; ?> - Number of Queries: <?php echo $db->queryCount(); ?> - Query Time: <?php echo $db->queryTime(); ?></div>
<?php
  }
?>
<!--eof- parse time display -->
<!--bof- banner #6 display -->
<?php
  if (SHOW_BANNERS_GROUP_SET6 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET6)) {
    if ($banner->RecordCount() > 0) {
?>
<div id="bannerSix" class="banners" ALIGN=center><?php echo zen_display_banner('static', $banner); ?></div>
<?php
    }
  }
?>
<!--eof- banner #6 display -->
</body>