<div id="yn_wookmark_main" >
	<ul id="yn_wookmark">

		{foreach from=$aPhotos item=aPhoto}
		<li>
			{img server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_240' max_width=150 max_height=240 title=$aPhoto.title class='yn_photo_image'}
			<p>
				{$aPhoto.photo_id} 
			</p>
		</li>
		{/foreach}
	</ul>
</div>

{literal}
<script type="text/javascript">
    $(document).ready(new function() {

      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#yn_wookmark_main'), // Optional, used for some extra CSS styling
        offset: 10, // Optional, the distance between grid items
        itemWidth: 150 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#yn_wookmark li');
      
      // Call the layout function.
      handler.wookmark(options);
      
      // Capture clicks on grid items.
      handler.click(function(){
        // Randomize the height of the clicked item.
        var newHeight = $('img', this).height() + Math.round(Math.random()*300+30);
        $(this).css('height', newHeight+'px');
        
        // Update the layout.
        handler.wookmark();
      });
    });
  </script>

{/literal}


<div id="temp-test">
</div>