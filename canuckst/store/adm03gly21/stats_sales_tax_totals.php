<?php
/*
//////////////////////////////////////////////////////////
//  SALES TAX	TOTALS REPORT                        			//
//                                                      //
//  By Heather Gardner (AKA: LadyHLG)                   //
//                                                      //
//  Powered by Zen-Cart (www.zen-cart.com)              //
//  Portions Copyright (c) 2006 The Zen Cart Team       //
//                                                      //
//  Released under the GNU General Public License       //
//  available at www.zen-cart.com/license/2_0.txt       //
//  or see "license.txt" in the downloaded zip          //
/////DESCRIPTION//////////////////////////////////////////
//	Simple sales tax summary report						 					//
//////////////////////////////////////////////////////////
//  $Id: stats_sales_tax_totals.php v2		 							//
*/


  require('includes/application_top.php');
	require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $janfirst = mktime(0, 0, 0, 01, 01, date("y"));
	$_GET['start_date'] = (!isset($_GET['start_date']) ? date("m-d-Y",($janfirst)) : $_GET['start_date']);
  $_GET['end_date'] = (!isset($_GET['end_date']) ? date("m-d-Y",(time())) : $_GET['end_date']);


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
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
<?php 
	$str_today = date("m-d-Y",(time()));
	$date_today = explode("-", $str_today);

?>  
  var day0 = new Date(<?php echo $date_today[2] . ', ' . ($date_today[0] - 1) . ', ' . $date_today[1] ?>);
  var day_y = new Date(day0 - 1);
  var day_lm1 = new Date(new Date( <?php echo $date_today[2] . ', ' . ($date_today[0] - 1) . ', 1' ?>) - 1);
  var day_lm0 = new Date(new Date( day_lm1.getYear(), day_lm1.getMonth(), 1));
//  alert(day_y);
  function setToday()
  {
//	var str = (today.getMonth()) + '-' + today.getDate() + '-' + today.getYear();
  	document.all.start_date.value='<?php echo $str_today; ?>';
  	document.all.end_date.value='<?php echo $str_today; ?>';
  }
  function setYesterday()
  {
	var str = (day_y.getMonth()+1) + '-' + day_y.getDate() + '-' + day_y.getYear();
  	document.all.start_date.value= str;
  	document.all.end_date.value= str;
  }
  function setThisMonth()
  {
  	document.all.start_date.value='<?php echo $date_today[0] . '-01-' . $date_today[2]; ?>';
  	document.all.end_date.value='<?php echo $str_today; ?>';
  }
  function setLastMonth()
  {
  	document.all.start_date.value=(day_lm0.getMonth()+1) + '-' + day_lm0.getDate() + '-' + day_lm0.getYear();
  	document.all.end_date.value=(day_lm1.getMonth()+1) + '-' + day_lm1.getDate() + '-' + day_lm1.getYear();
  }
  function setThisYear()
  {
  	document.all.start_date.value='<?php echo '01-01-' . $date_today[2]; ?>';
  	document.all.end_date.value='<?php echo $str_today; ?>';
  }
  function setLastYear()
  {
  	document.all.start_date.value='<?php echo '01-01-' . ($date_today[2]-1); ?>';
  	document.all.end_date.value='<?php echo '12-31-' . ($date_today[2]-1); ?>';
  }
  // -->
</script>
<style type="text/css">
<!--
.taxtypeheaderrow{
background-color:#7f4000;
color:#FFFFFF;
font-weight:bold;
}
-->
</style>

