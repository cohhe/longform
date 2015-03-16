// Admin Javascript
jQuery( document ).ready( function( $ ) {

	// Choose layout
	$("#vh_layouts img").click(function() {
		$(this).parent().parent().find(".selected").removeClass("selected");
		$(this).addClass("selected");
	});
});