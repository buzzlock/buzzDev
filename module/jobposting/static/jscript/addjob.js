function yncEditorClick()
{
	//	
	Editor.setId('skills');
	Editor.getEditors();
	
    $("#description").click(function() {
        Editor.setId('description');
    });
	
} 


$Behavior.yncInitAdd = function()
{
	yncEditorClick();
}
