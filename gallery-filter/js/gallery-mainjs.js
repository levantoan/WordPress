(function($){
	//fix height bang nhau
	equalheight = function(container) {
		var currentTallest = 0, currentRowStart = 0, rowDivs = new Array(), $el, topPosition = 0;
		$(container).each(function() {
			$el = $(this);
			$($el).height('auto');
			topPostion = $el.position().top;

			if (currentRowStart != topPostion) {
				for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
					rowDivs[currentDiv].height(currentTallest);
				}
				rowDivs.length = 0; // empty the array
				currentRowStart = topPostion;
				currentTallest = $el.height();
				rowDivs.push($el);
			} else {
				rowDivs.push($el);
				currentTallest = (currentTallest < $el.height()) ? ($el.height()): (currentTallest);
			}
			for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
		});
	};

	$(window).load(function() {
		equalheight('.equalheight');
	});

	$(window).resize(function() {
		equalheight('.equalheight');
	});
	var $grid = $('.gallery_filter_container').isotope({
		percentPosition: true,
		itemSelector: '.gallery_filter_item',
		layoutMode: 'masonry',
		masonry: {
			columnWidth: '.gallery_filter_sizer'
		}
	});
	function concatValues( obj ) {
	  var value = '';
	  for ( var prop in obj ) {
	    value += obj[ prop ];
	  }
	  return value;
	}

	var filters = {};
	$('#filters').on( 'click', '.filter_a', function() {
		var $this = $(this);
		// get group key
		var $buttonGroup = $this.parents('.filter_group');
		var filterGroup = $buttonGroup.attr('data-filter-group');
		// set filter for group
		filters[ filterGroup ] = $this.attr('data-filter');
		var filterValue = concatValues( filters );
		// arrange, and use filter fn		
		$grid.isotope({ filter: filterValue });
		return false;
	});
	$('.all_company a').on('click',function(){
		$('.all_country a').trigger('click');
	});
	$('.filter_group').each( function( i, buttonGroup ) {
		var $buttonGroup = $( buttonGroup );
		$buttonGroup.on( 'click', 'a', function() {
			$buttonGroup.find('.selected').removeClass('selected');
			$( this ).addClass('selected');
		});
	});
	var loading = false;
	$('.gallery_filter_loadmore').on('click','a',function(e){		
		e.preventDefault();
		if(loading) return false;
		console.log('a');
		var $this = $(this);
		var pageCurrent = parseInt($this.attr('data-page'));
		var nonce = $this.data('nonce');
		var paged = parseInt(pageCurrent+1);
		$.ajax({
			type : "post",
			dataType : "json",
			url : gallery_array.ajaxurl,
			data : {
				action: "gallery_load_more",
				nonce: nonce,
				page: paged
			},
			context: this,
			beforeSend: function(){
				$this.html(gallery_array.loading);
				loading = true;
			},
			success: function(response) {
				if(response.success) {
					var $newItems = $(response.data.content);
					$('.gallery_filter_container').append( $newItems ).isotope( 'insert',$newItems);
					if(response.data.pagemore){
						$this.attr('data-page',paged).html(gallery_array.loadmoreText);
						loading = false;
					}else{
						$this.html(gallery_array.noloading);
						setTimeout(function(){
							$('.gallery_filter_loadmore').remove();
							loading = false;
						},1000);
					}
				}else{
					$this.html(gallery_array.loadmoreText);
					loading = false;
				}
			}
		});
	});
})(jQuery);