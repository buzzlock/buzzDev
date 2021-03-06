
var oRatingPosition = null;

$Core.rateForMusicSharing =
{
    aParams: {},
	
    bDisable: false,
	
    init: function(aParams)
    {
        this.aParams = aParams;
        
        if ($('.js_rating_value').length <= 0)
        {
            $('body').prepend('<div class="js_rating_value"></div>');				
        }
		
        $('#js_rate_temp_holder').remove();
				
        if (aParams['display'])
        {			
            $('#js_rating_holder_' + aParams['module'] + ' .js_rating_star').rating({
                callback: function(mValue, oLink){
                    $($(this).parents('form:first')).ajaxCall('musicsharing.ratingSongHTML5');
                },
                    
                focus: function(mValue, oLink){
                    $(oLink).attr('title', '');
			  			
                    if ($Core.rateForMusicSharing.bDisable === false)
                    {
                        var aParts = explode('|', $(this).val());
				  			
                        oRatingPosition = $(oLink).offset();
				  			
                        $('#js_rate_temp_holder').remove();
				  			
                        $('body').append('<div id="js_rate_temp_holder" style="display:none;">' + aParts[1] + '</div>');
				  			
                        $('.js_rating_value').html(aParts[1]).css({
                            'width': $('#js_rate_temp_holder').width() + 'px', 
                            'left': oRatingPosition.left + 'px', 
                            'top': (oRatingPosition.top - 30) + 'px'
                        }).show();
                    }
                },
                
                blur: function(mValue, oLink){
                    if ($Core.rateForMusicSharing.bDisable === false)
                    {
                        $('#js_rate_temp_holder').remove();
                        $('.js_rating_value').html('').hide();
                    }
                }
            });
        }
        else
        {		
            $('#js_rating_holder_' + aParams['module'] + ' .js_rating_star').rating({
                focus: function(mValue, oLink){			   			
                    oRatingPosition = $(oLink).offset();
			  			
                    $(oLink).attr('title', '');
			  			
                    $('#js_rate_temp_holder').remove();
			  			
                    $('body').append('<div id="js_rate_temp_holder" style="display:none;">' + aParams['error_message'] + '</div>');			  			
			  			
                    $('.js_rating_value').html('' + aParams['error_message'] + '</div>').css({
                        'width': $('#js_rate_temp_holder').width() + 'px', 
                        'left': oRatingPosition.left + 'px', 
                        'top': (oRatingPosition.top - 30) + 'px'
                    }).show();			   			
                },
                    
                blur: function(mValue, oLink){
                    $('#js_rate_temp_holder').remove();
                    $('.js_rating_value').html('').hide();			    		
                }
            });
	
            $('#js_rating_holder_' + aParams['module'] + ' .js_rating_star').rating('disable');
        }
    },
    
    rebuild: function(bHasVoted){
        var bStatus = '';
        
        if (bHasVoted == 'true')
        {
            $Core.rateForMusicSharing.bDisable = false;
            bStatus = 'enable';
        }
        else
        {
            $Core.rateForMusicSharing.bDisable = true;
            bStatus = 'disable';
        }
        
        $('#js_rating_holder_' + this.aParams['module'] + ' .js_rating_star').rating(bStatus);
    },
    
    success: function(){
        $('#js_rating_holder_' + this.aParams['module'] + ' .js_rating_star').rating('disable');
		
        this.bDisable = true;
    }
}