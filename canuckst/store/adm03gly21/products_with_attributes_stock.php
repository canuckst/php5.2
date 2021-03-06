<?php
/**
 * @package admin
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: orders.php 0000 2006-10-15 00:00:00Z kuroi $
 */

require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
require(DIR_WS_CLASSES . 'products_with_attributes_stock.php');

$stock = new products_with_attributes_stock;

if(isset($_SESSION['languages_id'])){ $language_id = $_SESSION['languages_id'];} else { $language_id=1;}

if(isset($_GET['action']))
{
	$action = $_GET['action'];
}
else
{
	$action = '';
}

switch($action)
{
	case 'add':
		if(isset($_GET['products_id']) and is_numeric((int)$_GET['products_id']))
		{
			$products_id = (int)$_GET['products_id'];
		}
		if(isset($_POST['products_id']) and is_numeric((int)$_POST['products_id']))
		{
			$products_id = (int)$_POST['products_id'];
		}

		if(isset($products_id))
		{

			if(zen_products_id_valid($products_id))
			{

				$product_name = zen_get_products_name($products_id);
				$product_attributes = $stock->get_products_attributes($products_id, $language_id);
  				$hidden_form .= zen_draw_hidden_field('products_id',$products_id)."\n";
			}
			else
			{

				zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, zen_get_all_get_params(array('action')), 'NONSSL'));
			}
		}
		else
		{

			$query = 'SELECT DISTINCT
                        attrib.products_id, description.products_name
                      FROM 
                        '.TABLE_PRODUCTS_ATTRIBUTES.' attrib, '.TABLE_PRODUCTS_DESCRIPTION.' description
                      WHERE 
                        attrib.products_id = description.products_id and description.language_id='.$language_id.' order by description.products_name';

			$products = $db->execute($query);
			while(!$products->EOF)
			{
				$products_array_list[] = array(
				'id' => $products->fields['products_id'],
				'text' => $products->fields['products_name']
				);
				$products->MoveNext();
			}
		}
		break;
	case 'edit':
		$hidden_form = '';
		if(isset($_GET['products_id']) and is_numeric((int)$_GET['products_id']))
		{
			$products_id = $_GET['products_id'];
		}

		if(isset($_GET['attributes']))
		{
			$attributes = $_GET['attributes'];
		}

		if(isset($products_id) and isset($attributes))
		{
			$attributes = explode(',',$attributes);
			foreach($attributes as $attribute_id){
				$hidden_form .= zen_draw_hidden_field('attributes[]',$attribute_id)."\n";
				$attributes_list[] = $stock->get_attributes_name($attribute_id, $language_id);
			}
			$hidden_form .= zen_draw_hidden_field('products_id',$products_id)."\n";
		}
		else
		{
			zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, zen_get_all_get_params(array('action')), 'NONSSL'));
		}

		break;

	case 'confirm':
		if(isset($_POST['products_id']) and is_numeric((int)$_POST['products_id']))
		{

			if(!isset($_POST['quantity']) || !is_numeric($_POST['quantity']))
			{
				zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, zen_get_all_get_params(array('action')), 'NONSSL'));
			}
			$products_id = $_POST['products_id'];
			$product_name = zen_get_products_name($products_id);
			if(is_numeric($_POST['quantity']))
			{
				$quantity = $_POST['quantity'];
			}

			$attributes = $_POST['attributes'];
	
			foreach($attributes as $attribute_id)
			{
				$hidden_form .= zen_draw_hidden_field('attributes[]',$attribute_id)."\n";
				$attributes_list[] = $stock->get_attributes_name($attribute_id, $_SESSION['languages_id']);
			}
			$hidden_form .= zen_draw_hidden_field('products_id',$products_id)."\n";
			$hidden_form .= zen_draw_hidden_field('quantity',$quantity)."\n";
			$s_mack_noconfirm .="products_id=" . $products_id . "&"; //s_mack:noconfirm
			$s_mack_noconfirm .="quantity=" . $quantity . "&"; //s_mack:noconfirm

			if(sizeof($attributes) > 1)
			{
				sort($attributes);
				$stock_attributes = implode(',',$attributes);
			}
			else
			{
				$stock_attributes = $attributes[0];
			}
			$s_mack_noconfirm .='attributes=' . $stock_attributes . '&'; //kuroi: to pass string not array

			$query = 'select * from '.TABLE_PRODUCTS_WITH_ATTRIBUTES_STOCK.' where products_id = '.$products_id.' and stock_attributes="'.$stock_attributes.'"';
			$stock_check = $db->Execute($query);

			if(!$stock_check->EOF)
			{
				$hidden_form .= zen_draw_hidden_field('add_edit','edit');
				$hidden_form .= zen_draw_hidden_field('stock_id',$stock_check->fields['stock_id']);
				$s_mack_noconfirm .="stock_id=" . $stock_check->fields['stock_id'] . "&"; //s_mack:noconfirm
				$s_mack_noconfirm .="add_edit=edit&"; //s_mack:noconfirm
				$add_edit = 'edit';
			}
			else
			{
				$hidden_form .= zen_draw_hidden_field('add_edit','add')."\n";
				$s_mack_noconfirm .="add_edit=add&"; //s_mack:noconfirm
			}

		}
		else
		{
			zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, zen_get_all_get_params(array('action')), 'NONSSL'));
		}
		zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, $s_mack_noconfirm . 'action=execute&products_id='.$products_id, 'NONSSL')); //s_mack:noconfirm
		break;
	case 'execute':
		$attributes = $_POST['attributes'];
		if ($_GET['attributes']) { $attributes = $_GET['attributes']; } //s_mack:noconfirm

		$products_id = $_POST['products_id'];
		if ($_GET['products_id']) { $products_id = $_GET['products_id']; } //s_mack:noconfirm

		$quantity = $_GET['quantity']; //s_mack:noconfirm
		if ($_GET['quantity']) { $quantity = $_GET['quantity']; } //s_mack:noconfirm
		if(!is_numeric((int)$quantity)) //s_mack:noconfirm
		{
			zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, zen_get_all_get_params(array('action')), 'NONSSL'));
		}

		if(($_POST['add_edit'] == 'add') || ($_GET['add_edit'] == 'add')) //s_mack:noconfirm
		{
			$query = 'insert into `'.TABLE_PRODUCTS_WITH_ATTRIBUTES_STOCK.'` (`products_id`,`stock_attributes`,`quantity`) values ('.$products_id.',"'.$attributes.'",'.$quantity.')';

		}
		elseif(($_POST['add_edit'] == 'edit') || ($_GET['add_edit'] == 'edit')) //s_mack:noconfirm
		{
			$stock_id = $_POST['stock_id']; //s_mack:noconfirm
			if ($_GET['stock_id']) { $stock_id = $_GET['stock_id']; } //s_mack:noconfirm
			if(!is_numeric((int)$stock_id)) //s_mack:noconfirm
			{
				zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, zen_get_all_get_params(array('action')), 'NONSSL'));
			}

			$query = 'update `'.TABLE_PRODUCTS_WITH_ATTRIBUTES_STOCK.'` set quantity='.$quantity.' where stock_id='.$stock_id.' limit 1';
		}
		$db->Execute($query);

		$stock->update_parent_products_stock($products_id);
		$messageStack->add_session('Product successfully updated', 'success');
		zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'products_id='.$products_id, 'NONSSL'));

		break;
	case 'delete':
		$products_id=$_POST['products_id'];
		if(isset($_POST['confirm']))
		{
			// delete it
			if($_POST['confirm'] == 'Yes'){
				$query = 'delete from '.TABLE_PRODUCTS_WITH_ATTRIBUTES_STOCK.' where products_id="'.$_POST['products_id'].'" and stock_attributes="'.$_POST['attributes'].'" limit 1';
				$db->Execute($query);
				$stock->update_parent_products_stock((int)$_POST['products_id']);
				$messageStack->add_session('Product Variant was deleted', 'failure');
				zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'products_id='.$products_id, 'NONSSL'));
			} else {
				zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'products_id='.$products_id, 'NONSSL'));
			}
		}
		break;

	case 'resync':
		$products_id=$_GET['products_id'];
		if(is_numeric((int)$_GET['products_id'])){

			$stock->update_parent_products_stock((int)$_GET['products_id']);
			$messageStack->add_session('Parent Product Quantity Updated', 'success');
			zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'products_id='.$products_id, 'NONSSL'));

		} else {
			zen_redirect(zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'products_id='.$products_id, 'NONSSL'));
		}
		break;
	default:
		// Show a list of the products
	    if (isset($_POST['products_id'])) {
	    	$products_id = $_POST['products_id'];
	    } else {
	    	$products_id = $_GET['products_id'];
	    }
	    if (isset($_POST['products_model'])) {
	    	$products_model = $_POST['products_model'];
	    } else {
	    	$products_model = $_GET['products_model'];
	    }
	    break;
}


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" type="text/javascript" src="includes/menu.js"></script>
<script language="javascript" type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript">
<!--
function init()
{
	cssjsmenu('navbar');
	if (document.getElementById)
	{
		var kill = document.getElementById('hoverJS');
		kill.disabled = true;
	}
}
// -->
</script>
</head>
<body onLoad="init()">
<!-- header //-->
<?php
require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<div style="padding: 20px;">