</head>
<body onLoad="init()">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
        <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
      </tr>

      <tr>
        <td>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="2" cellpadding="2">
              <tr><?php echo zen_draw_form('search', FILENAME_STATS_SALES_TAX_TOTALS, '', 'get'); ?>
                <td class="main" align="right"><?php echo TEXT_INFO_START_DATE . ' ' . zen_draw_input_field('start_date', $_GET['start_date']); ?></td>
                <td class="main" align="right"><?php echo TEXT_INFO_END_DATE . ' ' . zen_draw_input_field('end_date', $_GET['end_date']) . zen_hide_session_id(); ?></td>
                <td class="main" align="right"><?php echo zen_draw_checkbox_field('order_details', '1', false) . CHECKBOX_SHOW_ORDER_DETAILS; ?></td>
								<td class="main" align="right"><?php echo zen_image_submit('button_display.gif', IMAGE_DISPLAY); ?></td></tr>
              <tr>
				<td class="main" colspan="4">Report for: 
                <input type="button" onClick="javascript:setToday(); search.submit();" value="Today"> <input type="button" onClick="javascript:setYesterday(); search.submit();" value="Yesterday"> <input type="button" onClick="javascript:setThisMonth(); search.submit();" value="This Month"> <input type="button" onClick="javascript:setLastMonth(); search.submit();" value="Last Month"> <input type="button" onClick="javascript:setThisYear(); search.submit();" value="This Year"> <input type="button" onClick="javascript:setLastYear(); search.submit();" value="Last Year"></td>
              </tr>
            </table></form>
            </td>
          </tr>

<?php
// reverse date from m-d-y to y-m-d
    $date1 = explode("-", $_GET['start_date']);
    $m1 = $date1[0];
    $d1 = $date1[1];
    $y1 = $date1[2];

    $date2 = explode("-", $_GET['end_date']);
    $m2 = $date2[0];
    $d2 = $date2[1];
    $y2 = $date2[2];

    $sd = $y1 . '-' . $m1 . '-' . $d1 . ' 0:00:00';
    $ed = $y2. '-' . $m2 . '-' . $d2 . ' 23:59:59';

//  $sd = $_GET['start_date'];
//  $ed = $_GET['end_date'];

 global $currencies;
 
 define('STATUS_ORDER_DELIVERED', 4);
 
//get grand total of shipping fees
$shipping_total_sum_query = "SELECT Sum(ot.value) AS SumOfShipping FROM " . TABLE_ORDERS_TOTAL . " as ot INNER JOIN " . TABLE_ORDERS . " as o ON ot.orders_id = o.orders_id WHERE ot.class='ot_shipping' AND  o.orders_status >= " . STATUS_ORDER_DELIVERED . " AND o.date_purchased >= '" . $sd . "' and o.date_purchased <= '" . $ed . "'";
 $shipping_total_sum = $db->Execute($shipping_total_sum_query);
 $TotalShipping = $shipping_total_sum->fields['SumOfShipping'];
 $ShippingValue = $currencies->format($TotalShipping);

//get grand total of sales taxes
$sales_tax_total_sum_query = "SELECT Sum(ot.value) AS SumOfSalesTaxes FROM " . TABLE_ORDERS_TOTAL . " as ot INNER JOIN " . TABLE_ORDERS . " as o ON ot.orders_id = o.orders_id WHERE ot.class in ('ot_tax','ot_custom') AND o.orders_status >= " . STATUS_ORDER_DELIVERED . "  AND o.date_purchased >= '" . $sd . "' and o.date_purchased <= '" . $ed . "'";
 $sales_tax_total_sum = $db->Execute($sales_tax_total_sum_query);
 $TotalTaxPaid = $sales_tax_total_sum->fields['SumOfSalesTaxes'];
 $Taxvalue = $currencies->format($TotalTaxPaid);  

//get sub-total of sales
$sales_subtotal_sum_query = "SELECT Sum(ot.value) AS SubOfSales FROM " . TABLE_ORDERS_TOTAL . " as ot INNER JOIN " . TABLE_ORDERS . " as o ON ot.orders_id = o.orders_id WHERE ot.class='ot_subtotal' AND o.orders_status >= " . STATUS_ORDER_DELIVERED . "  AND o.date_purchased >= '" . $sd . "' and o.date_purchased <= '" . $ed . "'";
 $sales_subtotal_sum = $db->Execute($sales_subtotal_sum_query);
 $SubTotalSales = $sales_subtotal_sum->fields['SubOfSales'];
 $SubSalesvalue = $currencies->format($SubTotalSales);  

