<div class="checkout-summary">
    <h5>Your Cart</h5>

    <?php 
      //echo "opop\n";
      //var_dump($page_data["cart"]);
      //exit(0);
        $count = 0;
        $length =  count($page_data["cart"]);
        foreach($page_data["cart"] as $id => $item){
            $price = $item["subtotal"]/$item["count"];
    ?>        
    <div class="checkout-summary-item">
        <div style="float:left; width:100px">
             <b> <?php echo $item["itemName"]; ?> </b>
                
        </div>

        <div style="float:left; margin-left:50px;">
          <span> <?php echo $item["subtotal"]. " Rs"; ?>
        </div>    
        
        <div style="float:right; border: 1px solid; width:85px;">
                <div style="display:inline; float:left"> 
                <button class="quantity-btn"  onclick="alert('dissabled')" >-</button> 
                </div>
                <div class="product-count"style="display:inline; float:left; margin-left:10%;" id="vm-page-item-count-<?php echo $id;?>"> <?php echo $item["count"]; ?> </div>
                <div style="display:inline; float:right">
                <!--Add('vm-page-item-count-<?php echo $id;?>',<?php echo $id;?>,-1,'<?php echo $item['itemName'];?>',<?php echo $item['price'];?>)  -->
                 <button class="quantity-btn"  onclick="alert('dissabled')">+</button>
                </div>             
        </div>
    </div>   
    <?php }?>
            
    <h5> Payment </h5>
    <?php
        $data = $page_data['rzpdata']['data'];
        $json = $page_data['rzpdata']['json'];
        $displayCurrency = $page_data['rzpdata']['displayCurrency'];
    ?>

<form action="Checkout/verify" method="POST">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $data['key']?>"
    data-amount="<?php echo $data['amount']?>"
    data-currency="INR"
    data-name="<?php echo $data['name']?>"
    data-image="<?php echo $data['image']?>"
    data-description="<?php echo $data['description']?>"
    data-prefill.name="<?php echo $data['prefill']['name']?>"
    data-prefill.email="<?php echo $data['prefill']['email']?>"
    data-prefill.contact="<?php echo $data['prefill']['contact']?>"
    data-notes.shopping_order_id="3456"
    data-order_id="<?php echo $data['order_id']?>"
    <?php if ($displayCurrency !== 'INR') { ?> data-display_amount="<?php echo $data['display_amount']?>" <?php } ?>
    <?php if ($displayCurrency !== 'INR') { ?> data-display_currency="<?php echo $data['display_currency']?>" <?php } ?>
  >
   
  </script>
  <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
  <input type="hidden" name="cart_data" id="cartdata-for-rzp-verify" />
  <input type="hidden" name="order_id" id="orderid-for-rzp-verify" value="<?php echo $data['order_id']?>" />
  <input type="hidden" name="amount" id="amount-for-rzp-verify" value="<?php echo $data['amount']?>" />
</form>
        
</div>

<script>
      // after page load script  
        document.getElementById("cartdata-for-rzp-verify").value = getCartDataFromLocal();
</script>