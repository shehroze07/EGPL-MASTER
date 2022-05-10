        
<?php $FloorplanXml[0] = str_replace('"n<','<',$FloorplanXml[0]);
      $FloorplanXml[0] = str_replace('>n"','>',$FloorplanXml[0]);

?>
<?php
function remove_item_from_cart() {
    global $woocommerce;
$cart = $woocommerce->instance()->cart;
$id = $_POST['product_id'];
$cart_id = $cart->generate_cart_id($id);
$cart_item_id = $cart->find_product_in_cart($cart_id);
echo $id;
if($cart_item_id){
   $cart->set_quantity($cart_item_id, 0);
   return true;
} 
return false;
}

add_action('wp_ajax_remove_item_from_cart', 'remove_item_from_cart');
add_action('wp_ajax_nopriv_remove_item_from_cart', 'remove_item_from_cart');
?>


       <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/jquery-1.12.4.js"></script>
	<script type="text/javascript">
            
            var floorPlanSetting = '<?php echo json_encode( $floorPlanSettings ); ?>';
            var loggedInUser = '<?php echo   json_encode($loggedInUsers)  ; ?>';
            var TurnUsers = '<?php echo   ($value) ; ?>';
            var pro = '<?php echo   json_encode($get_product)  ; ?>';
            console.log(pro);
            var cartCount='<?php
                    $cartcount = WC()->cart->get_cart_contents_count();
                    if ($cartcount > 0) { echo $cartcount; }
                ?>';
            console.log("---------start------------");
            console.log(loggedInUser);
            console.log("-----------middle----------");
            console.log(cartCount);
            console.log("--------end-------------");
            var flowstatus = "";
            var hex=new Array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');
            var popupstatus = 'off';
            var checkopenfunction;
            pluginBasePath = '<?php echo plugin_dir_url( __FILE__ ); ?>';
            removeLegendLabel = "";
            boothTagsList = "";

            var currentslectedboothtitle = "";
		mxBasePath = '<?php echo plugin_dir_url( __FILE__ ); ?>src';
		mxPostID = '<?php echo $id; ?>';
		mxBoothTypes = '<?php echo $boothTypes; ?>';
                mxLegendLabelsTypes = '<?php echo $FloorplanLegends; ?>';
                mxBoothtagsTypes = '<?php echo $FloorplanTags; ?>';
                
		mxFloorBackground = '<?php echo $FloorBackground; ?>';
		mxFloorPlanXml = '<?php echo $FloorplanXml[0]; ?>';
                mxCurrentPackageBooths = '<?php echo $_SESSION['listofselectedbooths']; ?>';
                mxUserentryflow = '<?php echo $userentryflow; ?>';
                flowstatus = location.href;
                console.log(flowstatus)
                if(mxCurrentPackageBooths!=""){
                    
                    mxCurrentPackageBooths = JSON.parse(mxCurrentPackageBooths);
                    
                }
		mxCurrentSiteLogo = '<?php echo $current_site_logo; ?>';
		mxCurrentSiteTitle = '<?php echo $current_site_name; ?>';
		mxCurrentSiteUrl = '<?php echo $current_site_url; ?>';
                mxgetAllusersData = '<?php echo $getAllusers_data; ?>';
                mxgetallfloorplanlist = '<?php echo $listoffloorplan; ?>';
                mxpackageboothflowstatus = '<?php echo $packageboothflow; ?>';
                
                mxgetjosnusersData = JSON.parse(mxgetAllusersData);
                
                floorplanstatuslockunlock ='<?php echo $floorplanstatuslockunlock;?>';
                
                
              
                allBoothsProductData = '<?php echo $sellboothsjson; ?>';
                // console.log(allBoothsProductData);
                if(allBoothsProductData !=""){
                    allBoothsProductData = allBoothsProductData.replace(/\\/g, '');
                     //console.log(allBoothsProductData);
                    allBoothsProductData = JSON.parse(allBoothsProductData);
                    
                }else{
                    
                     allBoothsProductData =  [];
                }
                
                //console.log(allBoothsProductData);
                newcompanynamesArray = [];
                expogenielogging = [];
                var startfloorplanedtitng = {};
                startfloorplanedtitng.currentuserID = '<?php echo $user_ID; ?>';
                startfloorplanedtitng.datetime = '<?php echo date("Y-m-d h:i:sa"); ?>';
                startfloorplanedtitng.action = 'Start Editing';
                expogenielogging.push(startfloorplanedtitng);
                jQuery.each( mxgetjosnusersData, function( key, value ) {
                   
                    var indexarray = {};
                    
                    indexarray.userID = key;
                    indexarray.companyname = mxgetjosnusersData[key].companyname;
                    newcompanynamesArray.push(indexarray);
                    
                });
                
                console.log('Package Booths List');
                console.log(mxCurrentPackageBooths);
		console.log(mxpackageboothflowstatus);		  
				  
				  
                //danyal case senstive
				function SortByName(a, b){
				  var aName = a.companyname.toLowerCase();
				  var bName = b.companyname.toLowerCase(); 
				  return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
				}
				
				newcompanynamesArray = newcompanynamesArray.sort(SortByName);
                //#end
                
               // console.log(newcompanynamesArray)
                
                
                boothsproducts ='<?php echo $boothsproductsData; ?>';
                boothpricetegs ='<?php echo $mxPriceTegsObject; ?>';
                arrayoflevels ='<?php echo $arrayoflevels; ?>';
                arrayoftasks ='<?php echo $arrayoftasks; ?>';
                userloggedinstatus = '<?php echo is_user_logged_in();?>'
                currencysymbole =  '<?php echo get_woocommerce_currency_symbol( $currency ); ?>';
                
                
               
                
                if(boothsproducts !=""){
                    boothsproducts = JSON.parse(boothsproducts);
                }
                
                baseCurrentSiteURl ='<?php echo  get_site_url(); ?>';
                mxCurrentfloorplanstatus ='<?php echo  $current_floor_plan_status; ?>';
                
		var ArrayOfObjects = [];
                var LegendsOfObjects = [];
                var BoothTagsObjects = [];
                var PricetegsObjects = [];
                var arrayoflevelsObjects = [];
                var arrayoftasksObjects = [];
                var arrayfloorplanlist = [];
		var json = {};
                var legendsdilog;
		//console.log(mxFloorPlanXml);
		var jsonBooth = JSON.parse(mxBoothTypes);
                var legendlabelID = "";
                var pricetegID = "";
                
                
                 jQuery.each(jsonBooth, function(index, value) {
			json = {};
			json.background = jsonBooth[index].background;
			json.width = jsonBooth[index].width;
			json.height = jsonBooth[index].height,
			json.style = jsonBooth[index].style;
			ArrayOfObjects.push(json);
		});
                
                if(mxLegendLabelsTypes !=""){
                
                var jsonLegends = JSON.parse(mxLegendLabelsTypes);
                
               
                jQuery.each(jsonLegends, function(index1, value1) {
			json1 = {};
			json1.ID = jsonLegends[index1].ID;
			json1.colorstatus = jsonLegends[index1].colorstatus;
			json1.name = jsonLegends[index1].name,
			json1.colorcode = jsonLegends[index1].colorcode;
                        json1.colorcodeOcc = jsonLegends[index1].colorcodeOcc;
                        
			LegendsOfObjects.push(json1);
		});
                
                
                }
                
                if(mxBoothtagsTypes !=""){
                
                var jsonLegends = JSON.parse(mxBoothtagsTypes);
                
               
                jQuery.each(jsonLegends, function(index1, value1) {
			json1 = {};
			json1.ID = jsonLegends[index1].ID;
			json1.name = jsonLegends[index1].name,
			BoothTagsObjects.push(json1);
		});
                
                
                }
                if(arrayoflevels !=""){
                    arrayoflevels = JSON.parse(arrayoflevels);
                    jQuery.each(arrayoflevels, function(index1, value1) {
			json1 = {};
			json1.key = index1;
			json1.name =value1;
			
                        
			arrayoflevelsObjects.push(json1);
		   });
                  
                }
                
                if(mxgetallfloorplanlist !=""){
                  
                    mxgetallfloorplanlist = JSON.parse(mxgetallfloorplanlist);
                    
                    jQuery.each(mxgetallfloorplanlist, function(index1, value1) {
			json1 = {};
			json1.ID = index1;
			json1.title =value1;
			
                        
			arrayfloorplanlist.push(json1);
		   });
                   
                 
                   
                }
                
                
                
                if(arrayoftasks !=""){
                    console.log(arrayoftasks);
                    arrayoftasks = JSON.parse(arrayoftasks);
                    
                    jQuery.each(arrayoftasks, function(index1, value1) {
			json1 = {};
			json1.key = index1;
			json1.name =value1;
			
                        
			arrayoftasksObjects.push(json1);
		   });
                   
                }
                
                if(boothpricetegs !=""){
                
                var priceTegsObjectsArray = JSON.parse(boothpricetegs);
                console.log(boothpricetegs)
               
                jQuery.each(priceTegsObjectsArray, function(index1, value1) {
			json1 = {};
			json1.ID = priceTegsObjectsArray[index1].ID;
			json1.price = priceTegsObjectsArray[index1].price;
			json1.level = priceTegsObjectsArray[index1].level,
			json1.name = priceTegsObjectsArray[index1].name;
                        
			PricetegsObjects.push(json1);
		});
                
                
                }
                
                
             
		
		
               
               
	</script>

        <title><?php echo $current_site_name; ?> - Floor Plan Editor </title>
   
      
        <?php if($current_floor_plan_status == 'viewer' ){?>
        <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ); ?>styles/main.css">
        <?php } ?>
        <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ); ?>styles/grapheditor.css?v=1.69">
       <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/latest/sweetalert2.min.css">
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/mobile-detect.min.js?v=2.19"></script>
        

      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ); ?>styles/jquery-confirm.css?v=2.15">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ); ?>styles/spectrum.css?v=2.15">
      <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" /> 
      
     
      
      <script type="text/javascript">
		// Parses URL parameters. Supported parameters are:
		// - lang=xy: Specifies the language of the user interface.
		// - touch=1: Enables a touch-style user interface.
		// - storage=local: Enables HTML5 local storage.
		// - chrome=0: Chromeless mode.
                 var md = new MobileDetect(window.navigator.userAgent);
                function iframeLoaded(){
    
    
                    jQuery("#loadingicon").hide();
                    jQuery("#helpvidep").show();


                }
		var urlParams = (function(url)
		{
			var result = new Object();
			var idx = url.lastIndexOf('?');
	
			if (idx > 0)
			{
				var params = url.substring(idx + 1).split('&');
				
				for (var i = 0; i < params.length; i++)
				{
					idx = params[i].indexOf('=');
					
					if (idx > 0)
					{
						result[params[i].substring(0, idx)] = params[i].substring(idx + 1);
					}
				}
			}
			
			return result;
		})(window.location.href);
	
		// Default resources are included in grapheditor resources
		mxLoadResources = false;
	</script>
        
        
       
        
       
        <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/jquery-confirm.js?v=2.21"></script>
        <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/Init.js?v=2.22"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>jscolor/jscolor.js?v=2.22"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>sanitizer/sanitizer.min.js?v=2.22"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/mxClient.js?v=2.98"></script>

	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/EditorUi.js?v=9.15"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/Editor.js?v=2.64"></script>

	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/Sidebar.js?v=3.80"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/Graph.js?v=3.45"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/Shapes.js?v=2.75"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/Actions.js?v=3.90"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/Menus.js?v=2.77"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/Format.js?v=6.86"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/Toolbar.js?v=3.01"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/Dialogs.js?v=3.78"></script>
        <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/customefunctions.js?v=2.78"></script>
        <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/jquery.printPage.js?v=2.78"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <!-- <script type="text/javascript" src="https://cdn.tinymce.com/4/tinymce.min.js"></script> -->
        <script type="text/javascript" src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/sweetalert2/latest/sweetalert2.min.js"></script>
      
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
         <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/spectrum.js?v=2.78"></script> 
         
      
   
        
        
	<script type="text/javascript">
		// Extends EditorUi to update I/O action states based on availability of backend
                
             
                jQuery(document).ready(function() {
                    
                   
                    jQuery(".geSprite-print").printPage();
                    jQuery(".customepickcolor").spectrum({
                        showPalette: true,
                        chooseText: "Apply",
                        cancelText: "Cancel",
                        palette: [
                        ['black', 'white', 'blanchedalmond'],
                        ['rgb(255, 128, 0);', 'hsv 100 70 50', 'lightyellow']
                        ]
                    });
                    
                });
                
		(function()
		{
			var editorUiInit = EditorUi.prototype.init;
			
			EditorUi.prototype.init = function()
			{
				editorUiInit.apply(this, arguments);
				this.actions.get('export').setEnabled(false);

				// Updates action states which require a backend
				if (!Editor.useLocalStorage)
				{
					mxUtils.post(OPEN_URL, '', mxUtils.bind(this, function(req)
					{
						var enabled = req.getStatus() != 404;
						this.actions.get('open').setEnabled(enabled || Graph.fileSupport);
						this.actions.get('import').setEnabled(enabled || Graph.fileSupport);
						this.actions.get('save').setEnabled(enabled);
						this.actions.get('saveAs').setEnabled(enabled);
						this.actions.get('export').setEnabled(enabled);
					}));
				}
			};
			
			// Adds required resources (disables loading of fallback properties, this can only
			// be used if we know that all keys are defined in the language specific file)
			mxResources.loadDefaultBundle = false;
			var bundle = mxResources.getDefaultBundle(RESOURCE_BASE, mxLanguage) ||
				mxResources.getSpecialBundle(RESOURCE_BASE, mxLanguage);

			// Fixes possible asynchronous requests
			mxUtils.getAll([bundle, STYLE_PATH + '/default.xml'], function(xhr)
			{
				// Adds bundle text to resources
				mxResources.parse(xhr[0].getText());
				
				// Configures the default graph theme
				var themes = new Object();
				themes[Graph.prototype.defaultThemeName] = xhr[1].getDocumentElement(); 
				
				// Main
				new EditorUi(new Editor(urlParams['chrome'] == '0', themes));
			}, function()
			{
				document.body.innerHTML = '<center style="margin-top:10%;">Error loading resource files. Please check browser console.</center>';
			});
		})();
                
  
