<script type="text/javascript" src="http://ckeditor.com/apps/ckeditor/4.0/ckeditor.js"></script>
<script type="text/javascript">
	var controls = {
		 autoGrow_onStartup: true,
		 autoGrow_minHeight: 500,
	     toolbar : [
			['Source','-','Templates'],
			['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
			['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
			['Format'],
			['Maximize', 'ShowBlocks'],
			['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		
			['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
			['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
			['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
			['Link','Unlink','Anchor'],
			['Image','Flash','Table','HorizontalRule','SpecialChar','PageBreak']
		 ]
		 
	}
	

	CKEDITOR.replace('wysiwyg', controls);


	$(function(){
		$("#submit").click(function(){
			CKEDITOR.instances.wysiwyg.updateElement();
		});
	});
</script>