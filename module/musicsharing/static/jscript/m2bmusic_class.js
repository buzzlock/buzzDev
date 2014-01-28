function plugin_completeProgress()
{
    if(music_redict_url == null)
    {
        return false;
    }
    window.location.href = music_redict_url;
}
function music_test_active(index,tab){

	hide = getObj(tab);

	show = getObj(tab+"_inactive");

	if (hide.style.display != "none" && tab != index) {

		hide.style.display = "none";

		show.style.display = "";

	}	
	

}

function music_get_active(show){

	music_test_active(show,"url");

	music_test_active(show,"html_code");

	music_test_active(show,"bb_code");

}

 function music_get_url(show, getstr){	

	getObj(show + "").style.display="";

	getObj(show + "_inactive").style.display="none";

	getObj("result_url").value = getstr;

	getObj("result_url").style.display = "";

	music_get_active(show);   

 }

function url_select_text(input_id){

	input_id.select();

}
function addToPlayListFromFlash(musicid,path,user_id,pathmodule){
     var url = path + '/module/musicsharing/static/addplaylist.php';
   //tb_show("Add Song to Playlist",url+"?musicid="+musicid+"&userid="+user_id+"&pathmodule="+pathmodule+"&pathurl="+path+"&TB_iframe=true&width=400&height=100",null);
   tb_show("Add Song to Playlist",$.ajaxBox('musicsharing.addplaylist', 'height=200&width=300&'+"idsong="+musicid+"&userid="+user_id+"&pathmodule="+pathmodule+"&pathurl="+path))
   document.getElementById('TB_closeAjaxWindow').innerHTML = '<a id="TB_closeWindowButton" href ="#" onclick="self.parent.tb_remove();"><img alt="" src="'+path+'/theme/frontend/default/style/default/image/misc/close.gif"></a>';
   
}
function JsChangeMusicTitleFromFlash(MusicID){
     $.ajaxCall('musicsharing.share','musicid='+MusicID );
}
function getObj(name) 
{   if (document.getElementById) { return document.getElementById(name); }
            else if (document.all)       { return document.all[name]; }
            else if (document.layers)    { return document.layers[name]; }
}
function addtocart(item_id,type)
{
    $.ajaxCall('musicsharing.cart.addtocart','item_id='+item_id +'&type='+type);  
}
function removecartitem(item_id,type,index)
{
    $.ajaxCall('musicsharing.cart.removecartitem','item_id='+item_id +'&type='+type+'&index='+index);  
}
function removedownloadlistitem(item_id,index)
{
    $.ajaxCall('musicsharing.cart.removedownloadlistitem','item_id='+item_id+'&index='+index);  
}
 function loadanblumitem(item_id,url)
{
    var l = document.getElementById('album_item_download_'+item_id);
    //var aig = document.getElementById('download_button_'+item_id);
    l.innerHTML = '<img src="'+url+'module/musicsharing/static/image/ajax-loader.gif"/>';
    //aig.innerHTML = '<img src="'+url+'module/musicsharing/static/image/icon_up.gif"/>';
    $.ajaxCall('musicsharing.cart.loadalbumitem','item_id='+item_id);
}
function addcouponcode()
{
    var coup = getObj('coupon_code').value;
    $.ajaxCall('musicsharing.cart.addcouponcode','coupon='+coup);  
}
function checkout(gateway)
{
   document.getElementById('pay_gate_way').value = gateway;
}
function selectAll()
{
    var check = document.getElementsByName('downloadItem');
    var is_select = document.getElementById('selectAllDownload');
    var count = 100;
    for(var i = 0 ; i < count ; i++){
        var check = document.getElementsByName('downloadItem['+i+']');
       
        if (check[0] != null )
        {
            check[0].checked = is_select.checked;     
        }  
        
    }
}

function  deleteItem(url)
{
          if ( confirm("Are you sure you want to delete this item ?"))
          {
              window.location.href = url;
          }
}
function removeall()
{
    var count = 100;
    var is_select = false;
    for(var i = 0 ; i < count ; i++){
        var check = document.getElementsByName('downloadItem['+i+']');
       
        if (check[0] != null )
        {
            if (check[0].checked == true)
            {
                is_select = true;
                break;
            }
        }  
        
    }
    if ( is_select == false)
    {
        alert("You do not select any item(s) to delete");return false;
    }
    return confirm("Are you sure you want to delete this item(s)?")
}
function show_price(item_id,type,price,currency)
{
    price = roundNumber(price,2);
     $('#'+type+'_price_id_'+item_id).text(price + " " + currency);
}
function hide_price(type,item_id,currency)
{
     $('#'+type+'_price_id_'+item_id).text("");
     
}
function loadMessageFromRequest(id,url)
{
    var l = document.getElementById('message_request_'+id);
    //var aig = document.getElementById('download_button_'+item_id);
    l.innerHTML = '<img src="'+url+'module/musicsharing/static/image/ajax-loader.gif"/>';
    //aig.innerHTML = '<img src="'+url+'module/musicsharing/static/image/icon_up.gif"/>';
    $.ajaxCall('musicsharing.cart.loadMessageFromRequest','id='+id);   
}
function close(id)
{
    $('#message_request_'+id).text('');
}
function addtocartfromblock(item_id,type)
{
    $.ajaxCall('musicsharing.cart.addtocartfromblock','item_id='+item_id +'&type='+type);  
}
function roundNumber(num, dec) {
        var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
        return result;
    }
var is_already = false;    
function makeBill(f)
{
    
    if(f == null || f == undefined && is_already == false){     
        //console.log(window.cart_form);
        is_already = true;
        window.cart_form.onsubmit = function(){return true;};
        $('#cart_bill').submit();   return true;
        //window.cart_form.submit();
    }else{
        window.cart_form =  f;
        $(f).ajaxCall('musicsharing.cart.makeBill','');    
    }    
    return false;
}
function makeRequest(f)
{
    if(f == null || f == undefined && is_already == false){     
        //console.log(window.cart_form);
        is_already = true;
        window.cart_form.submit();
    }else{
        window.cart_form =  f;
        $(f).ajaxCall('musicsharing.cart.makeRequest','');    
    }    
    return false;
}


