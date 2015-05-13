var DP_main_url = "http://clearprize.com/"; //Do not change this.
var client_url = "http://yourdomain.com"; // your main url.
var continue_shopping_url = "index.php"; //change it if needed.

$(document).ready(function() {
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        type = 'add_cart_element';
        shop_id = $(this).attr('shop_id');
        product_id = $(this).attr('product_id');
        merchant_id = $(this).attr('merchant_id');
        $.ajax({
            type: 'POST',
            url: "decideprice/events.php",
            dataType: 'json',
            data: {
                type: type,
                shop_id: shop_id,
                product_id: product_id,
                merchant_id: merchant_id
            },
            success: function(data) {
                if (data.msg !== '' && data.msg !== null) {
                    ohSnap(data.msg, 'green');
                } else if (data.error !== '' && data.error !== null) {
                    ohSnap(data.error, 'red');
                }
            }
        });
    });

    $(document).on('click', '.buy-product', function(e) {

        e.preventDefault();
        shop_id = $(this).attr('shop_id');
        product_id = $(this).attr('product_id');
        merchant_id = $(this).attr('merchant_id');
        var buy = "true";
        var type = 'add_cart_element';
        $.ajax({
            type: 'POST',
            url: "decideprice/events.php",
            dataType: 'json',
            data: {
                type: type,
                shop_id: shop_id,
                product_id: product_id,
                merchant_id: merchant_id,
                buy: buy
            },
            success: function(data) {
                if (data.msg !== '' && data.msg !== null) {
                    window.location = client_url + 'cart.php';
                } else if (data.error !== '' && data.error !== null) {
                    ohSnap(data.error, 'red');
                } else if (data.buy_message !== '' && data.buy_message !== null) {
                    window.location = client_url + 'cart.php';
                }
            }
        });
    });



    $(document).on('click', '.cart_product_remove', function(e) {
        e.preventDefault();
        row_to_delete = $(this).parent().parent();
        shop_id = $(this).attr('shop_id');
        product_id = $(this).attr('product_id');
        merchant_id = $(this).attr('merchant_id');
        type = 'remove_cart';

        swal({
                title: "Are you sure?",
                text: "The Product Will Be Removed From Your Cart!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: 'POST',
                        url: "decideprice/events.php",
                        dataType: 'json',
                        data: {
                            type: type,
                            merchant_id: merchant_id,
                            shop_id: shop_id,
                            product_id: product_id
                        },
                        success: function(data) {

                            if (data.msg !== '' && data.msg !== null) {
                                swal("Deleted!", data.msg, "success");
                                setTimeout(function() {
                                    $('.cancel').trigger('click');
                                  }, 1500);
                                update_cart();
                                row_to_delete.remove();

                            } else if (data.empty !== '' && data.empty !== null) {
                                update_cart();
                                row_to_delete.remove();
                                swal("Deleted!", data.msg, "success");
                            }
                        }
                    });

                }
            });




    });
    $(document).on('keyup', '.pdt_qty', function(e) {

        e.preventDefault();
        var elm = $(this);
        var count = elm.val();
        single_price = $(this).closest('tr').find('.single_price').html();

        sub_total = $(this).closest('tr').find('.sub_total').html(single_price * count);

        if ($.isNumeric(count) && count > 0 && count <= 100) {
            var parent = elm.parent().parent();
            shop_id = $(this).attr('shop_id');
            product_id = $(this).attr('product_id');
            merchant_id = $(this).attr('merchant_id');
            var type = 'update_item_qty';
            $.ajax({
                type: 'POST',
                url: "decideprice/events.php",
                dataType: 'json',
                data: {
                    type: type,
                    merchant: merchant_id,
                    shop: shop_id,
                    product: product_id,
                    count: count
                },
                success: function(data) {
                    if (data.msg !== '' && data.msg !== null) {
                        update_cart();

                    } else if (data.empty !== '' && data.empty !== null) {
                        ohSnap(data.empty, 'red');
                    }
                }
            });
        } else {
            ohSnap('Only number between 1 and 100 is allowed', 'red');

        }


    });

    function update_cart() {
        $.ajax({
            type: 'POST',
            url: "decideprice/events.php",
            dataType: 'json',
            data: {
                type: 'update_cart'
            },
            success: function(data) {
                if (data) {
                    document.getElementById('cart_total').innerHTML = data.total;
                } else {
                    document.getElementById('cart_total').innerHTML = '';
                }
            }
        });
    }

    $(document).on('click', '.update-cart', function(e) {
        e.preventDefault();
        update_cart();
    });


    $(document).on('click', '.check_out', function(e) {
        e.preventDefault();
        cart = $.cookie('decideprice_cart');
        cart_count = $.cookie('decideprice_cart_count');
        price_flag = 0;

        function isNumeric(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }
        if (typeof cart != 'undefined' && cart != '') {
            cart_total = $('#cart_total').text();

            if (isNumeric(cart_total)) {
                if (cart_total > 10) {
                    price_flag = 1;
                } else {
                    price_flag = 0;
                }
            } else {
                price_flag = 0;
                error_msg = 'minimum amount for purchasing is 10 rupees';
            }
        } else {
            error_msg = "Sorry .. Can't find any product in your cart."
        }

        if (price_flag == 1) {
            var timestamp = (new Date()).getTime();
            document.getElementById('cart_cookie').value = cart;
            document.getElementById('count_cookie').value = cart_count;

            $('#api_payment').trigger('submit');
        } else {
            ohSnap(error_msg, 'red');
        }

    });

    $(document).on('click', '.continue_shopping', function() {
        window.location = continue_shopping_url;
    });


});