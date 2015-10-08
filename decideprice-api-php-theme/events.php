<?php

include "functions.php";
if (!class_exists('decideprice_theme')) {
    require_once('decideprice_theme.php');
}
$decideprice_theme = new decideprice_theme();
$connects          = new common;
if (isset($_POST['type'])) {
    $type = $connects->sanitize($_POST["type"]);
    if ($type !== '') {
        if ($type === 'add_cart_element') {
            $msg              = '';
            $error            = '';
            $buy_message      = '';
            $merchant         = $connects->sanitize($_POST['merchant_id']);
            $shop             = $connects->sanitize($_POST['shop_id']);
            $product          = $connects->sanitize($_POST['product_id']);
            $check_cart_stock = $decideprice_theme->get('product-stock/' . $product);
            if ($check_cart_stock['result']) {
                $add = $connects->set_cart_cookie($merchant, $shop, $product);
                if ($add) {
                    $msg = 'Product Added to Cart';
                } else {
                    if (isset($_POST['buy'])) {
                        $buy_message = 'buy product';
                        $error       = '';
                        $msg         = '';
                    } else {
                        $error = 'This product is already in cart';
                    }
                }
            } else {
                $error = 'Product not in stock';
            }
            
            echo json_encode(array(
                'msg' => $msg,
                'error' => $error,
                'buy_message' => $buy_message
            ));
        }
        
        if ($type === 'remove_cart') {
            $empty    = '';
            $msg      = '';
            $error    = '';
            $merchant = $connects->sanitize($_POST['merchant_id']);
            $shop     = $connects->sanitize($_POST['shop_id']);
            $product  = $connects->sanitize($_POST['product_id']);
            $flag     = 1;
            $remove   = $connects->remove_cart_cookie($merchant, $shop, $product);
            if ($remove && $remove === 'empty') {
                $empty = 'true';
            } elseif ($remove) {
                $msg = 'Product removed from cart';
            } else {
                $error = 'No such product in cart';
            }
            echo json_encode(array(
                'msg' => $msg,
                'error' => $error,
                'empty' => $empty
            ));
        }
        
        
        if ($type === 'update_cart') {
            $html     = '';
            $html_amt = '';
            $total    = 0;
            if (isset($_COOKIE['decideprice_cart'])) {
                $html       = '';
                $html_amt   = '';
                $cart_data  = stripcslashes($_COOKIE['decideprice_cart']);
                $cart_array = json_decode($cart_data, TRUE);
                
                $cart_data = $decideprice_theme->get('/get-cart-data/' . $_COOKIE['decideprice_cart']);
                
                if (is_array($cart_data)) {
                    $sub_total = 0;
                    foreach ($cart_data['result'] as $key => $value) {
                        // print_r($value['merchant_id']);
                        $cnt = 1;
                        if (isset($_COOKIE['decideprice_cart_count'])) {
                            $cart_data  = stripcslashes($_COOKIE['decideprice_cart_count']);
                            $cart_count = json_decode($cart_data, TRUE);
                        }
                        foreach ($cart_count as $key1 => $value1) {
                            
                            if (isset($value1[$value['merchant_id'] . '_' . $value['shop_id'] . '_' . $value['prod_det_id']])) {
                                $cnt = $value1[$value['merchant_id'] . '_' . $value['shop_id'] . '_' . $value['prod_det_id']];
                            }
                        }
                        $sub_total += ( float ) $value['price'] * ( float ) $cnt;
                    }
                    
                    $total = $sub_total;
                    $shop_deals = $decideprice_theme->get('deals/'.$total );
                    if($shop_deals['result']!=NULL || $shop_deals['result']!=''){
                        if($shop_deals['result'][0]['deal_type']=='perc')
                        { 
                            $total_with_deal = round($total - ($total*$shop_deals['result'][0]['deal_perc']/100),2);
                            $deal_value = $shop_deals['result'][0]['deal_perc'];
                        }
                        else
                        { 
                            $total_with_deal = $total - $shop_deals['result'][0]['deal_amt'];
                            $deal_value = $shop_deals['result'][0]['deal_amt'];
                        } 
                        $deal = 'yes';
                        $deal_title= $shop_deals['result'][0]['title'];
                        $deal_type = $shop_deals['result'][0]['deal_type'];
                    }else{
                        $deal = 'no';
                        $total_with_deal = 0;
                         $deal_title = '';
                         $deal_type = '';
                         $deal_value = 0;
                    }
                } else {
                    $total = 0;
                }
            } else {
                $error = "cart is empty";
            }
            echo json_encode(array(
                'total' => $total,
                'total_with_deal'=>$total_with_deal,
                'deal' =>$deal,
                'deal_title'=>$deal_title,
                'deal_type'=>$deal_type,
                'deal_value'=>$deal_value
            ));
        }
        if ($type === 'update_item_qty') {
            $cart_count = array();
            $merchant   = $connects->sanitize($_POST['merchant']);
            $shop       = $connects->sanitize($_POST['shop']);
            $product    = $connects->sanitize($_POST['product']);
            $key_val    = $merchant . '_' . $shop . '_' . $product;
            $count      = $connects->sanitize($_POST['count']);
            if (isset($_COOKIE['decideprice_cart_count'])) {
                $cart_data  = stripcslashes($_COOKIE['decideprice_cart_count']);
                $cart_count = json_decode($cart_data, TRUE);
            }
            if (sizeof($cart_count) <= 0) {
                $cart_count[0][$key_val] = $count;
            } else {
                foreach ($cart_count as $key => $value) {
                    $cart_count[$key][$key_val] = $count;
                }
            }
            
            $cart_json = json_encode($cart_count, TRUE);
            @setcookie("decideprice_cart_count", $cart_json, time() + 86400, '/', NULL, 0);
            echo json_encode(array(
                'msg' => 'set'
            ));
        }
        
    }
    
} //isset post type
?>