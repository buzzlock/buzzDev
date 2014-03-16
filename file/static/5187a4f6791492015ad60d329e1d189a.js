
 /* static\jscript\common.js */var sClientInfo=navigator.userAgent.toLowerCase();var bIsIE=(sClientInfo.indexOf("msie")!=-1);var bIsWin=((sClientInfo.indexOf("win")!=-1)||(sClientInfo.indexOf("16bit")!=-1));function getParam(sParam)
{return oParams[sParam];}
function getPhrase(sParam)
{return oTranslations[sParam];}
function isModule(sModule)
{return(typeof(oModules[sModule])!='undefined'?true:false);}
function debug(sMessage)
{if(getCookie('js_console'))
{if($('#firebug_no_console').length<=0)
{var $sConsole='';$sConsole+='<div id="firebug_no_console" style="position:fixed; bottom:0px; left:0px; z-index:9000; border-top:2px #000 solid; width:100%; text-align:left;">';$sConsole+='<div style="background:#5F5F5F; color:#fff; padding:4px; font-size:14px; font-weight:bold;">Javascript Console</div>';$sConsole+='<div style="background:#5F5F5F; color:#fff; padding:4px; font-size:12px; font-weight:bold; border-top:1px #3F3F3F solid;"><a href="#" onclick="$(\'#firebug_no_console_content\').html(\'\'); return false;">Clear</a></div>';$sConsole+='<div id="firebug_no_console_content" style="height:200px; overflow:auto; background:#0F0F0F; color:#fff;"></div>';$sConsole+='</div>';$('body').prepend($sConsole);}
$('#firebug_no_console_content').prepend('<div style="border-bottom:1px #4F4F4F solid; padding:5px 0px 5px 10px;">'+sMessage+'</div>');}
if(typeof console==='undefined'||typeof console.log=='undefined')
{return false;}
console.log(sMessage);}
function p(sMessage)
{debug(sMessage);}
function d(aArray)
{p(print_r(aArray,true));}
function setCookie(name,value,expires)
{var today=new Date();today.setTime(today.getTime());if(expires)
{expires=expires*1000*60*60*24;}
var expires_date=new Date(today.getTime()+(expires));document.cookie=getParam('sJsCookiePrefix')+name+"="+escape(value)+
((expires)?";expires="+expires_date.toGMTString():"")+
((getParam('sJsCookiePath'))?";path="+getParam('sJsCookiePath'):"")+
((getParam('sJsCookieDomain'))?";domain="+getParam('sJsCookieDomain'):"");debug('Adding Cookie: '+name+' -> '+value);}
function deleteCookie(name)
{if(this.getCookie(name))
{document.cookie=getParam('sJsCookiePrefix')+name+"="+
((getParam('sJsCookiePath'))?";path="+getParam('sJsCookiePath'):"")+
((getParam('sJsCookieDomain'))?";domain="+getParam('sJsCookieDomain'):"")+";expires=Thu, 01-Jan-1970 00:00:01 GMT";debug('Deleting Cookie: '+name);}}
function getCookie(check_name)
{var a_all_cookies=document.cookie.split(';');var a_temp_cookie='';var cookie_name='';var cookie_value='';var b_cookie_found=false;var check_name=getParam('sJsCookiePrefix')+check_name;for(var i=0;i<a_all_cookies.length;i++)
{a_temp_cookie=a_all_cookies[i].split('=');cookie_name=a_temp_cookie[0].replace(/^\s+|\s+$/g,'');if(cookie_name==check_name)
{b_cookie_found=true;if(a_temp_cookie.length>1)
{cookie_value=unescape(a_temp_cookie[1].replace(/^\s+|\s+$/g,''));}
return cookie_value;break;}
a_temp_cookie=null;cookie_name='';}
if(!b_cookie_found)
{return null;}}
function parse(strInputCode)
{strInputCode=strInputCode.replace(/&(lt|gt);/g,function(strMatch,p1)
{return(p1=="lt")?"<":">";});return strInputCode.replace(/<\/?[^>]+(>|$)/g,"");}
function substr(sString,iStart,iLength)
{if(iStart<0)
{iStart+=sString.length;}
if(iLength==undefined)
{iLength=sString.length;}
else if(iLength<0)
{iLength+=sString.length;}
else
{iLength+=iStart;}
if(iLength<iStart)
{iLength=iStart;}
return sString.substring(iStart,iLength);}
function str_repeat(str,repeat)
{var output='';for(var i=0;i<repeat;i++)
{output+=str;}
return output;}
function print_r(array,return_val)
{var output="",pad_char=" ",pad_val=4;var formatArray=function(obj,cur_depth,pad_val,pad_char)
{if(cur_depth>0)
{cur_depth++;}
var base_pad=repeat_char(pad_val*cur_depth,pad_char);var thick_pad=repeat_char(pad_val*(cur_depth+1),pad_char);var str="";if(obj instanceof Array||obj instanceof Object){str+="Array\n"+base_pad+"(\n";for(var key in obj){if(obj[key]instanceof Array){str+=thick_pad+"["+key+"] => "+formatArray(obj[key],cur_depth+1,pad_val,pad_char);}else{str+=thick_pad+"["+key+"] => "+obj[key]+"\n";}}
str+=base_pad+")\n";}else if(obj==null||obj==undefined){str='';}else{str=obj.toString();}
return str;};var repeat_char=function(len,pad_char){var str="";for(var i=0;i<len;i++){str+=pad_char;};return str;};output=formatArray(array,0,pad_val,pad_char);if(return_val!==true){document.write("<pre>"+output+"</pre>");return true;}else{return output;}}
function isset()
{var a=arguments;var l=a.length;var i=0;if(l==0){throw new Error('Empty isset');}
while(i!=l){if(typeof(a[i])=='undefined'||a[i]===null){return false;}else{i++;}}
return true;}
function empty(mixed_var){var key;if(mixed_var===""||mixed_var===0||mixed_var==="0"||mixed_var===null||mixed_var===false||mixed_var===undefined||trim(mixed_var)==""){return true;}
if(typeof mixed_var=='object'){for(key in mixed_var){if(typeof mixed_var[key]!=='function'){return false;}}
return true;}
return false;}
function trim(str,charlist){var whitespace,l=0,i=0;str+='';if(!charlist){whitespace=" \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";}else{charlist+='';whitespace=charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g,'\$1');}
l=str.length;for(i=0;i<l;i++){if(whitespace.indexOf(str.charAt(i))===-1){str=str.substring(i);break;}}
l=str.length;for(i=l-1;i>=0;i--){if(whitespace.indexOf(str.charAt(i))===-1){str=str.substring(0,i+1);break;}}
return whitespace.indexOf(str.charAt(0))===-1?str:'';}
function ltrim(str,charlist){charlist=!charlist?' \s\xA0':(charlist+'').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g,'\$1');var re=new RegExp('^['+charlist+']+','g');return(str+'').replace(re,'');}
function rtrim(str,charlist){charlist=!charlist?' \s\xA0':(charlist+'').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g,'\$1');var re=new RegExp('['+charlist+']+$','g');return(str+'').replace(re,'');}
function function_exists(function_name){if(typeof function_name=='string'){return(typeof window[function_name]=='function');}else{return(function_name instanceof Function);}}
function explode(delimiter,string,limit){var emptyArray={0:''};if(arguments.length<2||typeof arguments[0]=='undefined'||typeof arguments[1]=='undefined')
{return null;}
if(delimiter===''||delimiter===false||delimiter===null)
{return false;}
if(typeof delimiter=='function'||typeof delimiter=='object'||typeof string=='function'||typeof string=='object')
{return emptyArray;}
if(delimiter===true){delimiter='1';}
if(!limit){return string.toString().split(delimiter.toString());}else{var splitted=string.toString().split(delimiter.toString());var partA=splitted.splice(0,limit-1);var partB=splitted.join(delimiter.toString());partA.push(partB);return partA;}}
function in_array(needle,haystack,strict){var found=false,key,strict=!!strict;for(key in haystack){if((strict&&haystack[key]===needle)||(!strict&&haystack[key]==needle)){found=true;break;}}
return found;}
function getResizedWindow()
{var myWidth=0;var myHeight=0;if(typeof(window.innerWidth)=='number')
{myWidth=window.innerWidth;myHeight=window.innerHeight;}
else if(document.documentElement&&(document.documentElement.clientWidth||document.documentElement.clientHeight))
{myWidth=document.documentElement.clientWidth;myHeight=document.documentElement.clientHeight;}else if(document.body&&(document.body.clientWidth||document.body.clientHeight))
{myWidth=document.body.clientWidth;myHeight=document.body.clientHeight;}
return myHeight;}
function htmlspecialchars(string,quote_style,charset,double_encode){var optTemp=0,i=0,noquotes=false;if(typeof quote_style==='undefined'||quote_style===null){quote_style=2;}
string=string.toString();if(double_encode!==false){string=string.replace(/&/g,'&amp;');}
string=string.replace(/</g,'&lt;').replace(/>/g,'&gt;');var OPTS={'ENT_NOQUOTES':0,'ENT_HTML_QUOTE_SINGLE':1,'ENT_HTML_QUOTE_DOUBLE':2,'ENT_COMPAT':2,'ENT_QUOTES':3,'ENT_IGNORE':4};if(quote_style===0){noquotes=true;}
if(typeof quote_style!=='number'){quote_style=[].concat(quote_style);for(i=0;i<quote_style.length;i++){if(OPTS[quote_style[i]]===0){noquotes=true;}
else if(OPTS[quote_style[i]]){optTemp=optTemp|OPTS[quote_style[i]];}}
quote_style=optTemp;}
if(quote_style&OPTS.ENT_HTML_QUOTE_SINGLE){string=string.replace(/'/g,'&#039;');}
if(!noquotes){string=string.replace(/"/g,'&quot;');}
return string;}
function getPageScroll(){var xScroll;var yScroll;if(self.pageYOffset){yScroll=self.pageYOffset;xScroll=self.pageXOffset;}else if(document.documentElement&&document.documentElement.scrollTop){yScroll=document.documentElement.scrollTop;xScroll=document.documentElement.scrollLeft;}else if(document.body){yScroll=document.body.scrollTop;xScroll=document.body.scrollLeft;}
return new Array(xScroll,yScroll);}
function getPageHeight(){var windowHeight;if(self.innerHeight){windowHeight=self.innerHeight;}else if(document.documentElement&&document.documentElement.clientHeight){windowHeight=document.documentElement.clientHeight;}else if(document.body){windowHeight=document.body.clientHeight;}
return windowHeight;}
function htmlentities(str){return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
function parse_url(str,component){var o={strictMode:false,key:["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],q:{name:"queryKey",parser:/(?:^|&)([^&=]*)=?([^&]*)/g},parser:{strict:/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,loose:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/}};var m=o.parser[o.strictMode?"strict":"loose"].exec(str),uri={},i=14;while(i--){uri[o.key[i]]=m[i]||"";}
switch(component){case'PHP_URL_SCHEME':return uri.protocol;case'PHP_URL_HOST':return uri.host;case'PHP_URL_PORT':return uri.port;case'PHP_URL_USER':return uri.user;case'PHP_URL_PASS':return uri.password;case'PHP_URL_PATH':return uri.path;case'PHP_URL_QUERY':return uri.query;case'PHP_URL_FRAGMENT':return uri.anchor;default:var retArr={};if(uri.protocol!==''){retArr.scheme=uri.protocol;}
if(uri.host!==''){retArr.host=uri.host;}
if(uri.port!==''){retArr.port=uri.port;}
if(uri.user!==''){retArr.user=uri.user;}
if(uri.password!==''){retArr.pass=uri.password;}
if(uri.path!==''){retArr.path=uri.path;}
if(uri.query!==''){retArr.query=uri.query;}
if(uri.anchor!==''){retArr.fragment=uri.anchor;}
return retArr;}}
function isScrolledIntoView(elem)
{if(!$Core.exists(elem)){return;}
var docViewTop=$(window).scrollTop();var docViewBottom=docViewTop+$(window).height();var elemTop=$(elem).offset().top;var elemBottom=elemTop+$(elem).height();return((docViewTop<elemTop)&&(docViewBottom>elemBottom));}
 /* static\jscript\main.js */var $Cache={};var $oEventHistory={};var $oStaticHistory={};var $bDocumentIsLoaded=false;if(typeof window.console=='undefined')
{window.console={log:function(sTxt){}};}
if(typeof window.console.log=='undefined')
{window.console.log=function(sTxt){};}
$.fn.message=function(sMessage,sType)
{switch(sType)
{case'valid':sClass='valid_message';break;case'error':sClass='error_message';break;case'public':sClass='public_message';break;}
this.html(this.html()+'<div class="'+sClass+'">'+sMessage+'</\div>');return this;};$.getParams=function(sUrl)
{var aArgs=sUrl.split('#');var aArgsFinal=aArgs[1].split('?');var aFinal=aArgsFinal[1].split('&');var aUrlParams=Array();for(i=0;i<aFinal.length;i++)
{var aArg=aFinal[i].split('=');aUrlParams[aArg[0]]=aArg[1];}
return aUrlParams;};$.ajaxProcess=function(sMessage,sSize)
{sMessage=(sMessage?sMessage:getPhrase('core.processing'));if(empty(sSize))
{sSize='small';}
return'<span style="margin-left:4px; margin-right:4px; font-size:9pt; font-weight:normal;"><img src="'+eval('oJsImages.ajax_'+sSize+'')+'" class="v_middle" /> '+(sMessage==='no_message'?'':sMessage+'...')+'</span>';};$Behavior.imageHoverHolder=function()
{$('body').click(function(){$('.image_hover_menu_link').each(function(){if($(this).hasClass('image_hover_active'))
{$(this).removeClass('image_hover_active');$(this).parent().find('.image_hover_menu:first').hide();$(this).hide();}});});$('.image_hover_holder').mouseover(function()
{if(!empty($(this).find('.image_hover_menu:first').html()))
{$(this).addClass('image_hover_holder_hover').find('.image_hover_menu_link:first').show();}});$('.image_hover_holder').mouseout(function()
{if(!$(this).find('.image_hover_menu_link').hasClass('image_hover_active'))
{$(this).removeClass('image_hover_holder_hover').find('.image_hover_menu_link:first').hide();}});$('.image_hover_menu_link').click(function(){var oMenu=$(this).parent().find('.image_hover_menu:first');if($(this).hasClass('image_hover_active'))
{$(this).removeClass('image_hover_active');oMenu.hide();return false;}
$('.image_hover_menu_link').each(function(){if($(this).hasClass('image_hover_active'))
{$(this).removeClass('image_hover_active');$(this).parent().find('.image_hover_menu:first').hide();$(this).hide();}});$(this).addClass('image_hover_active');oMenu.show();return false;});};$Behavior.targetBlank=function()
{$('.targetBlank').click(function()
{window.open($(this).get(0).href);return false;});};var bCacheIsHover=false;$Behavior.dropDown=function()
{$('.sJsDropMenu').click(function()
{$(this).blur();$('.dropContent').hide();$('.sub_menu_bar li a').removeClass('is_already_open');if($(this).hasClass('is_already_open'))
{$(this).parent().find('.dropContent:first').hide();$(this).removeClass('is_already_open');}
else
{$(this).parent().find('.dropContent:first').show();$(this).addClass('is_already_open');}
return false;});$('.dropContent').mouseover(function(){bCacheIsHover=true;});$('.dropContent').mouseout(function(){bCacheIsHover=false;});$('body').click(function()
{if(!bCacheIsHover){$('.dropContent').hide();$('.sub_menu_bar li a').each(function(){if($(this).hasClass('is_already_open')){$(this).removeClass('is_already_open');}});}});};$Behavior.goJump=function()
{$('.goJump').change(function()
{if($(this).get(0).value=="")
{return false;}
if($(this).get(0).value.search(/delete/i)!=-1&&!confirm(getPhrase('core.are_you_sure')))
{return false;}
window.location.href=$(this).get(0).value;});};$Behavior.inlinePopup=function()
{$('.inlinePopup').click(function()
{var $aParams=$.getParams($(this).get(0).href);var sParams='&tb=true';for(sVar in $aParams)
{sParams+='&'+sVar+'='+$aParams[sVar]+'';}
sParams=sParams.substr(1,sParams.length);tb_show($(this).get(0).title,$.ajaxBox($aParams['call'],sParams));return false;});};$Behavior.blockClick=function()
{$('.block .menu ul li a').click(function()
{$(this).parents('.block:first').find('li').removeClass('active');$(this).parent().addClass('active');if(this.href.match(/#/))
{var aParts=explode('#',this.href);var aParams=explode('?',aParts[1]);var aParamParts=explode('&',aParams[1]);var aRequest=Array();for(i in aParamParts)
{var aPart=explode('=',aParamParts[i]);aRequest[aPart[0]]=aPart[1];}
$('.js_block_click_lis_cache').remove();$(this).parents('.menu:first').find('ul').append('<li class="js_block_click_lis_cache" style="margin-top:2px;">'+$.ajaxProcess('no_message')+'</li>');$.ajaxCall(aParams[0],aParams[1]+'&js_block_click_lis_cache=true','GET');}
return false;});};$Behavior.deleteLink=function()
{$('.delete_link').click(function()
{if(confirm(getPhrase('core.are_you_sure')))
{$aParams=$.getParams($(this).get(0).href);var sParams='';for(sVar in $aParams)
{sParams+='&'+sVar+'='+$aParams[sVar]+'';}
sParams=sParams.substr(1,sParams.length);$.ajaxCall($aParams['call'],sParams);}
return false;});};$Behavior.globalToolTip=function()
{if($('#js_global_tooltip').length<=0)
{$('body').prepend('<div id="js_global_tooltip" style="display:none;"></div>');}
$('.js_hover_title').mouseover(function()
{var offset=$(this).offset();var sContent='';if(isset($(this).find('.js_hover_info'))&&$(this).find('.js_hover_info').html()!==null&&$(this).find('.js_hover_info').html().length<1)
{}
else
{$('#js_global_tooltip').css('display','block');$('#js_global_tooltip').css('left',(offset.left-10)+'px');if($(this).find('.js_hover_info').length>0)
{sContent=$(this).find('.js_hover_info').html();}
else
{var oParent=$(this).parent();if(!empty(oParent.attr('title')))
{oParent.data('title',oParent.attr('title')).removeAttr('title');}
sContent=oParent.data('title');}
$('#js_global_tooltip').html('<div id="js_global_tooltip_display">'+sContent+'</div>');$('#js_global_tooltip').css('top',(offset.top-($('#js_global_tooltip_display').height()+10))+'px');}});$('.js_hover_title').mouseout(function()
{$('#js_global_tooltip').hide().html('').css('top','0px').css('left','0px');});};$Behavior.clearTextareaValue=function()
{$('.js_comment_text_area #text').focus(function()
{if($(this).val()==$('#js_comment_write_phrase').html())
{$(this).val('');}});};$Behavior.loadEditor=function()
{if((!getParam('bWysiwyg')||typeof(bForceDefaultEditor)!='undefined')&&typeof(Editor)=='object')
{Editor.getEditors();}};var sMoreFeedIds={};$Core.loadMoreFeeds=function()
{$Core.bRebuiltActivityFeed=false;$.ajaxCall('feed.appendMore','ids='+sMoreFeedIds,'GET');return false;};$Core.bRebuiltActivityFeed=false;$Core.rebuildActivityFeedCount=function(iTotal,sIds)
{sMoreFeedIds=sIds;$('.activity_feed_updates_link').hide();if(iTotal&&$Core.bRebuiltActivityFeed==true)
{$('#activity_feed_updates_link_holder').show();if(iTotal==1)
{$('#activity_feed_updates_link_single').show();}
else
{$('#activity_feed_updates_link_plural').show();$('#js_new_update_view').html(iTotal);}}
else
{$('#activity_feed_updates_link_holder').hide();$Core.bRebuiltActivityFeed=true;}};$Core.reloadActivityFeed=function(){if($('#sHashTagValue').length<1)
{setTimeout("$.ajaxCall('feed.reloadActivityFeed', 'reload-ids=' + $Core.getCurrentFeedIds(), 'GET');",2000);}};$Core.getCurrentFeedIds=function()
{var sMoreFeedIds='';$('.js_parent_feed_entry').each(function(){sMoreFeedIds+=$(this).attr('id').replace('js_item_feed_','')+',';});return sMoreFeedIds;};$Core.processForm=function(sSelector,bReset)
{if(bReset===true)
{$(sSelector).find('.button:first').removeClass('button_off').attr('disabled',false);$(sSelector).find('.table_clear_ajax').hide();}
else
{$(sSelector).find('.button:first').addClass('button_off').attr('disabled',true);$(sSelector).find('.table_clear_ajax').show();}};$Core.exists=function(sSelector)
{return($(sSelector).length>0?true:false);};$Core.searchFriends=function($aParams)
{if(typeof($Core.searchFriendsInput)=='undefined'){return;}
$Core.searchFriendsInput.init($aParams);};$Core.loadStaticFile=function($aFiles)
{$Core.loadStaticFiles($aFiles);};var sCustomHistoryUrl='';$Core.loadStaticFiles=function($aFiles)
{if(typeof($aFiles)=='string')
{$aFiles=new Array($aFiles);}
if(!$bDocumentIsLoaded)
{if(!isset($Cache['post_static_files']))
{$Cache['post_static_files']=new Array();}
$Cache['post_static_files'].push($aFiles);return;}
$Core.dynamic_js_files=0;$($aFiles).each(function($sKey,$sFile){if(substr($sFile,-3)=='.js'&&!isset($oStaticHistory[$sFile]))
{$Core.dynamic_js_files++;}});$($aFiles).each(function($sKey,$sFile)
{if(!isset($oStaticHistory[$sFile]))
{$oStaticHistory[$sFile]=true;if(substr($sFile,-3)=='.js')
{$.ajax($sFile).always(function(){$Core.dynamic_js_files--;});}
else if(substr($sFile,-4)=='.css')
{var sCustomId='';if(substr($sFile,-10)=='custom.css'){sCustomHistoryUrl=$sFile;sCustomId='js_custom_css_loader';}
$('head').append('<link '+sCustomId+' rel="stylesheet" type="text/css" href="'+$sFile+'?v='+getParam('sStaticVersion')+'" />');}
else
{eval($sFile);}}
else
{if(substr($sFile,-10)=='custom.css'){sCustomHistoryUrl=$sFile;}}});if(!empty(sCustomHistoryUrl)){$('#js_custom_css_loader').remove();$('head').append('<link id="js_custom_css_loader" rel="stylesheet" type="text/css" href="'+sCustomHistoryUrl+'?v='+getParam('sStaticVersion')+'" />');}};$Behavior.globalInit=function()
{$('.sJsConfirm').click(function()
{if(confirm(getPhrase('core.are_you_sure')))
{return true;}
return false;});$('#select_lang_pack').click(function()
{tb_show(oTranslations['core.language_packages'],$.ajaxBox('language.select','height=300&amp;width=300'));return false;});if(!oCore['core.is_admincp'])
{if($('#country_iso').length>0&&!empty(oCore['core.country_iso']))
{if(empty($('#country_iso').val()))
{$('#js_country_iso_option_'+oCore['core.country_iso']).attr('selected',true);}}}
$('.js_item_active').click(function()
{$(this).parent().find('.js_item_active input').attr('checked',false);if($(this).hasClass('item_is_active'))
{$(this).parent().find('.item_is_active input').attr('checked',true);}
else
{$(this).parent().find('.item_is_not_active input').attr('checked',true);}});if($('.moderate_link').length>0)
{$('.moderation_drop').click(function()
{if(parseInt($('.js_global_multi_total').html())===0)
{return false;}
if($(this).hasClass('is_clicked'))
{$('.moderation_holder ul').hide();$(this).removeClass('is_clicked');}
else
{$('.moderation_holder ul').show();$('.moderation_holder ul').css({'margin-top':'-'+($(this).height()+$('.moderation_holder ul').height()+4)+'px'});$(this).addClass('is_clicked');}
return false;});var iEmptyModLinks=0;$('.moderate_link').each(function()
{var sName='js_item_m_'+$(this).attr('rel')+'_'+$(this).attr('href').replace('#','');if(getCookie(sName))
{$(this).addClass('moderate_link_active');}
else
{iEmptyModLinks++;}});if(iEmptyModLinks===0)
{$('.moderation_action_unselect').show();$('.moderation_action_select').hide();}}
$('.moderation_process_action').click(function()
{if($(this).attr('rel')=='mail.mailThreadAction'&&$(this).attr('href').replace('#','')=='forward'){var sGlobalModeration='';$('.js_global_item_moderate').each(function(){sGlobalModeration+=','+parseInt($(this).val());});$Core.box('mail.compose',500,'forward_thread_id='+$('#js_forward_thread_id').val()+'&forwards='+sGlobalModeration);$Core.moderationLinkClear();}
else if($(this).attr('rel')=='mail.archive'&&$(this).attr('href').replace('#','')=='export'){$(this).parents('form:first').submit();$Core.moderationLinkClear();}
else if($(this).attr('rel')=='mail.moderation'&&$(this).attr('href').replace('#','')=='move'){$Core.box('mail.listFolders',400);}
else{$('.moderation_process').show();$('#js_global_multi_form_holder').ajaxCall($(this).attr('rel'),'action='+$(this).attr('href').replace('#',''));$Core.moderationLinkClear();}
return false;});$('.moderation_clear_all').click(function()
{$Core.moderationLinkClear();return false;});$('.moderation_action').click(function()
{var sType=$(this).attr('rel');$(this).hide();if(sType=='select')
{$('.moderation_action_unselect').show();}
else
{$('.moderation_action_select').show();}
$('.moderate_link').each(function()
{$Core.moderationLinkClick(this,sType);});return false;});$('.moderate_link').click(function()
{return $Core.moderationLinkClick(this);});$('.page_section_menu ul li a').click(function()
{var sRel=$(this).attr('rel');if(empty(sRel))
{return true;}
$('.page_section_menu ul li').removeClass('active');$('.page_section_menu_holder').hide();$('#'+sRel).show();$(this).parent().addClass('active');if($('#page_section_menu_form').length>0)
{$('#page_section_menu_form').val(sRel);}
return false;});if($('.js_date_picker').length>0)
{var sFormat=oParams['sDateFormat'];sFormat=sFormat.charAt(0)+'/'+sFormat.charAt(1)+'/'+sFormat.charAt(2);sFormat=sFormat.replace('D','d').replace('M','m').replace('Y','yy');$('.js_date_picker').datepicker('destroy');$('.js_date_picker').datepicker({dateFormat:sFormat,onSelect:function(dateText,inst)
{var aParts=explode('/',dateText);var sMonth;var sDay;var sYear;switch(oParams['sDateFormat']){case'YMD':sMonth=ltrim(aParts[1],'0');sDay=ltrim(aParts[2],'0');sYear=aParts[0];break;case'DMY':sMonth=ltrim(aParts[1],'0');sDay=ltrim(aParts[0],'0');sYear=aParts[2];break;default:sMonth=ltrim(aParts[0],'0');sDay=ltrim(aParts[1],'0');sYear=aParts[2];break;}
$(this).parents('.js_datepicker_holder:first').find('.js_datepicker_month').val(sMonth);$(this).parents('.js_datepicker_holder:first').find('.js_datepicker_day').val(sDay);$(this).parents('.js_datepicker_holder:first').find('.js_datepicker_year').val(sYear);}});$('.js_datepicker_image').each(function(){$(this).click(function(){$(this).parent().find('.js_date_picker').datepicker('show');});});}
$('#js_login_as_page').click(function(){$Core.box('pages.login',500);return false;});$('.mobile_view_options').click(function(){$('#js_mobile_form_holder').toggle();return false;});if(typeof $.browser!='undefined'&&$.browser.msie&&parseInt($.browser.version,10)<8&&!getParam('bJsIsMobile')){$('#js_update_internet_explorer').show();}};$Core.pageSectionMenuShow=function(sId)
{$('.page_section_menu_holder').hide();$('.page_section_menu ul li').removeClass('active');$(sId).show();$('.page_section_menu ul li a').each(function()
{if($(this).attr('rel')==sId.replace('#',''))
{$(this).parent().addClass('active');return false;}});};$Core.moderationLinkClear=function()
{var aCookies=document.cookie.split(';');$(aCookies).each(function(sKey,sValue)
{if(sValue.match(/js_item_m/i))
{var aParts=explode('=',sValue);deleteCookie(trim(aParts[0].replace(getParam('sJsCookiePrefix'),'')));}});$('.moderate_link').removeClass('moderate_link_active');$('#js_global_multi_form_ids').html('');$('.js_global_multi_total').html('0');$('.moderation_drop').addClass('not_active');$('.moderation_holder ul').hide();$('.moderation_action_unselect').hide();$('.moderation_action_select').show();};$Core.moderationLinkClick=function(oObj,sType)
{var sName='js_item_m_'+$(oObj).attr('rel')+'_'+$(oObj).attr('href').replace('#','');var iTotalItems=parseInt($('.js_global_multi_total').html());if(($(oObj).hasClass('moderate_link_active')&&sType!='select')||sType=='unselect')
{$(oObj).removeClass('moderate_link_active');$('#js_global_multi_form_ids').find('.'+sName).remove();deleteCookie(sName);iTotalItems--;}
else
{if(!$(oObj).hasClass('moderate_link_active'))
{$(oObj).addClass('moderate_link_active');$('#js_global_multi_form_ids').append('<div class="'+sName+'"><input class="js_global_item_moderate" type="hidden" name="item_moderate[]" value="'+$(oObj).attr('href').replace('#','')+'" /></div>');setCookie(sName,$(oObj).attr('rel')+'_'+$(oObj).attr('href').replace('#',''),1);iTotalItems++;}}
iTotalItems=$('.moderate_link_active').length;$('.js_global_multi_total').html(iTotalItems);if(iTotalItems)
{$('.moderation_drop').removeClass('not_active');}
else
{$('.moderation_drop').addClass('not_active');}
return false;};$Behavior.privacySettingDropDown=function()
{$('body').click(function()
{$('.privacy_setting_active').each(function()
{if($(this).hasClass('is_active'))
{$(this).parent().find('.privacy_setting_holder').hide();$(this).removeClass('is_active');}});});$('.privacy_setting_active').click(function()
{var $oParent=$(this).parent().find('.privacy_setting_holder');if($(this).hasClass('is_active'))
{$oParent.hide();$(this).removeClass('is_active');}
else
{$('.privacy_setting_active').each(function()
{if($(this).hasClass('is_active'))
{$(this).parent().find('.privacy_setting_holder').hide();$(this).removeClass('is_active');}});$oParent.show();$(this).addClass('is_active');}
$('#js_global_tooltip').hide().html('').css('top','0px').css('left','0px');return false;});$('.privacy_setting_holder ul li a').click(function()
{var $oParent=$(this).parents('.privacy_setting_div:first').find('.privacy_setting_active');var $sContent=$(this).html();if($sContent.toLowerCase().indexOf('<span>')>-1)
{var $aParts=explode('<span>',$sContent);if(!isset($aParts[1]))
{$aParts=explode('<SPAN>',$sContent);}
$sContent=$aParts[0];}
$oParent.html(''+$sContent+'<span class="js_hover_info">'+$sContent+'</span>');$(this).parents('.privacy_setting_div:first').find('.privacy_setting_holder').hide();$oParent.removeClass('is_active');$(this).parents('.privacy_setting_div:first').find('input').val($(this).attr('rel'));$('.privacy_setting_holder ul li a').removeClass('is_active_image');$(this).addClass('is_active_image');return false;});};var cacheShadownInfo=false;var shadow=null;var minHeight=null;$Core.resizeTextarea=function(oObj)
{if(cacheShadownInfo===false)
{var lineHeight=oObj.css('lineHeight');minHeight=oObj.height();cacheShadownInfo=true;shadow=$('<div></div>').css({position:'absolute',top:-10000,left:-10000,width:oObj.width(),fontSize:oObj.css('fontSize'),fontFamily:oObj.css('fontFamily'),lineHeight:oObj.css('lineHeight'),resize:'none'}).appendTo(document.body);}
var val=oObj.val().replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/&/g,'&amp;').replace(/\n/g,'<br/>');shadow.html(val);oObj.css('height',Math.max(shadow.height()+20,minHeight));};$Core.getObjectPosition=function(sId)
{if($('#'+sId).length<=0)
{return false;}
var curleft=0;var curtop=0;var obj=document.getElementById(sId);if(obj.offsetParent)
{do
{curleft+=obj.offsetLeft;curtop+=obj.offsetTop;}while(obj=obj.offsetParent);}
return{left:curleft,top:curtop};};$Core.getFriends=function(aParams)
{tb_show('',$.ajaxBox('friend.search','height=410&width=600&input='+aParams['input']+'&type='+(isset(aParams['type'])?aParams['type']:'')+''));};$Core.browseUsers=function(aParams)
{tb_show('',$.ajaxBox('user.browse','height=410&width=600&input='+aParams['input']+''));};$Core.composeMessage=function(aParams)
{if(aParams===undefined)
{aParams=new Array();}
tb_show('',$.ajaxBox('mail.compose','height=300&width=500'+(!isset(aParams['user_id'])?'':'&id='+aParams['user_id'])+'&no_remove_box=true'));};$Core.addAsFriend=function(iUserId)
{tb_show('',$.ajaxBox('friend.request','width=420&user_id='+iUserId+''));return false;};$Core.getParams=function(sHref)
{var aParams=new Array();var aUrlParts=explode('/',sHref);var iRequest=0;for(i in aUrlParts)
{if(empty(aUrlParts[i]))
{continue;}
aUrlParts[i]=aUrlParts[i].replace('#','');if(aUrlParts[i].match(/_/i))
{var aUrlParams=explode('_',aUrlParts[i]);aParams[aUrlParams[0]]=aUrlParams[1];}
else
{iRequest++;aParams['req'+iRequest]=aUrlParts[i];}}
return aParams;};$Core.getRequests=function(sHref,bReturnPath)
{var sParams='';var sUrlString='';var sModuleName=oCore['core.section_module'];switch(oCore['core.url_rewrite'])
{case'1':if(getParam('sHostedVersionId')==''){var oReq=new RegExp(""+getParam('sJsHome')+"(.*?)$","i");var aMatches=oReq.exec(sHref+(getParam('sHostedVersionId')==''?'':getParam('sHostedVersionId')+'/'));var aParts=explode('/',aMatches[1]);sUrlString='/'+aMatches[1];}
else{var aParts=explode('/',ltrim(sHref.pathname,'/'));sUrlString=sHref.pathname;}
break;case'3':if(oCore['profile.is_user_profile'])
{var aProfileMatches=sHref.match(/http:\/\/(.*?)\.(.*?)/i);sModuleName=aProfileMatches[1];}
var oReq=new RegExp(""+oParams['sJsHome']+"(.*?)$","i");var aMatches=oReq.exec(sHref);sUrlString=sModuleName+'/'+(aMatches!=null&&isset(aMatches[1])?aMatches[1]:'');break;default:var oReq=new RegExp("(.*?)=\/(.*?)$","i");var aMatches=oReq.exec(sHref);if(aMatches!==null)
{var aParts=explode('/',aMatches[2]);sUrlString=aMatches[2];}
break;}
if(bReturnPath===true)
{return'/'+ltrim(sUrlString,'/');}
return $Core.parseUrlString(sUrlString);};$Core.parseUrlString=function(sUrlString)
{var sParams='';var aUrlParts=explode('/',sUrlString);var iRequest=0;var iLoadCount=0;for(i in aUrlParts)
{if(empty(aUrlParts[i])||aUrlParts[i]=='#')
{continue;}
iLoadCount++;if(iLoadCount!=1&&aUrlParts[i].match(/_/i))
{var aUrlParams=explode('_',aUrlParts[i]);sParams+='&'+aUrlParams[0]+'='+aUrlParams[1];}
else
{iRequest++;sParams+='&req'+iRequest+'='+aUrlParts[i];}}
return sParams;};$Core.reverseUrl=function(sForm,aSkip)
{var aForms=explode('&',sForm);var sUrlParam='';for(i in aForms)
{var aFormParts=aForms[i].match(/(.*?)=(.*?)$/i);if(aFormParts!==null)
{if(isset(aSkip))
{if(in_array(aFormParts[1],aSkip))
{continue;}}
sUrlParam+=aFormParts[1]+'_'+encodeURIComponent(aFormParts[2])+'/';}}
return sUrlParam;};$Core.getHashParam=function(sHref)
{var sParams='';var aParams=$.getParams(sHref);for(var sKey in aParams)
{sParams+='&'+sKey+'='+aParams[sKey];}
sParams=ltrim(sParams,'&');return sParams;};$Core.box=function($sRequest,$sWidth,$sParams)
{tb_show('',$.ajaxBox($sRequest,'width='+$sWidth+($sParams?'&'+$sParams:'')));return false;};$Core.ajax=function(sCall,$oParams)
{var sParams='&'+getParam('sGlobalTokenName')+'[ajax]=true&'+getParam('sGlobalTokenName')+'[call]='+sCall;if(!sParams.match(/\[security_token\]/i))
{sParams+='&'+getParam('sGlobalTokenName')+'[security_token]='+oCore['log.security_token'];}
if(isset($oParams['params']))
{if(typeof($oParams['params'])=='string')
{sParams+=$oParams['params'];}
else
{$.each($oParams['params'],function($sKey,$sValue)
{sParams+='&'+$sKey+'='+encodeURIComponent($sValue)+'';});}}
$.ajax({type:(isset($oParams['type'])?$oParams['type']:'GET'),url:getParam('sJsStatic')+"ajax.php",dataType:'html',data:sParams,success:$oParams['success']});};$Core.popup=function(sUrl,aParams)
{oDate=new Date();iId=oDate.getTime();var sParams='';var iCount=0;var bCenter=false;for(i in aParams)
{if(i=='center')
{bCenter=true;continue;}
iCount++;if(iCount!=1)
{sParams+=',';}
sParams+=i+'='+aParams[i];}
if(bCenter===true)
{sParams+=',left='+(($(window).width()-aParams['width'])/2)+',top='+(($(window).height()-aParams['height'])/2)+'';}
window.open(sUrl,iId,sParams);};$Core.ajaxMessage=function()
{$('#global_ajax_message').html(getPhrase('core.saving')).animate({opacity:0.9}).slideDown();};$Core.toggleCategory=function(sName,iId)
{$('.'+sName).toggle();$('#show_more_'+iId).toggle();$('#show_less_'+iId).toggle();};if(substr(window.location.hash,0,2)=='#!')
{if(oCore['core.url_rewrite']=='1')
{var sUrl=trim(getParam('sJsHome'),'/');}
else
{var sUrl=getParam('sJsHome')+'index.php?'+getParam('sGetMethod')+'=';}
window.location=sUrl+window.location.hash.replace('#!','');}
$Core.page=function($aParams)
{if(typeof CorePageAjaxBrowsingStart=='function')
{CorePageAjaxBrowsingStart($aParams);}
if(isset($aParams['phrases']))
{for(sKey in $aParams['phrases'])
{if(!isset(oTranslations[sKey]))
{oTranslations[sKey]=$aParams['phrases'][sKey];}}}
$('.js_user_tool_tip_holder').remove();$('#js_user_profile_css').remove();if(isset($aParams['profilecss'])){$('body').append($aParams['profilecss']);}
if(!empty($aParams['files']))
{$Core.loadStaticFiles($aParams['files']);}
if(isset($aParams['customcss'])){var sCustomCss='';$('#js_global_custom_css').remove();for(sKey in $aParams['customcss']){sCustomCss+=$aParams['customcss'][sKey];}
if(!empty(sCustomCss)){}}
if(isset($aParams['nebula_current_menu'])){$('#nb_features_holder .menu_is_selected').removeClass('menu_is_selected');$('a[href="'+$aParams['nebula_current_menu']+'"]').addClass('menu_is_selected');$('#nb_features_link').removeClass('nb_is_clicked');$('#nb_features_holder').slideUp('fast');}
else
{$('#nb_features_holder .menu_is_selected').removeClass('menu_is_selected');$('a[href="'+oParams['sJsHome']+'"]').addClass('menu_is_selected');$('#nb_features_link').removeClass('nb_is_clicked');$('#nb_features_holder').slideUp('fast');}
document.title=$aParams['title'];$('#main_content_holder').html(''+$aParams['content']+'');$('body').css('cursor','auto');$oEventHistory[($Core.hasPushState()?$Core.getRequests(window.location,true):window.location.hash.replace('#!',''))]=$aParams['content'];$Core.loadInit();scroll(0,0);$Behavior.loadTinymceEditor=function(){};};$Core.updatePageHistory=function()
{var $sLocation=window.location.hash.replace('#!','');if(empty($sLocation))
{$sLocation='/';}
$oEventHistory[$sLocation]=$('#main_content_holder').html();};$Behavior.janRainLoader=function(){if(!getParam('bJsIsMobile')&&$('#janrainEngageEmbedHolder').length<1)
{$('body').prepend('<div style="position:absolute; z-index:5000; display:none;" id="janrainEngageEmbedHolder"><a href="#" style="position:absolute; bottom:5px; right:5px; z-index:1000000;" onclick="$(this).parent().hide(); return false;">Close</a><div id="janrainEngageEmbed"></div></div>');}
$('.rpxnow').click(function(){if(getParam('bJsIsMobile')){$.ajaxCall('janrain.login');return false;}
janrain.engage.signin.widget.init();$('#janrainEngageEmbedHolder').show();$('#janrainEngageEmbedHolder').css({top:getPageScroll()[1]+(getPageHeight()/5),left:'50%','margin-left':'-'+(($('#janrainEngageEmbed').width()/2)+12)+'px'});return false;});}
var bAjaxLinkIsClicked=false;var bCanByPassClick=false;var sClickProfileName='';$Behavior.linkClickAll=function()
{if(typeof $.browser!='undefined'&&$.browser.msie&&$.browser.version=='7.0')
{return false;}
if(!oCore['core.site_wide_ajax_browsing'])
{return false;}
$('a').click(function()
{var $sLink=$(this).attr('href');if(!$sLink)
{return;}
if((substr($sLink,0,7)!='http://'&&substr($sLink,0,8)!='https://')||substr($sLink,-1)=='#')
{return;}
if($(this).hasClass('photo_holder_image')&&!getParam('bPhotoTheaterMode')){}
else{if($(this).hasClass('no_ajax_link')||$(this).hasClass('thickbox')||$(this).hasClass('sJsConfirm'))
{return;}}
$('.js_box_image_holder_full').remove();var $aUrlParts=parse_url($sLink);if($aUrlParts['host']!=getParam('sJsHostname'))
{return;}
if(!isset($aUrlParts['query']))
{var sTempHost=$aUrlParts['scheme']+'://'+$aUrlParts['host']+$aUrlParts['path'];$aUrlParts['query']=sTempHost.replace(getParam('sJsHome'),'/');}
if(isset($aUrlParts['query']))
{var aUrlParts=explode('/',$aUrlParts['query']);var sAdminPath='admincp';if(getParam('sAdminCPLocation')!=''){sAdminPath=getParam('sAdminCPLocation');}
if(aUrlParts[1]==sAdminPath)
{return;}
if(aUrlParts[1]=='user'&&aUrlParts[2]=='logout')
{return;}}
if(bCanByPassClick===true&&aUrlParts[1]!=sClickProfileName){bCanByPassClick=false;return;}
if($('#noteform').length>0)
{$('#noteform').hide();}
if($('#js_photo_view_image').length>0)
{$('#js_photo_view_image').imgAreaSelect({hide:true});}
if($('#user_profile_photo').length>0)
{$('#user_profile_photo').imgAreaSelect({hide:true});}
$('.ajax_link_reset').hide();bAjaxLinkIsClicked=true;$('body').css('cursor','wait');$('.js_user_tool_tip_holder').hide();$('#js_global_tooltip').hide();$(this).addClass('is_built');$Core.addUrlPager(this);if(typeof BehaviorlinkClickAllAClick=='function')
{var bReturn=BehaviorlinkClickAllAClick($aUrlParts);if(bReturn==true)
{return false;}}
$.ajaxCall('core.page','ajax_page_display=true'+$Core.getRequests(this)+'&do='+$Core.getRequests(this,true),'GET');return false;});};$Core.loadInit=function()
{if($Core.dynamic_js_files>0)
{setTimeout(function(){$Core.loadInit();},20);return false;}
debug('$Core.loadInit() Loaded');$('*:not(.star-rating, .dont-unbind)').unbind();$.each($Behavior,function()
{this(this);});};$Core.init=function()
{if(!$Core.hasPushState()&&oCore['core.disable_hash_bang_support'])
{oCore['core.site_wide_ajax_browsing']=false;}
$bDocumentIsLoaded=true;$(document).ready(function()
{$.each($Behavior,function()
{this(this);});});$('script,link').each(function()
{if(!empty(this.src))
{var $sVar=this.src;if(this.src.indexOf('f=')!==-1)
{var $aFiles=explode('f=',$sVar);var $aParts=explode('&v=',$aFiles[1]);var $aGetFiles=explode(',',$aParts[0]);$($aGetFiles).each(function($sKey,$sFile)
{if(substr($sFile,0,7)=='module/')
{$oStaticHistory[getParam('sJsHome')+$sFile]=true;}
else
{$oStaticHistory[getParam('sJsStatic')+'jscript/'+$sFile]=true;}});return;}}
else if(!empty(this.href))
{var $sVar=this.href;if(this.href.indexOf('f=')!==-1)
{var $aFiles=explode('f=',$sVar);var $aParts=explode('&v=',$aFiles[1]);var $aGetFiles=explode(',',$aParts[0]);$($aGetFiles).each(function($sKey,$sFile)
{$oStaticHistory[getParam('sJsHome')+$sFile]=true;});return;}}
if(!empty($sVar))
{var $aParts=explode('?',$sVar);$oStaticHistory[$aParts[0]]=true;}});if(isset($Cache['post_static_files']))
{$($Cache['post_static_files']).each(function($sKey,$mValue)
{$Core.loadStaticFiles($mValue);});}
if(oCore['core.site_wide_ajax_browsing'])
{if($.browser.msie&&$.browser.version=='7.0')
{}
else
{if($Core.hasPushState()){$oEventHistory[$Core.getRequests(window.location,true)]=$('#main_content_holder').html();var $iTotalCount=0;$(window).bind('popstate',function(event){$iTotalCount++;if($.browser.safari&&$iTotalCount==1){return}
$Core.changeHistoryState({path:$Core.getRequests(window.location,true)});});}
else{$.address.change(function(event)
{$Core.changeHistoryState(event);});}}}};$Core.hasPushState=function(){return(typeof(window.history.pushState)=='function'?true:false);};$Core.addUrlPager=function(oObject,bShort)
{if($Core.hasPushState()){window.history.pushState('','',oObject.href);}
else{window.location='#!'+(bShort?oObject.href:$Core.getRequests(oObject.href,true));}};$Core.changeHistoryState=function(event){$('.js_box').each(function()
{if(!$(this).hasClass('js_box_no_remove'))
{var $sLink=$(this).find('.js_box_history:first').html();if(isset($aBoxHistory[$sLink]))
{delete $aBoxHistory[$sLink];}
$(this).remove();}});if($Core.hasPushState()){bAjaxLinkIsClicked=false;}
if(isset($oEventHistory[event.path])&&!bAjaxLinkIsClicked)
{$('#main_content_holder').html($oEventHistory[event.path].replace('$Core.loadInit();','').replace('$Core.updatePageHistory();',''));$Core.loadInit();scroll(0,0);}
else
{if(empty($oEventHistory))
{if(event.path=='/')
{if(isset($oEventHistory[$Core.getRequests(window.location,true)]))
{$('#main_content_holder').html($oEventHistory[$Core.getRequests(window.location,true)].replace('$Core.loadInit();','').replace('$Core.updatePageHistory();',''));$Core.loadInit();scroll(0,0);}
else
{$oEventHistory[$Core.getRequests(window.location,true)]=$('#main_content_holder').html();}}}
else
{if(event.path=='/')
{if(isset($oEventHistory[$Core.getRequests(window.location,true)]))
{$('#main_content_holder').html($oEventHistory[$Core.getRequests(window.location,true)].replace('$Core.loadInit();','').replace('$Core.updatePageHistory();',''));$Core.loadInit();scroll(0,0);}}}}
if(bAjaxLinkIsClicked)
{bAjaxLinkIsClicked=false;}};$Core.reloadPage=function()
{if(typeof window.location.reload=='function')window.location.reload();else if(typeof history!='undefined'&&history.go=='function')history.go(0);};$Behavior.addModerationListener=function()
{$(window).on('moderation_ended',function(){if($('.moderation_row:visible').length<1)
{if($('a.pager_previous_link').length>0&&$('a.pager_previous_link:first').attr('href')!='#')
{window.location.href=$('a.pager_previous_link:first').attr('href');return;}
if(window.location.href.indexOf('page_1')>(-1))
{window.location.href=window.location.href.replace('/page_1','');return;}
return $Core.reloadPage();if($('a.pager_next_link').length>0)
{if(isset($Core.Pager)&&isset($Core.Pager.count)&&($Core.Pager.count-$Core.Pager.size)>20)
{window.location.href=$('a.pager_next_link:first').attr('href');return;}
window.location.href=$('a.pager_next_link:first').attr('href');}
else
{wndow.location.href=window.location.href;}}
else if($('.moderation_row:first').is(':animated'))
{setTimeout('$(window).trigger("moderation_ended");',1000);}
else
{}});};$Behavior.loadDelayedBlocks=function()
{if(isset($Core.delayedBlocks)&&Object.prototype.toString.call($Core.delayedBlocks).indexOf('Array')>(-1))
{$.ajaxCall('core.loadDelayedBlocks','locations='+$Core.delayedBlocks.join(','));}
if($('#delayed_block').length>0)
{if((oParams['sController']=='core.index-member')||(oCore['sController']=='pages.view'))
{console.log('Behavior.loadDelayedBlock, Dont load the content');}
else
{var sContent=$('#delayed_block').html();var sUrl=$Core.getRequests(window.location.href,true);var aUrl=sUrl.split('/');var oUrlParams={};var aTemp=[];for(var i in aUrl)
{if(aUrl[i].indexOf('_')>(-1))
{aTemp=aUrl[i].split('_');oUrlParams[aTemp[0]]=aTemp[1];}
oUrlParams['req'+i]=aUrl[i];}
var sParams=$.param({params:oUrlParams});$.ajaxCall('core.loadDelayedBlocks','loadContent='+sContent+'&'+sParams,'GET');}}
if($('.load_delayed').length>0)
{var oGet={};$('.load_delayed').each(function(){if($(this).attr('id')==undefined||$(this).attr('id').length<1)
{$(this).attr('id','load_delayed_'+Math.floor(Math.random()*999));}
oGet[$(this).find('.block_id').html()]={block_id:$(this).find('.block_id').html(),block_name:$(this).find('.block_name').html(),block_param:$(this).find('.block_param').html()};});var sParams=encodeURIComponent(JSON.stringify(oGet));console.log(sParams);$.ajaxCall('core.loadDelayedBlocks','delayedTemplates='+sParams,'GET');}};if(!Array.prototype.map){Array.prototype.map=function(callback,thisArg){var T,A,k;if(this==null){throw new TypeError(" this is null or not defined");}
var O=Object(this);var len=O.length>>>0;if(typeof callback!=="function"){throw new TypeError(callback+" is not a function");}
if(thisArg){T=thisArg;}
A=new Array(len);k=0;while(k<len){var kValue,mappedValue;if(k in O){kValue=O[k];mappedValue=callback.call(T,kValue,k,O);A[k]=mappedValue;}
k++;}
return A;};}
if(!Array.prototype.filter)
{Array.prototype.filter=function(fun)
{"use strict";if(this==null)
throw new TypeError();var t=Object(this);var len=t.length>>>0;if(typeof fun!="function")
throw new TypeError();var res=[];var thisp=arguments[1];for(var i=0;i<len;i++)
{if(i in t)
{var val=t[i];if(fun.call(thisp,val,i,t))
res.push(val);}}
return res;};}
 /* static\jscript\ajax.js */$.ajaxBox=function(sCall,sExtra)
{var sParams=getParam('sJsAjax')+'?'+getParam('sGlobalTokenName')+'[ajax]=true&'+getParam('sGlobalTokenName')+'[call]='+sCall;if(sExtra)
{sParams+='&'+sExtra;}
if(!sParams.match(/\[security_token\]/i))
{sParams+='&'+getParam('sGlobalTokenName')+'[security_token]='+oCore['log.security_token'];}
return sParams;};var oCacheAjaxRequest=null;var aCacheAjaxLastCall={};window.onbeforeunload=function()
{if(oCacheAjaxRequest!==null)
{oCacheAjaxRequest.abort();}};$.fn.ajaxCall=function(sCall,sExtra,bNoForm,sType)
{if(empty(sType))
{sType='POST';}
switch(sCall){case'share.friend':case'share.email':case'share.bookmark':case'share.post':sType='POST';break;default:break;}
var sUrl=getParam('sJsAjax');if(typeof oParams['im_server']!='undefined'&&sCall.indexOf('im.')>(-1))
{sUrl=getParam('sJsAjax').replace(getParam('sJsHome'),getParam('im_server'));}
var sParams='&'+getParam('sGlobalTokenName')+'[ajax]=true&'+getParam('sGlobalTokenName')+'[call]='+sCall+''+(bNoForm?'':this.getForm());if(sExtra)
{sParams+='&'+ltrim(sExtra,'&');}
if(!sParams.match(/\[security_token\]/i))
{sParams+='&'+getParam('sGlobalTokenName')+'[security_token]='+oCore['log.security_token'];}
sParams+='&'+getParam('sGlobalTokenName')+'[is_admincp]='+(oCore['core.is_admincp']?'1':'0');sParams+='&'+getParam('sGlobalTokenName')+'[is_user_profile]='+(oCore['profile.is_user_profile']?'1':'0');sParams+='&'+getParam('sGlobalTokenName')+'[profile_user_id]='+(oCore['profile.user_id']?oCore['profile.user_id']:'0');if(getParam('bJsIsMobile')){sParams+='&js_mobile_version=true';}
oCacheAjaxRequest=$.ajax({type:sType,url:sUrl,dataType:"script",data:sParams});return oCacheAjaxRequest;};$.ajaxCall=function(sCall,sExtra,sType)
{return $.fn.ajaxCall(sCall,sExtra,true,sType);};$.fn.getForm=function()
{var objForm=this.get(0);var prefix="";var submitDisabledElements=false;if(arguments.length>1&&arguments[1]==true)
{submitDisabledElements=true;}
if(arguments.length>2)
{prefix=arguments[2];}
var sXml='';if(objForm&&objForm.tagName=='FORM')
{var formElements=objForm.elements;for(var i=0;i<formElements.length;i++)
{if(!formElements[i].name)
{continue;}
if(formElements[i].name.substring(0,prefix.length)!=prefix)
{continue;}
if(formElements[i].type&&(formElements[i].type=='radio'||formElements[i].type=='checkbox')&&formElements[i].checked==false)
{continue;}
if(formElements[i].disabled&&formElements[i].disabled==true&&submitDisabledElements==false)
{continue;}
var name=formElements[i].name;if(name)
{sXml+='&';if(formElements[i].type=='select-multiple')
{for(var j=0;j<formElements[i].length;j++)
{if(formElements[i].options[j].selected==true)
{sXml+=name+"="+encodeURIComponent(formElements[i].options[j].value)+"&";}}}
else
{sXml+=name+"="+encodeURIComponent(formElements[i].value);}}}}
if(!sXml&&objForm)
{sXml+="&"+objForm.name+"="+encodeURIComponent(objForm.value);}
return sXml;};
 /* static\jscript\thickbox/thickbox.js */var $iBoxTotalOpen=0;var $iCurrentZIndex=1;var $aBoxHistory={};var iImageIterationCount=0;var iCurrentImageIterationCount=0;var $sCurrentId=null;var sLastOpenUrl=null;var bIsPhotoImage=false;$Behavior.addDraggableToBoxes=function()
{if($('.js_box').length>0&&!$.ui)
{$Core.loadStaticFile(getParam('sJsHome')+'static/jscript/jquery/ui.js');}
$('.js_box').each(function()
{$(this).draggable('destroy');tb_draggable(this);});$('a.thickbox').click(function()
{var aExtra=$(this).html().match(/userid="([0-9a-z]?)"/i);var sHref=this.href;if(aExtra!=null&&isset(aExtra[1]))
{sHref+='userid_'+aExtra[1]+'/';}
var bReturn=tb_show('',sHref,this);if(bReturn===true){return true;}
return false;});};function js_box_remove($oObj)
{$('#main_core_body_holder').show();$('.imgareaselect-outer').remove();$('#noteform').remove();$('.imgareaselect-border1').remove();$('.imgareaselect-border2').remove();$('.imgareaselect-border3').remove();$('.imgareaselect-border4').remove();$('.feed_share_on_item a').removeClass('active');var $oParent=$($oObj).parents('.js_box:first');var $oBoxParent=$($oObj).parents('.js_box_image_holder_full:first');var $sLink=$oParent.find('.js_box_history:first').html();if(isset($aBoxHistory[$sLink]))
{delete $aBoxHistory[$sLink];}
$oBoxParent.fadeOut(100,function()
{$(this).remove();});$oParent.fadeOut(100,function()
{$(this).remove();});$('#global_attachment_list_inline').hide();if(bIsPhotoImage){bIsPhotoImage=false;}
return false;}
function tb_show_new_image(thisObject,sSrc,imageWidth,imageHeight,iCurrentIte)
{$('.js_box_image_gallery img').removeClass('is_active');$(thisObject).find('img').addClass('is_active');iCurrentImageIterationCount=iCurrentIte;$('#js_thickbox_core_image').attr({'src':sSrc,'width':imageWidth,'height':imageHeight});}
function js_box_next_image()
{iCurrentImageIterationCount++;var thisObject=$('#js_next_image_thumb_'+iCurrentImageIterationCount);if(thisObject.length<=0)
{iCurrentImageIterationCount=0;return js_box_next_image();}
var aParts=explode('|',thisObject.attr('rel'));tb_show_new_image('#js_next_image_thumb_'+iCurrentImageIterationCount,''+thisObject.attr('href')+'',aParts[0],aParts[1],iCurrentImageIterationCount);return false;}
function tb_show(caption,url,thisObject,sForceMessage,bForceNoCilck,sType)
{var baseURL;if(url.indexOf("?")!==-1)
{baseURL=url.substr(0,url.indexOf("?"));}
else
{baseURL=url;}
var urlString=/\.jpg$|\.jpeg$|\.png$|\.gif$|\.bmp$/;var urlType=baseURL.toLowerCase().match(urlString);if(urlType=='.jpg'||urlType=='.jpeg'||urlType=='.png'||urlType=='.gif'||urlType=='.bmp')
{imgPreloader=new Image();imgPreloader.onload=function()
{imgPreloader.onload=null;var pagesize=tb_getPageSize();var x=pagesize[0]-150;var y=pagesize[1]-150;var imageWidth=imgPreloader.width;var imageHeight=imgPreloader.height;if(imageWidth>x)
{imageHeight=imageHeight*(x/imageWidth);imageWidth=x;if(imageHeight>y)
{imageWidth=imageWidth*(y/imageHeight);imageHeight=y;}}
else if(imageHeight>y)
{imageWidth=imageWidth*(y/imageHeight);imageHeight=y;if(imageWidth>x)
{imageHeight=imageHeight*(x/imageWidth);imageWidth=x;}}
TB_WIDTH=imageWidth+30;TB_HEIGHT=imageHeight+60;$('.js_box_image_holder').remove();$('.js_box_image_holder').unbind('click');var sHtml='';sHtml+='<div class="js_box_image_holder"><div class="js_box '+(oParams['bJsIsMobile']?'mobile_js_box':'')+' js_box_image" style="display:block;"><div class="js_box_content">';if($(thisObject).parents('.js_box_thumbs_holder').length>0)
{var sCurrentSource=$(thisObject).find('img:first').attr('src');var sNewSource='';var SubimgPreloaderImageWidth='';var SubimgPreloaderImageHeight='';var SubArrayWidth=new Array();var SubArrayHeight=new Array();var sSubHtml='';var aUrlParts=new Array();var aUrlPartsNew=new Array();iImageIterationCount=0;iCurrentImageIterationCount=0;sHtml+='<div>';$(thisObject).parents('.js_box_thumbs_holder').find('.js_box_image_holder_thumbs').find('.thickbox').each(function()
{iImageIterationCount++;sNewSource=$(this).find('img').attr('src');aUrlParts=explode('_',sCurrentSource);aUrlPartsNew=explode('_',sNewSource);if(aUrlParts[0]==aUrlPartsNew[0])
{iCurrentImageIterationCount=iImageIterationCount;}
{SubimgPreloader=new Image();SubimgPreloader.src=$(this).attr('href');SubimgPreloaderImageWidth=SubimgPreloader.width;SubimgPreloaderImageHeight=SubimgPreloader.height;if(SubimgPreloaderImageWidth>x)
{SubimgPreloaderImageHeight=SubimgPreloaderImageHeight*(x/SubimgPreloaderImageWidth);SubimgPreloaderImageWidth=x;if(imageHeight>y)
{SubimgPreloaderImageWidth=SubimgPreloaderImageWidth*(y/SubimgPreloaderImageHeight);imageHeight=y;}}
else if(imageHeight>y)
{SubimgPreloaderImageWidth=SubimgPreloaderImageWidth*(y/SubimgPreloaderImageHeight);SubimgPreloaderImageHeight=y;if(SubimgPreloaderImageWidth>x)
{SubimgPreloaderImageHeight=SubimgPreloaderImageHeight*(x/SubimgPreloaderImageWidth);SubimgPreloaderImageWidth=x;}}
SubArrayWidth.push(SubimgPreloaderImageWidth);SubArrayHeight.push(SubimgPreloaderImageHeight);sSubHtml+='<a rel="'+SubimgPreloaderImageWidth+'|'+SubimgPreloaderImageHeight+'" id="js_next_image_thumb_'+iImageIterationCount+'" href="'+$(this).attr('href')+'" onclick="tb_show_new_image(this, \''+$(this).attr('href')+'\', '+SubimgPreloaderImageWidth+', '+SubimgPreloaderImageHeight+', '+iImageIterationCount+'); return false;"><img src="'+sNewSource+'" alt="" '+(aUrlParts[0]==aUrlPartsNew[0]?' class="is_active" ':'')+' /></a>';}});TB_WIDTH=((Math.max.apply(Math,SubArrayWidth))+30);TB_HEIGHT=((Math.max.apply(Math,SubArrayHeight)));sHtml+='<div class="js_box_image_holder_browse">';sHtml+='<div class="js_box_image_gallery_display" style="height:'+TB_HEIGHT+'px; line-height:'+TB_HEIGHT+'px;">';sHtml+='<a href="#" onclick="return js_box_next_image();"><img src="'+url+'" width="'+imageWidth+'" height="'+imageHeight+'" alt="" id="js_thickbox_core_image" /></a>';sHtml+='</div>';sHtml+='<div class="js_box_image_gallery">'+sSubHtml+'</div>';sHtml+='</div>';sHtml+='</div>';}
else
{sHtml+='<a href="#" onclick="$(\'.js_box_image_holder\').remove(); return false;"><img src="'+url+'" width="'+imageWidth+'" height="'+imageHeight+'" alt="" id="js_thickbox_core_image" /></a>';}
sHtml+='</div><div class="js_box_close"><a href="#" onclick="return js_box_remove(this);">'+oTranslations['core.close']+'</a></div></div></div>';$('body').prepend(sHtml);$('.js_box_image').css({top:(($(window).height()-$('.js_box_image').outerHeight())/2)+"px",left:(($(window).width()-$('.js_box_image').outerWidth())/2)+"px",display:'block'});var bCanCloseImageBox=true;$('.js_box').click(function()
{bCanCloseImageBox=false;});$('.js_box_image_holder').click(function()
{if(!bCanCloseImageBox)
{bCanCloseImageBox=true;}
else
{$(this).remove();}});if($.browser.msie&&$.browser.version=='7.0')
{$Behavior.ie7FixZindex();}};imgPreloader.src=url;return false;}
var bIsAlreadyOpen=false;if($(thisObject).hasClass('photo_holder_image')&&!empty($(thisObject).attr('rel')))
{if(!getParam('bPhotoTheaterMode')){return true;}
if(getParam('bJsIsMobile')){return true;}
if($Core.exists('.js_box_image_holder_full')){$('#photo_view_ajax_loader').show();}
sLastOpenUrl=(empty(window.location.hash)?$Core.getRequests(window.location,true):'/'+window.location.hash);var sUserId=url.match(/userid_([0-9]+)/);var sAlbumId=url.match(/albumid_([0-9]+)/);var queryString=''+getParam('sGlobalTokenName')+'[call]=photo.view&width=940'+(typeof sPhotoCategory!='undefined'?'&category='+sPhotoCategory:'')+'&req2='+$(thisObject).attr('rel')+'&theater=true&no_remove_box=true'+(sUserId!=null&&isset(sUserId[1])?'&userid='+sUserId[1]:'')+(sAlbumId!=null&&isset(sAlbumId[1])?'&albumid='+sAlbumId[1]:'');var params=tb_parseQuery(queryString);bIsPhotoImage=true;if(isset($aBoxHistory[params[''+getParam('sGlobalTokenName')+'[call]']]))
{bIsAlreadyOpen=true;}
if($('#noteform').length>0)
{$('#noteform').hide();}
if($('#js_photo_view_image').length>0)
{$('#js_photo_view_image').imgAreaSelect({hide:true});}}
else
{if($Core.exists('.js_box_image_holder_full')){js_box_remove($('.js_box_image_holder_full').find('.js_box_content:first'));}
if(url.indexOf('#')!=-1)
{var aParams=url.split('#');url='#'+aParams[1];}
var queryString=url.replace(/^[^\?]+\??/,'');var params=tb_parseQuery(queryString);}
if(!bIsPhotoImage&&isset($aBoxHistory[params[''+getParam('sGlobalTokenName')+'[call]']]))
{return false;}
if(!bIsAlreadyOpen)
{$iBoxTotalOpen++;$iCurrentZIndex++;$aBoxHistory[params[getParam('sGlobalTokenName')+'[call]']]=true;$sCurrentId='js_box_id_'+$iBoxTotalOpen;}
if(caption===null)
{caption='';}
var bIsFullMode=false;if(params['width']=='full')
{params['width']=($(window).width());params['height']=($(window).height());bIsFullMode=true;}
else if(params['width']=='scan')
{params['width']=($(window).width()-(oCore['core.is_admincp']?100:150));params['height']=($(window).height()-(oCore['core.is_admincp']?100:150));}
TB_WIDTH=(!empty(params['width'])?((params['width']*1)+30):630);TB_HEIGHT=(params['height']*1)+40||440;var pagesize=tb_getPageSize();if(TB_HEIGHT>pagesize[1])
{TB_HEIGHT=(pagesize[1]-75);}
if(TB_WIDTH>pagesize[0])
{TB_WIDTH=(pagesize[0]-75);}
ajaxContentW=TB_WIDTH-30;ajaxContentH=TB_HEIGHT-45;if(!bIsAlreadyOpen)
{var sHtml='';if(bIsPhotoImage){$('.js_box_image_holder').remove();$('.js_box_image_holder').unbind('click');sHtml+='<div class="js_box_image_holder_full">';sHtml+='<div class="js_box_image_holder_full_loader" style="position:absolute; top:50%; left:50%;"><img src="'+oJsImages['loading_animation']+'" alt="" /></div>';sHtml+='<div style="display:none;" id="'+$sCurrentId+'" class="js_box'+(oParams['bJsIsMobile']?' mobile_js_box':' ')+(isset(params['no_remove_box'])?' js_box_no_remove':'')+'" style="width:'+ajaxContentW+'px;">';sHtml+='<div class="js_box_content"></div>';sHtml+='<div class="js_box_close"><a href="#" onclick="return js_box_remove(this);">'+oTranslations['core.close']+'</a><span class="js_box_history">'+params[getParam('sGlobalTokenName')+'[call]']+'</span></div>';sHtml+='</div>';sHtml+='</div>';}
else{if(bIsPhotoImage)
{$('.js_box_image_holder').remove();$('.js_box_image_holder').unbind('click');sHtml+='<div class="js_box_image_holder_full">';}
if(bForceNoCilck){sHtml+='<div class="js_box_image_holder">';}
sHtml+='<div id="'+$sCurrentId+'" class="js_box'+(oParams['bJsIsMobile']?' mobile_js_box':' ')+(isset(params['no_remove_box'])?' js_box_no_remove':'')+'" style="width:'+ajaxContentW+'px;">';if(!bIsPhotoImage)
{sHtml+='<div class="js_box_title">'+caption+'</div>';}
sHtml+='<div class="js_box_content"><span class="js_box_loader">'+oTranslations['core.loading']+'...</span></div>';{sHtml+='<div class="js_box_close"><a href="#" onclick="return js_box_remove(this);">'+oTranslations['core.close']+'</a><span class="js_box_history">'+params[getParam('sGlobalTokenName')+'[call]']+'</span></div>';}
sHtml+='</div>';if(bIsPhotoImage)
{sHtml+='</div>';}
if(bForceNoCilck){sHtml+='</div>';}}
$('body').prepend(sHtml);var $oNew=$('#'+$sCurrentId+'');tb_position($oNew,(bIsFullMode?bIsFullMode:''));if(!bIsPhotoImage){$oNew.show();}else{$('.js_box_image_holder_full_loader').css({'margin-left':'-'+($('.js_box_image_holder_full_loader').find('img:first').width()/2)+'px','margin-top':'-'+($('.js_box_image_holder_full_loader').find('img:first').height()/2)+'px'});}}
else
{$oNew=$('.js_box_image_holder_full').find('.js_box:first');}
if(getParam('bJsIsMobile')){queryString+='&js_mobile_version=true';}
if(url.indexOf('TB_inline')!=-1){if(params['type'])
{switch(params['type'])
{case'delete':var sHtml='';sHtml+='<div id="js_inline_delete">';if(!params['call'])
{sHtml+='<form method="post" action="'+sMainUrl+'">';sHtml+='<div><input type="hidden" name="'+getParam('sGlobalTokenName')+'[security_token]" value="'+getParam('sSecurityToken')+'" />';}
sHtml+='<div>';sHtml+=getPhrase('core.are_you_sure');sHtml+='<div class="t_center p_4">';sHtml+=' <input type="hidden" name="item_id" value="'+params['itemId']+'" id="js_inline_delete_id" /> ';if(!params['call'])
{sHtml+=' <input type="submit" value="'+getPhrase('core.yes')+'" class="button" /> ';}
else
{sHtml+=' <input type="button" value="'+getPhrase('core.yes')+'" class="button" onclick="$(\'#js_inline_delete_id\').ajaxCall(\''+params['call']+'\', \''+queryString+'\'); tb_remove();" /> ';}
sHtml+=' <input type="button" value="'+getPhrase('core.no')+'" class="button" onclick="tb_remove();" /> ';sHtml+='</div>';sHtml+='</div>';if(!params['call'])
{sHtml+='</form>';}
sHtml+='</div>';break;}
$oNew.find('.js_box_content').html('');$oNew.find('.js_box_content').html(sHtml);}
else
{var sHtml=$('#'+params['inlineId']).children();}
$oNew.find('.js_box_content').html('');$oNew.find('.js_box_content').html(sHtml);return;}
else if(isset(params['TB_iframe'])){var sIframe='';$oNew.find('.js_box_content').html('');$oNew.find('.js_box_content').html(sIframe);}
else{if(!empty(sForceMessage)){$oNew.find('.js_box_content').html('');$oNew.find('.js_box_content').html(sForceMessage);return;}
var sAjaxType='GET';if((params[''+getParam('sGlobalTokenName')+'[call]']=='share.popup')||sType=='POST'){sAjaxType='POST';}
$.ajax({type:sAjaxType,dataType:'html',url:getParam('sJsAjax'),data:queryString,success:function(sMsg)
{$oNew.find('.js_box_content').html('');$oNew.find('.js_box_content').html(sMsg);if(!empty($oNew.find('.js_box_title_store:first').html()))
{$oNew.find('.js_box_title:first').html($oNew.find('.js_box_title_store:first').html());}
$oNew.find('.js_box_title:first').show();$oNew.find('.js_box_close:first').show();if(bIsFullMode){$oNew.find('.js_box_content').height(ajaxContentH);}
if(bIsPhotoImage)
{var thisHeight=$(window).height();var thisBodyHeight=$('body').height();var newHeight=(thisHeight>thisBodyHeight?thisHeight:thisBodyHeight);$('.js_box_image_holder_full').css({'top':'0px','height':(newHeight+50)+'px'});var bCanCloseImageBox=true;$Behavior.onCloseThickbox=function()
{$('.js_box').click(function()
{bCanCloseImageBox=false;});$('.js_box a').click(function()
{bCanCloseImageBox=false;});$('.js_box_image_holder_full').click(function()
{if(!bCanCloseImageBox)
{bCanCloseImageBox=true;}
else
{$('#main_core_body_holder').show();if($('#noteform').length>0)
{$('#noteform').hide();}
if($('#js_photo_view_image').length>0)
{$('#js_photo_view_image').imgAreaSelect({hide:true});}
bIsPhotoImage=false;$(this).remove();delete $aBoxHistory[params[getParam('sGlobalTokenName')+'[call]']];}});}
$Behavior.onCloseThickbox();}}});}}
$Behavior.thickboxBrowser=function(){if($Core.exists('.js_box_image_holder_full')){$(document).keyup(function(e){if(e.keyCode==27){js_box_remove($('.js_box_image_holder_full').find('.js_box_content'));}});}};function tb_get_active()
{var $aAllBoxIndex=new Array();var $aAllBoxIndexHolder=new Array();$('.js_box').each(function()
{$aAllBoxIndex[parseInt($(this).css('z-index'))]=$(this).attr('id');$aAllBoxIndexHolder.push(parseInt($(this).css('z-index')));});var $iFocusBox=parseInt(Math.max.apply(Math,$aAllBoxIndexHolder));if(isset($aAllBoxIndex[$iFocusBox])&&$('#'+$aAllBoxIndex[$iFocusBox]).length>0)
{return $aAllBoxIndex[$iFocusBox];}
return null;}
function tb_remove()
{$('#main_core_body_holder').show();var $aAllBoxIndex=new Array();var $aAllBoxIndexHolder=new Array();$('.js_box').each(function()
{$aAllBoxIndex[parseInt($(this).css('z-index'))]=$(this).attr('id');$aAllBoxIndexHolder.push(parseInt($(this).css('z-index')));});var $iFocusBox=parseInt(Math.max.apply(Math,$aAllBoxIndexHolder));if(isset($aAllBoxIndex[$iFocusBox])&&$('#'+$aAllBoxIndex[$iFocusBox]).length>0)
{var $sLink=$('#'+$aAllBoxIndex[$iFocusBox]).find('.js_box_history:first').html();$('#'+$aAllBoxIndex[$iFocusBox]).fadeOut('fast');$('#'+$aAllBoxIndex[$iFocusBox]).parent('.js_box_image_holder').fadeOut('fast');delete $aBoxHistory[$sLink];}
$('#global_attachment_list_inline').hide();return false;}
function tb_draggable($oObj)
{if($($oObj).parent().hasClass('js_box_image_holder_full'))
{return false;}
$($oObj).draggable({handle:'.js_box_title',opacity:0.35,zIndex:3000,start:function(event,ui)
{if($('#global_attachment_list_inline').length>0)
{$('#global_attachment_list_inline').hide();}},stop:function(event,ui)
{if($('#global_attachment_list_inline').length>0)
{$Core.updateInlineBox();$('#global_attachment_list_inline').show();}}});}
function tb_position($oObj,bFull)
{if(bFull!==true)
{if(!$.ui)
{$Core.loadStaticFile(getParam('sJsHome')+'static/jscript/jquery/ui.js');}
tb_draggable('.js_box');}
var $aAllBoxIndex=new Array();$('.js_box').each(function()
{$aAllBoxIndex.push(parseInt($(this).css('z-index')));});$('.js_box').unbind('click');$('.js_box').click(function()
{var $aAllBoxIndexInner=new Array();$('.js_box').each(function()
{$aAllBoxIndexInner.push(parseInt($(this).css('z-index')));});$(this).css({'z-index':(parseInt(Math.max.apply(Math,$aAllBoxIndexInner))+1)});});if(bFull===true)
{$($oObj).css({top:getPageScroll()[1],left:'50%','margin-left':'-'+(($($oObj).width()/2)+12)+'px','z-index':(parseInt(Math.max.apply(Math,$aAllBoxIndex))+1)});}
else
{$($oObj).css({top:getPageScroll()[1]+(getPageHeight()/5),left:'50%','margin-left':'-'+(($($oObj).width()/2)+12)+'px','z-index':(parseInt(Math.max.apply(Math,$aAllBoxIndex))+1)});}
if($.browser.msie&&$.browser.version=='7.0')
{$Behavior.ie7FixZindex();}}
function tb_parseQuery(query)
{var Params={};if(!query)
{return Params;}
var Pairs=query.split(/[;&]/);for(var i=0;i<Pairs.length;i++)
{var KeyVal=Pairs[i].split('=');if(!KeyVal||KeyVal.length!=2)
{continue;}
var key=unescape(KeyVal[0]);var val=unescape(KeyVal[1]);val=val.replace(/\+/g,' ');Params[key]=val;}
return Params;}
function tb_getPageSize()
{var de=document.documentElement;var w=window.innerWidth||self.innerWidth||(de&&de.clientWidth)||document.body.clientWidth;var h=window.innerHeight||self.innerHeight||(de&&de.clientHeight)||document.body.clientHeight;arrayPageSize=[w,h];return arrayPageSize;}
 /* module/friend/static/jscript/search.js */$Core.searchFriendsInput={aParams:{},iCnt:0,aFoundUsers:{},aLiveUsers:{},sId:'',bNoSearch:false,aFoundUser:{},sHtml:'',init:function($aParams)
{this.aParams=$aParams;if(!isset(this.aParams['search_input_id']))
{this.aParams['search_input_id']='search_input_name_'+Math.round(Math.random()*10000);}
if(this._get('no_build')){this.sId=$aParams['id'].replace('#','');}
else{this.sId=$aParams['id'].replace('#','').replace('.','')+'__tmp__';}
this.build();},build:function()
{var $sHtml='';if(!this._get('no_build')){$sHtml+='<div style="width:'+this._get('width')+'; position:relative;" class="js_friend_search_form" id="'+this.sId+'">';$sHtml+='<input type="text" id="'+this._get('search_input_id')+'" name="null" value="'+this._get('default_value')+'" autocomplete="off" onfocus="$Core.searchFriendsInput.buildFriends(this);" onkeyup="$Core.searchFriendsInput.getFriends(this);" style="width:100%;" class="js_temp_friend_search_input" />';$sHtml+='<div class="js_temp_friend_search_form" style="display:none;"></div>';$sHtml+='</div>';$(this._get('id')).html($sHtml);}
else{$sHtml+='<div class="js_temp_friend_search_form js_temp_friend_search_form_main" style="display:none;"></div>';$('#'+this.sId).find('form:first').append($sHtml);}
$('#'+this.sId).find('.js_temp_friend_search_input').keypress(function(e)
{switch(e.keyCode)
{case 9:case 40:case 38:var $iNextCnt=0;$('.js_friend_search_link').each(function()
{$iNextCnt++;if($(this).hasClass('js_temp_friend_search_form_holder_focus'))
{$(this).removeClass('js_temp_friend_search_form_holder_focus');return false;}});if(!$iNextCnt)
{return false;}
$Core.searchFriendsInput.bNoSearch=true;var $iNewCnt=0;var $iActualFocus=0;$('.js_friend_search_link').each(function()
{$iNewCnt++;if((e.keyCode==38?($iNextCnt-1)==$iNewCnt:($iNextCnt+1)==$iNewCnt))
{$iActualFocus++;$(this).addClass('js_temp_friend_search_form_holder_focus');return false;}});if(!$iActualFocus)
{$('.js_friend_search_link').each(function()
{$(this).addClass('js_temp_friend_search_form_holder_focus');return false;});}
return false;break;case 13:$Core.searchFriendsInput.bNoSearch=true;$('.js_friend_search_link').each(function()
{if($(this).hasClass('js_temp_friend_search_form_holder_focus'))
{$Core.searchFriendsInput.processClick(this,$(this).attr('rel'));}});break;default:break;}});},buildFriends:function($oObj)
{$($oObj).val('');if(empty($Cache.friends)&&!isset(this.aParams['is_mail']))
{$.ajaxCall('friend.buildCache',(this._get('allow_custom')?'&allow_custom=1':''),'GET');}},getFriends:function($oObj)
{if(empty($oObj.value))
{this.closeSearch($oObj);return;}
if(this.bNoSearch)
{this.bNoSearch=false;return;}
if(isset(this.aParams['is_mail'])&&this.aParams['is_mail']==true)
{$.ajaxCall('friend.getLiveSearch','parent_id='+$($oObj).attr('id')+'&search_for='+$($oObj).val()+'&width='+this._get('width')+'&total_search='+$Core.searchFriendsInput._get('max_search'),'GET');return;}
var $iFound=0;var $sHtml='';$($Cache.friends).each(function($sKey,$aUser)
{var $mRegSearch=new RegExp($oObj.value,'i');if($aUser['full_name'].match($mRegSearch))
{if(isset($Core.searchFriendsInput.aLiveUsers[$aUser['user_id']]))
{return;}
$iFound++;$Core.searchFriendsInput.storeUser($aUser['user_id'],$aUser);$sHtml+='<li><a rel="'+$aUser['user_id']+'" class="js_friend_search_link '+(($iFound===1&&!$Core.searchFriendsInput._get('global_search'))?'js_temp_friend_search_form_holder_focus':'')+'" href="#" onclick="return $Core.searchFriendsInput.processClick(this, \''+$aUser['user_id']+'\');"><img src="'+$aUser['user_image']+'" alt="" style="width:25px; height:25px;" />'+$aUser['full_name']+'<div class="clear"></div></a></li>';if($iFound>$Core.searchFriendsInput._get('max_search'))
{return false;}}});if($iFound)
{if(this._get('global_search')){$sHtml+='<li><a href="#" class="holder_notify_drop_link" onclick="$(this).parents(\'form:first\').submit(); return false;">'+oTranslations['friend.show_more_results_for_search_term'].replace('{search_term}',htmlspecialchars($oObj.value))+'</a></li>';}
$($oObj).parent().find('.js_temp_friend_search_form').html('<div class="js_temp_friend_search_form_holder" style="width:'+this._get('width')+';"><ul>'+$sHtml+'</ul></div>').show();}
else
{$($oObj).parent().find('.js_temp_friend_search_form').html('').hide();}},storeUser:function($iUserId,$aData)
{this.aFoundUsers[$iUserId]=$aData;},removeSelected:function($oObj,$iUserId)
{if(isset(this.aLiveUsers[$iUserId]))
{delete this.aLiveUsers[$iUserId];}
$($oObj).parents('li:first').remove();},processClick:function($oObj,$iUserId)
{if(!isset(this.aFoundUsers[$iUserId]))
{return false;}
if(isset(this.aLiveUsers[$iUserId]))
{return false;}
this.aLiveUsers[$iUserId]=true;$Behavior.reloadLiveUsers=function(){$Core.searchFriendsInput.aLiveUsers={};$Behavior.reloadLiveUsers=function(){}}
this.bNoSearch=false;var $aUser=this.aFoundUser=this.aFoundUsers[$iUserId];var $oPlacement=$(this._get('placement'));$($oObj).parents('.js_friend_search_form:first').find('.js_temp_friend_search_form').html('').hide();var $sHtml='';$sHtml+='<li>';$sHtml+='<a href="#" class="friend_search_remove" title="Remove" onclick="$Core.searchFriendsInput.removeSelected(this, '+$iUserId+');  return false;">Remove</a>';if(!this._get('inline_bubble'))
{$sHtml+='<div class="friend_search_image"><img src="'+$aUser['user_image']+'" alt="" style="width:25px; height:25px;" /></div>';}
$sHtml+='<div class="friend_search_name">'+$aUser['full_name']+'</div>';if(!this._get('inline_bubble'))
{$sHtml+='<div class="clear"></div>';}
$sHtml+='<div><input type="hidden" name="'+this._get('input_name')+'[]" value="'+$aUser['user_id']+'" /></div>';$sHtml+='</li>';this.sHtml=$sHtml;if(empty($oPlacement.html()))
{$oPlacement.html('<div class="js_custom_search_friend_holder"><ul'+(this._get('inline_bubble')?' class="inline_bubble"':'')+'></ul>'+(this._get('inline_bubble')?'<div class="clear"></div>':'')+'</div>');}
if(this._get('onBeforePrepend'))
{this._get('onBeforePrepend')(this._get('onBeforePrepend'));}
$oPlacement.find('ul').prepend(this.sHtml);if(this._get('onclick'))
{this._get('onclick')(this._get('onclick'));}
if(this._get('global_search')){window.location.href=$aUser['user_profile'];$($oObj).parents('.js_temp_friend_search_form:first').hide();}
this.aFoundUsers={};if(this._get('inline_bubble')){$('#'+this._get('search_input_id')+'').val('').focus();}
return false;},closeSearch:function($oObj)
{$($oObj).parent().find('.js_temp_friend_search_form').html('').hide();},_get:function($sParam)
{return(isset(this.aParams[$sParam])?this.aParams[$sParam]:'');}}
 /* static\jscript\quick_edit.js */$Behavior.quickEdit=function()
{$('.sJsQuickEdit').dblclick(function()
{$(this).createQuickEditForm($(this).find('.quickEdit').get(0).href);return false;});$('.quickEdit').click(function()
{$(this).createQuickEditForm($(this).get(0).href);return false;});};$.fn.createQuickEditForm=function(sUrl)
{$aParams=$.getParams(sUrl);eval('var sTempVar = \'js_cache_quick_edit'+$aParams['id']+'\';');$(this).blur();if(document.getElementById(sTempVar))
{return;}
var sParams='';for(sVar in $aParams)
{sParams+='&'+sVar+'='+$aParams[sVar]+'';}
sParams=sParams.substr(1,sParams.length);var sProcessing='<span style="margin-left:4px; margin-right:4px; display:none; font-size:9pt; font-weight:normal;" id="js_quick_edit_processing'+$aParams['id']+'">'+getPhrase('core.processing')+'...</span>';switch($aParams['type'])
{case'input':$('body').append('<div id="js_cache_quick_edit'+$aParams['id']+'" style="display:none;">'+$('#'+$aParams['id']).html(sHtml)+'</div>');var sValue=$('#'+$aParams['content']).html();sValue=sValue.replace(/"/g,"&quot;").replace(/'/g,"&#039;");var sHtml;sHtml=' <input style="vertical-align:middle;" size="20" type="text" name="quick_edit_input" value="'+sValue+'" id="js_quick_edit'+$aParams['id']+'" /> ';sHtml+=' <input style="vertical-align:middle;" type="button" value="'+getPhrase('core.save')+'" class="button" onclick="$(\'#js_quick_edit_processing'+$aParams['id']+'\').show(); $(\'#js_cache_quick_edit'+$aParams['id']+'\').remove(); $(\'#js_quick_edit'+$aParams['id']+'\').ajaxCall(\''+$aParams['call']+'\', \''+sParams+'\');" /> ';sHtml+=' <input style="vertical-align:middle;" type="button" value="'+getPhrase('core.cancel')+'" class="button button_off" onclick="$(\'#'+$aParams['id']+'\').html($(\'#js_cache_quick_edit'+$aParams['id']+'\').html()); $(\'#js_cache_quick_edit'+$aParams['id']+'\').remove(); $Core.loadInit();" /> ';sHtml+=sProcessing;$('#'+$aParams['id']).html(sHtml);$('#js_quick_edit'+$aParams['id']).focus();break;case'text':$('#'+$aParams['id']).hide();$('body').append('<div id="js_cache_quick_edit'+$aParams['id']+'" style="display:none;">'+$('#'+$aParams['id']).html(sHtml)+'</div>');var sHtml;$.ajaxCall($aParams['data'],''+sParams+'');sHtml='<div id="js_quick_edit_id'+$aParams['id']+'">'+$.ajaxProcess(getPhrase('core.loading_text_editor'))+'</div>';sHtml+='<div class="t_right" style="padding:4px 0 4px 0;">';sHtml+=sProcessing;sHtml+=' <input type="button" value="'+getPhrase('core.save')+'" class="button" onclick="if (function_exists(\'js_quick_edit_callback\')){js_quick_edit_callback();} $(\'#js_quick_edit_processing'+$aParams['id']+'\').show(); $(\'#js_cache_quick_edit'+$aParams['id']+'\').remove(); $(\'#js_quick_edit'+$aParams['id']+'\').ajaxCall(\''+$aParams['call']+'\', \''+sParams+'\');" /> ';sHtml+=' <input type="button" value="'+getPhrase('core.cancel')+'" class="button button_off" onclick="$(\'#'+$aParams['id']+'\').html($(\'#js_cache_quick_edit'+$aParams['id']+'\').html()); $(\'#js_cache_quick_edit'+$aParams['id']+'\').remove()" /> ';if(isset($aParams['main_url']))
{if(function_exists('quickSubmit'))
{sHtml+=' <input type="button" onclick="quickSubmit(\''+$aParams['id']+'\', \''+$aParams['main_url']+'\')" value="'+getPhrase('core.go_advanced')+'" class="button button_off" /> ';}
else
{sHtml+=' <input type="button" value="'+getPhrase('core.go_advanced')+'" class="button button_off" onclick="window.location.href=\''+$aParams['main_url']+'\';" /> ';}}
sHtml+='</div>';$('#'+$aParams['id']).html(sHtml);$('#'+$aParams['id']).show();$('#js_quick_edit'+$aParams['id']).focus();break;}};
 /* module/photo/static/jscript/view.js */$Behavior.photoView=function()
{$('#js_update_photo_form').submit(function()
{$('#js_updating_photo').html($.ajaxProcess(oTranslations['photo.updating_photo']));$(this).ajaxCall('photo.updatePhoto');$('#js_photo_edit_form').hide();$('#js_photo_outer_content').show();return false;});$('#js_photo_cancel_edit').click(function()
{$('#js_photo_edit_form').hide();$('#js_photo_outer_content').show();return false;});}
var bLoadedKeyBrowser=false;var bByPassLoadedKeyBrowser=false;$Behavior.eventKeyboard=function()
{$('.comment_mini_end textarea').focus(function(){bByPassLoadedKeyBrowser=true;});$('.comment_mini_end textarea').blur(function(){bByPassLoadedKeyBrowser=false;});if(bLoadedKeyBrowser==true)
{return;}
$(document).keydown(function(e){if(!bByPassLoadedKeyBrowser){if(e.keyCode==37)
{$('#photo_view_theater_mode .previous a:first').click();}
else if(e.keyCode==39)
{$('#photo_view_theater_mode .next a:first').click();}}});bLoadedKeyBrowser=true;}
 /* module/photo/static/jscript/photo.js */function plugin_completeProgress()
{if($('#js_photo_upload_failed').hasClass('js_photo_upload_failed'))
{alert(oTranslations['photo.none_of_your_files_were_uploaded_please_make_sure_you_upload_either_a_jpg_gif_or_png_file']);return false;}
if($('#js_photo_action').val()=='upload')
{$('#js_upload_form_outer').show();}
iCnt=0;sHtml='';$('.js_uploaded_image').each(function()
{iCnt++;if(iCnt==1)
{$(this).addClass('row_first');}
else
{$(this).removeClass('row_first');}
sHtml+='<div id="js_uploaded_photo_'+this.id.replace('js_photo_','')+'"><input type="hidden" name="val[photo_id][]" value="'+this.id.replace('js_photo_','')+'" /></div>';});$('#js_post_form_content').html(sHtml);switch($('#js_photo_action').val())
{case'process':$('#js_post_form').submit();break;default:iNewInputBars=0;$('.js_uploader_files').remove();sInput='';if(typeof oProgressBar!="undefined")
{for(i=1;i<=oProgressBar['total'];i++)
{sInput+='<div class="js_uploader_files"><input type="file" name="'+oProgressBar['file_id']+'" size="30" class="js_uploader_files_input" disabled="disabled" onchange="addMoreToProgressBar();" /></div>'+"\n";}}
$('#js_uploader_files_outer').append(sInput);break;}}
function plugin_startProgress(sProgressKey)
{$('#js_upload_form_outer').hide();}
function deleteNewPhoto(iId)
{if(confirm(getPhrase('core.are_you_sure')))
{$('#js_photo_'+iId).remove();$('#js_uploaded_photo_'+iId).remove();iCnt=0;$('.js_uploaded_image').each(function()
{iCnt++;});if(!iCnt)
{$('#js_uploaded_images').hide();}
$.ajaxCall('photo.deleteNewPhoto','id='+iId);return false;}
return false;}
function plugin_addFriendToSelectList()
{$('#js_allow_list_input').show();}
function plugin_cancelFriendSelection()
{$('#js_allow_list_input').hide();}
$Behavior.photoCategoryDropDownBuild=function()
{if($('.js_photo_active_items').length>0)
{$('.js_photo_active_items').each(function()
{var aParts=explode(',',$(this).html());for(i in aParts)
{if(empty(aParts[i]))
{continue;}
$(this).parents('.js_category_list_holder:first').find('.js_photo_category_'+aParts[i]+':first').attr('selected',true);}});}}
$Behavior.photoCategoryDropDown=function()
{$('.js_photo_category').click(function()
{iId=this.id.replace('js_photo_category_','');iItemId=$(this).parents('div:first').parent().parent().parent().find('.js_photo_item_id').html();if($(this).hasClass('selected'))
{$(this).removeClass('selected');$('#js_photo_category_id_'+(iItemId===null?'':iItemId)+iId).remove();}
else
{$(this).addClass('selected');$(this).prepend('<div id="js_photo_category_id_'+(iItemId===null?'':iItemId)+iId+'"><input type="hidden" name="val'+(iItemId===null?'':'['+iItemId+']')+'[category_id][]" value="'+iId+'" /></div>');}
return false;});$('.js_photo_category_done').click(function()
{$('.select_clone').hide();return false;});$('.select_clone_select').click(function()
{$(this).next('.select_clone').toggle();return false;});$(document).click(function()
{$('.select_clone').hide();});$('.hover_action').each(function()
{$(this).parents('.js_outer_photo_div:first').css('width',this.width+'px');});$('#js_photo_album_select').change(function()
{if(empty(this.value))
{$('#js_photo_view_this_album').remove();}
else
{if($('#js_photo_view_this_album').length>0)
{$('#js_photo_view_this_album').show();}
else
{$('#js_photo_action').append('<option value="view_album" id="js_photo_view_this_album">'+oTranslations['photo.view_this_album']+'</option>');}}});}
function uploadComplete()
{if(typeof swfu!='undefined')
{var oStats=swfu.getStats();if(oStats.in_progress>0||oStats.files_queued>0)
{}
else
{var sPhotos="";for(var i in window.aImagesUrl)
{sPhotos+='photos[]='+window.aImagesUrl[i]+'&';}
sPhotos=sPhotos.substr(0,sPhotos.length-1);window.parent.$.ajaxCall('photo.process','js_disable_ajax_restart=true&'+sPhotos+'&action='+$('#js_photo_action').val());}}}
if(typeof $Core.Photo=='undefined')$Core.Photo={};$Core.Photo.setCoverPhoto=function(iPhotoId,iItemId)
{$.ajaxCall('pages.setCoverPhoto','photo_id='+iPhotoId+'&page_id='+iItemId);}
 /* static\jscript\switch_legend.js */$Behavior.switchLegends=function()
{$('.legend').click(function()
{var sNextDisplay=$(this).next().get(0).style.display;var sId=$(this).get(0).id;if(sNextDisplay==''||sNextDisplay=='block')
{$($(this).next()).hide('fast');$(this).addClass('legendClosed');if(!getCookie(sId))
{setCookie(sId,true,365);}}
else
{$($(this).next()).show('fast');$(this).removeClass('legendClosed');deleteCookie(sId);}});$('.legend').each(function()
{if(getCookie($(this).get(0).id))
{$(this).addClass('legendClosed');$(this).next().hide();}});};
 /* static\jscript\switch_menu.js */$Behavior.switchLabelMenu=function()
{$('.label_flow_menu a').click(function()
{$(this).blur();if($(this).parent().hasClass('label_flow_menu_active'))
{return false;}
var aArgs=$(this).get(0).href.split('#');var aArgsFinal=aArgs[1].split('?');$(this).switchMenu('label_flow_menu_active',aArgsFinal[0],aArgsFinal[1]);return false;});};$.fn.switchMenu=function(sMenu,sAjaxCall,sParams)
{$(this).parent().parent().find('li').removeClass(sMenu);$(this).parent().addClass(sMenu);$(this).parents().next('.labelFlowContent').html('<div class="label_flow" style="height:'+($(this).parents().next('.labelFlowContent').height())+'px; text-align:center;"><img src="'+oJsImages['ajax_large']+'" alt="" style="vertical-align:middle;" /></div>');if(sAjaxCall)
{this.ajaxCall(sAjaxCall,sParams,null,'GET');}
return this;};
 /* module/feed/static/jscript/feed.js */var $sFormAjaxRequest=null;var $bButtonSubmitActive=true;var $ActivityFeedCompleted={};var $sCurrentSectionDefaultPhrase=null;var $sCssHeight='40px';var $sCustomPhrase=null;var $sCurrentForm=null;var $sStatusUpdateValue=null;var $iReloadIteration=0;var $oLastFormSubmit=null;var bCheckUrlCheck=false;var bCheckUrlForceAdd=false;$Core.isInView=function(elem)
{if(!$Core.exists(elem)){return false;}
var docViewTop=$(window).scrollTop();var docViewBottom=docViewTop+$(window).height();var elemTop=$(elem).offset().top;var elemBottom=elemTop+$(elem).height();return((docViewTop<elemTop)&&(docViewBottom>elemBottom));}
$Core.resetActivityFeedForm=function()
{$('.activity_feed_form_attach li a').removeClass('active');$('.activity_feed_form_attach li a:first').addClass('active');$('.global_attachment_holder_section').hide();$('#global_attachment_status').show();$('.global_attachment_holder_section textarea').val($('#global_attachment_status_value').html()).css({height:$sCssHeight});$('.activity_feed_form_button_status_info').hide();$('.activity_feed_form_button_status_info textarea').val('');$Core.resetActivityFeedErrorMessage();$sFormAjaxRequest=$('.activity_feed_form_attach li a.active').find('.activity_feed_link_form_ajax').html();$Core.activityFeedProcess(false);$('.js_share_connection').val('0');$('.feed_share_on_item a').removeClass('active');$.each($ActivityFeedCompleted,function()
{this(this);});$('#js_add_location, #js_location_input, #js_location_feedback').hide();$('.activity_feed_form_button_position').show();$('#hdn_location_name, #val_location_name ,#val_location_latlng').val('');$('#btn_display_check_in').removeClass('is_active');}
$Core.resetActivityFeedErrorMessage=function()
{$('#activity_feed_upload_error').hide();$('#activity_feed_upload_error_message').html('');}
$Core.resetActivityFeedError=function(sMsg)
{$('.activity_feed_form_share_process').hide();$('.activity_feed_form_button .button').removeClass('button_not_active');$bButtonSubmitActive=true;$('#activity_feed_upload_error').show();$('#activity_feed_upload_error_message').html(sMsg);}
$Core.activityFeedProcess=function($bShow)
{if($bShow)
{$bButtonSubmitActive=false;$('.activity_feed_form_share_process').show();$('.activity_feed_form_button .button').addClass('button_not_active');}
else
{$bButtonSubmitActive=true;$('.activity_feed_form_share_process').hide();$('.activity_feed_form_button .button').removeClass('button_not_active');$('.egift_wrapper').hide();}}
$Core.addNewPollOption=function()
{$('.js_poll_feed_answer').append('<li><input type="text" name="val[answer][][answer]" value="" size="30" class="js_feed_poll_answer v_middle" /></li>');return false;}
$Core.forceLoadOnFeed=function()
{if($iReloadIteration>=2){return;}
if(!$Core.exists('#js_feed_pass_info')){return;}
$iReloadIteration++;$('#feed_view_more_loader').show();$('.global_view_more').hide();setTimeout("$.ajaxCall('feed.viewMore', $('#js_feed_pass_info').html().replace(/&amp;/g, '&') + '&iteration="+$iReloadIteration+"', 'GET');",1000);}
$Core.handlePasteInFeed=function(oObj)
{if((substr($(oObj).val(),0,7)=='http://'||substr($(oObj).val(),0,8)=='https://'||(substr($(oObj).val(),0,4)=='www.')))
{bCheckUrlCheck=true;$('.activity_feed_form_share_process').show();$(oObj).parent().append('<div id="js_preview_link_attachment_custom_form_sub" class="js_preview_link_attachment_custom_form" style="margin-top:5px;"></div>');$Core.ajax('link.preview',{type:'POST',params:{'no_page_update':'1',value:$(oObj).val()},success:function($sOutput){$('.activity_feed_form_share_process').hide();if(substr($sOutput,0,1)=='{'){}
else{$('#js_global_attach_value').val($(oObj).val());bCheckUrlForceAdd=true;$('#js_preview_link_attachment_custom_form_sub').html($sOutput);}}});}}
$Behavior.activityFeedProcess=function(){if(!$Core.exists('#js_feed_content')){$iReloadIteration=0;return;}
if($Core.exists('.global_view_more')){if($Core.isInView('.global_view_more')){$Core.forceLoadOnFeed();}
$(window).scroll(function(){if($Core.isInView('.global_view_more')){$Core.forceLoadOnFeed();}});}
$('.like_count_link').each(function(){var sHtml=$(this).parent().find('.like_count_link_holder:first').html();});$sFormAjaxRequest=$('.activity_feed_form_attach li a.active').find('.activity_feed_link_form_ajax').html();if(typeof Plugin_sFormAjaxRequest=='function')
{Plugin_sFormAjaxRequest();}
if($Core.exists('.profile_timeline_header')){$(window).scroll(function(){if(isScrolledIntoView('.profile_timeline_header')){$('.timeline_main_menu').removeClass('timeline_main_menu_fixed');$('#timeline_dates').removeClass('timeline_dates_fixed');}
else{if(!$('.timeline_main_menu').hasClass('timeline_main_menu_fixed')){$('.timeline_main_menu').addClass('timeline_main_menu_fixed');if($('#content').height()>600){$('#timeline_dates').addClass('timeline_dates_fixed');}}}});}
$('#global_attachment_status textarea').keyup(function(){$Core.handlePasteInFeed($(this));}).bind('paste',function()
{var that=this;setTimeout(function(){$Core.handlePasteInFeed(that);},0);});$('#global_attachment_status textarea').keydown(function(){$Core.resizeTextarea($(this));});$('.activity_feed_form_button_status_info textarea').keydown(function(){$Core.resizeTextarea($(this));});$('#global_attachment_status textarea').focus(function()
{if($(this).val()==$('#global_attachment_status_value').html())
{$(this).val('');$(this).css({height:'50px'});$('.activity_feed_form_button').show();$(this).addClass('focus');$('.activity_feed_form_button_status_info textarea').addClass('focus');}});$('.activity_feed_form_button_status_info textarea').focus(function()
{var $sDefaultValue=$(this).val();var $bIsDefault=true;$('.activity_feed_extra_info').each(function()
{if($(this).html()==$sDefaultValue)
{$bIsDefault=false;return false;}});if(($('#global_attachment_status textarea').val()==$('#global_attachment_status_value').html()&&empty($sDefaultValue))||!$bIsDefault)
{$(this).val('');$(this).css({height:'50px'});$(this).addClass('focus');$('#global_attachment_status textarea').addClass('focus');}});$('#js_activity_feed_form').submit(function()
{if($sCurrentForm=='global_attachment_status'){var oStatusUpdateTextareaFilled=$('#global_attachment_status textarea');if($sStatusUpdateValue==oStatusUpdateTextareaFilled.val()){oStatusUpdateTextareaFilled.val('');}}
else{var oCustomTextareaFilled=$('.activity_feed_form_button_status_info textarea');if($sCustomPhrase==oCustomTextareaFilled.val()){oCustomTextareaFilled.val('');}}
if($bButtonSubmitActive===false)
{return false;}
$Core.activityFeedProcess(true);if($sFormAjaxRequest===null)
{return true;}
$('.js_no_feed_to_show').remove();if(bCheckUrlForceAdd){$('.activity_feed_form_button_status_info textarea').val($('#global_attachment_status textarea').val());$sFormAjaxRequest='link.addViaStatusUpdate';}
$(this).ajaxCall($sFormAjaxRequest);if(bCheckUrlForceAdd){$('#js_preview_link_attachment_custom_form_sub').remove();}
return false;});$('.activity_feed_form_attach li a').click(function()
{$sCurrentForm=$(this).attr('rel');if($sCurrentForm=='view_more_link'){$('.view_more_drop').toggle();return false;}
else{$('.view_more_drop').hide();}
if($sCurrentForm=='global_attachment_status'){$('#btn_display_check_in').show();}else{$('#btn_display_check_in').hide();$('#hdn_location_name, #val_location_name ,#val_location_latlng').val('');$('#btn_display_check_in').removeClass('is_active');}
$('#js_preview_link_attachment_custom_form_sub').remove();$('#activity_feed_upload_error').hide();$('.global_attachment_holder_section').hide();$('.activity_feed_form_attach li a').removeClass('active');$(this).addClass('active');if($(this).find('.activity_feed_link_form').length>0)
{$('#js_activity_feed_form').attr('action',$(this).find('.activity_feed_link_form').html()).attr('target','js_activity_feed_iframe_loader');$sFormAjaxRequest=null;if(empty($('.activity_feed_form_iframe').html()))
{$('.activity_feed_form_iframe').html('<iframe id="js_activity_feed_iframe_loader" name="js_activity_feed_iframe_loader" height="200" width="500" frameborder="1" style="display:none;"></iframe>');}}
else
{$sFormAjaxRequest=$(this).find('.activity_feed_link_form_ajax').html();}
$('#'+$(this).attr('rel')).show();$('.activity_feed_form_holder_attach').show();$('.activity_feed_form_button').show();var $oStatusUpdateTextarea=$('#global_attachment_status textarea');var $sStatusUpdateTextarea=$oStatusUpdateTextarea.val();$sStatusUpdateValue=$('#global_attachment_status_value').html();var $oCustomTextarea=$('.activity_feed_form_button_status_info textarea');var $sCustomTextarea=$oCustomTextarea.val();$sCustomPhrase=$(this).find('.activity_feed_extra_info').html();var $bHasDefaultValue=false;$('.activity_feed_extra_info').each(function()
{if($(this).html()==$sCustomTextarea)
{$bHasDefaultValue=true;return false;}});if($(this).attr('rel')!='global_attachment_status')
{$('.activity_feed_form_button_status_info').show();if((empty($sCustomTextarea)&&($sStatusUpdateTextarea==$sStatusUpdateValue||empty($sStatusUpdateTextarea)))||($sStatusUpdateTextarea==$sStatusUpdateValue&&$bHasDefaultValue)||(!$bButtonSubmitActive&&$bHasDefaultValue))
{$oCustomTextarea.val($sCustomPhrase).css({height:$sCssHeight});}
else if($sStatusUpdateTextarea!=$sStatusUpdateValue&&$bButtonSubmitActive&&!empty($sStatusUpdateTextarea))
{$oCustomTextarea.val($sStatusUpdateTextarea);}
$('.activity_feed_form_button .button').addClass('button_not_active');$bButtonSubmitActive=false;}
else
{$('.activity_feed_form_button_status_info').hide();$('.activity_feed_form_button .button').removeClass('button_not_active');if(!$bHasDefaultValue&&!empty($sCustomTextarea))
{$oStatusUpdateTextarea.val($sCustomTextarea);}
else if($bHasDefaultValue&&empty($sStatusUpdateTextarea))
{$oStatusUpdateTextarea.val($sStatusUpdateValue).css({height:$sCssHeight});}
$bButtonSubmitActive=true;}
if($(this).hasClass('no_text_input'))
{$('.activity_feed_form_button_status_info').hide();}
$('.activity_feed_form_button .button').show();$('#js_piccup_upload').hide();return false;});}
$Behavior.activityFeedLoader=function()
{if(empty($('.view_more_drop').html())){$('.timeline_view_more').parent().hide();}
$('.js_feed_entry_add_comment').click(function()
{$('.js_comment_feed_textarea').each(function()
{if($(this).val()==$('.js_comment_feed_value').html())
{$(this).removeClass('js_comment_feed_textarea_focus');$(this).val($('.js_comment_feed_value').html());}
$(this).parents('.comment_mini').find('.feed_comment_buttons_wrap').hide();});$(this).parents('.js_parent_feed_entry:first').find('.comment_mini_content_holder').show();$(this).parents('.js_parent_feed_entry:first').find('.feed_comment_buttons_wrap').show();if($(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea').val()==$('.js_comment_feed_value').html())
{$(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea').val('');}
$(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea').focus().addClass('js_comment_feed_textarea_focus');$(this).parents('.js_parent_feed_entry:first').find('.comment_mini_textarea_holder').addClass('comment_mini_content');$(this).parents('.js_parent_feed_entry:first').find('.js_feed_comment_form').find('.comment_mini_image').show();var iTotalComments=0;$(this).parents('.js_parent_feed_entry:first').find('.js_mini_feed_comment').each(function()
{iTotalComments++;});if(iTotalComments>2)
{$.scrollTo($(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea_browse:first'),340);}
return false;});$('.js_comment_feed_textarea').focus(function()
{$Core.commentFeedTextareaClick(this);});$('#js_captcha_load_for_check_submit').submit(function(){if(function_exists(''+Editor.sEditor+'_wysiwyg_feed_comment_form'))
{eval(''+Editor.sEditor+'_wysiwyg_feed_comment_form(this);');}
$oLastFormSubmit.parent().parent().find('.js_feed_comment_process_form:first').show();$(this).ajaxCall('comment.add',$oLastFormSubmit.getForm());return false;});$('.js_comment_feed_form').submit(function()
{if($Core.exists('#js_captcha_load_for_check')){$('#js_captcha_load_for_check').css({top:getPageScroll()[1]+(getPageHeight()/5),left:'50%','margin-left':'-'+(($('#js_captcha_load_for_check').width()/2)+12)+'px',display:'block'});$oLastFormSubmit=$(this);return false;}
if(function_exists(''+Editor.sEditor+'_wysiwyg_feed_comment_form'))
{eval(''+Editor.sEditor+'_wysiwyg_feed_comment_form(this);');}
$(this).parent().parent().find('.js_feed_comment_process_form:first').show();$(this).ajaxCall('comment.add');$(this).find('.error_message').remove();return false;});$('.js_comment_feed_new_reply').click(function(){var oParent=$(this).parents('.js_mini_feed_comment:first').find('.js_comment_form_holder:first');if((Editor.sEditor=='tiny_mce'||Editor.sEditor=='tinymce')&&isset(tinyMCE)&&isset(tinyMCE.activeEditor)){$('.js_comment_feed_form').find('.js_feed_comment_parent_id:first').val($(this).attr('rel'));tinyMCE.activeEditor.focus();if(typeof($.scrollTo)=='function'){$.scrollTo('.js_comment_feed_form',800);}
return false;}
var sCommentForm=$(this).parents('.js_feed_comment_border:first').find('.js_feed_comment_form:first').html();oParent.html(sCommentForm);oParent.find('.js_feed_comment_parent_id:first').val($(this).attr('rel'));oParent.find('.js_comment_feed_textarea:first').focus();$Core.commentFeedTextareaClick(oParent.find('.js_comment_feed_textarea:first'));$('.js_feed_add_comment_button .error_message').remove();oParent.find('.button_set_off:first').show().removeClass('button_set_off');$Core.loadInit();return false;});$('.comment_mini').hover(function(){$('.feed_comment_delete_link').hide();$(this).find('.feed_comment_delete_link:first').show();},function(){$('.feed_comment_delete_link').hide();});}
$Core.commentFeedTextareaClick=function($oObj)
{$($oObj).keydown(function()
{if($(this).hasClass('no_resize_textarea')){return;}
$Core.resizeTextarea($(this));});if($($oObj).val()==$('.js_comment_feed_value').html())
{$($oObj).val('');}
$($oObj).addClass('js_comment_feed_textarea_focus').addClass('is_focus');$($oObj).parents('.comment_mini').find('.feed_comment_buttons_wrap:first').show();$($oObj).parent().parent().find('.comment_mini_textarea_holder:first').addClass('comment_mini_content');$($oObj).parent().parent().find('.comment_mini_image:first').show();}
$Behavior.activityFeedAttachLink=function()
{$('#js_global_attach_link').click(function()
{$Core.activityFeedProcess(true);$Core.ajax('link.preview',{params:{'no_page_update':'1',value:$('#js_global_attach_value').val()},type:'POST',success:function($sOutput)
{$('#js_global_attachment_link_cancel').show();if(substr($sOutput,0,1)=='{'){var $oOutput=$.parseJSON($sOutput);$Core.resetActivityFeedError($oOutput['error']);$bButtonSubmitActive=false;$('.activity_feed_form_button .button').addClass('button_not_active');}
else{$Core.activityFeedProcess(false);$('#js_preview_link_attachment').html($sOutput);$('#global_attachment_link_holder').hide();}}});});}
$ActivityFeedCompleted.link=function()
{$bButtonSubmitActive=true;$('#global_attachment_link_holder').show();$('.activity_feed_form_button .button').removeClass('button_not_active');$('#js_preview_link_attachment').html('');$('#js_global_attach_value').val('http://');}
$ActivityFeedCompleted.photo=function()
{$bButtonSubmitActive=true;$('#global_attachment_photo_file_input').val('');}
var sToReplace='';function attachFunctionTagger(sSelector)
{$(sSelector).data('selector',sSelector).keyup(function(eventObject,sSelector){var sInput=$($(this).data('selector')).val();var iInputLength=sInput.length;var iAtSymbol=sInput.lastIndexOf('@');if(sInput=='@'||empty(sInput)||iAtSymbol<0||iAtSymbol==(iInputLength-1))
{$($(this).data('selector')).siblings('.chooseFriend').hide(function(){$(this).remove();});return;}
var sNameToFind=sInput.substring(iAtSymbol+1,iInputLength);var aFoundFriends=[],sOut='';for(var i in $Cache.friends)
{if($Cache.friends[i]['full_name'].toLowerCase().indexOf(sNameToFind.toLowerCase())>=0)
{var sNewInput=sInput.substr(0,iAtSymbol).replace(/\'/g,'\\\'').replace(/\"/g,'&#34;');sToReplace=sNewInput;aFoundFriends.push({user_id:$Cache.friends[i]['user_id'],full_name:$Cache.friends[i]['full_name'],user_image:$Cache.friends[i]['user_image']});sOut+='<div class="tagFriendChooser" onclick="$(\''+$(this).data('selector')+'\').val(sToReplace + \'\' + (getParam(\'bEnableMicroblogSite\') ? \'@'+$Cache.friends[i]['user_name']+'\' : \'[x='+$Cache.friends[i]['user_id']+']'+$Cache.friends[i]['full_name'].replace(/\&#039;/g,'\\\'')+'[/x]\') + \' \').putCursorAtEnd();$(\''+$(this).data('selector')+'\').siblings(\'.chooseFriend\').remove();"><div class="tagFriendChooserImage"><img style="vertical-align:middle;width:25px; height:25px;" src="'+$Cache.friends[i]['user_image']+'"> </div><span>'+(($Cache.friends[i]['full_name'].length>25)?($Cache.friends[i]['full_name'].substr(0,25)+'...'):$Cache.friends[i]['full_name'])+'</span></div>';sOut=sOut.replace("\n",'').replace("\r",'');}}
$($(this).data('selector')).siblings('.chooseFriend').remove();if(!empty(sOut)){$($(this).data('selector')).after('<div class="chooseFriend" style="width: '+$(this).parent().width()+'px;">'+sOut+'</div>');}}).focus(function(){if(typeof $Cache=='undefined'||typeof $Cache.friends=='undefined')
{$.ajaxCall('friend.buildCache','','GET');}});}
$Behavior.tagger=function()
{var aSelectors=['#js_activity_feed_form > .activity_feed_form_holder > #global_attachment_status > textarea','.js_comment_feed_textarea','.js_comment_feed_textarea_focus'];for(var i in aSelectors)
{if($(aSelectors[i]).length>=1)
{var bChanged=false;$.each($(aSelectors[i]),function(key,value)
{if($(value).attr('id')!=undefined)
{aSelectors.push('#'+$(value).attr('id'));bChanged=true;}});if(bChanged)
{aSelectors.splice(i,1);}}}
for(var i in aSelectors)
{var sSelector=aSelectors[i];if(sSelector=='#pageFeedTextarea'||sSelector=='#eventFeedTextarea'||sSelector=='#profileFeedTextarea')
{continue;}
if($(sSelector).length>1)
{$.each($(sSelector),function(key,value)
{if($(value).attr('id')!=undefined)
{attachFunctionTagger('#'+$(value).attr('id'));}});continue;}
attachFunctionTagger(sSelector);}};
 /* module/photo/static/jscript/index.js */$Behavior.showSpecialInfo=function()
{$('.photo_hover_info').hide();$('.photo_row').hover(function()
{if($(this).hasClass('photo_hover_info_shown'))
{return;}
$(this).addClass('photo_hover_info_shown');$(this).find('.photo_hover_info').show().css('z-index','199');},function()
{$(this).find('.photo_hover_info').hide().css('z-index','1');$(this).removeClass('photo_hover_info_shown');});if(!isset($Core.Photo))$Core.Photo={};$Core.Photo.inlineAction=function(item_id,counter_holder,action,toggle)
{$('.'+toggle).toggle();$.ajaxCall('like.'+action,'item_type_id=photo&module_name=photo&type_id=photo&item_id='+item_id+'&counterholder='+counter_holder+'&action_type_id=2');};$Core.Photo.albumInlineAction=function(item_id,counter_holder,action,toggle)
{console.log(counter_holder);$('.'+toggle).toggle();$.ajaxCall('like.'+action,'item_type_id=photo&module_name=photo&type_id=photo_album&item_id='+item_id+'&counterholder='+counter_holder+'&action_type_id=2');};};
 /* static\jscript\user_info.js */var $bUserToolTipIsHover=false;var $bUserActualToolTipIsHover=false;var $iUserToolTipWaitTime=900;var $oUserToolTipObject=null;var $sHoveringOn=null;var aHideUsers=new Array();var bUserInfoLogDebug=false;$Core.userInfoLog=function(sLog){if(bUserInfoLogDebug){p(sLog);}};$Core.loadUserToolTip=function($sUserName)
{setTimeout('$Core.showUserToolTip(\''+$sUserName+'\');',$iUserToolTipWaitTime);};$Core.closeUserToolTip=function(sUser)
{if($bUserActualToolTipIsHover===true&&sUser==$sHoveringOn){$Core.userInfoLog('CANCEL CLOSE: '+sUser);return;}
aHideUsers[sUser]=true;$Core.userInfoLog('CLOSE: '+sUser);$('#js_user_tool_tip_cache_'+sUser+'').parent().parent().hide();};$Core.showUserToolTip=function(sUser)
{var $oObj=$oUserToolTipObject;$('.js_user_tool_tip_holder').hide();if($bUserToolTipIsHover===false){$Core.userInfoLog('NO LOAD: '+sUser);return;}
if(isset(aHideUsers[sUser])){$Core.userInfoLog('HIDING: '+sUser);delete aHideUsers[sUser];return;}
if(sUser!=$sHoveringOn){$Core.userInfoLog('NO SHOW: '+sUser);return;}
$Core.userInfoLog('SHOWING: '+sUser);var $oOffset=$($oObj).offset();$('#js_user_tool_tip_cache_'+sUser+'').parent().parent().css('display','block').css('top',($oOffset.top+16)+'px').css('left',$oOffset.left+'px');};$Behavior.userHoverToolTip=function()
{$('#main_content_holder .user_profile_link_span a').mouseover(function()
{$Core.userInfoLog('----------------------------- START -----------------------------');var $sUserName=$(this).parent().attr('id').replace('js_user_name_link_','');if(empty($sUserName))
{return;}
if($('#js_user_tool_tip_cache_'+$sUserName+'').length<=0)
{$('body').append('<div class="js_user_tool_tip_holder"><div class="js_user_tool_tip_body"><div id="js_user_tool_tip_cache_'+$sUserName+'"></div></div></div>');$.ajaxCall('user.tooltip','user_name='+$sUserName,'GET');$('#js_user_tool_tip_cache_'+$sUserName+'').hover(function(){$bUserActualToolTipIsHover=true;$Core.userInfoLog('MOUSE ON');},function(){oCloseObject=$(this).attr('id').replace('js_user_tool_tip_cache_','');setTimeout('$Core.closeUserToolTip(\''+oCloseObject+'\');',$iUserToolTipWaitTime);$bUserActualToolTipIsHover=false;$Core.userInfoLog('MOUSE OFF');});}
if(isset(aHideUsers[$sUserName])){delete aHideUsers[$sUserName];}
$bUserToolTipIsHover=true;$sHoveringOn=$sUserName;$Core.userInfoLog('HOVER: '+$sUserName);$('.js_user_tool_tip_holder').hide();$oUserToolTipObject=this;if($('#js_user_tool_tip_cache_'+$sUserName).html().length<=0){}else{$Core.loadUserToolTip($sUserName);}});$('#main_content_holder .user_profile_link_span a').mouseout(function()
{$bUserToolTipIsHover=false;oCloseObject=$(this).parent().attr('id').replace('js_user_name_link_','');setTimeout('$Core.closeUserToolTip(\''+oCloseObject+'\');',$iUserToolTipWaitTime);});};
 /* module/theme/static/jscript/sort.js */var oCacheImageBlocks={};function makeSortable(sSelector,sConnectWith)
{$(sSelector).sortable({items:'.js_sortable',update:function(element,ui)
{if(function_exists('designOnUpdate'))
{designOnUpdate();}},start:function(element,ui)
{$('.js_temp_place_holder').removeClass('js_temp_place_holder_hide').addClass('js_sort_holder_active');$(ui.item).attr('id','clone_'+$(ui.item).attr('id'));},opacity:0.4,helper:'clone',handle:'.js_sortable_header',placeholder:'js_sort_holder',cursor:'move',axis:(oCore['core.can_move_on_a_y_and_x_axis']?false:'y'),connectWith:sConnectWith});}
$Behavior.sortBlocks=function()
{$('.js_sortable_header').each(function()
{if(!$(this).parent().hasClass('js_sortable'))
{return;}
if($(this).hasClass('is_already_loaded'))
{return;}
$(this).addClass('is_already_loaded');this.style.cursor='move';var sCacheHtml='';$(this).find('.js_edit_header_bar').each(function()
{sCacheHtml+='<div class="js_edit_header_bar'+($(this).hasClass('js_edit_header_hover')?' js_edit_header_hover':'')+'"'+($(this).hasClass('js_edit_header_hover')?' style="display:none;"':'')+'>'+$(this).html()+'</div>';$(this).remove();});$(this).prepend(sCacheHtml+'<div class="js_edit_header_bar js_edit_header_hover" style="display:none;"></div>');});$('.js_sortable_header').dblclick(function()
{if(!$(this).parent().hasClass('js_sortable'))
{return;}
$(this).parent().find('.content:first').toggle();$(this).parent().find('.bottom:first').toggle();});$('.js_sortable_header').mouseover(function()
{$(this).find('.js_edit_header_hover').show();}).mouseout(function()
{$(this).find('.js_edit_header_hover').hide();});if(typeof oCore['profile.user_id']!='undefined'&&oCore['profile.user_id']>0&&oCore['core.can_move_on_a_y_and_x_axis']!=true)
{makeSortable('#left','#left');makeSortable('#right','#right');makeSortable('#content','#content');}
else
{makeSortable('body','');}
$('.js_temp_place_holder').remove();$('.js_content_parent').each(function()
{});}
 /* module/theme/static/jscript/design.js */$Core.design={aParams:{},init:function(aParams)
{this.aParams=aParams;window.bHideIm=true;},updateSorting:function()
{var iCnt=0;var sOrder='';var aCache=new Array();var aClones=new Array();var aFinalCache=new Array();$('.js_sortable').each(function()
{if(!empty(this.id))
{if(this.id.match(/clone_(.*)/))
{aClones[this.id.replace('clone_','')]=$(this).parents('.js_content_parent:first').attr('id');}
aCache[this.id]='content';aCache[this.id]=$(this).parent().attr('id').replace('js_can_move_blocks_','');this.id=this.id.replace('clone_','');}});for(sBlock in aCache)
{iCnt++;if(!isset(aClones[sBlock]))
{aFinalCache[sBlock.replace('clone_','')]=aCache[sBlock];sOrder+='&val[order]['+sBlock.replace('clone_','')+']['+aCache[sBlock]+']='+iCnt+'';}}
for(sParam in this.aParams)
{sOrder+='&val[param]['+sParam+']='+this.aParams[sParam];}
if(function_exists('designOnOrder'))
{designOnOrder(aFinalCache);}
$.ajaxCall('theme.updateOrder',sOrder);}}
function on_change_color(aParam,sHex)
{var iIndexOfMatch=aParam['name'].indexOf('%20');while(iIndexOfMatch!=-1)
{aParam['name']=aParam['name'].replace('%20',' ');iIndexOfMatch=aParam['name'].indexOf('%20');}
if(!empty(sHex)&&!oValidateCss.isHex('#'+sHex))
{p(sHex+' is not a valid hex.');return false;}
switch(aParam['attr'])
{case'font-color':aParam['attr']='color';break;default:break;}
if(sHex=='#transparent')
{sHex='transparent';}
if(function_exists('theme_on_change_color'))
{theme_on_change_color(aParam,sHex);}
p(aParam['name']+' -> '+aParam['attr']+' -> '+sHex);if(aParam['name']=='a')
{$('body').append('<style type="text/css">'+aParam['name']+'{'+aParam['attr']+':#'+sHex+';}</style>');}
else if(aParam['name']=='a:hover')
{$('body').append('<style type="text/css">'+aParam['name']+'{'+aParam['attr']+':#'+sHex+';}</style>');}
else if(aParam['name'].search(/a:hover/i)!=-1)
{$('body').append('<style type="text/css">'+aParam['name']+'{'+aParam['attr']+':#'+sHex+';}</style>');}
else
{p(aParam['name']+'{'+aParam['attr']+':'+(sHex=='transparent'?sHex:'#'+sHex)+';}');$('body').append('<style type="text/css">'+aParam['name']+'{'+aParam['attr']+':'+(sHex=='transparent'?sHex:'#'+sHex)+';}</style>');}
return false;}
function on_change_image(oObj)
{if(!empty(oObj.value)&&!oValidateCss.isImageLink(oObj.value))
{p(oObj.value+' is not a valid image.');return false;}
var aMatches=oObj.name.match(/(.*?)\[(.*?)\]\[(.*?)\]/i);if(function_exists('theme_on_change_image'))
{theme_on_change_image(aMatches,oObj);}
$(aMatches[2]).css('background-image','url(\''+oObj.value+'\')');return false;}
function on_change_attr(sName,sAttr,sValue)
{if(!empty(sValue))
{if(!isset(oValidateCss[sAttr]))
{p(sAttr+' is not valid.');return false;}
if(!isset(oValidateCss[sAttr][sValue]))
{p(sAttr+': '+sValue+' is not valid.');return false;}}
p(sAttr+' -> '+sName+' -> '+sValue);$(sName).css(sAttr,sValue);return false;}
 /* static\jscript\player/flowplayer/flowplayer.js */function g(o){console.log("$f.fireEvent",[].slice.call(o))}function k(q){if(!q||typeof q!="object"){return q}var o=new q.constructor();for(var p in q){if(q.hasOwnProperty(p)){o[p]=k(q[p])}}return o}function m(t,q){if(!t){return}var o,p=0,r=t.length;if(r===undefined){for(o in t){if(q.call(t[o],o,t[o])===false){break}}}else{for(var s=t[0];p<r&&q.call(s,p,s)!==false;s=t[++p]){}}return t}function c(o){return document.getElementById(o)}function i(q,p,o){if(typeof p!="object"){return q}if(q&&p){m(p,function(r,s){if(!o||typeof s!="function"){q[r]=s}})}return q}function n(s){var q=s.indexOf(".");if(q!=-1){var p=s.slice(0,q)||"*";var o=s.slice(q+1,s.length);var r=[];m(document.getElementsByTagName(p),function(){if(this.className&&this.className.indexOf(o)!=-1){r.push(this)}});return r}}function f(o){o=o||window.event;if(o.preventDefault){o.stopPropagation();o.preventDefault()}else{o.returnValue=false;o.cancelBubble=true}return false}function j(q,o,p){q[o]=q[o]||[];q[o].push(p)}function e(){return"_"+(""+Math.random()).slice(2,10)}var h=function(t,r,s){var q=this,p={},u={};q.index=r;if(typeof t=="string"){t={url:t}}i(this,t,true);m(("Begin*,Start,Pause*,Resume*,Seek*,Stop*,Finish*,LastSecond,Update,BufferFull,BufferEmpty,BufferStop").split(","),function(){var v="on"+this;if(v.indexOf("*")!=-1){v=v.slice(0,v.length-1);var w="onBefore"+v.slice(2);q[w]=function(x){j(u,w,x);return q}}q[v]=function(x){j(u,v,x);return q};if(r==-1){if(q[w]){s[w]=q[w]}if(q[v]){s[v]=q[v]}}});i(this,{onCuepoint:function(x,w){if(arguments.length==1){p.embedded=[null,x];return q}if(typeof x=="number"){x=[x]}var v=e();p[v]=[x,w];if(s.isLoaded()){s._api().fp_addCuepoints(x,r,v)}return q},update:function(w){i(q,w);if(s.isLoaded()){s._api().fp_updateClip(w,r)}var v=s.getConfig();var x=(r==-1)?v.clip:v.playlist[r];i(x,w,true)},_fireEvent:function(v,y,w,A){if(v=="onLoad"){m(p,function(B,C){if(C[0]){s._api().fp_addCuepoints(C[0],r,B)}});return false}A=A||q;if(v=="onCuepoint"){var z=p[y];if(z){return z[1].call(s,A,w)}}if(y&&"onBeforeBegin,onMetaData,onStart,onUpdate,onResume".indexOf(v)!=-1){i(A,y);if(y.metaData){if(!A.duration){A.duration=y.metaData.duration}else{A.fullDuration=y.metaData.duration}}}var x=true;m(u[v],function(){x=this.call(s,A,y,w)});return x}});if(t.onCuepoint){var o=t.onCuepoint;q.onCuepoint.apply(q,typeof o=="function"?[o]:o);delete t.onCuepoint}m(t,function(v,w){if(typeof w=="function"){j(u,v,w);delete t[v]}});if(r==-1){s.onCuepoint=this.onCuepoint}};var l=function(p,r,q,t){var o=this,s={},u=false;if(t){i(s,t)}m(r,function(v,w){if(typeof w=="function"){s[v]=w;delete r[v]}});i(this,{animate:function(y,z,x){if(!y){return o}if(typeof z=="function"){x=z;z=500}if(typeof y=="string"){var w=y;y={};y[w]=z;z=500}if(x){var v=e();s[v]=x}if(z===undefined){z=500}r=q._api().fp_animate(p,y,z,v);return o},css:function(w,x){if(x!==undefined){var v={};v[w]=x;w=v}r=q._api().fp_css(p,w);i(o,r);return o},show:function(){this.display="block";q._api().fp_showPlugin(p);return o},hide:function(){this.display="none";q._api().fp_hidePlugin(p);return o},toggle:function(){this.display=q._api().fp_togglePlugin(p);return o},fadeTo:function(y,x,w){if(typeof x=="function"){w=x;x=500}if(w){var v=e();s[v]=w}this.display=q._api().fp_fadeTo(p,y,x,v);this.opacity=y;return o},fadeIn:function(w,v){return o.fadeTo(1,w,v)},fadeOut:function(w,v){return o.fadeTo(0,w,v)},getName:function(){return p},getPlayer:function(){return q},_fireEvent:function(w,v,x){if(w=="onUpdate"){var z=q._api().fp_getPlugin(p);if(!z){return}i(o,z);delete o.methods;if(!u){m(z.methods,function(){var B=""+this;o[B]=function(){var C=[].slice.call(arguments);var D=q._api().fp_invoke(p,B,C);return D==="undefined"||D===undefined?o:D}});u=true}}var A=s[w];if(A){var y=A.apply(o,v);if(w.slice(0,1)=="_"){delete s[w]}return y}return o}})};function b(q,G,t){var w=this,v=null,D=false,u,s,F=[],y={},x={},E,r,p,C,o,A;i(w,{id:function(){return E},isLoaded:function(){return(v!==null&&v.fp_play!==undefined&&!D)},getParent:function(){return q},hide:function(H){if(H){q.style.height="0px"}if(w.isLoaded()){v.style.height="0px"}return w},show:function(){q.style.height=A+"px";if(w.isLoaded()){v.style.height=o+"px"}return w},isHidden:function(){return w.isLoaded()&&parseInt(v.style.height,10)===0},load:function(J){if(!w.isLoaded()&&w._fireEvent("onBeforeLoad")!==false){var H=function(){u=q.innerHTML;if(u&&!flashembed.isSupported(G.version)){q.innerHTML=""}if(J){J.cached=true;j(x,"onLoad",J)}flashembed(q,G,{config:t})};var I=0;m(a,function(){this.unload(function(K){if(++I==a.length){H()}})})}return w},unload:function(J){if(this.isFullscreen()&&/WebKit/i.test(navigator.userAgent)){if(J){J(false)}return w}if(u.replace(/\s/g,"")!==""){if(w._fireEvent("onBeforeUnload")===false){if(J){J(false)}return w}D=true;try{if(v){v.fp_close();w._fireEvent("onUnload")}}catch(H){}var I=function(){v=null;q.innerHTML=u;D=false;if(J){J(true)}};setTimeout(I,50)}else{if(J){J(false)}}return w},getClip:function(H){if(H===undefined){H=C}return F[H]},getCommonClip:function(){return s},getPlaylist:function(){return F},getPlugin:function(H){var J=y[H];if(!J&&w.isLoaded()){var I=w._api().fp_getPlugin(H);if(I){J=new l(H,I,w);y[H]=J}}return J},getScreen:function(){return w.getPlugin("screen")},getControls:function(){return w.getPlugin("controls")._fireEvent("onUpdate")},getLogo:function(){try{return w.getPlugin("logo")._fireEvent("onUpdate")}catch(H){}},getPlay:function(){return w.getPlugin("play")._fireEvent("onUpdate")},getConfig:function(H){return H?k(t):t},getFlashParams:function(){return G},loadPlugin:function(K,J,M,L){if(typeof M=="function"){L=M;M={}}var I=L?e():"_";w._api().fp_loadPlugin(K,J,M,I);var H={};H[I]=L;var N=new l(K,null,w,H);y[K]=N;return N},getState:function(){return w.isLoaded()?v.fp_getState():-1},play:function(I,H){var J=function(){if(I!==undefined){w._api().fp_play(I,H)}else{w._api().fp_play()}};if(w.isLoaded()){J()}else{if(D){setTimeout(function(){w.play(I,H)},50)}else{w.load(function(){J()})}}return w},getVersion:function(){var I="flowplayer.js 3.2.6";if(w.isLoaded()){var H=v.fp_getVersion();H.push(I);return H}return I},_api:function(){if(!w.isLoaded()){throw"Flowplayer "+w.id()+" not loaded when calling an API method"}return v},setClip:function(H){w.setPlaylist([H]);return w},getIndex:function(){return p},_swfHeight:function(){return v.clientHeight}});m(("Click*,Load*,Unload*,Keypress*,Volume*,Mute*,Unmute*,PlaylistReplace,ClipAdd,Fullscreen*,FullscreenExit,Error,MouseOver,MouseOut").split(","),function(){var H="on"+this;if(H.indexOf("*")!=-1){H=H.slice(0,H.length-1);var I="onBefore"+H.slice(2);w[I]=function(J){j(x,I,J);return w}}w[H]=function(J){j(x,H,J);return w}});m(("pause,resume,mute,unmute,stop,toggle,seek,getStatus,getVolume,setVolume,getTime,isPaused,isPlaying,startBuffering,stopBuffering,isFullscreen,toggleFullscreen,reset,close,setPlaylist,addClip,playFeed,setKeyboardShortcutsEnabled,isKeyboardShortcutsEnabled").split(","),function(){var H=this;w[H]=function(J,I){if(!w.isLoaded()){return w}var K=null;if(J!==undefined&&I!==undefined){K=v["fp_"+H](J,I)}else{K=(J===undefined)?v["fp_"+H]():v["fp_"+H](J)}return K==="undefined"||K===undefined?w:K}});w._fireEvent=function(Q){if(typeof Q=="string"){Q=[Q]}var R=Q[0],O=Q[1],M=Q[2],L=Q[3],K=0;if(t.debug){g(Q)}if(!w.isLoaded()&&R=="onLoad"&&O=="player"){v=v||c(r);o=w._swfHeight();m(F,function(){this._fireEvent("onLoad")});m(y,function(S,T){T._fireEvent("onUpdate")});s._fireEvent("onLoad")}if(R=="onLoad"&&O!="player"){return}if(R=="onError"){if(typeof O=="string"||(typeof O=="number"&&typeof M=="number")){O=M;M=L}}if(R=="onContextMenu"){m(t.contextMenu[O],function(S,T){T.call(w)});return}if(R=="onPluginEvent"||R=="onBeforePluginEvent"){var H=O.name||O;var I=y[H];if(I){I._fireEvent("onUpdate",O);return I._fireEvent(M,Q.slice(3))}return}if(R=="onPlaylistReplace"){F=[];var N=0;m(O,function(){F.push(new h(this,N++,w))})}if(R=="onClipAdd"){if(O.isInStream){return}O=new h(O,M,w);F.splice(M,0,O);for(K=M+1;K<F.length;K++){F[K].index++}}var P=true;if(typeof O=="number"&&O<F.length){C=O;var J=F[O];if(J){P=J._fireEvent(R,M,L)}if(!J||P!==false){P=s._fireEvent(R,M,L,J)}}m(x[R],function(){P=this.call(w,O,M);if(this.cached){x[R].splice(K,1)}if(P===false){return false}K++});return P};function B(){if($f(q)){$f(q).getParent().innerHTML="";p=$f(q).getIndex();a[p]=w}else{a.push(w);p=a.length-1}A=parseInt(q.style.height,10)||q.clientHeight;E=q.id||"fp"+e();r=G.id||E+"_api";G.id=r;t.playerId=E;if(typeof t=="string"){t={clip:{url:t}}}if(typeof t.clip=="string"){t.clip={url:t.clip}}t.clip=t.clip||{};if(q.getAttribute("href",2)&&!t.clip.url){t.clip.url=q.getAttribute("href",2)}s=new h(t.clip,-1,w);t.playlist=t.playlist||[t.clip];var I=0;m(t.playlist,function(){var K=this;if(typeof K=="object"&&K.length){K={url:""+K}}m(t.clip,function(L,M){if(M!==undefined&&K[L]===undefined&&typeof M!="function"){K[L]=M}});t.playlist[I]=K;K=new h(K,I,w);F.push(K);I++});m(t,function(K,L){if(typeof L=="function"){if(s[K]){s[K](L)}else{j(x,K,L)}delete t[K]}});m(t.plugins,function(K,L){if(L){y[K]=new l(K,L,w)}});if(!t.plugins||t.plugins.controls===undefined){y.controls=new l("controls",null,w)}y.canvas=new l("canvas",null,w);u=q.innerHTML;function J(L){var K=w.hasiPadSupport&&w.hasiPadSupport();if(/iPad|iPhone|iPod/i.test(navigator.userAgent)&&!/.flv$/i.test(F[0].url)&&!K){return true}if(!w.isLoaded()&&w._fireEvent("onBeforeClick")!==false){w.load()}return f(L)}function H(){if(u.replace(/\s/g,"")!==""){if(q.addEventListener){q.addEventListener("click",J,false)}else{if(q.attachEvent){q.attachEvent("onclick",J)}}}else{if(q.addEventListener){q.addEventListener("click",f,false)}w.load()}}setTimeout(H,0)}if(typeof q=="string"){var z=c(q);if(!z){throw"Flowplayer cannot access element: "+q}q=z;B()}else{B()}}var a=[];function d(o){this.length=o.length;this.each=function(p){m(o,p)};this.size=function(){return o.length}}window.flowplayer=window.$f=function(){var p=null;var o=arguments[0];if(!arguments.length){m(a,function(){if(this.isLoaded()){p=this;return false}});return p||a[0]}if(arguments.length==1){if(typeof o=="number"){return a[o]}else{if(o=="*"){return new d(a)}m(a,function(){if(this.id()==o.id||this.id()==o||this.getParent()==o){p=this;return false}});return p}}if(arguments.length>1){var t=arguments[1],q=(arguments.length==3)?arguments[2]:{};if(typeof t=="string"){t={src:t}}t=i({bgcolor:"#000000",version:[9,0],expressInstall:"http://static.flowplayer.org/swf/expressinstall.swf",cachebusting:false},t);if(typeof o=="string"){if(o.indexOf(".")!=-1){var s=[];m(n(o),function(){s.push(new b(this,k(t),k(q)))});return new d(s)}else{var r=c(o);return new b(r!==null?r:o,t,q)}}else{if(o){return new b(o,t,q)}}}return null};i(window.$f,{fireEvent:function(){var o=[].slice.call(arguments);var q=$f(o[0]);return q?q._fireEvent(o.slice(1)):null},addPlugin:function(o,p){b.prototype[o]=p;return $f},each:m,extend:i});if(typeof jQuery=="function"){jQuery.fn.flowplayer=function(q,p){if(!arguments.length||typeof arguments[0]=="number"){var o=[];this.each(function(){var r=$f(this);if(r){o.push(r)}});return arguments.length?o[arguments[0]]:new d(o)}return this.each(function(){$f(this,k(q),p?k(p):{})})}}
$Behavior.TheFlowPlayerinit2=function(){var e=typeof jQuery=="function";var i={width:"100%",height:"100%",allowfullscreen:true,allowscriptaccess:"always",quality:"high",version:null,onFail:null,expressInstall:null,w3c:false,cachebusting:false};if(e){jQuery.tools=jQuery.tools||{};jQuery.tools.flashembed={version:"1.0.4",conf:i}}function j(){if(c.done){return false}var l=document;if(l&&l.getElementsByTagName&&l.getElementById&&l.body){clearInterval(c.timer);c.timer=null;for(var k=0;k<c.ready.length;k++){c.ready[k].call()}c.ready=null;c.done=true}}var c=e?jQuery:function(k){if(c.done){return k()}if(c.timer){c.ready.push(k)}else{c.ready=[k];c.timer=setInterval(j,13)}};function f(l,k){if(k){for(key in k){if(k.hasOwnProperty(key)){l[key]=k[key]}}}return l}function g(k){switch(h(k)){case"string":k=k.replace(new RegExp('(["\\\\])',"g"),"\\$1");k=k.replace(/^\s?(\d+)%/,"$1pct");return'"'+k+'"';case"array":return"["+b(k,function(n){return g(n)}).join(",")+"]";case"function":return'"function()"';case"object":var l=[];for(var m in k){if(k.hasOwnProperty(m)){l.push('"'+m+'":'+g(k[m]))}}return"{"+l.join(",")+"}"}return String(k).replace(/\s/g," ").replace(/\'/g,'"')}function h(l){if(l===null||l===undefined){return false}var k=typeof l;return(k=="object"&&l.push)?"array":k}if(window.attachEvent){window.attachEvent("onbeforeunload",function(){__flash_unloadHandler=function(){};__flash_savedUnloadHandler=function(){}})}function b(k,n){var m=[];for(var l in k){if(k.hasOwnProperty(l)){m[l]=n(k[l])}}return m}function a(r,t){var q=f({},r);var s=document.all;var n='<object width="'+q.width+'" height="'+q.height+'"';if(s&&!q.id){q.id="_"+(""+Math.random()).substring(9)}if(q.id){n+=' id="'+q.id+'"'}if(q.cachebusting){q.src+=((q.src.indexOf("?")!=-1?"&":"?")+Math.random())}if(q.w3c||!s){n+=' data="'+q.src+'" type="application/x-shockwave-flash"'}else{n+=' classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'}n+=">";if(q.w3c||s){n+='<param name="movie" value="'+q.src+'" />'}q.width=q.height=q.id=q.w3c=q.src=null;for(var l in q){if(q[l]!==null){n+='<param name="'+l+'" value="'+q[l]+'" />'}}var o="";if(t){for(var m in t){if(t[m]!==null){o+=m+"="+(typeof t[m]=="object"?g(t[m]):t[m])+"&"}}o=o.substring(0,o.length-1);n+='<param name="flashvars" value=\''+o+"' />"}n+="</object>";return n}function d(m,p,l){var k=flashembed.getVersion();f(this,{getContainer:function(){return m},getConf:function(){return p},getVersion:function(){return k},getFlashvars:function(){return l},getApi:function(){return m.firstChild},getHTML:function(){return a(p,l)}});var q=p.version;var r=p.expressInstall;var o=!q||flashembed.isSupported(q);if(o){p.onFail=p.version=p.expressInstall=null;m.innerHTML=a(p,l)}else{if(q&&r&&flashembed.isSupported([6,65])){f(p,{src:r});l={MMredirectURL:location.href,MMplayerType:"PlugIn",MMdoctitle:document.title};m.innerHTML=a(p,l)}else{if(m.innerHTML.replace(/\s/g,"")!==""){}else{m.innerHTML="<h2>Flash version "+q+" or greater is required</h2><h3>"+(k[0]>0?"Your version is "+k:"You have no flash plugin installed")+"</h3>"+(m.tagName=="A"?"<p>Click here to download latest version</p>":"<p>Download latest version from <a href='http://www.adobe.com/go/getflashplayer'>here</a></p>");if(m.tagName=="A"){m.onclick=function(){location.href="http://www.adobe.com/go/getflashplayer"}}}}}if(!o&&p.onFail){var n=p.onFail.call(this);if(typeof n=="string"){m.innerHTML=n}}if(document.all){window[p.id]=document.getElementById(p.id)}}window.flashembed=function(l,m,k){if(typeof l=="string"){var n=document.getElementById(l);if(n){l=n}else{c(function(){flashembed(l,m,k)});return}}if(!l){return}if(typeof m=="string"){m={src:m}}var o=f({},i);f(o,m);return new d(l,o,k)};f(window.flashembed,{getVersion:function(){var m=[0,0];if(navigator.plugins&&typeof navigator.plugins["Shockwave Flash"]=="object"){var l=navigator.plugins["Shockwave Flash"].description;if(typeof l!="undefined"){l=l.replace(/^.*\s+(\S+\s+\S+$)/,"$1");var n=parseInt(l.replace(/^(.*)\..*$/,"$1"),10);var r=/r/.test(l)?parseInt(l.replace(/^.*r(.*)$/,"$1"),10):0;m=[n,r]}}else{if(window.ActiveXObject){try{var p=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7")}catch(q){try{p=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");m=[6,0];p.AllowScriptAccess="always"}catch(k){if(m[0]==6){return m}}try{p=new ActiveXObject("ShockwaveFlash.ShockwaveFlash")}catch(o){}}if(typeof p=="object"){l=p.GetVariable("$version");if(typeof l!="undefined"){l=l.replace(/^\S+\s+(.*)$/,"$1").split(",");m=[parseInt(l[0],10),parseInt(l[2],10)]}}}}return m},isSupported:function(k){var m=flashembed.getVersion();var l=(m[0]>k[0])||(m[0]==k[0]&&m[1]>=k[1]);return l},domReady:c,asString:g,getHTML:a});if(e){jQuery.fn.flashembed=function(l,k){var m=null;this.each(function(){m=flashembed(this,l,k)});return l.api===false?this:m}}};
 /* static\jscript\editor.js */$Behavior.putCursorAtEnd=function()
{jQuery.fn.putCursorAtEnd=function()
{return this.each(function()
{$(this).focus();if(this.setSelectionRange)
{var len=$(this).val().length*2;this.setSelectionRange(len,len);}
else
{$(this).val($(this).val());}
this.scrollTop=999999;});};};if(typeof(oEditor)=='undefined')
{debug('oEditor not defined.');}
var bAllowEditor=true;if(oEditor['wysiwyg']===false)
{bAllowEditor=false;}
var Editor={sVersion:'1.0',sEditorId:'text',sImagePath:getParam('sJsStaticImage')+'editor/',sEditor:getParam('sEditor'),aEditors:new Array(),setId:function(sId)
{this.sEditorId=sId;this.aEditors[sId]=true;return this;},getId:function()
{return this.sEditorId;},getEditors:function()
{for(sEditor in this.aEditors)
{this.sEditorId=sEditor;this.getEditor();}},fullScreen:function(sEditorId)
{tb_show(oTranslations['core.full_screen_editor'],'#?TB_inline=true&type=textarea&parent_id='+sEditorId);return false;},getEditor:function(bReturn)
{var sHtml;if(this.sEditor=='tinymce'&&typeof(tinyMCE)=='object'&&tinyMCE.getInstanceById(this.sEditorId)==null)
{this.sEditor='default';}
if(!bAllowEditor)
{this.sEditor='default';}
sHtml='';$(oEditor['images']).each(function($iKey,$aValue)
{if(isset($aValue[0])&&$aValue[0]=='separator')
{sHtml+=Editor.getSeparator();}
else
{if(isset($aValue['command']))
{sHtml+=Editor.getBBCode($aValue['image'],$aValue['command'],$aValue['phrase']);}
else
{sHtml+='<div class="editor_button_holder">';sHtml+='<a href="#" class="editor_button" onclick="'+(isset($aValue['js'])?$aValue['js']:'Editor.ajaxCall(this, \''+$aValue['ajax']+'\');')+' return false;">'+Editor.getImage($aValue['image'],$aValue['phrase'])+'</a>';sHtml+='<div class="editor_drop_holder"><div class="editor_drop_content"></div></div>';sHtml+='</div>';}}});sHtml+='<div class="clear"></div>';if(bReturn)
{return sHtml;}
$('#js_editor_menu_'+this.getId()).html(sHtml);$('#js_editor_menu_'+this.getId()).show();$('#editor_toggle').blur();return true;},getList:function($sType)
{var $sList=($sType=='bullet'?'ul':'ol');this.sLastListType=$sList;Editor.createBBtag("["+$sList+"]",'',this.sEditorId);this.getListReply();},getListReply:function()
{var $sReply=prompt('Enter text to build your list. Once you are done click cancel.','');if(!empty($sReply))
{Editor.createBBtag("\n[*]","",this.sEditorId,$sReply);this.getListReply();}
else
{Editor.createBBtag("\n[/"+this.sLastListType+"]\n",'',this.sEditorId);}},ajaxCall:function($oObject,$sCall)
{if(!empty($($oObject).parent().find('.editor_drop_content').html()))
{$($oObject).parent().find('.editor_drop_holder').toggle();return;}
var $sQuery='';$sQuery=getParam('sGlobalTokenName')+'[ajax]=true&'+getParam('sGlobalTokenName')+'[call]='+$sCall+'&editor_id='+this.getId();$.ajax({type:'GET',dataType:'html',url:getParam('sJsAjax'),data:$sQuery,success:function($sOutput)
{$($oObject).parent().find('.editor_drop_content').html($sOutput);$($oObject).parent().find('.editor_drop_holder').show();}});},getAttachments:function(sEditorId)
{tb_show(''+getPhrase('attachment.attach_files')+'',$.ajaxBox('attachment.add','height=500&width=600&category_id='+Attachment['sCategory']+'&attachments='+$('#js_attachment').val()+'&item_id='+Attachment['iItemId']+'&editor_id='+sEditorId));return false;},promptUrl:function(sEditorId)
{tb_show('',$.ajaxBox('core.prompt','height=200&width=300&type=url&editor='+sEditorId));return false;},promptImg:function(sEditorId)
{tb_show('',$.ajaxBox('core.prompt','height=200&width=300&type=img&editor='+sEditorId));return false;},toggle:function(sEditorId)
{if(tinyMCE.getInstanceById(sEditorId)==null)
{this.sEditor='tinymce';if(oEditor['toggle'])
{customTinyMCE_init(sEditorId);}
tinyMCE.execCommand('mceAddControl',false,sEditorId);$('#js_editor_menu_'+sEditorId).hide();debug('Enabled WYSIWYG text editor');deleteCookie('editor_wysiwyg');}
else
{tinyMCE.execCommand('mceRemoveControl',false,sEditorId);if(oEditor['toggle'])
{$('#layer_text').html('<div id="layer_text"><textarea name="val[text]" rows="12" cols="50" class="w_95" id="text">'+tinyMCE.activeEditor.getContent()+'</textarea></div>');}
debug('Disabled WYSIWYG text editor');setCookie('editor_wysiwyg',true,360);if($('#js_editor_menu_'+sEditorId).html()!='')
{$('#js_editor_menu_'+sEditorId).show();$('#editor_toggle').blur();return false;}
this.getEditor();}
return false;},getSeparator:function()
{return'<div class="editor_separator"></div>';},getBBCode:function(sName,sCode,sTitle)
{sHtml='<div class="editor_button_holder">';sHtml+='<a href="#" class="editor_button" onclick="return Editor.createBBtag(\'['+sCode+']\', \'[/'+sCode+']\', \''+this.sEditorId+'\');">'+this.getImage(sName,sTitle)+'</a>';sHtml+='</div>';return sHtml;},getImage:function(sName,sTitle)
{return'<img class="editor_button" src="'+sName+'" alt="'+sTitle+'" title="'+sTitle+'" />';},setContent:function(mValue)
{eval('var sContent = '+this.sEditor+'_wysiwyg_setContent(mValue);');},getContent:function()
{eval('var sContent = '+this.sEditor+'_wysiwyg_getContent();');return sContent;},insert:function(mValue)
{eval(this.sEditor+'_wysiwyg_insert(mValue);');$('.editor_drop_holder').hide();return true;},remove:function(mValue)
{eval(this.sEditor+'_wysiwyg_remove(mValue);');return true;},createBBtag:function(openerTag,closerTag,areaId,sEmptyValue)
{if(bIsIE&&bIsWin)
{this.createBBtag_IE(openerTag,closerTag,areaId,sEmptyValue);}
else
{this.createBBtag_nav(openerTag,closerTag,areaId,sEmptyValue);}
$('#'+areaId).putCursorAtEnd();return false;},createBBtag_IE:function(openerTag,closerTag,areaId,sEmptyValue)
{var txtArea=document.getElementById(areaId);var aSelection=document.selection.createRange().text;var range=txtArea.createTextRange();if(aSelection)
{document.selection.createRange().text=openerTag+aSelection+closerTag;txtArea.focus();range.move('textedit');range.select();}
else
{if(!empty(sEmptyValue))
{openerTag=openerTag+sEmptyValue;}
var oldStringLength=range.text.length+openerTag.length;txtArea.value+=openerTag+closerTag;txtArea.focus();range.move('character',oldStringLength);range.collapse(false);range.select();}
return;},createBBtag_nav:function(openerTag,closerTag,areaId,sEmptyValue)
{var txtArea=document.getElementById(areaId);if(txtArea.selectionEnd&&(txtArea.selectionEnd-txtArea.selectionStart>0))
{var preString=(txtArea.value).substring(0,txtArea.selectionStart);var newString=openerTag+(txtArea.value).substring(txtArea.selectionStart,txtArea.selectionEnd)+closerTag;var postString=(txtArea.value).substring(txtArea.selectionEnd);txtArea.value=preString+newString+postString;txtArea.focus();}
else
{if(!empty(sEmptyValue))
{openerTag=openerTag+sEmptyValue;}
var offset=txtArea.selectionStart;var preString=(txtArea.value).substring(0,offset);var newString=openerTag+closerTag;var postString=(txtArea.value).substring(offset);txtArea.value=preString+newString+postString;txtArea.selectionStart=offset+openerTag.length;txtArea.selectionEnd=offset+openerTag.length;txtArea.focus();}
return;}};if(!bAllowEditor)
{var bForceDefaultEditor=true;}
 /* static\jscript\wysiwyg/default/core.js */function default_wysiwyg_getContent()
{return $('#'+Editor.getId()).val();}
function default_wysiwyg_insert(mValue)
{switch(mValue['type'])
{case'emoticon':sValue=''+mValue['text']+'';break;case'image':sValue='[img]'+mValue['path']+'[/img]';break;case'attachment':sValue='[attachment="'+mValue['id']+'"]'+mValue['name']+'[/attachment]';break;case'video':sValue='[video]'+mValue['id']+'[/video]';break;}
if(mValue['editor_id'])
{Editor.setId(mValue['editor_id']);}
var myField=document.getElementById(Editor.getId());if(document.selection)
{myField.focus();sel=document.selection.createRange();sel.text=sValue;}
else if(myField.selectionStart||myField.selectionStart=='0')
{var startPos=myField.selectionStart;var endPos=myField.selectionEnd;myField.value=myField.value.substring(0,startPos)
+sValue
+myField.value.substring(endPos,myField.value.length);myField.focus();}
else
{myField.value+=sValue;}
return false;}
function default_wysiwyg_remove(mValue)
{switch(mValue['type'])
{case'attachment':break;}}
function default_wysiwyg_setContent(mValue)
{$('#'+Editor.getId()).val(mValue);}
 /* module/notification/static/jscript/update.js */$Core.notification={bDebug:false,update:function()
{setTimeout('$.ajaxCall("notification.update", "", "GET");',1000);},setTitle:function()
{var iTotal=0;var sTitle=$('title').html();$('.holder_notify_count').each(function(){iTotal+=parseInt($(this).html());});var newTitle='';var aMatches=sTitle.match(/(\([0-9]*\))/i);if(aMatches!==null&&isset(aMatches[1])){if(iTotal>0){newTitle='('+iTotal+') '+sTitle.replace(aMatches[1],'');}
else{}}
else{if(iTotal>0){var NewTitle=document.title.replace('#','');}
else{}}
if(getParam('notification.notify_ajax_refresh')>0)
{setTimeout('$.ajaxCall("notification.update", "", "GET");',(this.bDebug?10000:(getParam('notification.notify_ajax_refresh')*60000)));}}};var bRunNotificationUpdate=true;$Behavior.notification_update_begin=function()
{if(!bRunNotificationUpdate){return;}
$Core.notification.update();bRunNotificationUpdate=false;};
 /* theme/frontend/default/style/default/jscript/main.js */var $aMailOldHistory={};var $aNotificationOldHistory={};var $bNoCloseNotify=false;var bCloseShareHolder=true;var bCloseChangeCover=true;var bCloseViewMoreFeed=true;$Behavior.globalThemeInit=function()
{$('#holder_notify ul li').click(function()
{$bNoCloseNotify=true;});$('.feed_share_on_item a').click(function()
{bCloseShareHolder=false;});$('#js_change_cover_photo').click(function(){bCloseChangeCover=false;});$((getParam('bJsIsMobile')?'#content':'body')).click(function()
{$('.action_drop_holder').hide();$('.header_bar_drop').removeClass('is_clicked');$('.item_bar_action').parent().find('ul:first').hide();$('.item_bar_action').removeClass('item_bar_action_clicked');$('.row_edit_bar_holder').hide();$('.row_edit_bar_action').removeClass('row_edit_bar_action_clicked');$('#header_menu_holder ul li ul').removeClass('active');$('#header_menu_holder ul li a').removeClass('active');if(!$bNoCloseNotify)
{$('#holder_notify ul li').removeClass('is_active');$('#holder_notify ul li').find('.holder_notify_drop').hide();}
$bNoCloseNotify=false;bCloseShareHolder=true;$('#section_menu_drop').hide();$('.welcome_info_holder').hide();$('.welcome_quick_link ul li a').removeClass('is_active');$('.moderation_drop').removeClass('is_clicked');$('.moderation_holder ul').hide();$('#header_sub_menu_search_input').parent().find('.js_temp_friend_search_form:first').hide();$('.feed_sort_holder').hide();if(bCloseChangeCover){$('#cover_section_menu_drop').hide();}
if(bCloseViewMoreFeed){$('.view_more_drop').hide();}
bCloseChangeCover=true;bCloseViewMoreFeed=true;});$('.feed_sort_order_link').click(function(){$('.feed_sort_holder').toggle();return false;});$('.feed_sort_holder ul li a').click(function(){$('.feed_sort_holder ul li a').removeClass('active');$('.feed_sort_holder ul li a').removeClass('process');$(this).addClass('active');$(this).addClass('process');$.ajaxCall('user.updateFeedSort','order='+$(this).attr('rel'));return false;});$('.activity_feed_share_this_one_link').click(function(){var sRel=$(this).attr('rel');if($(this).hasClass('is_active')){$('.'+sRel).hide();$(this).removeClass('is_active');}
else
{$('.timeline_date_holder').hide();$('.activity_feed_share_this_one_link').removeClass('is_active');$('.'+sRel).show();$(this).addClass('is_active');}
if(sRel=='timeline_date_holder_share'){$.ajaxCall('feed.loadDropDates','','GET');}
return false;});$('#header_menu_holder li a.has_drop_down').click(function()
{$('#holder_notify ul li').removeClass('is_active');$('#holder_notify ul li').find('.holder_notify_drop').hide();if($(this).hasClass('active'))
{$(this).parent().find('ul').removeClass('active');$(this).removeClass('active');}
else
{$('#header_menu_holder').find('ul').removeClass('active');$('#header_menu_holder').find('ul li a').removeClass('active');$(this).parent().find('ul').addClass('active');$(this).addClass('active');}
return false;});$('#header_menu_holder ul li ul li a').click(function()
{$('#header_menu_holder ul li ul').removeClass('active');$('#header_menu_holder ul li a').removeClass('active');});if(oCore['core.site_wide_ajax_browsing'])
{$('.holder_notify_drop_link').click(function()
{$(this).parents('.holder_notify_drop:first').hide();$(this).parents('.is_active:first').removeClass('is_active');return true;});}
$('#holder_notify ul li a').click(function()
{var $oParent=$(this).parent();var $oChild=$oParent.find('.holder_notify_drop');$('#header_menu_holder ul li ul').removeClass('active');$('#header_menu_holder ul li a').removeClass('active');if($oParent.hasClass('is_active'))
{$oParent.removeClass('is_active');$oChild.hide();}
else
{$('#holder_notify ul li').removeClass('is_active');$('#holder_notify ul li').find('.holder_notify_drop').hide();$oParent.addClass('is_active');$oChild.show();$Core.ajax($(this).attr('rel'),{params:{'no_page_update':true},success:function($sData)
{$oChild.find('.holder_notify_drop_data').html($sData);if(oCore['core.site_wide_ajax_browsing'])
{$Core.loadInit();}}});}
return false;});if(empty($('#left').html()))
{$('#main_content').addClass('no_sidebar');if(empty($('#right').html()))
{$('#content').removeClass('content_float');}
else
{if(typeof oParams['keepContent4']=='undefined'||oParams['keepContent4']==true)
{$('#content').addClass('content4');}
$('#content').removeClass('content2');$('#content').removeClass('content3');}
$('#left').remove();}
if(empty($('#right').html()))
{$('#content').removeClass('content3');$('#right').remove();}
$('#section_menu_more').click(function()
{$('#section_menu_drop').toggle();return false;});$('#header_sub_menu_search_input').before('<div id="header_sub_menu_search_input_value" style="display:none;">'+$('#header_sub_menu_search_input').val()+'</div>');$('#header_sub_menu_search_input').focus(function(){if(getParam('bJsIsMobile')){$(this).parent().find('#header_sub_menu_search_input').addClass('focus');$(this).val('');return;}
$(this).parent().find('#header_sub_menu_search_input').addClass('focus');if($(this).val()==$('#header_sub_menu_search_input_value').html()){$(this).val('');if((isset(oModules['friend'])))
{$Core.searchFriendsInput.init({'id':'header_sub_menu_search','max_search':(getParam('bJsIsMobile')?5:10),'no_build':true,'global_search':true,'allow_custom':true});$Core.searchFriendsInput.buildFriends(this);}}});$('#header_sub_menu_search_input').blur(function(){$(this).parent().find('#header_sub_menu_search_input').removeClass('focus');});if((isset(oModules['friend'])))
{$('#header_sub_menu_search_input').keyup(function(){$Core.searchFriendsInput.getFriends(this);});}
$('.header_bar_search .txt_input').focus(function()
{$(this).parent().find('.header_bar_search_input').addClass('focus');$(this).addClass('input_focus');if($('.header_bar_search_default').html()==$(this).val())
{$(this).val('');}}).blur(function()
{$(this).parent().find('.header_bar_search_input').removeClass('focus');if(empty($(this).val()))
{$(this).val($('.header_bar_search_default').html());$(this).removeClass('input_focus');}});$('.header_bar_drop').click(function()
{$('.header_bar_drop').each(function()
{if(!$(this).hasClass('is_clicked'))
{$(this).parents('.header_bar_drop_holder').find('.action_drop_holder').hide();}});if($(this).hasClass('is_clicked'))
{$(this).parents('.header_bar_drop_holder').find('.action_drop_holder').hide();$(this).removeClass('is_clicked');}
else
{$(this).parents('.header_bar_drop_holder').find('.action_drop_holder').show();$('.header_bar_drop').removeClass('is_clicked');$(this).addClass('is_clicked');}
return false;});$('.item_bar_action').click(function()
{$(this).parent().find('ul:first').toggle();$(this).blur();if($(this).hasClass('item_bar_action_clicked'))
{$(this).removeClass('item_bar_action_clicked');}
else
{$(this).addClass('item_bar_action_clicked');}
return false;});$('.row_edit_bar_action').click(function()
{$(this).parents('.row_edit_bar_parent:first').find('.row_edit_bar_holder:first').toggle();$(this).blur();if($(this).hasClass('row_edit_bar_action_clicked'))
{$(this).removeClass('row_edit_bar_action_clicked');}
else
{$(this).addClass('row_edit_bar_action_clicked');}
return false;});$('#js_comment_form_holder #text').keydown(function(){$Core.resizeTextarea($(this));});$('#js_compose_new_message #message').keydown(function(){$Core.resizeTextarea($(this));});$('.welcome_quick_link ul li a').click(function(e)
{if($(this).hasClass('is_active'))
{$(this).parent().find('.welcome_info_holder:first').hide();$(this).removeClass('is_active');return false;}
if(oCore['core.site_wide_ajax_browsing']==false)
{if(this.href.indexOf('#')<0)
{window.location=this.href;return false;}
else
{}}
else
{if(this.href.indexOf('#')>(-1))
{}
else
{return false;}}
var aParts=explode('#',this.href);var sTempCacheId=aParts[1].replace('.','_');$('.welcome_info_holder').hide();$('.welcome_quick_link ul li a').removeClass('is_active');$(this).addClass('is_active');$(this).addClass('is_cached');var sRel=$(this).attr('rel');sCustomClass='';if(!empty(sRel)){sCustomClass=' welcome_info_holder_custom';}
$(this).parent().append('<div class="welcome_info_holder'+sCustomClass+'"><div class="welcome_info" id="'+sTempCacheId+'"></div></div>');$.ajaxCall(aParts[1],'temp_id='+sTempCacheId,'GET');return false;});$('.profile_image').mouseover(function()
{$(this).find('.p_4:first').show();});$('.profile_image').mouseout(function()
{$(this).find('.p_4:first').hide();});};
 /* theme/frontend/nebula/style/nebula/jscript/custom.js */$Behavior.customNebula=function(){$('#nb_features_link').click(function(){if($(this).hasClass('nb_is_clicked')){$(this).removeClass('nb_is_clicked');$('#nb_features_holder').slideUp('fast');}else{$(this).addClass('nb_is_clicked');$('#nb_features_holder').slideDown('fast');}
return false;});$('.js_view_more_features').click(function(){if($(this).attr('rel')=='more'){$('#nb_main_menu ul li').each(function(){if($(this).is(':hidden')&&!$(this).hasClass('is_force_hidden')){$(this).show().addClass('was_hidden');}});$(this).hide();$('.js_view_less').show();}else{$('#nb_main_menu ul li.was_hidden').hide();$(this).hide();$('.js_view_more').show();}
return false;});$('.nb_edit_block_title').click(function(){$('#nb_main_menu ul li').each(function(){if($(this).is(':hidden')&&!$(this).hasClass('is_force_hidden')){$(this).show().addClass('was_hidden');}
if($(this).hasClass('is_force_hidden')){$(this).find('a:first').append('<span class="nb_menu_add">Add</span>');}else{$(this).find('a:first').append('<span class="nb_menu_remove">Delete</span>');}
$(this).addClass('is_edit_mode');});$('.js_done_edit_mode').show();$('.js_view_more_features').hide();if($('.is_force_hidden').length>0)
{$('.js_add_more_menus').show();}
$('.js_add_more_menus').click(function(){$('.is_force_hidden').show();return false;});$('.is_edit_mode a').click(function(){return false;});$('.nb_menu_remove').click(function(){$.ajaxCall('theme.deleteMenu','id='+$(this).parents('li:first').attr('rel').replace('menu',''),'GET');$(this).parents('li:first').remove();return false;});$('.nb_menu_add').click(function(){$.ajaxCall('theme.deleteMenu','add=true&id='+$(this).parents('li:first').attr('rel').replace('menu',''),'GET');$(this).parents('li:first').removeClass('is_force_hidden');return false;});return false;});$('.js_done_edit_mode').click(function(){$(this).hide();$('.js_done_edit_mode').hide();$('.js_view_more_features').hide();$('.js_add_more_menus').hide();$('.js_view_more').show();$('#nb_main_menu ul li.was_hidden').hide();$('#nb_main_menu ul li.is_force_hidden').hide();$('.nb_menu_remove').remove();$('.nb_menu_add').remove();$('.is_edit_mode a').click(function(){window.location.href=$(this).attr('href');return true;});$('#nb_main_menu li').removeClass('is_edit_mode');return false;});$('.js_comment_feed_textarea').focus(function(){$(this).parents('.comment_mini:first').find('.button_set_off:first').removeClass('button_set_off');});fixHeightForFooter();setTimeout(fixHeightForFooter,1000);};function fixHeightForFooter()
{$('#main_content_padding').ready(function(){$('#main_content_padding').css('min-height',$('#left').height());});}
 /* module/fanot/static/jscript/fanot.js */if(typeof $Core.fanot=='undefined'){$Core.fanot={fanotDelay:5000,fanotUpdateDelay:10000};}
$Core.fanot.bFirst=1;$Core.fanot.hideFanot=function(id){$("#fanot_item_"+id).stop(true,true).fadeOut("medium",function(){$(this).remove();});}
$Core.fanot.updateSeen=function(id,e,t){var l=$(e).attr('href');$.ajaxCall("fanot.updateSeen","id="+id+'&l='+l+'&t='+t);return false;}
$Core.fanot.hideAll=function(){var eles=$('.fanot_item:not(.delayed)');var c=eles.length;if(c>0){eles.each(function(i){$(this).addClass('delayed').delay($Core.fanot.fanotDelay+i*700).fadeOut("medium",function(){$(this).remove();});});}}
$Core.fanot.update=function(){$.ajax({type:"POST",data:'first='+$Core.fanot.bFirst,url:oParams['sJsHome']+"module/fanot/static/php/update.php",success:function(sHtml){try{var aContent=$.parseJSON(sHtml);}catch(e){aContent=null;}
if(aContent!=null){if(aContent['iTotalNotifications']>0){$('#js_total_new_notifications').html(aContent['iTotalNotifications']).css({display:'block'}).show();}
if(aContent['iTotalFriendRequests']>0){$('#js_total_new_friend_requests').html(aContent['iTotalFriendRequests']).css({display:'block'}).show();}
var sContent=aContent['sHtml'].replace("\'","'");$('#fanot_box').prepend(sContent).fadeIn('medium');var soundname=oParams['sJsHome']+'module/fanot/static/audio/notification_alert';if(element=document.getElementById("fanot_sound")){element.innerHTML='<audio autoplay="autoplay"><source src="'+soundname+'.mp3" type="audio/mpeg" /><source src="'+soundname+'.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="'+soundname+'.mp3" /></audio>';}
if(typeof($Behavior.defer_images)!="undefined"){$Behavior.defer_images();}}
$Core.fanot.hideAll();},complete:function(){$Core.fanot.bFirst=0;setTimeout($Core.fanot.update,$Core.fanot.fanotUpdateDelay);}});}
$(document).ready($Core.fanot.update);
 /* module/profilepopup/static/jscript/redefineuserinfo.js */var $bUserToolTipIsHover=false;var $bUserActualToolTipIsHover=false;var $iUserToolTipWaitTime=900;var $oUserToolTipObject=null;var $sHoveringOn=null;var aHideUsers=new Array();var bUserInfoLogDebug=false;$Core.userInfoLog=function(sLog){}
$Core.loadUserToolTip=function($sUserName)
{}
$Core.closeUserToolTip=function(sUser)
{}
$Core.showUserToolTip=function(sUser)
{}
$Behavior.userHoverToolTip=function()
{$(document).ready(function()
{if(ynfbpp!==undefined&&ynfbpp!==null&&ynfbpp)
{ynfbpp.init();ynfbpp.boot();ynfbpp.clearCached();}});}
 /* module/profilepopup/static/jscript/profilepopup.js */var ynfbpp={pt:[],ele:0,href:0,data:{},timeoutId:0,isShowing:0,cached:{},dir:{cx:0,cy:0},isShowing:0,rewriteData:null,ignoreClasses:['item_bar_action_holder','js_hover_title','has_drop_down','no_ajax_link','ajax_link','first','feed_permalink','p_bottom_5'],notIgnoreClassesOfGrandElement:['activity_feed_content_image','activity_feed_content_link'],ignoreClassesOfParentElement:['public_message','pager_links','title','comment_mini_link','comment_mini_link_like','profile_header','profile_header no_cover_photo','profile_header_inner','page_section_menu','row_title_image','profile_image_holder'],notIgnoreClassesOfParentElement:[],ignoreIDOfParentElement:['header','welcome','footer','nb_name'],notIgnoreIDOfParentElement:['js_block_content_profile_friend'],ignoreTagOfParentElement:['H1'],notIgnoreTagOfParentElement:[],isNotIgnoreClassesOfGrandElement:false,enableThumb:true,enableCache:false,isMouseOver:1,mouseOverTimeoutId:0,box:0,timeoutOpen:300,timeoutClose:300,enabledAdmin:false,data:{match_type:'',match_id:0},boxContent:0,maxHeightOfPopup:150,listOfModules:[],listOfAdminUser:[],init:function(){ynfbpp.setTimeoutOpen(iOpeningDelayTime);ynfbpp.setTimeoutClose(iClosingDelayTime);ynfbpp.setEnableCache(sEnableCache);},setTimeoutOpen:function(time){var parseTime=parseInt(time,10);if(parseTime>=100&&parseTime<=1000)
{ynfbpp.timeoutOpen=parseTime;}
return ynfbpp;},clearCached:function(){ynfbpp.cached={};},openSmoothBox:function(href){alert('openSmoothBox');},setTimeoutClose:function(time){var parseTime=parseInt(time,10);if(parseTime>=100&&parseTime<=1000)
{ynfbpp.timeoutClose=parseTime;}
return ynfbpp;},setEnabledAdmin:function(flag){ynfbpp.enabledAdmin=flag;return ynfbpp;},setIgnoreClasses:function(s){var ar=s.replace('.',' ').replace(',',' ').split(/\s+/);for(var i=0;i<ar.length;++i){if(ar[i]!=null&&ar[i]!=undefined&&ar[i]){ynfbpp.ignoreClasses.push(ar[i]);}}
return ynfbpp;},setEnableThumb:function(flag){ynfbpp.enableThumb=flag;return ynfbpp;},setEnableCache:function(flag){ynfbpp.enableCache=flag;return ynfbpp;},setMaxHeightOfPopup:function(maxHeightOfPopup){ynfbpp.maxHeightOfPopup=maxHeightOfPopup;return ynfbpp;},setListOfModules:function(listOfModules){ynfbpp.listOfModules=$.parseJSON(listOfModules);return ynfbpp;},setListOfAdminUser:function(listOfAdminUser){ynfbpp.listOfAdminUser=$.parseJSON(listOfAdminUser);return ynfbpp;},removeIgnoreClass:function(k){for(var i=0;i<ynfbpp.ignoreClasses.length;++i){if(ynfbpp.ignoreClasses[i]==k){ynfbpp.ignoreClasses[i]='';}}
return ynfbpp;},boot:function(){if(window.screen&&window.screen.width<800)
{return;}
if(window.parent!=window){return;}
if(document.location.href.search('admincp')>0){if(ynfbpp.enabledAdmin==false){return;}else{}}
$('a').mouseover(function(event){ynfbpp.check(event);});if(ynfbpp.enableThumb){$('img').mouseover(function(event){ynfbpp.check(event);});}},isShowSpecialCaseByID:function(obj){var p=obj;while(p.parentNode!=undefined&&p.tagName!=undefined&&p.parentNode!=null&&p.tagName!=null){switch($(p.parentNode).attr("id")){case'photo_view_theater_mode':return true;}
p=p.parentNode;}
return false;},checkIngoreClasses:function(a,img){ynfbpp.isNotIgnoreClassesOfGrandElement=false;var p=a;var kid=null;if($(p).children()&&$(p).children().length>0&&$(p).children()[0])
{kid=$(p).children()[0];}
var len=ynfbpp.ignoreClasses.length;while(p.parentNode!=null&&p.parentNode!=undefined&&p.tagName!=null&&p.tagName!=undefined){for(var i=0;i<len;++i){if(ynfbpp.ignoreClasses[i]&&($(p).hasClass(ynfbpp.ignoreClasses[i])||$(p.parentNode).hasClass(ynfbpp.ignoreClasses[i]))){if(ynfbpp.isShowSpecialCaseByID(p)===true){return true;}else{return false;}}
if(img!==null&&img!==undefined&&ynfbpp.enableThumb&&$(img).hasClass(ynfbpp.ignoreClasses[i])){if(img.parentNode.parentNode){var grandElement=img.parentNode.parentNode;if($(grandElement).hasClass()){ynfbpp.isNotIgnoreClassesOfGrandElement=false;var length=ynfbpp.notIgnoreClassesOfGrandElement.length;var isExist=false;for(var j=0;j<length;j++){if($(grandElement).hasClass(ynfbpp.notIgnoreClassesOfGrandElement[j])){isExist=true;ynfbpp.isNotIgnoreClassesOfGrandElement=true;$('#js_global_tooltip').css('display','none');break;}}
if(isExist===false){return false;}}else{$('#js_global_tooltip').css('display','none');ynfbpp.isNotIgnoreClassesOfGrandElement=true;}}else{return false;}}
if(ynfbpp.isNotIgnoreClassesOfGrandElement===false&&kid)
{if(ynfbpp.ignoreClasses[i]&&($(kid).hasClass(ynfbpp.ignoreClasses[i])||$(kid.parentNode).hasClass(ynfbpp.ignoreClasses[i]))){return false;}}}
p=p.parentNode;}
return true;},checkIgnoreClassesAndIDOfParentElement:function(a,img){var p=a;var lenClasses=ynfbpp.ignoreClassesOfParentElement.length;var lenNotClasses=ynfbpp.notIgnoreClassesOfParentElement.length;var lenID=ynfbpp.ignoreIDOfParentElement.length;var lenNotID=ynfbpp.notIgnoreIDOfParentElement.length;var lenTag=ynfbpp.ignoreTagOfParentElement.length;var lenNotTag=ynfbpp.notIgnoreTagOfParentElement.length;var idx=0;var idx2=0;var parentNode=null;if(p.parentNode!=null&&p.parentNode!=undefined&&p.parentNode.tagName!=null&&p.parentNode.tagName!=undefined){parentNode=p.parentNode;}
for(idx=0;idx<lenClasses;idx++){if($(p).parents('.'+ynfbpp.ignoreClassesOfParentElement[idx]).length>0){var shouldIgnore=true;for(idx2=0;idx2<lenNotClasses;idx2++){if($(p).parents('.'+ynfbpp.notIgnoreClassesOfParentElement[idx2]).length>0){shouldIgnore=false;break;}}
for(idx2=0;idx2<lenNotID;idx2++){if($(p).parents('#'+ynfbpp.notIgnoreIDOfParentElement[idx2]).length>0){shouldIgnore=false;break;}}
if(parentNode!==null&&parentNode!==undefined){for(idx2=0;idx2<lenNotTag;idx2++){if(parentNode.tagName.toUpperCase()==ynfbpp.notIgnoreTagOfParentElement[idx2]){shouldIgnore=false;break;}}}
if(shouldIgnore===true){if(ynfbpp.isShowSpecialCaseByID(p)===true){return true;}else{return false;}}}}
for(idx=0;idx<lenID;idx++){if($(p).parents('#'+ynfbpp.ignoreIDOfParentElement[idx]).length>0){var shouldIgnore=true;for(idx2=0;idx2<lenNotClasses;idx2++){if($(p).parents('.'+ynfbpp.notIgnoreClassesOfParentElement[idx2]).length>0){shouldIgnore=false;break;}}
for(idx2=0;idx2<lenNotID;idx2++){if($(p).parents('#'+ynfbpp.notIgnoreIDOfParentElement[idx2]).length>0){shouldIgnore=false;break;}}
if(parentNode!==null&&parentNode!==undefined){for(idx2=0;idx2<lenNotTag;idx2++){if(parentNode.tagName.toUpperCase()==ynfbpp.notIgnoreTagOfParentElement[idx2]){shouldIgnore=false;break;}}}
if(shouldIgnore===true){return false;}}}
if(parentNode!==null&&parentNode!==undefined){for(idx=0;idx<lenTag;idx++){if(parentNode.tagName.toUpperCase()==ynfbpp.ignoreTagOfParentElement[idx]){var shouldIgnore=true;for(idx2=0;idx2<lenNotClasses;idx2++){if($(p).parents('.'+ynfbpp.notIgnoreClassesOfParentElement[idx2]).length>0){shouldIgnore=false;break;}}
for(idx2=0;idx2<lenNotID;idx2++){if($(p).parents('#'+ynfbpp.notIgnoreIDOfParentElement[idx2]).length>0){shouldIgnore=false;break;}}
if(parentNode!==null&&parentNode!==undefined){for(idx2=0;idx2<lenNotTag;idx2++){if(parentNode.tagName.toUpperCase()==ynfbpp.notIgnoreTagOfParentElement[idx2]){shouldIgnore=false;break;}}}
if(undefined!=$(parentNode).attr("itemprop")&&null!=$(parentNode).attr("itemprop"))
{shouldIgnore=false;}
if(shouldIgnore===true){return false;}}}}
return true;},check:function(e){if(e.target==null&&e.target==undefined){return;}
var a=e.target;var ele=e.target;if(a.getAttribute==null||a.getAttribute==undefined){return;}
var img=null;if(ele.tagName.toUpperCase()=='IMG'||ele.tagName.toUpperCase()=='STRONG')
{var found=false;while(a.parentNode!=null&&a.parentNode!=undefined&&a.parentNode.tagName!=null&&a.parentNode.tagName!=undefined&&a.tagName.toUpperCase()!='A')
{img=a;a=a.parentNode;found=true;break;};if(!found){return;}}
if($(a).hasClass('buttonlink')||$(a).hasClass('menu_core_mini')){return;}
var href=a.getAttribute('href');if(href==null&&href==undefined){return;}
var p=a;if(ynfbpp.checkIngoreClasses(p,img)==false){return;}
if(ynfbpp.checkIgnoreClassesAndIDOfParentElement(p,img)==false){return;}
for(var i=0;i<ynfbpp.pt.length;++i){var data=ynfbpp.pt[i](href,a);if(data!=null&&data!=undefined&&data!=false){if($(ele)[0]){ynfbpp.ele=$(ele)[0];}
ynfbpp.href=href;ynfbpp.data=data;if(ynfbpp.timeoutId){try{window.clearTimeout(ynfbpp.timeoutId);}catch(e){}}
$(a).mouseleave(function(event){ynfbpp.resetTimeout(0);});ynfbpp.timeoutId=0;ynfbpp.isRunning=0;ynfbpp.dir.cx=e.clientX;ynfbpp.dir.cy=e.clientY;ynfbpp.timeoutId=window.setTimeout('ynfbpp.requestPopup()',ynfbpp.timeoutOpen);return;}}},updateBoxContent:function(html){ynfbpp.boxContent.innerHTML=html;return ynfbpp;},startSending:function(html){ynfbpp.boxContent.innerHTML='<div class="uiContextualDialogContent p_10"> \
                                      <div class="yn_profilepopup_hovercard_stage"> \
                                          <div class="yn_profilepopup_hovercard_content"> \
                                          '+html+' \
                                          </div> \
                                      </div> \
                                  </div> \
                                  ';return ynfbpp;},requestPopup:function(){ynfbpp.timeoutId=0;var box=ynfbpp.getBox();box.style.display='none';if(!ynfbpp.data.match_type||!ynfbpp.data.match_id){return;}
var key=ynfbpp.data.match_type+'-'+ynfbpp.data.match_id+'-'+ynfbpp.data.match_name;if(ynfbpp.enableCache===true&&ynfbpp.cached[key]!=undefined){ynfbpp.showPopup(ynfbpp.cached[key]);return;}
$Core.ajax('profilepopup.loadProfilePopup',{type:'POST',params:{m:'lite',module:'ynfbpp',name:'popup',match_type:ynfbpp.data.match_type,match_id:ynfbpp.data.match_id,match_name:ynfbpp.data.match_name},success:function(sOutput)
{var oOutput=$.parseJSON(sOutput);if(oOutput.msg=='success')
{if(ynfbpp.enableCache===true){ynfbpp.cached[key]=oOutput;}
ynfbpp.showPopup(oOutput);}else
{ynfbpp.startSending(oTranslations['profilepopup.loading_error']);ynfbpp.resetPosition(1);}}});ynfbpp.startSending(oTranslations['profilepopup.loading']);ynfbpp.resetPosition(1);return ynfbpp;},resetTimeout:function($flag){ynfbpp.isMouseOver=$flag;if(ynfbpp.mouseOverTimeoutId){try{window.clearTimeout(ynfbpp.mouseOverTimeoutId);ynfbpp.mouseOverTimeoutId=0;if(ynfbpp.timeoutId){try{window.clearTimeout(ynfbpp.timeoutId);ynfbpp.timeoutId=0;}catch(e){}}}catch(e){}}
if($flag==0){ynfbpp.data.match_id=0;ynfbpp.mouseOverTimeoutId=window.setTimeout('ynfbpp.closePopup()',ynfbpp.timeoutClose);}
return ynfbpp;},closePopup:function(){box=ynfbpp.getBox();if(box&&box.style)
{box.style.display='none';}
ynfbpp.isShowing=0;return ynfbpp;},resetPosition:function(flag){ynfbpp.isShowing=1;var box=ynfbpp.getBox();var ele=ynfbpp.ele;if(!ele){return;}
var pos={x:$(ele).offset().left,y:$(ele).offset().top};var size={x:$(ele).width(),y:$(ele).height()};if(pos==null||pos==undefined){return;}
if(ynfbpp.dir.cy>500){box.style.top=pos.y+'px';$(box).removeClass('yn_profilepopup_dialog_dir_down').addClass('yn_profilepopup_dialog_dir_up');}else{box.style.top=pos.y+size.y+'px';$(box).removeClass('yn_profilepopup_dialog_dir_up').addClass('yn_profilepopup_dialog_dir_down');}
if($('html').attr('dir')=='ltr')
{if($(window).width()-ynfbpp.dir.cx>425){$(box).removeClass('yn_profilepopup_dialog_dir_left').addClass('yn_profilepopup_dialog_dir_right');var px=size.x>245?ynfbpp.dir.cx:pos.x;box.style.left=px+'px';}else{$(box).removeClass('yn_profilepopup_dialog_dir_right').addClass('yn_profilepopup_dialog_dir_left');var px=size.x>245?ynfbpp.dir.cx:(pos.x+size.x);box.style.left=px+'px';}}else
{if(ynfbpp.dir.cx<376){$(box).removeClass('yn_profilepopup_dialog_dir_left').addClass('yn_profilepopup_dialog_dir_right');var px=size.x>245?ynfbpp.dir.cx:pos.x;box.style.left=px+'px';}else{var px=size.x>245?ynfbpp.dir.cx:(pos.x+size.x);box.style.left=px+'px';$(box).removeClass('yn_profilepopup_dialog_dir_right').addClass('yn_profilepopup_dialog_dir_left');}}
if(flag){box.style.display='block';}},showPopup:function(json){if(json==null||json==undefined){return;}
if(json.match_type!=ynfbpp.data.match_type||json.match_id!=ynfbpp.data.match_id){ynfbpp.closePopup();return;}
ynfbpp.resetPosition(1);var box=ynfbpp.getBox();ynfbpp.updateBoxContent(json.content);if(ynfbpp.isNotIgnoreClassesOfGrandElement===true){$('#js_global_tooltip').css('display','none');}
box.style.display='block';return ynfbpp;},unfriend:function(sUserID){if(confirm(oTranslations['core.are_you_sure'])){$.ajaxCall('profilepopup.unfriend','id='+sUserID);}
return false;},refreshPage:function(url){if(url===null||url===undefined){window.location.href=window.location.href;}else{window.location.href=url;}},leaveEvent:function(id,confirmText){if(confirm(confirmText)){$.ajaxCall('profilepopup.leaveEvent','id='+id);}},leaveFEvent:function(id,confirmText){if(confirm(confirmText)){$.ajaxCall('profilepopup.leaveFEvent','id='+id);}},getBox:function(){if(ynfbpp.box){return ynfbpp.box;}
var ct=document.createElement('DIV');ct.setAttribute('id','yn_profilepopup_dialog');var html='<div class="yn_profilepopup_dialog_overlay" id="ynfbppUiOverlay" onmouseover="ynfbpp.resetTimeout(1)" onmouseout="ynfbpp.resetTimeout(0)">\
      <div class="yn_profilepopup_overlay_content" id="ynfbppUiOverlayContent">\
      </div> \
      <i class="yn_profilepopup_contextual_dialog_arrow"></i> \
     </div> \
  ';ct.innerHTML=html;var body=document.getElementsByTagName('body')[0];body.appendChild(ct);$(ct).addClass('yn_profilepopup_dialog');if($('#yn_profilepopup_dialog')[0]){ynfbpp.box=$('#yn_profilepopup_dialog')[0];}
if($('#ynfbppUiOverlayContent')[0]){ynfbpp.boxContent=$('#ynfbppUiOverlayContent')[0];}
return ynfbpp.box;},triggerEventOfActionsNotCallCoreLoadInit:function(){if($('#js_shoutbox_input').length){$('#js_shoutbox_input').focus(function(){ynfbpp.bootSpecificID('js_block_border_shoutbox_display');});}},bootSpecificID:function(id){if(window.screen&&window.screen.width<800)
{return;}
if(window.parent!=window){return;}
if(document.location.href.search('admincp')>0){if(ynfbpp.enabledAdmin==false){return;}else{}}
if($('#'+id).length){$('#'+id).find('a').mouseover(function(event){ynfbpp.check(event);});if(ynfbpp.enableThumb){$('#'+id).find('img').mouseover(function(event){ynfbpp.check(event);});}}},isRewriteData:function(name){if(undefined!=ynfbpp.rewriteData&&null!=ynfbpp.rewriteData&&name.length>0){var len=ynfbpp.rewriteData.length;var i=0;for(i=0;i<len;i++){if(name==ynfbpp.rewriteData[i].replacement){return true;}}}
return false;}};ynfbpp.pt=[function(href,a)
{var sYNBaseUrl=getParam('sJsHome');var idx=0;var match=null;var reg=null;var username='';var iAdminUser=false;if(href.search(/index.php\?do=/i)>0)
{match=href.match(/\/index\.php\?do\=\/(\w+\-\d+)(\/)?/i);if(match!=null&&match!=undefined)
{username=trim(match[1],'/');reg=new RegExp("profile-([^\-]+)$","i");match=href.match(reg);if(match!=undefined&&match!=null)
{if(isNaN(trim(match[1],'/'))===false)
{if(ynfbpp.isRewriteData(username)==true){return false;}else{return{match_id:decodeURIComponent(trim(match[1],'/')),match_name:decodeURIComponent(username),match_type:'user'};}}}}else
{var tail=trim(sYNBaseUrl,'/')+'/index.php?do=/';tail=href.substring(tail.length,href.length);username=tail.substring(0,tail.indexOf('/'));username=trim(username,'/');tail=trim(tail,'/');if(username!==undefined&&username!==null&&username!=''&&username.length>0)
{if(tail.indexOf('/')>0){iAdminUser=false;}else{iAdminUser=true;}
if(isModule(username)===true)
{iAdminUser=false;}
if(iAdminUser===true)
{if(ynfbpp.isRewriteData(username)==true){return false;}else{return{match_id:'true',match_name:decodeURIComponent(username),match_type:'user'};}}}}}else if(sYNBaseUrl.length>0&&href.search(sYNBaseUrl)>=0)
{var tail=href.substring(sYNBaseUrl.length,href.length);reg=new RegExp("profile-([^\-]+)$","i");var match=tail.match(reg);if(match!=undefined&&match!=null){if(isNaN(trim(match[1],'/'))===false)
{if(ynfbpp.isRewriteData(trim(tail,'/'))==true){return false;}else{return{match_id:decodeURIComponent(trim(match[1],'/')),match_name:decodeURIComponent(trim(tail,'/')),match_type:'user'};}}}else
{username=tail.substring(0,tail.indexOf('/'));username=trim(username,'/');tail=trim(tail,'/');if(username!==undefined&&username!==null&&username!=''&&username.length>0)
{if(tail.indexOf('/')>0){iAdminUser=false;}else{iAdminUser=true;}
if(isModule(username)===true)
{iAdminUser=false;}
if(iAdminUser===true)
{if(ynfbpp.isRewriteData(username)==true){return false;}else{return{match_id:'true',match_name:decodeURIComponent(username),match_type:'user'};}}}}}
return false;},function(href,a)
{var match=href.match(/\/pages\/(\d+)(\/)?/i);if(match!=undefined&&match!=null){var sYNBaseUrl=getParam('sJsHome');var tail=href.substring(sYNBaseUrl.length,href.length);if(tail!==undefined&&tail!==null&&trim(tail,'/').search('pages')>=0)
{return{match_id:decodeURIComponent(trim(match[1],'/')),match_name:'',match_type:'page'};}}
return false;},function(href,a)
{if(href.indexOf('file/pic')>0){return false;}
var match=href.match(/\/event\/(\d+)(\/)?/i);if(match!=undefined&&match!=null){var sYNBaseUrl=getParam('sJsHome');var tail=href.substring(sYNBaseUrl.length,href.length);if(tail!==undefined&&tail!==null&&trim(tail,'/').search('event')>=0)
{return{match_id:decodeURIComponent(trim(match[1],'/')),match_name:'',match_type:'event'};}}
return false;},function(href,a)
{if(href.indexOf('file/pic')>0){return false;}
var match=href.match(/\/fevent\/(\d+)(\/)?/i);if(match!=undefined&&match!=null){var sYNBaseUrl=getParam('sJsHome');var tail=href.substring(sYNBaseUrl.length,href.length);if(tail!==undefined&&tail!==null&&trim(tail,'/').search('fevent')>=0)
{return{match_id:decodeURIComponent(trim(match[1],'/')),match_name:'',match_type:'fevent'};}}
return false;}];$Behavior.readyBootPopup=function(){ynfbpp.init();ynfbpp.boot();ynfbpp.clearCached();ynfbpp.triggerEventOfActionsNotCallCoreLoadInit();}
 /* module/wall/static/jscript/wall.js */var $sFormAjaxRequest=null;var $bButtonSubmitActive=true;var $ActivityFeedCompleted={};var $sCurrentSectionDefaultPhrase=null;var $sCssHeight='40px';var $sCustomPhrase=null;var $sCurrentForm=null;var $sStatusUpdateValue=null;var $iReloadIteration=0;var $oLastFormSubmit=null;var bCheckUrlCheck=false;var bCheckUrlForceAdd=false;var aUrlChecked={};var wallFillterViewId='all';function getSocialStreamFeeds(){$('.socialstream_get_feeds_link').hide(0,function(){$('.socialstream_get_feeds_img').show();});$.ajaxCall('socialstream.getFeeds');}
$Core.isInView=function(elem){if(!$Core.exists(elem)){return false;}
var docViewTop=$(window).scrollTop();var docViewBottom=docViewTop+$(window).height();var elemTop=$(elem).offset().top;var elemBottom=elemTop+$(elem).height();return((docViewTop<elemTop)&&(docViewBottom>elemBottom));}
$Core.resetActivityFeedForm=function()
{$('.activity_feed_form_attach li a').removeClass('active');$('.activity_feed_form_attach li a:first').addClass('active');$('.global_attachment_holder_section').hide();$('#global_attachment_status').show();bCheckUrlCheck=false;aUrlChecked={};$('._uiMentionHighlighter').html('');$('.activity_feed_form_holder ._uiMentionTypeahead').val('');$('.activity_feed_form_button_status_info').hide();$('.activity_feed_form_button_status_info textarea').val('');$Core.resetActivityFeedErrorMessage();$sFormAjaxRequest=$('.activity_feed_form_attach li a.active').find('.activity_feed_link_form_ajax').html();$Core.activityFeedProcess(false);$('.js_share_connection').val('0');$('.feed_share_on_item a').removeClass('active');$.each($ActivityFeedCompleted,function(){this(this);});$('#js_add_location, #js_location_input, #js_location_feedback').hide();$('.activity_feed_form_button_position').show();$('#hdn_location_name, #val_location_name ,#val_location_latlng').val('');$('#btn_display_check_in').removeClass('is_active');}
$Core.resetActivityFeedErrorMessage=function(){$('#activity_feed_upload_error').hide();$('#activity_feed_upload_error_message').html('');}
$Core.resetActivityFeedError=function(sMsg){$('.activity_feed_form_share_process').hide();$('.activity_feed_form_button .button').removeClass('button_not_active');$bButtonSubmitActive=true;$('#activity_feed_upload_error').show();$('#activity_feed_upload_error_message').html(sMsg);}
$Core.activityFeedProcess=function($bShow){if($bShow){$bButtonSubmitActive=false;$('.activity_feed_form_share_process').show();$('.activity_feed_form_button .button').addClass('button_not_active');}else{$bButtonSubmitActive=true;$('#activity_feed_upload_error').hide();$('.activity_feed_form_share_process').hide();$('.activity_feed_form_button .button').removeClass('button_not_active');$('.egift_wrapper').hide();}}
$Core.addNewPollOption=function(){$('.js_poll_feed_answer').append('<li><input type="text" name="val[answer][][answer]" value="" size="30" class="js_feed_poll_answer v_middle" /></li>');return false;}
$Core.forceLoadOnFeed=function(){if('search'==oCore['core.section_module'].toLowerCase()){return false;}
if($iReloadIteration>=2){return;}
if(!$Core.exists('#js_feed_pass_info')){return;}
$iReloadIteration++;$('#feed_view_more_loader').show();$('.global_view_more').hide();setTimeout("$.ajaxCall('wall.viewMore', $('#js_feed_pass_info').html().replace(/&amp;/g, '&') + '&iteration="+$iReloadIteration+'&viewId='+wallFillterViewId+"', 'GET');",1000);}
$Core.handlePasteInFeed=function(){}
$Core.cancelParseLinkInFeed=function(){$('#js_preview_link_attachment_custom_form_sub').remove();$('#js_status_attachment_link_cancel').hide();bCheckUrlCheck=false;}
$Core.wallHandlePasteInLink=function(input,oParam){var val=$("#js_global_attach_value").val();if(val==''||val=='http://'){$("#js_global_attach_value").val(oParam.url);}}
$Core.wallHandlePasteInFeed=function(input,oParam){var url=oParam.url;if(typeof aUrlChecked[url]!='undefined'){return;}
aUrlChecked[url]=1;bCheckUrlCheck=true;$('.activity_feed_form_share_process').show();$(input).closest('.global_attachment_holder_section').find('.js_preview_link_attachment_custom_form').remove();$(input).closest('.global_attachment_holder_section').prepend('<div id="js_preview_link_attachment_custom_form_sub" class="js_preview_link_attachment_custom_form"></div>');$Core.ajax('wall.preview',{type:'POST',params:{'no_page_update':'1',value:oParam.url},success:function($sOutput){bCheckUrlCheck=false;if(substr($sOutput,0,1)=='{'){var $oOutput=$.parseJSON($sOutput);$Core.resetActivityFeedError($oOutput['error']);$bButtonSubmitActive=false;$('.activity_feed_form_button .button').addClass('button_not_active');}else{bCheckUrlForceAdd=true;$Core.activityFeedProcess(false);$('.activity_feed_form_share_process').hide();$('#js_status_attachment_link_cancel').show();$('#js_global_attach_value').val(url);$('#js_preview_link_attachment_custom_form_sub').html($sOutput);}}});}
$Behavior.activityFeedAttachLink=function(){$('#js_global_attach_link').click(function(){$Core.activityFeedProcess(true);$Core.ajax('wall.preview',{params:{'no_page_update':'1',value:$('#js_global_attach_value').val()},type:'POST',success:function($sOutput){$('#js_global_attachment_link_cancel').show();bCheckUrlCheck=false;if(substr($sOutput,0,1)=='{'){var $oOutput=$.parseJSON($sOutput);$Core.resetActivityFeedError($oOutput['error']);$bButtonSubmitActive=false;$('.activity_feed_form_button .button').addClass('button_not_active');}else{$Core.activityFeedProcess(false);$('#js_preview_link_attachment').html($sOutput);$('#global_attachment_link_holder').hide();}}});});}
$Behavior.activityFeedProcess=function(){if(!$Core.exists('#js_feed_content')){$iReloadIteration=0;return;}
if($Core.exists('.global_view_more')){if($Core.isInView('.global_view_more')){$Core.forceLoadOnFeed();}
$(window).scroll(function(){if($Core.isInView('.global_view_more')){$Core.forceLoadOnFeed();}});}
$sFormAjaxRequest=$('.activity_feed_form_attach li a.active').find('.activity_feed_link_form_ajax').html();if(typeof Plugin_sFormAjaxRequest=='function'){Plugin_sFormAjaxRequest();}
if($Core.exists('.profile_timeline_header')){$(window).scroll(function(){if(isScrolledIntoView('.profile_timeline_header')){$('.timeline_main_menu').removeClass('timeline_main_menu_fixed');$('#timeline_dates').removeClass('timeline_dates_fixed');}else{if(!$('.timeline_main_menu').hasClass('timeline_main_menu_fixed')){$('.timeline_main_menu').addClass('timeline_main_menu_fixed');if($('#content').height()>600){$('#timeline_dates').addClass('timeline_dates_fixed');}}}});}
$('#global_attachment_status textarea').focus(function(){if($(this).val()==$('#global_attachment_status_value').html()){$(this).val('');$('.activity_feed_form_button').show();$(this).addClass('focus');$('.activity_feed_form_button_status_info textarea').addClass('focus');}});$('.activity_feed_form_button_status_info textarea').focus(function(){var $sDefaultValue=$(this).val();var $bIsDefault=true;$('.activity_feed_extra_info').each(function(){if($(this).html()==$sDefaultValue){$bIsDefault=false;return false;}});if(($('#global_attachment_status textarea').val()==$('#global_attachment_status_value').html()&&empty($sDefaultValue))||!$bIsDefault){$(this).val('');$(this).addClass('focus');$('#global_attachment_status textarea').addClass('focus');}});$('#js_activity_feed_form').submit(function(){if($sCurrentForm=='global_attachment_status'){var oStatusUpdateTextareaFilled=$('#global_attachment_status textarea');if($sStatusUpdateValue==oStatusUpdateTextareaFilled.val()){oStatusUpdateTextareaFilled.val('');}}else{var oCustomTextareaFilled=$('.activity_feed_form_button_status_info textarea');if($sCustomPhrase==oCustomTextareaFilled.val()){oCustomTextareaFilled.val('');$('#input_hidden_user_info').val('');$('#input_hidden_user_status').val('');}}
if($bButtonSubmitActive===false){return false;}
$Core.activityFeedProcess(true);if($sFormAjaxRequest===null){return true;}
$('.js_no_feed_to_show').remove();if(bCheckUrlForceAdd){$('.activity_feed_form_button_status_info textarea').val($('#global_attachment_status textarea').val());$sFormAjaxRequest='link.addViaStatusUpdate';}
$(this).ajaxCall($sFormAjaxRequest);if(bCheckUrlForceAdd){$('#js_preview_link_attachment_custom_form_sub').remove();}
return false;});$('.activity_feed_form_attach li a').click(function(){$sCurrentForm=$(this).attr('rel');if($sCurrentForm=='view_more_link'){$('.view_more_drop').toggle();return false;}else{$('.view_more_drop').hide();}
$('#js_preview_link_attachment_custom_form_sub').remove();$('#activity_feed_upload_error').hide();$('.global_attachment_holder_section').hide();$('.activity_feed_form_attach li a').removeClass('active');$(this).addClass('active');if($(this).find('.activity_feed_link_form').length>0){$('#js_activity_feed_form').attr('action',$(this).find('.activity_feed_link_form').html()).attr('target','js_activity_feed_iframe_loader');$sFormAjaxRequest=null;if(empty($('.activity_feed_form_iframe').html())){$('.activity_feed_form_iframe').html('<iframe id="js_activity_feed_iframe_loader" name="js_activity_feed_iframe_loader" height="200" width="500" frameborder="1" style="display:none;"></iframe>');}}else{$sFormAjaxRequest=$(this).find('.activity_feed_link_form_ajax').html();}
$('#'+$(this).attr('rel')).show();$('.activity_feed_form_holder_attach').show();$('.activity_feed_form_button').show();var $oStatusUpdateTextarea=$('#global_attachment_status textarea');var $sStatusUpdateTextarea=$oStatusUpdateTextarea.val();$sStatusUpdateValue=$('#global_attachment_status_value').html();var $oCustomTextarea=$('.activity_feed_form_button_status_info textarea');var $sCustomTextarea=$oCustomTextarea.val();$sCustomPhrase=$(this).find('.activity_feed_extra_info').html();var $bHasDefaultValue=false;$('.activity_feed_extra_info').each(function(){if($(this).html()==$sCustomTextarea){$bHasDefaultValue=true;return false;}});if($(this).attr('rel')!='global_attachment_status'){$('.activity_feed_form_button_status_info').show();if((empty($sCustomTextarea)&&($sStatusUpdateTextarea==$sStatusUpdateValue||empty($sStatusUpdateTextarea)))||($sStatusUpdateTextarea==$sStatusUpdateValue&&$bHasDefaultValue)||(!$bButtonSubmitActive&&$bHasDefaultValue)){$oCustomTextarea.val($sCustomPhrase).css({height:$sCssHeight});}else if($sStatusUpdateTextarea!=$sStatusUpdateValue&&$bButtonSubmitActive&&!empty($sStatusUpdateTextarea)){$oCustomTextarea.val($sStatusUpdateTextarea);}
$('.activity_feed_form_button .button').addClass('button_not_active');$bButtonSubmitActive=false;}else{$('.activity_feed_form_button_status_info').hide();$('.activity_feed_form_button .button').removeClass('button_not_active');if(!$bHasDefaultValue&&!empty($sCustomTextarea)){$oStatusUpdateTextarea.val($sCustomTextarea);}else if($bHasDefaultValue&&empty($sStatusUpdateTextarea)){$oStatusUpdateTextarea.val($sStatusUpdateValue).css({height:$sCssHeight});}
$bButtonSubmitActive=true;}
if($(this).hasClass('no_text_input')){$('.activity_feed_form_button_status_info').hide();}
if($(this).attr('rel')=='global_attachment_photo'){if(((navigator.userAgent.match(/iPhone/i))||(navigator.userAgent.match(/iPod/i))||(navigator.userAgent.match(/iPad/i)))){$('#js_piccup_upload').remove();$('.activity_feed_form_button .button').hide().after('<div id="js_piccup_upload"><input type="button" name="Filedata" id="Filedata" value="Choose photo"></div>');mobileInit();}}else{$('.activity_feed_form_button .button').show();$('#js_piccup_upload').hide();}
return false;});}
$Behavior.activityFeedLoader=function(){$('.js_feed_entry_add_comment').click(function(){$('.js_comment_feed_textarea').each(function(){if($(this).val()==$('.js_comment_feed_value').html()){$(this).removeClass('js_comment_feed_textarea_focus');$(this).val($('.js_comment_feed_value').html());$(this).closest('._uiCommentComposer').find('._uiMentionHidden').val('');}
$(this).parents('.comment_mini').find('.feed_comment_buttons_wrap').hide();});$(this).parents('.js_parent_feed_entry:first').find('.comment_mini_content_holder').show();$(this).parents('.js_parent_feed_entry:first').find('.feed_comment_buttons_wrap').show();if($(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea').val()==$('.js_comment_feed_value').html()){$(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea').val('');}
$(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea').focus().addClass('js_comment_feed_textarea_focus');$(this).parents('.js_parent_feed_entry:first').find('.comment_mini_textarea_holder').addClass('comment_mini_content');$(this).parents('.js_parent_feed_entry:first').find('.js_feed_comment_form').find('.comment_mini_image').show();var iTotalComments=0;$(this).parents('.js_parent_feed_entry:first').find('.js_mini_feed_comment').each(function(){iTotalComments++;});if(iTotalComments>2){$.scrollTo($(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea_browse:first'),340);}
return false;});$('.js_comment_feed_textarea').click(function(){$Core.commentFeedTextareaClick(this);});$('#js_captcha_load_for_check_submit').submit(function(){if(function_exists(''+Editor.sEditor+'_wysiwyg_feed_comment_form')){eval(''+Editor.sEditor+'_wysiwyg_feed_comment_form(this);');}
$oLastFormSubmit.parent().parent().find('.js_feed_comment_process_form:first').show();$(this).ajaxCall('comment.add',$oLastFormSubmit.getForm());return false;});$('.js_comment_feed_form').submit(function(){if($Core.exists('#js_captcha_load_for_check')){$('#js_captcha_load_for_check').css({top:getPageScroll()[1]+(getPageHeight()/5),left:'50%','margin-left':'-'+(($('#js_captcha_load_for_check').width()/2)+12)+'px',display:'block'});$oLastFormSubmit=$(this);return false;}
if(function_exists(''+Editor.sEditor+'_wysiwyg_feed_comment_form')){eval(''+Editor.sEditor+'_wysiwyg_feed_comment_form(this);');}
$(this).parent().parent().find('.js_feed_comment_process_form:first').show();var el=$(this).find('._uiMentionHidden');if(el.val()==$('.js_comment_feed_value:first').text()){el.val('');}
$(this).ajaxCall('comment.add');return false;});$('.js_comment_feed_new_reply').click(function(){var oParent=$(this).parents('.js_mini_feed_comment:first').find('.js_comment_form_holder:first');if((Editor.sEditor=='tiny_mce'||Editor.sEditor=='tinymce')&&isset(tinyMCE)&&isset(tinyMCE.activeEditor)){$('.js_comment_feed_form').find('.js_feed_comment_parent_id:first').val($(this).attr('rel'));tinyMCE.activeEditor.focus();if(typeof($.scrollTo)=='function'){$.scrollTo('.js_comment_feed_form',800);}
return false;}
oParent.html($('.js_feed_comment_form').html());oParent.find('.js_feed_comment_parent_id:first').val($(this).attr('rel'));oParent.find('.js_comment_feed_textarea:first').focus();$Core.commentFeedTextareaClick(oParent.find('.js_comment_feed_textarea:first'));$Core.loadInit();return false;});$('.comment_mini').hover(function(){$('.feed_comment_delete_link').hide();$(this).find('.feed_comment_delete_link:first').show();},function(){$('.feed_comment_delete_link').hide();});}
$Core.commentFeedTextareaClick=function($oObj)
{if($($oObj).val()==$('.js_comment_feed_value').html()){$($oObj).val('');$($oObj).closest('._uiCommentComposer').find('._uiMentionHidden').val('');}
$($oObj).addClass('js_comment_feed_textarea_focus').addClass('is_focus');$($oObj).parents('.comment_mini').find('.feed_comment_buttons_wrap:first').show();$($oObj).closest('.js_feed_comment_form').find('.comment_mini_textarea_holder:first').addClass('comment_mini_content');$($oObj).closest('.js_feed_comment_form').find('.comment_mini_image:first').show();}
$ActivityFeedCompleted.link=function(){$bButtonSubmitActive=true;$('#global_attachment_link_holder').show();$('.activity_feed_form_button .button').removeClass('button_not_active');$('#js_preview_link_attachment').html('');$('#js_global_attach_value').val('http://');}
$ActivityFeedCompleted.photo=function(){$bButtonSubmitActive=true;$('#global_attachment_photo_file_input').val('');}
var sToReplace='';$Core.createEmotions=function(wraper){var len=Typeahead.emotions.length;if(!len){return;}
if(!wraper.find('.div_emotions_wraper').length){var list1=$('<div class="div_emotions_wraper">').appendTo(wraper);list1.html('<img src="'+oParams.sJsHome+'file/pic/emoticon/default/happy.png" class="v_middle" \="" />');var list2=$('<div class="div_emotions_list">').appendTo(list1);var html='';for(var i=0;i<len;++i)
{var obj=Typeahead.emotions[i];html+='<span title="'+obj.title+'" icon="'+obj.text+'" class="emotion_items">'+'<img width="16" height="16" src="SJSHOME/file/pic/emoticon/PACKAGE/IMAGE" text-id="TEXT" />'.replace('SJSHOME',oParams.sJsHome).replace('IMAGE',obj.image).replace('PACKAGE',obj.package_path).replace('TITLE',obj.title).replace('TEXT',obj.text)+'</span>';}
list2.html(html);}}
$Behavior.tagger=function(){if($.isEmptyObject(Typeahead.data))
{$.ajaxCall('wall.getFriendsData');}
$Core.createEmotions($('#user_status_area'));$Core.createEmotions($('#user_info_area'));$('.js_feed_comment_process_form').hide();$('#user_status_area .emotion_items').bind('click',function(){var ele=$(this);var text=ele.attr('icon');var textarea=ele.closest('._uiPostComposer').find('textarea');textarea.trigger('focus');var caret=textarea.caret().start;var val=textarea.val();val=val.substr(0,caret)+text+val.substr(caret);textarea.val(val);textarea.trigger('changed');});$('#user_info_area .emotion_items').bind('click',function(){var ele=$(this);var text=ele.attr('icon');var textarea=ele.closest('._uiPostComposer').find('textarea');var caret=textarea.caret().start;textarea.trigger('focus');var val=textarea.val();val=val.substr(0,caret)+text+val.substr(caret);textarea.val(val);textarea.trigger('changed');});$('#js_global_attachment_link_cancel').bind('click',function(){$('#js_global_attachment_link_cancel').hide();})
if($('.textarea_post_user_status').length>=1){$.each($('.textarea_post_user_status'),function(key,value){Typeahead.create($(value),{minHeight:40,deltaHeight:20,tagsKey:'user_status',onParseLink:$Core.wallHandlePasteInFeed});});}
if($('#activity_feed_textarea_status_info').length){Typeahead.create($('#activity_feed_textarea_status_info'),{minHeight:40,deltaHeight:20,tagsKey:'user_status',onParseLink:$Core.wallHandlePasteInLink});};function decorateCommentMini(ele){var t='\x3Cdiv class=\"_uiCommentComposer\"\x3E\n\t\x3Cdiv class=\"_uiMentionTypeAheadContainer\"\x3E\n\t\t\x3Cdiv class=\"_uiMentionHighlighterArea _uiCommentHighlighterArea\"\x3E\n\t\t\t\x3Cdiv class=\"innerWrap\"\x3E\n\t\t\t\t\x3Cspan class=\"_uiMentionHighlighter\" style=\"\"\x3E  \x3C\x2Fspan\x3E\n\t\t\t\t\x3C\x2Fdiv\x3E\n\t\t\t\x3C\x2Fdiv\x3E\n\t\t\x3Cdiv class=\"_uiTypeaheaMentiondArea\"\x3E\n\t\t\x3Cdiv class=\"innerWrap\"\x3E\n\t\t\x3Ctextarea cols=\"60\" rows=\"1\" name=\"val[plain_text]\" class=\"js_comment_feed_textarea _uiMentionTypeahead text js_comment_feed_textarea_focus\" id=\"js_feed_comment_form_textarea_ID\" style=\"\"\x3E{adding_your_comment}\x3C\x2Ftextarea\x3E\n\t\t\t\x3Cdiv style=\"position:absolute;\" class=\"js_feed_comment_process_form\"\x3E{writing_your_comment}\x3Cimg src=\"[CORE_URL]theme\x2Ffrontend\x2Fdefault\x2Fstyle\x2Fdefault\x2Fimage\x2Fajax\x2Fadd.gif\" alt=\"\"\x3E\x3C\x2Fdiv\x3E\n\t\t\x3C\x2Fdiv\x3E\n\t\t\x3C\x2Fdiv\x3E\n\t\t\x3Cinput type=\"hidden\" name=\"val[text]\" class=\"_uiMentionHidden\" value=\"\"\x3E\n\t\x3C\x2Fdiv\x3E\n\x3C\x2Fdiv\x3E';t=t.replace('{adding_your_comment}',$('.js_comment_feed_value:first').text());var id=ele.attr('id').replace('js_feed_comment_form_textarea_','').replace(/\s+/m,'');var newId='js_feed_comment_form_textarea_'+id;t=t.replace('js_feed_comment_form_textarea_ID',newId).replace('[CORE_URL]',oParams.sJsHome);ele.parent().append(t);ele.remove();return newId;}
if($('.js_comment_feed_textarea').length){$.each($('.js_comment_feed_textarea'),function(key,value){var ele=$(value);if(!ele.hasClass('_uiMentionTypeahead')){var newId=decorateCommentMini(ele);value=$('#'+newId);$(value).bind('click',function(evt){$Core.commentFeedTextareaClick($(this));});}
Typeahead.create($(value),{minHeight:20,deltaHeight:0});});}
return;};var Typeahead={cached:{},data:{},emotions:[],init:function(ele,opts){if(ele.attr('typeahead-init')){return;}
ele.attr('typeahead-init','1');Typeahead.create(ele,opts);},setData:function(obj)
{Typeahead.data=obj;},sharedTags:{},create:function(ele,newOpts){var time=20;var timeId=0;var tokenPos=0;var tokenStr='';var input=$(ele);var container=input.closest('._uiMentionTypeAheadContainer');var hidden=container.find('._uiMentionHidden');var hightlight=container.find('._uiMentionHighlighter');var isHide=true;var ul=0;var reg2=/(\[x\=\d+\])([^\[]+)(\[\/x\])/gi;var linkReg=/(http|https):\/\/(\w|\-|\_)+(\.\w{2,})+([\w#!:.?+=&%@!\-\/])*(\s|\n)/i;var opts=$.extend({min:0,max:10,onSubmit:false,minHeight:0,deltaHeight:0,remoteUrl:false,maxDisplay:10,autoParseLink:true,onParseLink:false,tagsKey:false,},newOpts);var tagsKey='all';if(opts.tagsKey==false){tagsKey=input.attr('id');}
if(typeof Typeahead.sharedTags[tagsKey]=='undefined'){Typeahead.sharedTags[tagsKey]={};}
function escape(text){return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&");}
function filterData(keyword){var rows=new Array();var length=Typeahead.data.length;var taken=keyword.substr(1).replace(/$/g,'').split(/\s+/g);for(var i=0;i<taken.length;++i){taken[i]=escape(taken[i]);}
var num=taken.length;var reg=new RegExp('(^|\\s+)('+taken.join('|')+')','gi');for(var i=0;i<length&&rows.length<=30;i++){var obj=Typeahead.data[i];var match=obj.text.match(reg);if(match&&match.length>=num){rows.push(obj);}}
var ret={'q':keyword,'rows':rows}
return ret;}
function createUl(){ul=$('#_uiAcList');if(ul.length==0){ul=$('<ul class="_uiFlyLayer _uiAcList" id="_uiAcList">').appendTo('body');}}
function hideUl(){isHide=true;ul&&ul.hide();}
function showUl(){var offset=input.offset();ul.css({left:offset.left,top:offset.top+input.height(),width:input.width()}).show();isHide=false;}
function goUp(){var e=ul.find('li.active');e=e.prev();if(e.length==0){e=ul.find('li.item:first');}
ul.find('li').removeClass('active');e.addClass('active');}
function goDown(){var e=ul.find('li.active');e=e.next();if(e.length==0){e=ul.find('li.item:first');}
ul.find('li').removeClass('active');e.addClass('active');}
function getActiveLi(){if(isHide){return false;}
var e=ul.find('li.active');if(e.length==0){e=ul.find('.item:first');}
if(e.length==0){return false;}
return e;}
function createLi(obj){var html='<img class="lfloat mright5" width="32" height="32" src="'+obj.photo+'" />'+obj.text+'';var li=$('<li class="clearfix item">').appendTo(ul);li.html(html);li.bind('mousedown',function(evt){selectHandle(obj);}).bind('mouseover',function(evt){$(this).addClass('active');}).bind('mouseout',function(evt){$(this).removeClass('active');});}
function createList(data){createUl();ul.html('');Typeahead.cached[data.q]=data;if(data.q!=tokenStr){hideUl();return;}
var tags=Typeahead.sharedTags[tagsKey];var count=0;for(var i=0;i<data.rows.length;++i){var o=data.rows[i];var id=o.type+'.'+o.id;if(typeof tags[id]!='undefined')
continue;createLi(data.rows[i]);if(++count>opts.maxDisplay){break;};}
if(!count){hideUl();}else{showUl();}}
function processInput(){caretPos=input.caret().start;var val=input.val();val+=" ";var q=val.substr(0,caretPos);if(opts.onParseLink&&!bCheckUrlCheck){var match=val.match(linkReg);if(match){var url=match[0].replace(/\s|\n/g,'');input.trigger('parseLink',{'url':url});}}
tokenPos=q.lastIndexOf('@');if(tokenPos<0){return'';};q=q.substr(tokenPos,caretPos);if(q==tokenStr){return;}
tokenStr=q;if(tokenStr==''){return;}
if(tokenStr.length<opts.min||tokenStr.length>opts.max){hideUl();return;}
if(typeof Typeahead.cached[tokenStr]=='object'){createList(Typeahead.cached[tokenStr]);return;}
if(opts.remoteUrl){$.getJSON(opts.remoteUrl,{'q':tokenStr},createList);}else{createList(filterData(tokenStr));}}
var oldText='';function updateHidden(val){val=val.replace('﻿','');var html=val.replace(reg2,'<b>$2</b>').replace(/\n/gmi,'<br />')
hidden.val(val);hightlight.html(html+'  ');var newVal=val.replace(reg2,'$2﻿');var caret=input.caret().start;var height=hightlight.height();hightlight.css({width:input.width()});if(height>opts.minHeight){input.css({'height':height+opts.deltaHeight});}
if(oldText&&oldText!=newVal){input.val(newVal);}
oldText=newVal;}
var isProcessing=false;function processHidden(){if(isProcessing){return;}
isProcessing=true;var tags=Typeahead.sharedTags[tagsKey];var of=input.caret();var val=input.val();for(var i in tags){var tag=tags[i];var len=tag.text.length;var pos=val.search(tag.text);if(pos>-1){val=val.substr(0,pos)+tag.token+val.substr(pos+len);}else{delete(tags[i]);}}
Typeahead.sharedTags[tagsKey]=tags;updateHidden(val);var id=hidden.attr('id');if(null==id){}else if(id=='input_hidden_user_status'){$('#input_hidden_user_info').val(val);}else if(id=='input_hidden_user_info'){$('#input_hidden_user_status').val(val);}
isProcessing=false;}
function selectHandle(obj){hideUl();var pos1=tokenPos;var pos2=input.caret().start;var text=obj.text+'﻿';var newCaretPos=pos1+text.length+1;var val=input.val();val=val.substr(0,pos1)+text+' '+val.substr(pos2);input.val(val);input.caret(newCaretPos,newCaretPos);input.focus();var id=obj.type+'.'+obj.id;var token='[x='+obj.id+']'+obj.text+'[/x]';Typeahead.sharedTags[tagsKey][id]={'text':text,'text2':obj.text,'token':token};processHidden();}
function keyupHandle(evt){processInput();timeId=window.setTimeout(processHidden,time);}
function keydownHandle(evt){if(evt&&evt.keyCode){var keyCode=evt.keyCode
if(!evt.shiftKey&&!evt.altKey&&!evt.ctrlKey){if(false==isHide){if(38==keyCode){evt.preventDefault();goUp();}else if(40==keyCode){evt.preventDefault();goDown();}else if(13==keyCode||9==keyCode){evt.preventDefault();var li=getActiveLi();if(li){li.trigger('mousedown');}}else if(9==keyCode||27==keyCode){evt.preventDefault();hideUl();}else if(37==keyCode){}else if(39==keyCode){}}else{if(13==keyCode&&opts.onSubmit){evt.preventDefault();opts.onSubmit(input);}}
return;}
if(evt.ctrlKey&&(86==evt.keyCode||67==evt.keyCode)){}}}
function clickHandle(){ul&&hideUl();}
function parseLinkHandle(evt,param){opts.onParseLink(input,param);}
function parseLinkChanged(evt,param){attatchedLink=param.attatchedLink;}
function focus(evt){if(input.val()==input.attr('placeholder')){input.val('');}}
function changedHandle(){processHidden();}
hightlight.css({'width':input.width()});input.bind('keyup',keyupHandle).bind('keydown',keydownHandle).bind('paste',processHidden).bind('cut',processHidden).bind('parseLink',parseLinkHandle).bind('changed',changedHandle)
$(document).bind('click',clickHandle);window.setTimeout(processHidden,1000);}};
 /* module/wall/static/jscript/extra.js */function feed_filter(type,value,element){switch(type){case"type":$("#feed_type_id").val(value);$("#feed_type_id_label").html(element.innerHTML);break;case"limit":$("#feed_limit").val(value);$("#feed_limit_label").html(element.innerHTML);break;}
var viewId=$("#feed_type_id").val();wallFillterViewId=viewId;$('#js_feed_content').html('<div id="feed_filtering_animation">'+addImg+'</div>');setTimeout("doFilter('"+viewId+"')",100);}
function doFilter(viewId){$('#activity_feed_updates_link_holder').hide();$iReloadIteration=0;$.ajaxCall('wall.filterFeed','resettimeline=1&profile_user_id='+iProfileUserId+'&is_filter=1&viewId='+wallFillterViewId+'&userId='+$('#userId').val(),'GET');};function callDoFilter(viewId){if(undefined!=isDoFilterAdvWall&&null!=isDoFilterAdvWall&&isDoFilterAdvWall==false){isDoFilterAdvWall=true;doFilter(viewId);}}
function callReloadActivityFeed(){if(undefined!=isReloadActivityFeedAdvWall&&null!=isReloadActivityFeedAdvWall&&isReloadActivityFeedAdvWall==false){isReloadActivityFeedAdvWall=true;setTimeout("$.ajaxCall('wall.reloadActivityFeed', 'reload-ids=' + $Core.getCurrentFeedIds() + '&viewId=' + $('#feed_type_id').val(), 'GET');",2000);}}
 /* static/jscript/player/flowplayer/core.js */$Core.player={aParams:{},supportsVideo:function(){return!!document.createElement('video').canPlayType;},canPlayVideo:function(){var bCanPlay=false;if(this.supportsVideo()){var v=document.createElement('video');if(v.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"')){bCanPlay=true;p('Supports: video/mp4');}
else if(v.canPlayType('video/ogg; codecs="theora, vorbis"')){bCanPlay=true;p('Supports: video/ogg');}
else if(v.canPlayType('video/webm; codecs="vp8, vorbis"')){bCanPlay=true;p('Supports: video/webm');}}
return bCanPlay;},load:function(aParams)
{if(document.getElementById(aParams['id'])===null)
{return false;}
this.aParams=aParams;if(getParam('bUseHTML5Video')&&this.aParams['type']=='video'&&this.canPlayVideo()){var sHtml='';sHtml+='<video width="600" height="366" preload="auto" controls autoplay>';sHtml+='<source type="video/webm" src="'+aParams['play'].replace('.flv','.webm')+'">';sHtml+='<source type="video/mp4" src="'+aParams['play'].replace('.flv','.mp4')+'">';sHtml+='<source type="video/ogg" src="'+aParams['play'].replace('.flv','.ogg')+'">';sHtml+='</video>';$('#'+this.aParams['id']+'').html(sHtml);return this;}
var sCall='$f(\''+this.aParams['id']+'\', {src: \''+getParam('sJsStatic')+'jscript/player/flowplayer/flowplayer.swf\', wmode: \'transparent\', zIndex: -1},{';switch(this.aParams['type'])
{case'video':sCall+='clip: {';sCall+='url: \''+(getParam('bUseHTML5Video')?this.aParams['play'].replace('.flv','.mp4'):this.aParams['play'])+'\',';sCall+='autoBuffering: true,';if(isset(this.aParams['auto']))
{sCall+='autoPlay: '+this.aParams['auto'];}
sCall=rtrim(sCall,',');sCall+='}';break;case'music':sCall+='clip: {';if(!empty(this.aParams['play']))
{sCall+='url: \''+this.aParams['play']+'\',';}
if(isset(this.aParams['on_finish']))
{sCall+='onFinish: '+this.aParams['on_finish']+',';}
else
{if(this.aParams['playlist']!=undefined)
{sCall+='onFinish: function(clip){\n';sCall+='debug("Event onFinish triggered");';sCall+='var aPlaylist = {\n';for(oPlay in this.aParams['playlist'])
{sCall+='"'+this.aParams['playlist'][oPlay]+'" : \n{"iNext" : '+this.aParams['aNextSong'][oPlay]+', "sNextPath" : "'+this.aParams['playlist'][this.aParams['aNextSong'][oPlay]]+'"},';}
sCall=rtrim(sCall,',');sCall+='};';sCall+='\n$(".isSelected").removeClass("isSelected");';sCall+='\n$("#js_music_track_" + aPlaylist[clip.originalUrl]["iNext"]).addClass("isSelected");';sCall+='$f().unload();';sCall+='\n$Core.player.play("'+this.aParams['id']+'",aPlaylist[clip.originalUrl]["sNextPath"]);';sCall+='$.ajaxCall(\'music.play\', \'id=\' + aPlaylist[clip.originalUrl]["iNext"], \'GET\');';sCall+='},';}}
if(isset(this.aParams['on_start']))
{sCall+='onStart: '+this.aParams['on_start']+','}
sCall=rtrim(sCall,',');sCall+='},';sCall+='plugins: {';sCall+='controls: {fullscreen: false, height: 30, autoHide: false},';sCall+='audio: {';sCall+='url: \''+getParam('sJsStatic')+'jscript/player/flowplayer/flowplayer.audio.swf\'';sCall+='}';sCall+='}';break;default:p('Not a valid call.');break;}
sCall+='});';if(this.aParams['playlist']!=undefined)
{for(var iSong in this.aParams['playlist'])
{if(iSong!=undefined)
{sCall+='\n $Core.player.play("'+this.aParams['id']+'","'+this.aParams['playlist'][iSong]+'");';sCall+='\n$.ajaxCall(\'music.play\',"id='+iSong+'", "GET");';break;}}}
eval(sCall);return this;},play:function(sId,sPath)
{p('#'+sId+' Playing song: '+sPath);$('#'+sId).show();$f(sId).play(sPath);return false;}};
$Core.init();