jQuery(window).load(function() {
   
   jQuery('.block-msg-default').remove();
   jQuery('.blockOverlay').remove();
  
});
</script>

<?php if($current_floor_plan_status != 'viewer' ){?>
<script>
                    var tid = setTimeout(mycode, 300000);
                    function mycode() {
                      // do some stuff...
                      
                      tid = setTimeout(mycode, 300000); // repeat myself
                      if(expogenielogging.length !="" && expogenielogging.length > 1){
                          var Scurrenttime = jQuery.now();
                          var Ecurrenttime = jQuery.now() - 120000;
                          var lastEl = expogenielogging[expogenielogging.length-1];
                          var lastdattime = new Date(lastEl.datetime).getTime()
                          console.log(lastdattime +" >="+ Scurrenttime);
                          if(lastdattime  >= Ecurrenttime && lastdattime  <= Scurrenttime){
                              
                               console.log('Save----floorplan');
                          }else{
                              
                
                
                              window.onbeforeunload = null;
                              updatelockstatus('unlock');
                              window.location.replace(mxCurrentSiteUrl+"/floor-plan-warning/");
                              
                              
                              
                              
                              
                          }
                          
                          //console.log(new Date(lastEl.datetime).getTime());
                          //console.log(currenttime);
                      }else{
                          window.onbeforeunload = null;
                          updatelockstatus('unlock');
                          window.location.replace(mxCurrentSiteUrl+"/floor-plan-warning/");
                          
                      }
                      
                      
                      
                      
                    }
                    function abortTimer() { // to be called when you want to stop the timer
                      clearTimeout(tid);
                    }
                     
                  
                     
                     
                    function updatelockstatus(valueofstatus){
                        
                      var url = '<?php echo site_url();?>';
                      var data = new FormData();
                      data.append('status', valueofstatus);
                      data.append('post_id', mxPostID);
                      var urlnew = url + '/wp-content/plugins/floorplan/floorplan.php?floorplanRequest=savedlockunlockstatus';
                        jQuery.ajax({
                            url: urlnew,
                            data: data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'POST',
                            success: function (data) {
                                
                            }
                        });
                        
                        
                        
                        
                    }
                    
	</script>
<?php }?>
        <style>
            
            .select2-results__options {
    background: #ffffff !important;
    border: 1px #cccccc solid !important;
}
.select2-container--default .select2-dropdown .select2-search {
    border-bottom: none; 
    padding: 0px 0px; 
    border-top: none; 
    margin-top: 0px; 
}
.select2-search--dropdown {
    
    padding: 0px; 
}
.select2-results__option, .select2-search--dropdown {
    padding-left: 5px;
    padding-right: 5px;
}
.select2-container--default .select2-dropdown .select2-search input {
    padding: 0px
    
}
.select2-results__option[aria-selected] {
   
    color: #333 !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered{
    
     color: #333 !important;
}
            
        </style>
          
         <?php if($current_floor_plan_status != 'viewer' ){?>
        
        
            
        
        
        <div class="blockUI" style="display:none;"></div>
        <div class="blockUI blockOverlay" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgba(142, 159, 167, 0.8); opacity: 1; cursor: wait; position: absolute;"></div>
        <div class="blockUI block-msg-default blockElement" style="z-index: 1011; position: absolute; padding: 0px; margin: 0px;  top: 300px;  text-align: center; color: rgb(0, 0, 0);  cursor: wait; height: 200px;left: 47%;">
            <div class="blockui-default-message" style="color: #fff;">
        <i class="fa fa-circle-o-notch fa-spin fa-2x"></i><h2 style="color: #fff;">Please Wait..</h2></div></div> 
    
         <?}?>

         
