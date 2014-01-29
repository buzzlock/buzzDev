function addtoFriend(user_id)
{
    if(!user_id)
    {
        return false;
    }
    tb_show('', $.ajaxBox('friend.request', 'width=420&user_id=' + user_id + '&tb=true'));
    return false;
}
function addSearch()
{
    var html='<input class="uscn_view_seach" name="search[search]" value="" type="text"/><input type="hidden" name="current_level_view" id="current_level_view" value="1"/>';
    if($('.uscn_view_seach').length <=0)
    {
        $(html).insertAfter('#js_block_border_userconnect_display .menu ul');    
        $('.uscn_view_seach').keypress(function(e) {
            if(e.keyCode == 13) {
               console.log(this.value); 
               if(this.value !="" || this.value !=" ") 
               {
                   var level = getLevel();
                   $(this).ajaxCall('userconnect.search','level='+level+'&keysearch='+this.value);       
                    
               }
            }
        });

    }
    
}
function getLevel()
{
    var v = $('#current_level_view').val();
    return v;
}
function setLevel(lv)
{
    $('#current_level_view').val(lv);
    $('.uscn_view_seach').val("");
}
function viewConnection(from_1,from_id,level)
{
    var w =200;
    switch(level)
    {
        case 2:
            w = 400;break;
        case 3:
            w = 500;break;
        case 4:
            w = 600;break;
        case 5:
            w = 740;break;
    }
    $Core.box('userconnect.viewConnectionPath',w, 'from_1='+from_1+'&from_id='+from_id+'&level='+level);
}
function initMinimenu()
{
    $('ul.connection_path li.li_u_img .uscn_control').show();
    $('ul.connection_path li.li_u_img').mouseover(function()
    {        
        $(this).find('.uscn_mini_menu').show();
    });
    
    $('ul.connection_path li.li_u_img').mouseout(function()
    {
        $(this).find('.uscn_mini_menu').hide();
    });
}
$Behavior.initMenuViewPath = function()
{
    initMinimenu();
     $('.inlinePopupUserConnect').unbind();
     $('.inlinePopupUserConnect').click(function()
    {
        var $aParams = $.getParams($(this).get(0).href);
        var sParams = '&tb=true';
        for (sVar in $aParams)
        {            
            sParams += '&' + sVar + '=' + $aParams[sVar] + '';
        }
        sParams = sParams.substr(1, sParams.length);        
        
        tb_show($(this).get(0).title, $.ajaxBox($aParams['call'], sParams));        
        
        return false;
    });
   
}
function replaceUser(index,str,lid)
{
   var li = $('ul.connection_path li.li_u_img').eq(index);
   li.html(str);
   li.attr("rel",lid);
   $Behavior.initMenuViewPath();
}
function findPath(el,from_id,to_id,level)
{
    var li = $(el).parent().parent().parent().parent();
    var posli = $('ul.connection_path li.li_u_img').index(li);
     var astr = "0";
    $('ul.connection_path li.li_u_img').each(function(index){
        astr += ","+$(this).attr('rel');
    });
   
    if(posli >0)
    {
        $('ul.connection_path li.li_u_img .uscn_control').hide();
        $('ul.connection_path li.li_u_img').unbind();
        for(i = posli;i<level;i++)    
        {
            li = $('ul.connection_path li.li_u_img').eq(i);
          
            var url = oParams['sJsHome']+'module/userconnect/static/image/waiting.gif';   
            li.find('.user_browse_image .quickFlipPanel img').animate(
            {
                opacity: 0,
                height: '0',
                width: '0',
            },500,function(){
                this.src = url;
                $(this).animate(
                {
                   opacity: 1,
                   height: '85',
                   width: '89', 
                },500,function(){
                    
                }
                );                
                
            }
            );

        }
        $Core.ajax('userconnect.findPath', 
        {
            params: 
            {     
                'start_i':posli,
                'from_id': from_id,
                'to_id':to_id,
                'level':level,
                'astr':astr,
            },
            success: function($sData)            
            {
                eval($sData);
                //console.log($sData);
            }
        });
    }
    
}