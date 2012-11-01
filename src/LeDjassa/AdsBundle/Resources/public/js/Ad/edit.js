<script type="text/javascript">
	$(document).ready(function() {

			// manage picture upload
			var $containerFile = $('#picture_ads');
    		var $container = $('#ad_edit_picture_ads');
		
    		$('#add_picture').click(function() {
        		addPicture();
   			 });

			$('input[type=file]').live('change' ,function(){

				var fileFieldId = $(this).attr('id'),
					fileField 	= document.getElementById(fileFieldId),
					picture     = fileField.files[0];

			    if (picture.type.match('image.*') && (typeof window.FileReader !== 'undefined')) {
			       
			        // new instance FileReader for manage onload
			        reader = new FileReader();
			        reader.onload = function (event) {

				        var img = document.createElement('img');
				        img.src = event.target.result;
				        
				        var fileFieldIdSelector = '#'+fileFieldId;

				        // remove precedent picture load
				        $(fileFieldIdSelector).next('img').remove();

				        // display picture load
				        $(img).insertAfter(fileFieldIdSelector);
			        }
			   
			        reader.readAsDataURL(picture);   
			    }
			 });

    		function addPicture() {
        		var index = $container.children().length + $containerFile.children().length;
        		var $newContainerPicture = $container.attr('data-prototype').replace(/__name__/g, index);

        		var $removePicture = $('<a href="#">Supprimer cette photo</a>');

       			$containerFile.append(
           			 $($newContainerPicture).append($removePicture)
       			);

       			$removePicture.click(function(e) {
        			e.preventDefault();
			        $('#ad_edit_picture_ads_' + index).parent('div').remove();
			    });
  			}

  			// manage delete picture
			$(".picture_delete").focus(function () {
				$.ajax({
	        		type: 'POST',
	            	dataType: 'json',
	            	url:  '{{ path('picture_delete') }}',
	            	data: 'pictureId='+ $(this).attr('id'),
	            	success: function(response)
	            	{	
	      				$('#'+response).parent('li').remove();
	        	    },
				});
			});

			//manage submit form to prevent empty value in input file
			$('form').live('submit' ,function(){

				$.each($('input[type=file]'), function(index, inputFile) { 
					if ($(inputFile).attr('value') == '') {
				    	$(inputFile).attr('disabled', 'disabled');
					};

				});
			});
		})
	</script>