//get grand total of sales
$sales_total_sum_query = "SELECT Sum(ot.value) AS SumOfSales FROM " . TABLE_ORDERS_TOTAL . " as ot INNER JOIN " . TABLE_ORDERS . " as o ON ot.orders_id = o.orders_id WHERE ot.class='ot_total' AND o.orders_status >= " . STATUS_ORDER_DELIVERED . "  AND o.date_purchased >= '" . $sd . "' and o.date_purchased <= '" . $ed . "'";
 $sales_total_sum = $db->Execute($sales_total_sum_query);
 $TotalSales = $sales_total_sum->fields['SumOfSales'];
 $Salesvalue = $currencies->format($TotalSales);  

 //get discount of sales
 $sales_discount_sum_query = "SELECT Sum(ot.value) AS SumOfDiscount FROM " . TABLE_ORDERS_TOTAL . " as ot INNER JOIN " . TABLE_ORDERS . " as o ON ot.orders_id = o.orders_id WHERE ot.class='ot_coupon' AND o.orders_status >= " . STATUS_ORDER_DELIVERED . "  AND o.date_purchased >= '" . $sd . "' and o.date_purchased <= '" . $ed . "'";
 $sales_discount_sum = $db->Execute($sales_discount_sum_query);
 $TotalDiscount = $sales_discount_sum->fields['SumOfDiscount'];
 $TotalDiscountvalue = $currencies->format($TotalDiscount);
 
 
 ?>
		<tr>
            <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
 
    	<tr>
        <td class="dataTableHeadingContent"><?php echo $_GET['start_date'] . " thru " . $_GET['end_date'] . "<table style='font-weight:bold;'><tr height=1><td style='background-color:#666;' colspan=3></td></tr>
          <tr><td align=right>Sub-Total : </td><td align=right>" . $SubSalesvalue . "</td><td colspan=2 style='font-weight:normal;font-size:0.8em;'>(Excluding shipping fee and taxes)</td></tr>
          <tr><td align=right>Total Shipping Fees : </td><td align=right>" . $ShippingValue . "</td><td></td></tr>
          <tr><td align=right>" . TEXT_INFO_SALES_TAX_TOTAL_SUMMARY .  ":  </td><td align=right>" . $Taxvalue . " </td><td style='font-weight:normal;font-size:0.8em;'>(Including Shipping Taxes)</td></tr>
          <tr><td align=right>Total Discount : </td><td align=right>(" . $TotalDiscountvalue . ")</td><td></td></tr>
          <tr><td align=right>" . TEXT_INFO_SALES_TOTAL_SUMMARY . ": </td><td align=right>" . $Salesvalue;?></td><td style="font-weight:normal;font-size:0.8em;" colspan="2"> (Includes Total Shipping Fees, Total Sales Taxes and Total Discount.) </td></tr>
          <tr height=1><td style='background-color:#666;' colspan=3></td></tr></table>
        </td>
      </tr>
			<tr><td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
					
<?php
 
  //list out indiividual sales tax totals
  $sales_tax_total_query = "SELECT ot.title, Sum(ot.value) AS SumOfSalesTax FROM " . TABLE_ORDERS_TOTAL . " as ot INNER JOIN " . TABLE_ORDERS . " as o ON ot.orders_id = o.orders_id WHERE ot.class in ('ot_tax','ot_custom') AND o.orders_status >=  " . STATUS_ORDER_DELIVERED . " AND o.date_purchased >= '" . $sd . "' and o.date_purchased <= '" . $ed . "' GROUP BY ot.title ORDER BY ot.title";
  $sales_tax_total = $db->Execute($sales_tax_total_query);
?>
          <tr>
            <td>
						<table border="0" cellspacing="2" cellpadding="2">
						<tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" align="right" >&nbsp; <?php echo TABLE_HEADING_SALES_TAX_TYPE; ?> &nbsp;</td>
						<td class="dataTableHeadingContent">&nbsp; <?php echo TABLE_HEADING_SALES_TAX_TOTAL; ?> &nbsp;</td>
          </tr>
