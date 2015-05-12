// JavaScript Document
$(document).ready(function(){

$('.modelactions').click(function(){
	$('.modelactions').removeClass('active');
	$(this).addClass('active');	

});

$('.main-navbar-li').click(function(){
	$('.main-navbar-li').removeClass('active');
	$(this).addClass('active');	

});

$("#menu-toggle").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled");
	$("#menu-toggle > span").toggleClass("fa-chevron-right",1);
	$("#menu-toggle > span").toggleClass("fa-chevron-left",0);
	$("#main-controls-logo").toggleClass("hidden",0);
	$("#adyou-logo").toggleClass("hidden",0);
	$(".menu-caret").toggleClass("hidden",0);
	$(".section-name").toggleClass("hidden",0);
});

$(".list-group-item").click(function(){
	$(".section-icon").removeClass("active");
	$(".expand-section").removeClass("fa-angle-up").addClass("fa-angle-down");
	$(this).find(".expand-section").removeClass("fa-angle-down").addClass("fa-angle-up");
	$(this).find(".section-icon").addClass("active");
});

$(".list-group-item").mouseover(function(){
	$(".section-icon").removeClass("hover");
	$(this).find(".section-icon").addClass("hover");
	});


$(".sidebar-action").click(function(){
	$(".sidebar-action").removeClass("active-action");
	$(this).addClass("active-action");
});


});