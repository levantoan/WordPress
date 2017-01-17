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
})(jQuery);