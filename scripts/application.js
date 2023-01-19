$(document).ready(function() {

	//Header Drop-Down Menu
	$("ul#header_menu li a").click(function() {
			var hidden = $(this).parents("li").children("ul").is(":hidden");
		
		$("ul#header_menu>li>ul").hide();
		$("ul#header_menu>li>a").removeClass();
		
		if (hidden) {
			$(this).parents("li").children("ul").toggle();
			$(this).parents("li").children("a").addClass("active");
			}
		});
	
	//Sidebar Accordion Menu
	$('.add_menu').accordion();
	
	/* Header Toggle */
	$("#header_toggle").on("click", function(){
		$("#header_menu").slideToggle(350);
		$(this).toggleClass("active");
	});
	
	/* Sidebar Toggle */
	$("#sidebar_toggle").on("click", function(){
		$("#sidebar_menu").slideToggle(350);
		$(this).toggleClass("active");
	});
	
	//Tabs
	//$("#tabs").tabbed();
	//$("#tabs_2").tabbed();
	
	//Placeholder Support For Legacy Browsers
	//$('input[placeholder], textarea[placeholder]').placeholder();
	$notifications = $("#notifications").notify();


	
});

function create( template, vars, opts ){
		return $notifications.notify("create", template, vars, opts);
	}