<!-- body_text_eof //-->
<!-- body_eof //-->
<?php

switch($action)
{
	case 'add':


		if(isset($products_id))
		{

			echo zen_draw_form('final_refund_exchange', FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'action=confirm&products_id='.$products_id, 'post', '', true)."\n";
			echo $hidden_form;

			echo '<p><strong>'.$product_name.'</strong></p>'."\n";

			foreach($product_attributes as $option_name => $options)
			{
				echo '<p><strong>'.$option_name.': </strong>';
				echo zen_draw_pull_down_menu('attributes[]',$options).'</p>'."\n";

			}

			echo '<p><strong>' . PWA_QUANTITY . '</strong>'.zen_draw_input_field('quantity').'</p>'."\n";

		}
		else
		{

			echo zen_draw_form('final_refund_exchange', FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'action=add', 'post', '', true)."\n";
			echo zen_draw_pull_down_menu('products_id',$products_array_list)."\n";
		}

?>
	<p><input type="submit" value="<?php echo PWA_SUBMIT ?>"></p>
	</form>
<?php
break;
	case 'edit':

		echo zen_draw_form('final_refund_exchange', FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'action=confirm&products_id='.$products_id, 'post', '', true)."\n";
		echo '<h3>'.zen_get_products_name($products_id).'</h3>';

		foreach($attributes_list as $attributes)
		{
			echo '<p><strong>'.$attributes['option'].': </strong>'.$attributes['value'].'</p>';
		}

		echo $hidden_form;
		echo '<p><strong>Quantity: </strong>'.zen_draw_input_field('quantity', $_GET['q']).'</p>'."\n"; //s_mack:prefill_quantity
?>
	<p><input type="submit" value="<?php echo PWA_SUBMIT ?>"></p>
	</form>
<?php
break;
	case 'delete':
		if(!isset($_POST['confirm']))
		{

			echo zen_draw_form('final_refund_exchange', FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'action=delete&products_id='.$products_id, 'post', '', true)."\n";
			echo PWA_DELETE_VARIANT_CONFIRMATION;
			foreach($_GET as $key=>$value)
			{
				echo zen_draw_hidden_field($key,$value);
			}
?>
 	<p><input type="submit" value="<?php echo TEXT_YES ?>" name="confirm"> * <input type="submit" value="<?php echo TEXT_NO ?>" name="confirm"></p>
 	</form>
<?php  
		}
		break;
	case 'confirm':

		echo '<h3>Confirm '.$product_name.'</h3>';

		foreach($attributes_list as $attributes)
		{
			echo '<p><strong>'.$attributes['option'].': </strong>'.$attributes['value'].'</p>';
		}

		echo '<p><strong>Quantity</strong>'.$quantity.'</p>';
		echo zen_draw_form('final_refund_exchange', FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'action=execute&products_id='.$products_id, 'post', '', true)."\n";
		echo $hidden_form;
?>
	<p><input type="submit" value="<?php echo PWA_SUBMIT ?>"></p>
	</form>
<?php 	
break;
	default:
		echo zen_draw_form('final_refund_exchange', FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, 'action=list', 'post', '', true)."\n";
		
		echo $hidden_form;
		echo '<p><strong>Products ID: </strong>'.zen_draw_input_field('products_id', $_POST['products_id']).' or '."\n";
		echo '<strong>Products Model: </strong>'.zen_draw_input_field('products_model', $_POST['products_model']).'</p>'."\n";
		
	?>
	<p><input type="submit" value="<?php echo PWA_SUBMIT ?>"></p>
	</form>


	<table id="mainProductTable">
	  <tr>
      <th><?php echo PWA_PRODUCT_ID; ?></th>
      <th><?php echo PWA_PRODUCT_NAME; ?></th>
      <th><?php echo PWA_PRODUCT_MODEL; ?></th>			
      <th><?php echo PWA_QUANTITY_FOR_ALL_VARIANTS; ?></th>
      <th><?php echo PWA_ADD_QUANTITY; ?></th> 
      <th><?php echo PWA_SYNC_QUANTITY; ?></th>
	  </tr>
<?php

// Show a list of the products
if ((isset($products_id)) && ($products_id !== '')) {
	$query = 'select distinct attrib.products_id, description.products_name, products.products_quantity, products.products_model
   	FROM '.TABLE_PRODUCTS_ATTRIBUTES.' attrib, '.TABLE_PRODUCTS_DESCRIPTION.' description, '.TABLE_PRODUCTS.' products
   	WHERE products.products_id='.$products_id.' and products.products_status is true and 
    	attrib.products_id = description.products_id and
    	attrib.products_id = products.products_id and description.language_id='.$language_id.' order by description.products_name ';
} else if ((isset($products_model)) && ($products_model !== '')) {
	$query = 'select distinct attrib.products_id, description.products_name, products.products_quantity, products.products_model
   	FROM '.TABLE_PRODUCTS_ATTRIBUTES.' attrib, '.TABLE_PRODUCTS_DESCRIPTION.' description, '.TABLE_PRODUCTS.' products
   	WHERE products.products_model like "'.$products_model.'%" and products.products_status is true and 
    	attrib.products_id = description.products_id and
    	attrib.products_id = products.products_id and description.language_id='.$language_id.' order by description.products_name ';
} else {
	$query = 'select distinct attrib.products_id, description.products_name, products.products_quantity, products.products_model
		FROM '.TABLE_PRODUCTS_ATTRIBUTES.' attrib, '.TABLE_PRODUCTS_DESCRIPTION.' description, '.TABLE_PRODUCTS.' products
	   	WHERE attrib.products_id = description.products_id and products.products_status is true and 
    	attrib.products_id = products.products_id and description.language_id='.$language_id.' order by description.products_name ';
}

$products = $db->Execute($query);

while(!$products->EOF)
{
	echo '<tr class="productRow">'."\n";
	echo '<td>'.$products->fields['products_id'].'</td>';
	echo '<td>'.$products->fields['products_name'].'</td>';
	echo '<td>'.$products->fields['products_model'].'</td>';	
	echo '<td>'.$products->fields['products_quantity'].'</td>';
	echo '<td><a href="'.zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, "action=add&amp;products_id=".$products->fields['products_id'], 'NONSSL').'">' . PWA_ADD_QUANTITY . '</a></td>';
	echo '<td><a href="'.zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, "action=resync&amp;products_id=".$products->fields['products_id'], 'NONSSL').'">' . PWA_SYNC_QUANTITY . '</a></td>';
	echo '</tr>'."\n";

	$query = 'select * from '.TABLE_PRODUCTS_WITH_ATTRIBUTES_STOCK.' where products_id="'.$products->fields['products_id'].'"';

	$attribute_products = $db->Execute($query);
	if($attribute_products->RecordCount() > 0)
	{

		echo '<tr>'."\n";
		echo '<td colspan="6">'."\n";
		echo '<table class="stockAttributesTable">';
		echo '<tr>';
		echo '<th class="stockAttributesHeadingStockId">'.PWA_STOCK_ID.'</th><th class="stockAttributesHeadingVariant">'.PWA_VARIANT.'</th><th class="stockAttributesHeadingQuantity">'.PWA_QUANTITY_IN_STOCK.'</th><th class="stockAttributesHeadingEdit">'.PWA_EDIT.'</th><th class="stockAttributesHeadingDelete">'.PWA_DELETE.'</th>';
		echo '</tr>';


		while(!$attribute_products->EOF)
		{
			echo '<tr>';
			echo '<td class="stockAttributesCellStockId">'."\n";
			echo $attribute_products->fields['stock_id'];
			echo '</td>'."\n";
			echo '<td class="stockAttributesCellVariant">'."\n";

			$attributes_of_stock = explode(',',$attribute_products->fields['stock_attributes']);
			$attributes_output = array();
			foreach($attributes_of_stock as $attri_id)
			{
				$stock_attribute = $stock->get_attributes_name($attri_id, $_SESSION['languages_id']);
				$attributes_output[] = '<strong>'.$stock_attribute['option'].':</strong> '.$stock_attribute['value'].'<br/>';
			}
			sort($attributes_output);
			echo implode("\n",$attributes_output);

			echo '</td>'."\n";
			echo '<td class="stockAttributesCellQuantity">'."\n";
			echo $attribute_products->fields['quantity'];
			echo '</td>'."\n";
			echo '<td class="stockAttributesCellDelete">'."\n";
			echo '<a href="'.zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, "action=edit&amp;products_id=".$products->fields['products_id'].'&amp;attributes='.$attribute_products->fields['stock_attributes'].'&amp;q='.$attribute_products->fields['quantity'], 'NONSSL').'">'.PWA_EDIT_QUANTITY.'</a>'; //s_mack:prefill_quantity



			echo '</td>'."\n";
			echo '<td class="stockAttributesCellEdit">'."\n";
			echo '<a href="'.zen_href_link(FILENAME_PRODUCTS_WITH_ATTRIBUTES_STOCK, "action=delete&amp;products_id=".$products->fields['products_id'].'&amp;attributes='.$attribute_products->fields['stock_attributes'], 'NONSSL').'">'.PWA_DELETE_VARIANT.'</a>';
			echo '</td>'."\n";
			echo '</tr>';

			$attribute_products->MoveNext();
		}
		echo '</table>';
		echo '</td>'."\n";
		echo '</tr>'."\n";
	}

	$products->MoveNext();

}
?>
	</table>
<?php  
break;
}
?>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>