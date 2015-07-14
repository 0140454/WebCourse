/* ------ 全局變數 ------ */
var slideshow_duration = 1000;
var slideshow_hidden = 0;
var slideshow_show = 3000;
var total_img = 12;
var max_zoom = 3.5;
var showing = false;
var playing = false;
var draging = false;
var img_id;
var show_ratio;
var bgi_x;
var bgi_y;
var previous_event;

/* ------ 自訂物件 ------ */
function RECT() {
	this.top = 0;
	this.bottom = 0;
	this.left = 0;
	this.right = 0;
}

function InRect(obj, x, y) {
	return (x > obj.left && x < obj.right && y > obj.top && y < obj.bottom);
}

/* ------ jQuery ------ */
$(window).resize(function() {
	if (showing)
		ShowImage("");
});

$(document).ready(function() {
	$("#show").mousedown(function(){
		if (InScrollRange(event) || InCustomRange(event))
			return;

		if (event.which == 1) /* Left Button */
			HideImage();
	});

	$("#show_img").mousewheel(function(event, delta) {
		event.preventDefault();
		
		var show_img = document.getElementById("show_img");
		var rect = show_img.getBoundingClientRect();
		
		var edge_offset_x = event.pageX - rect.left - document.body.scrollLeft;
		var edge_offset_y = event.pageY - rect.top - document.body.scrollTop;
		
		var pos_offset_x = edge_offset_x - bgi_x;
		var pos_offset_y = edge_offset_y - bgi_y;
		
		var ratio_x = pos_offset_x / (show_img.width * show_ratio);
		var ratio_y = pos_offset_y / (show_img.height * show_ratio);
		
		show_ratio += (delta < 0) ? -0.1 : 0.1;
		
		if (show_ratio < max_zoom)
		{
			bgi_x = edge_offset_x - (show_img.width * show_ratio * ratio_x);
			bgi_y = edge_offset_y - (show_img.height * show_ratio * ratio_y);
		}
		
		ShowImage("");
	});
	
	$("#show_img").mousedown(function(){
		event.preventDefault();
		
		previous_event = event;
		draging = true;
	});
	
	$("#show_img").mouseup(function(){
		event.preventDefault();
		
		previous_event = event;
		draging = false;
	});

	$("#show_img").mousemove(function() {
		if (draging)
		{
			event.preventDefault();
			
			bgi_x += (event.pageX - previous_event.pageX);
			bgi_y += (event.pageY - previous_event.pageY);

			previous_event = event;
			ShowImage("");
		}
	});
	
	$(document).mousemove(function() {
		if (draging)
		{
			event.preventDefault();
			
			bgi_x += (event.pageX - previous_event.pageX);
			bgi_y += (event.pageY - previous_event.pageY);

			previous_event = event;
			ShowImage("");
		}
	});
	
	$(document).mouseup(function() {
		event.preventDefault();
		
		previous_event = event;
		draging = false;
	});
	
	$(document).keydown(function() {
		if (showing)
		{
			if (event.keyCode == 27) /* ESC */
				HideImage();
			else if (event.keyCode == 37) /* Left */
				GotoImage(-1);
			else if (event.keyCode == 39) /* Right */
				GotoImage(1);
		}
	});
});

$.fn.GetScrollBar = function(axis) {
	var overflow = this.css("overflow");
	var overflow_axis = this.css("overflow-" + axis);

	return (this.get(0).scrollHeight > this.innerHeight());
}

function InCustomRange(event) {
	var custom_group = document.getElementsByName("custom");
	var x = event.pageX;
    var y = event.pageY;
	var result = false;
	
	for (var i = 0; i < custom_group.length && !result; i++)
	{
		var rect = custom_group[i].getBoundingClientRect();
		result = InRect(rect, x, y);
	}
	
	return result;
}

