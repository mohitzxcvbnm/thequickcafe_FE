
<script type = 'text/javascript' src = "<?php echo base_url(); 
         ?>/js/ws.js">
</script> 

<h1> <?php   

if($page_data['rzpdata']['payment_status']=='success'){
echo "payment success";
//var_dump($page_data["order"]);


}else{
echo "payment failed";
//var_dump($page_data);
}

?></h1>


<div class="row" style="border: 2px solid powderblue;">
    <div class="col">
    <p> Order ID : <?php echo $page_data["order"]->id; ?> </p>
    </div>
    <div class="col">
    <p> Payment Status : <?php echo $page_data['rzpdata']['payment_status']; ?> </p>
    </div>
    <div class="col">
    <p> Queue Position : <span id="your-position-inqueue"></span></p>
    </div>
</div> 
<h5>Note: Please be at machine when your queue position is flashing `1`.</h5>
<button id="start-despense-btn" onclick='startDespensing(<?php echo $page_data["order"]->id; ?>)' disabled>Checking Order Status ...</button>
<script>
    getOrderStatus(<?php echo $page_data["order"]->id;?>,<?php echo $page_data["vmid"];?>);
    setOrderForCheck(<?php echo $page_data["order"]->id;?>,<?php echo $page_data["vmid"];?>);
 </script>   