<?php
//echo 'I see ' . $sales_tax_total->RecordCount() . '<br>' . $sales_tax_total_query . '<br><br>' . 'start ' . date($_GET['start_date']) . ' end ' . date($_GET['end_date']) . '<br>Referral: ' . $_GET['referral_code'] . ' ' . strlen($_GET['referral_code']) . '<br>';
  
  while (!$sales_tax_total->EOF) {

		 $splitTax = splitTax($sales_tax_total->fields['title'], $sales_tax_total->fields['SumOfSalesTax']); 

		 while (list($desc,$tax) = each($splitTax)) {
					?>
          	<tr>
            	<td class="dataTableContent" align="right"><?php echo trim(trim($desc), ':') . ':';	?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($tax);	?></td>
          	</tr>
<?php
		 }
    $sales_tax_total->MoveNext();
  }
?>
	        </table>
					</td>
      </tr>
		
	<tr><td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>				
<?php
 if($_GET['order_details']==1){
  //list out indiividual sales tax totals
  $sales_tax_total_query = "SELECT ot.orders_id, ot.title, ot.value, ot.class FROM " . TABLE_ORDERS_TOTAL . " as ot INNER JOIN " . TABLE_ORDERS . " as o ON ot.orders_id = o.orders_id WHERE ot.class in ('ot_tax','ot_custom') AND o.orders_status >=  " . STATUS_ORDER_DELIVERED . "  AND o.date_purchased >= '" . $sd . "' and o.date_purchased <= '" . $ed . "' ORDER BY ot.title";
  $sales_tax_total = $db->Execute($sales_tax_total_query);
  $tax_title = '';
  $orders_summary_array = array()
?>
          <tr>
            <td>
						<table border="1" width="100%" cellspacing="2" cellpadding="2">
						<tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDERS_SUMMARY . " - " . $_GET['start_date'] . " thru " . $_GET['end_date'];?></td>
          </tr>
<?php
  //echo 'I see ' . $sales_tax_total->RecordCount() . '<br>' . $sales_tax_total_query . '<br><br>' . 'start ' . date($_GET['start_date']) . ' end ' . date($_GET['end_date']) . '<br>Referral: ' . $_GET['referral_code'] . ' ' . strlen($_GET['referral_code']) . '<br>';
  while (!$sales_tax_total->EOF) {
				$tax_orderid = $sales_tax_total->fields['orders_id'];
				
				//are we switching tax groups?
				if($tax_title != $sales_tax_total->fields['title']){
					//make next tax group heading 
						$tax_title = $sales_tax_total->fields['title'];
						echo "<tr><td class='taxtypeheaderrow'>".  $tax_title . "</td></tr>";
				}	
		 ?>
		 <tr>
     <td class="dataTableContent">
		 <?php 
		 	echo "<a href='orders.php?oID=". $tax_orderid. "&action=edit'>Order Number: " . $tax_orderid . "</a><br />";		 
		 
		 	$order_details_query = "SELECT ot.orders_total_id, ot.orders_id, ot.title, ot.text, ot.value, ot.class FROM " . TABLE_ORDERS_TOTAL . " as ot WHERE ot.orders_id = " . $tax_orderid . " ORDER BY ot.orders_total_id";
  		$order_details = $db->Execute($order_details_query);
			while (!$order_details->EOF) {
			
						echo $order_details->fields['title'] . "   " . $order_details->fields['text'] . "<br />";
						$orders_summary_array[] = array(
																							'orderid' => $order_details->fields['orders_id'],
																							'title' => $order_details->fields['title'],
																							'value' => $order_details->fields['value'],
																							'class' => $order_details->fields['class']);						

			    $order_details->MoveNext();
  }
		 ?>
		 </td>
		 </tr>
<?php		 


    $sales_tax_total->MoveNext();
  }
	} 
?>

        </table>
				</td>
      </tr>

    </td>
  </tr>
</table>
