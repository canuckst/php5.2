<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=product_info.<br />
 * Displays details of a typical product
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_product_info_display.php 3215 2006-03-20 06:05:55Z birdbrain $
 */
 //require(DIR_WS_MODULES . '/debug_blocks/product_info_prices.php');
?>
<div id="productGeneral">

<script language="javascript">
// to check if an available option has been selected for the colour, size dropdown list, and colour, side radio buttons
function checkSelection(){
	// to check if an available option has been selected for the size dropdown list, in current system the id is 2
	if (window.document.getElementById("attrib-2")!=null) {
		var obj1 = window.document.getElementById("attrib-2");
		var idx = obj1.selectedIndex;
		var txt = obj1.options[idx].text.toLowerCase(); 
		if (txt.indexOf("select from") != -1) {
			alert('Please select the size before adding to the cart.');
			return false; 
		}
	}
	// to check if an available option has been selected for the colour dropdown list, in current system the id is 20
	if (window.document.getElementById("attrib-20")!=null) {
		obj1 = window.document.getElementById("attrib-20");
		idx = obj1.selectedIndex;
		txt = obj1.options[idx].text.toLowerCase(); 
		if (txt.indexOf("select from") != -1) {
			alert('Please select the colour before adding to the cart.');
			return false; 
		}
	}
	
	// to check if one of the colour radio buttons has been selected, in current system the id is 1
	if (window.document.getElementById("attrib-1")!=null) {
		obj1 = window.document.getElementsByName("id[1]");
		var myOption = -1;
		for (var i=obj1.length-1; i > -1; i--) {
			if (obj1[i].checked) {
				myOption = i; i = -1;
			}
		}

		if (myOption == -1) {
			alert('Please select the colour before adding to the cart.');
			return false; 
		}
	}

	// to check if one of the side radio buttons has been selected, in current system the id is 21
	if (window.document.getElementById("attrib-21")!=null) {
		obj1 = window.document.getElementsByName("id[21]");
		myOption = -1;
		for (var i=obj1.length-1; i > -1; i--) {
			if (obj1[i].checked) {
				myOption = i; i = -1;
			}
		}

		if (myOption == -1) {
			alert('Please select the side before adding to the cart.');
			return false; 
		}
	}
	
	return true;
}

//change product main image to designated image, and select designated radio button
function setSelection(imgsrc, radioid) {
	if (imgsrc != '') {
		window.document.all.prod_main_img.src=imgsrc;
	}
	window.document.getElementById(radioid).checked = true;
}
</script>


<!--bof Form start-->
<?php echo zen_draw_form('cart_quantity', zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params(array('action')) . 'action=add_product'), 'post', 'enctype="multipart/form-data" onSubmit="return checkSelection();"') . "\n"; ?>
<!--eof Form start-->

<?php if ($messageStack->size('product_info') > 0) echo $messageStack->output('product_info'); ?>

<!--bof Category Name-->
<!--<table cellpadding="0" cellspacing="0" align="center">
                <tbody><TR><TD HEIGHT=2></TD></TR>
<tr><td><img src="images/m22.gif" height="29" width="9"></td>
<td class="fe" width="506"> &nbsp; &nbsp; <?php echo '<a href="' . zen_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' . zen_get_categories_name(zen_get_products_category_id((int)$_GET['products_id']), $_SESSION['languages_id']) .  '</a>'; ?></td><td><img src="images/m23.gif" height="29" width="10"></td></tr>
<TR><TD HEIGHT=10></TD></TR>
</tbody></table>
-->
<!--eof Category Name-->

<!--bof Category Icon -->
<?php if ($module_show_categories != 0) {?>
<?php
/**
 * display the category icons
 */
require($template->get_template_dir('/tpl_modules_category_icon_display.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_category_icon_display.php'); ?>
<?php } ?>
<!--eof Category Icon -->

<!--eof Prev/Next top position -->
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == 1 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
<?php
/**
 * display the product previous/next helper
 */
require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
<?php } ?>
<!--eof Prev/Next top position-->

<!--bof Product Name-->
<h1 id="productName" class="productGeneral"><?php echo $products_name; ?></h1>
<!--eof Product Name-->

<!--bof Product Code block -->
 <h3 id="itemCode" class="productGeneral"> <?php echo (($flag_show_product_info_model == 1 and $products_model !='') ? 'Item Code: ' .  $products_model : '') . "\n"; ?> </h3>
<!--eof Product Code block -->

