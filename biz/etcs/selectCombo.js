/**
 * selectCombo for jQuery v 1.2
 *
 * Copyright (c) 2007 Shelane Enos
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * Initial base logic from Remy Sharp's blog: http://remysharp.com/2007/01/20/auto-populating-select-boxes-using-jquery-ajax/
 *
 * @example  $('#myselect').selectCombo('myurltoprocess.lasso?additionalparamtoserverifnecessary=myparam', '#mytargetselect', {hidetarget: false});
 *
 * Option: hidetarget - Allows you to override the default hide behavior if set to false.  Default true will hide your target select and its label until and option from your source select is selected.  Use this if your target select is not empty when the page loads and its values correspond to your default selected of your source select.
 *
 * Parameter sent to server is q
 *
 * Expected server response is JSON in this format: [{oV: 'myfirstvalue', oT: 'myfirsttext'}, {oV: 'mysecondvalue', oT: 'mysecondtext'}]
 *
 * Aug 30, 2007 - Removed console.log line - could cause issues in IE
 *
 * Aug 22, 2007 - Added option to set target options when the page loads (you may have a default value selected in the primary select that you want to "preload" options in your target)
 *					Clearing options of target before calling getJSON (if primary not same as target) so that if no results are returned target reflects no results of selected primary
 *						(if you want there to be something loaded as a place holder for the target, have your server return something like: [{oV: '', oT: '-No Option-'}]
 *					Changed check on hidetarget.  Instead of checking that the primary select has no selected value, will now check that the target has no value.
 *						If hidetarget == true, target will hide onload when no value in target and will hide if no options available on change
 *
 *
 *
 * May 24, 2007 - Added option for indicator display during ajax call (initially hides the indicator and displays onchange - hides again on callback of JSON request)
 *				use: $('#myselect').selectCombo('myurltoprocess.lasso', '#mytargetselect', {indicator: '#loading'});//where #loading would be the id of the indicator div, img, or whatever
 *				  Added check that if target ID is the same as the original source, it will override hidetarget setting and not hide target
 */
 (function($){
$.fn.selectCombo = function(url, target, settings){
var defaults = {hidetarget: true, indicator: false, pageload: false};
$.extend(defaults, settings);
return this.each(function(){
var qobj = this;
var targetlabel = target.replace(/#/, '');
	targetlabel = "label[for='" + targetlabel + "']";

if(defaults.indicator != false)
	$(defaults.indicator).hide();

hidetargetinfo = function(){
	if($(qobj).attr('id') != $(target).attr('id')){

		$(targetlabel).hide();
		$(target).hide();
	}
}
if(defaults.hidetarget && $(target).val() == '')
	hidetargetinfo();

loadOptions = function(){
	qval = $(qobj).val();
	if(defaults.indicator != false)
		$(defaults.indicator).show();
	if(defaults.hidetarget)
		hidetargetinfo();
	if($(qobj).attr('id') != $(target).attr('id'))
		$(target).empty();
	//alert(url +' = >'+ qval);
	$.post(url, {q: qval}, function(j){
		if(j.length > 0){
			$(target).html(j);

			$("option:first", target).attr("selected","selected");
			if(defaults.indicator != false)
				$(defaults.indicator).hide();
			$(targetlabel).show();
			$(target).show();
		}
	});//end JSON
}

$(this).change(loadOptions);//end change fn

if(defaults.pageload && $(qobj).val() != ''){
	loadOptions();
}

});//end return for each
}
})(jQuery);