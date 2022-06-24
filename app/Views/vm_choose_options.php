
   <div class="row">
    <!-- <div class="col-sm-12 top10 product-box">
        <button onclick="resetSelection()"></button>
    </div>     -->
        
        <?php 
        $count = 0;
        $length =  count($page_data["products"]);
        foreach($page_data["products"] as $product){

        ?>    
            
        <div class="col-sm-12 top10 product-box">
                    <div class="product-image" style="background-image: url('<?php echo $product["img_url"];?>');background-size:100% auto;">
                    
                    </div>

                    <div class="product-desc">
                            <div style="text-align:center; float:left;padding-left:15px">
                                    <h5><?php echo $product["name"]; ?> </h5>
                                    <span class="product-price"><?php echo$product["price"]." ".$product["currency"];  ?> </span>    
                            </div>
                            <div class="top10" style="text-align:right; float:right;">
                                    <button class="quantity-btn" onclick="Add('vm-page-item-count-<?php echo $product['id'];?>',<?php echo $product['id'];?>,-1,'<?php echo $product['name'];?>',<?php echo $product['price'];?>)">-</button> 
                                    <span id="vm-page-item-count-<?php echo $product["id"];?>">0</span> 
                                    <button class="quantity-btn" onclick="Add('vm-page-item-count-<?php echo $product['id'];?>',<?php echo $product['id'];?>,1,'<?php echo $product['name'];?>',<?php echo $product['price'];?>)">+</button>             
                            </div>      
                    </div>  
            </div>

        <?php    
        if ($count == $length-1){
            // printing this div to add blank space at bottom so that content will not hide behind bottom nav
            echo '<div class="col-sm-12" style="height:50px"></div>';
        }
        $count++;
        }

        
        ?>
        <nav class="navbar fixed-bottom navbar-light bg-light">
            <span id="vm-page-cart-summary"> </span>
            <form action="<?php echo base_url(); ?>/Checkout" method="post">
            <!-- <a class="navbar-brand" href="<?php echo base_url(); ?>/Checkout">checkout</a> -->
            <input type="text" id="checkout-form-data" name="cart_data" value="" hidden/>
            <input type="submit" id="checkout-form-submit-btn"  value="checkout" disabled>
            <form>
        </nav>
    </div>

    <script>
      // after page load script  
      (function() {
        resetSelection();
      })
    </script>