<!--bof Main Product Image -->
<?php
  if (zen_not_null($products_image)) {
  ?>
<?php
/**
 * display the main product image
 */
   require($template->get_template_dir('/tpl_modules_main_product_image.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_main_product_image.php'); ?>
<?php
  }
?>
<!--eof Main Product Image-->

<!-- price, qty, shipping, add to cart -->
<div id="leftColumn">

<!--bof Product Price block -->
<div id="productPrices" class="productGeneral">
<?php
// base price
  if ($show_onetime_charges_description == 'true') {
    $one_time = '<span >' . TEXT_ONETIME_CHARGE_SYMBOL . TEXT_ONETIME_CHARGE_DESCRIPTION . '</span><br />';
  } else {
    $one_time = '';
  }
  echo $one_time . ((zen_has_product_attributes_values((int)$_GET['products_id']) and $flag_show_product_info_starting_at == 1) ? TEXT_BASE_PRICE : '') . '<div id="itemPrice">'. zen_get_products_display_price_complex((int)$_GET['products_id']). '</div>';
?></div>
<!--<br class="clearBoth" /> -->
<!--eof Product Price block -->

<!--bof free ship icon  -->
<?php if(zen_get_product_is_always_free_shipping($products_id_current)) { ?>
<div id="freeShippingIcon"><?php echo TEXT_PRODUCT_FREE_SHIPPING_ICON; ?></div>
<?php } ?>
<!--eof free ship icon  -->

<!--bof Quantity Discounts table -->
<?php
  if ($products_discount_type != 0) { ?>
<?php
/**
 * display the products quantity discount
 */
 require($template->get_template_dir('/tpl_modules_products_quantity_discounts.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_products_quantity_discounts.php'); ?>
<?php
  }
?>
<!--eof Quantity Discounts table -->

<!--bof Add to Cart Box -->
<?php
if (CUSTOMERS_APPROVAL == 3 and TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE_SHOWROOM == '') {
  // do nothing
} else {

    $display_qty = (($flag_show_product_info_in_cart_qty == 1 and $_SESSION['cart']->in_cart($_GET['products_id'])) ? '<p>' . PRODUCTS_ORDER_QTY_TEXT_IN_CART . $_SESSION['cart']->get_quantity($_GET['products_id']) . '</p>' : '');
            if ($products_qty_box_status == 0 or $products_quantity_order_max== 1) {
              // hide the quantity box and default to 1
              $the_button = '<input type="hidden" name="cart_quantity" value="1" />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT);
            } else {
              // show the quantity box
    $the_button = PRODUCTS_ORDER_QTY_TEXT . '<input type="text" name="cart_quantity" value="' . (zen_get_buy_now_qty($_GET['products_id'])) . '" maxlength="6" size="4" /><br />' . zen_get_products_quantity_min_units_display((int)$_GET['products_id']) . '<br />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT);
            }
    $display_button = zen_get_buy_now_button($_GET['products_id'], $the_button);
    if ($display_qty != '' or $display_button != '') { ?>
    <div id="cartAdd">
    <?php
      echo $display_qty;
      echo $display_button;
            ?>
          </div>
  <?php } // display qty and button ?>
<?php } // CUSTOMERS_APPROVAL == 3 ?>
<!--eof Add to Cart Box-->
</div>
<br class="clearBoth" /> 
<!-- eof price, qty, shipping, add to cart -->

<!-- AddThis Button BEGIN -->
<table style="width:700">
<tr><td width=380>&nbsp;</td>
<td valign="top">
<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-50382f694934ff9b"></script>
</td></tr>
</table>
<!-- AddThis Button END -->

<!--bof Attributes Module -->
<?php
  if ($pr_attr->fields['total'] > 0) {
?>
<?php
/**
 * display the product atributes
 */
  require($template->get_template_dir('/tpl_modules_attributes.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_attributes.php'); ?>
<?php
  }
?>
<!--eof Attributes Module -->


 <!--bof Product description -->
<?php if ($products_description != '') { ?>
<div id="productDescription" class="productGeneral biggerText"><h4>Features:</h4>
	<?php echo stripslashes($products_description); ?></div>
<?php } ?>
<!--eof Product description -->
<!--<br class="clearBoth" /> -->

<!--bof Product details list  -->
<?php /* if ( (($flag_show_product_info_model == 1 and $products_model !='') or ($flag_show_product_info_weight == 1 and $products_weight !=0) or ($flag_show_product_info_quantity == 1) or ($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name))) ) { ?>
<ul id="productDetailsList" class="floatingBox back">
  <?php //echo (($flag_show_product_info_model == 1 and $products_model !='') ? '<li>' . TEXT_PRODUCT_MODEL . $products_model . '</li>' : '') . "\n"; ?>
  <?php //echo (($flag_show_product_info_weight == 1 and $products_weight !=0) ? '<li>' . TEXT_PRODUCT_WEIGHT .  $products_weight . TEXT_PRODUCT_WEIGHT_UNIT . '</li>'  : '') . "\n"; ?>
  <?php //echo (($flag_show_product_info_quantity == 1) ? '<li>' . $products_quantity . TEXT_PRODUCT_QUANTITY . '</li>'  : '') . "\n"; ?>
  <?php //echo (($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name)) ? '<li>' . TEXT_PRODUCT_MANUFACTURER . $manufacturers_name . '</li>' : '') . "\n"; ?>
</ul>
<br class="clearBoth" />
<?php
  } */
?>
<!--eof Product details list -->


<!--bof Additional Product Images -->
<?php
/**
 * display the products additional images
 */
  require($template->get_template_dir('/tpl_modules_additional_images.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_additional_images.php'); ?>
<!--eof Additional Product Images -->

<!--bof Prev/Next bottom position -->
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == 2 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
<?php
/**
 * display the product previous/next helper
 */
 require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
<?php } ?>
<!--eof Prev/Next bottom position -->

<!--bof Tell a Friend button -->
<?php 
  if ($flag_show_product_info_tell_a_friend == 1) { ?>
<!-- <div id="productTellFriendLink" class="buttonRow forward"><?php echo ($flag_show_product_info_tell_a_friend == 1 ? '<a href="' . zen_href_link(FILENAME_TELL_A_FRIEND, 'products_id=' . $_GET['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_TELLAFRIEND, BUTTON_TELLAFRIEND_ALT) . '</a>' : ''); ?></div> -->
<?php
  }
?>
<!--eof Tell a Friend button -->

<!--bof Reviews button and count-->
<?php
  if ($flag_show_product_info_reviews == 1) {
    // if more than 0 reviews, then show reviews button; otherwise, show the "write review" button
    if ($reviews->fields['count'] > 0 ) { ?>
<!-- <div id="productReviewLink" class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS, zen_get_all_get_params()) . '">' . zen_image_button(BUTTON_IMAGE_REVIEWS, BUTTON_REVIEWS_ALT) . '</a>'; ?></div> -->
<br class="clearBoth" />
<p class="reviewCount"><?php echo ($flag_show_product_info_reviews_count == 1 ? TEXT_CURRENT_REVIEWS . ' ' . $reviews->fields['count'] : ''); ?></p>
<?php } else { ?>
<!-- <div id="productReviewLink" class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, zen_get_all_get_params(array())) . '">' . zen_image_button(BUTTON_IMAGE_WRITE_REVIEW, BUTTON_WRITE_REVIEW_ALT) . '</a>'; ?></div> -->
<br class="clearBoth" />
<?php
  }
}
?>
<!--eof Reviews button and count -->


<!--bof Product date added/available-->
<?php
  if ($products_date_available > date('Y-m-d H:i:s')) {
    if ($flag_show_product_info_date_available == 1) {
?>
  <p id="productDateAvailable" class="productGeneral centeredContent"><?php //echo sprintf(TEXT_DATE_AVAILABLE, zen_date_long($products_date_available)); ?></p>
<?php
    }
  } else {
    if ($flag_show_product_info_date_added == 1) {
?>
      <p id="productDateAdded" class="productGeneral centeredContent"><?php //echo sprintf(TEXT_DATE_ADDED, zen_date_long($products_date_added)); ?></p>
<?php
    } // $flag_show_product_info_date_added
  }
?>
<!--eof Product date added/available -->

<!--bof Product URL -->
<?php
  if (zen_not_null($products_url)) {
    if ($flag_show_product_info_url == 1) {
?>
    <p id="productInfoLink" class="productGeneral centeredContent"><?php echo sprintf(TEXT_MORE_INFORMATION, zen_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($products_url), 'NONSSL', true, false)); ?></p>
<?php
    } // $flag_show_product_info_url
  }
?>
<!--eof Product URL -->

<!--bof also purchased products module-->
<?php require($template->get_template_dir('tpl_modules_also_purchased_products.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_also_purchased_products.php');?>
<!--eof also purchased products module-->

<!--bof Form close-->
</form>
<!--bof Form close-->
</div>
<div class="buttonRow forward"><?php echo '<a style="cursor:point;" onclick="javascript:history.back(-1);">' . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>