function InScrollRange(event) {
	var default_scroll_size = 18;
	
    var x = event.pageX;
    var y = event.pageY;
    var target = $(event.target);
	var y_scroll = target.GetScrollBar("y");
    var x_scroll = target.GetScrollBar("x");
    var in_x = false;
    var in_y = false;

    if(y_scroll){
        var y_rect = new RECT();
        y_rect.top = target.offset().top;
        y_rect.right = target.offset().left + target.width();
        y_rect.bottom = y_rect.top + target.height();
        y_rect.left = y_rect.right - default_scroll_size;

        in_y = InRect(y_rect, x, y);
    }

    if(x_scroll){
        var x_rect = new RECT();
        x_rect.bottom = target.offset().top + target.height();
        x_rect.left = target.offset().left;
        x_rect.top = x_rect.bottom - default_scroll_size;
        x_rect.right = x_rect.left + target.width();

        in_x = InRect(x_rect, x, y);
    }

    return (in_x || in_y);
}

/* ------ 自訂函數 ------ */
function HideImage() {
	var show = document.getElementById("show");
	var show_img = document.getElementById("show_img");
	
	if (playing)
		Play();
				
	showing = false;
	show.style.display = "none";
}

function ShowImage(path) {
	var img = new Image();
	var show = document.getElementById("show");
	var show_img = document.getElementById("show_img");
	var img_path = path;

	if (img_path == "")
	{
		img_path = window.getComputedStyle(show_img, false).backgroundImage.slice(4, -1);
		if (img_path.indexOf("\"") >= 0 || img_path.indexOf("\'") >= 0)
			img_path = img_path.substring(1, img_path.length - 1);
	}
	else
	{
		show_ratio = -1;
		bgi_x = bgi_y = 0;
	}
				
	img.onload = function() {
		showing = true;

		img_id = parseInt(img_path.substring(img_path.lastIndexOf("/") + 1, img_path.lastIndexOf(".")));

		show.style.height = window.innerHeight;
		show.style.width = window.innerWidth;
		show.style.display = "inline";

		var default_show_ratio = Math.min(parseFloat(parseFloat(show.style.height) - 150) / parseFloat(img.height),
								 parseFloat(parseFloat(show.style.width) - 150) / parseFloat(img.width));

		show_img.height = img.height * default_show_ratio;
		show_img.width = img.width * default_show_ratio;

		var canvas = document.createElement('canvas');
		canvas.height = show_img.height;
		canvas.width = show_img.width;

		if (show_ratio < 1)
			show_ratio = 1;
		else if (show_ratio >= max_zoom)
			show_ratio = max_zoom;

		if (bgi_x > 0)
			bgi_x = 0;
		else if (bgi_x < show_img.width * (1 - show_ratio))
			bgi_x = show_img.width * (1 - show_ratio);

		if (bgi_y > 0)
			bgi_y = 0;
		else if (bgi_y < show_img.height * (1 - show_ratio))
			bgi_y = show_img.height * (1 - show_ratio);

		show_img.src = canvas.toDataURL();
		show_img.style.backgroundImage = "url('" + img.src + "')";
		show_img.style.backgroundRepeat = "no-repeat";
		show_img.style.backgroundSize = show_img.width * show_ratio + "px " + show_img.height * show_ratio + "px";
		show_img.style.backgroundPosition = bgi_x + "px " + bgi_y + "px";
	}
	
	img.src = img_path;
}

function GotoImage(offset) {
	img_id = (img_id + total_img + offset) % total_img;
	ShowImage("img/" + img_id + ".jpg");
}

function SetOpacity(opacity) {
	document.getElementById("show_img").style.opacity = opacity;
}

function FadeOut() {
	for (i = 0; i <= 1; i += 0.01)
		setTimeout("SetOpacity(" + (1 - i) + ")", i * slideshow_duration);
	
	setTimeout("FadeIn()", slideshow_duration + slideshow_hidden);
}

function FadeIn() {
	for (i = 0; i <= 1; i += 0.01)
		setTimeout("SetOpacity(" + i + ")", i * slideshow_duration);
		
	GotoImage(1);
	
	setTimeout("FadeOut()", slideshow_duration + slideshow_show);
}

function Play() {
	var play_button = document.getElementById("play");
	
	if (playing)
	{
		playing = false;
		
		var timeout_id = window.setTimeout(function() {}, 0);
		while (timeout_id--)
			window.clearTimeout(timeout_id);
			
		SetOpacity(1);

		play_button.value = "播放幻燈片";
	}
	else
	{
		playing = true;
		
		FadeOut();
		play_button.value = "停止幻燈片";
	}
}
