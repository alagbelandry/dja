<script type="text/javascript">
	$(document).ready(function() {

			// manage picture upload
    		var $container = $('#ad_picture_ads');
	
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

			//manage submit form to prevent empty value in input file
			$('form').live('submit' ,function(){

				$.each($('input[type=file]'), function(index, inputFile) { 
					if ($(inputFile).attr('value') == '') {
				    	$(inputFile).attr('disabled', 'disabled');
					};

				});
			});

    		function addPicture() {
        		var index = $container.children().length;
        		var $newContainerPicture = $container.attr('data-prototype').replace(/__name__/g, index);

        		var $removePicture = $('<a href="#">Supprimer cette photo</a>');

       			$container.append(
           			 $($newContainerPicture).append($removePicture)
       			);

       			$removePicture.click(function(e) {
        			e.preventDefault();
			        $('#ad_picture_ads_' + index).parent('div').remove();
			    });
  			}


			// manage autocomplete on quarter name field
			$("#ad_city_quarter_name").focus(function () {
				$.ajax({
	        		type: 'POST',
	            	dataType: 'json',
	            	url:  '{{ path('quarter_in_city_get') }}',
	            	data: 'idCity='+ $("select#ad_city_name option:selected").val(),
	            	success: function(response)
	            	{
	            		$("#ad_city_quarter_name").autocomplete({
							source: response
						});
	        	    },
				});
			});

			// manage display city list and quarter text
			var idArea = $("select#ad_city_area option:selected").val();
			if (!idArea) {
				hideCityAndQuarterField(idArea);
			} else {
				fillCityField(idArea);	
			};
			$("select#ad_city_area").change(function() {
				var idArea = $(this).val();
				if (!idArea) {
					hideCityAndQuarterField(idArea);
				} else {
					fillCityField(idArea);	
				}	
			});

		});
	
	/**
	 * [fillCityField Hydrate city field]
	 * @param  {[type]} $idArea [id area]
	 */
	function fillCityField(idArea) {
			// get list city
			$('select#ad_city_name, #ad_city_quarter_name').parent('div').show();
			$.ajax({
	        	type: 'POST',
	            dataType: 'json',
	            url:  '{{ path('city_in_area_get') }}',
	            data: 'areaId=' + idArea,
	            success: function(response)
	            {
	            	$('select#ad_city_name').find("option").remove();
	                $.each(response, function(i, item) {
	                $('#ad_city_name').append(new Option(item, i));
	             	});
	            },
			});

	}

	/**
	 * [hideCityAndQuarterField Hide city and quarter field depending arear value]
	 * @param  {[type]} $idArea [id area]
	 */
	function hideCityAndQuarterField($idArea) {
		$('select#ad_city_name, #ad_city_quarter_name').parent('div').hide();	
	}

</script>