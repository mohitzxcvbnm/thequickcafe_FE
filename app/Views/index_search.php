
<style>
    .search-box-input {
        width:80%;
        background: transparent;
        border: none;
        height:40px;
        margin-left:1%;
        color: black;
    }

    .search-box-input::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: black;
        opacity: 1; /* Firefox */
    }

    .search-box-input:focus {
        outline:none;
    }

    .serch-box {
        background: white;
        border-radius: 9px;
        height:40px;
        overflow:hidden;
    }
    .search-result {
        height:40px;
        /* margin-top:1px; */
        padding:5px;
        background-color: rgba(0, 0, 100, 0.3);
        border: solid 1px grey;
        /* border-color:white; */
        color : black;
    }

    .search-result-cont {
        margin-top:2px;
        padding-left:2px;
        padding-right:2px;
    }

    .scan-btn {
        margin-top: 11px;
        color: blue; 
        font-size: 18px;
    }

    .modal-dialog {
    width: 100%;
    height: 100%;
    /* padding: 0; */
    }

    .modal-content {
    height: 100%;
    border-radius: 0;
    }
    .search-li{
        color:blue;
        font-size:18px;
        text-align:center;
        border: 1px solid blue;
        border-bottom:none;
        border-right:none;
        border-left:none;        
        background:rgba(128,128,128, .1) ;
    }

    .search-li-first {
        border:none;
        border-top-left-radius:10px;
        border-top-right-radius:10px;
    }

    .search-li-last{
        border:none
        border-bottom-left-radius:10px;
        border-bottom-right-radius:10px;
    }

    .search-li-last:hover {
    font-size:20px;
    }

    .search-li:hover {
    font-size:20px;
    }

</style>
<div class="row" style=" padding:5px;">
    <div class="col-12" >    
    <p>Search Vending Machine</p>
    </div>
    <div class="col-12 serch-box" >
    <a  href="<?php echo base_url() ."/Home/scanqr" ?>">
    <i style="margin: 0;padding-right: 5px;" class="fa fa-camera scan-btn"></i>
    </a>
    <i class="fa fa-search" style="color: blue;  font-size: 18px;" ></i>
          <input type="text" class="search-box-input" id="search-box-input"  placeholder="Search..." onkeyup="SearchVm('search-box-input',fillSearchResult, '<?php echo getenv('BASE_DOMAIN')?>','<?php echo base_url()?>')">
          <!-- <a href="#" class="search_icon"><i class="fas fa-search"></i></a> -->   
    </div>
    <!-- <div class="search-result"> </div> -->
    <div class="col-12 search-result-cont"  >
        <ul style="list-style-type:none; border:1px solid blue; border-radius:10px; list-style-type: none; padding: 0;" id="search-result-ul">
        </ul>
    </div>
</div>
<script>
     window.onload = function() {
         console.log("ssssss")
        resetSelection("indexsearch");
      }
</script>

