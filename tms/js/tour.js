$(document).ready(function(){

    if($(".tour").length == 0){

        $(".activatetour").parents(".dropdown").remove();

    }else{

        $("body").on('click','.activatetour',startTour);
        $('body').on('click','.canceltour',endTour);
        $('body').on('click','.endtour',endTour);
        $('body').on('click','.restarttour',restartTour);
        $('body').on('click','.nextstep',nextStep);
        $('body').on('click','.prevstep',prevStep);
    }
});

function startTour(){
    /*$('.activatetour').remove();
    $('.endtour,.restarttour').show();
    if(!autoplay && total_steps > 1)
        $('.nextstep').show();*/
    showOverlay();
    nextStep();
}

function nextStep(){
    /*if(!autoplay){
        if(step > 0)
            $('.prevstep').show();
        else
            $('.prevstep').hide();
        if(step == total_steps-1)
            $('.nextstep').hide();
        else
            $('.nextstep').show();
    }*/
    if(step >= total_steps){
        //if last step then end tour
        endTour();
        return false;
    }
    ++step;
    showTooltip();
}

function prevStep(){
    /*if(!autoplay){
        if(step > 2)
            $('.prevstep').show();
        else
            $('.prevstep').hide();
        if(step == total_steps)
            $('.nextstep').show();
    }*/
    if(step <= 1)
        return false;
    --step;
    showTooltip();
}

function endTour(){
    step = 0;
    if(autoplay) clearTimeout(showtime);
    removeTooltip();
    hideControls();
    hideOverlay();
}

function restartTour(){
    step = 0;
    if(autoplay) clearTimeout(showtime);
    nextStep();
}

function showTooltip(){
    //remove current tooltip
    removeTooltip();

    var step_config		= config[step-1];
    var $elem			= $('.' + step_config.name);

    $elem.addClass('active-tour');

    if(autoplay)
        showtime	= setTimeout(nextStep,step_config.time);

    var bgcolor 		= step_config.bgcolor;
    var color	 		= step_config.color;

    /*custom*/
    var buttons = step_config.buttons.join('');

    var $tooltip		= $('<div>',{
        id			: 'tour_tooltip',
        class    	: 'tooltip',
        html		: '<p>'+step_config.text+'</p><p>'+buttons+'</p><span class="tooltip_arrow"></span>'
    }).css({
        'display'			: 'none',
        'background-color'	: bgcolor,
        'color'				: color
    });

    //position the tooltip correctly:

    //the css properties the tooltip should have
    var properties		= {};

    var tip_position 	= step_config.position;

    //append the tooltip but hide it
    $('BODY').prepend($tooltip);

    //get some info of the element
    var e_w				= $elem.outerWidth();
    var e_h				= $elem.outerHeight();
    var e_l				= $elem.offset().left;
    var e_t				= $elem.offset().top;


    switch(tip_position){
        case 'TL'	:
            properties = {
                'left'	: e_l,
                'top'	: e_t + e_h + 10 + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_TL');
            break;
        case 'TR'	:
            properties = {
                'left'	: e_l + e_w - $tooltip.width() + 'px',
                'top'	: e_t + e_h + 10 + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_TR');
            break;
        case 'BL'	:
            properties = {
                'left'	: e_l + 'px',
                'top'	: e_t - $tooltip.height() -10 + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_BL');
            break;
        case 'BR'	:
            properties = {
                'left'	: e_l + e_w - $tooltip.width() + 'px',
                'top'	: e_t - $tooltip.height() -10 + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_BR');
            break;
        case 'LT'	:
            properties = {
                'left'	: e_l + e_w + 10 + 'px',
                'top'	: e_t + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_LT');
            break;
        case 'LB'	:
            properties = {
                'left'	: e_l + e_w + 10 + 'px',
                'top'	: e_t + e_h - $tooltip.height() + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_LB');
            break;
        case 'RT'	:
            properties = {
                'left'	: e_l - $tooltip.width() - 10 + 'px',
                'top'	: e_t + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_RT');
            break;
        case 'RB'	:
            properties = {
                'left'	: e_l - $tooltip.width() -10 + 'px',
                'top'	: e_t + e_h - $tooltip.height() + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_RB');
            break;
        case 'T'	:
            properties = {
                'left'	: e_l + e_w/2 - $tooltip.width()/2 + 'px',
                'top'	: e_t + e_h + 10 + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_T');
            break;
        case 'R'	:
            properties = {
                'left'	: e_l - $tooltip.width() - 10 + 'px',
                'top'	: e_t + e_h/2 - $tooltip.height()/2 + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_R');
            break;
        case 'B'	:
            properties = {
                'left'	: e_l + e_w/2 - $tooltip.width()/2 + 'px',
                'top'	: e_t - $tooltip.height() - 10 + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_B');
            break;
        case 'L'	:
            properties = {
                'left'	: e_l + e_w  + 10 + 'px',
                'top'	: e_t + e_h/2 - $tooltip.height()/2 + 'px'
            };
            $tooltip.find('span.tooltip_arrow').addClass('tooltip_arrow_L');
            break;
    }


    /*
     if the element is not in the viewport
     we scroll to it before displaying the tooltip
     */
    var w_t	= $(window).scrollTop();
    var w_b = $(window).scrollTop() + $(window).height();
    //get the boundaries of the element + tooltip
    var b_t = parseFloat(properties.top,10);

    if(e_t < b_t)
        b_t = e_t;

    var b_b = parseFloat(properties.top,10) + $tooltip.height();
    if((e_t + e_h) > b_b)
        b_b = e_t + e_h;


    if((b_t < w_t || b_t > w_b) || (b_b < w_t || b_b > w_b)){
        $('html, body').stop()
            .animate({scrollTop: b_t}, 500, 'easeInOutExpo', function(){
                //need to reset the timeout because of the animation delay
                if(autoplay){
                    clearTimeout(showtime);
                    showtime = setTimeout(nextStep,step_config.time);
                }
                //show the new tooltip
                $tooltip.css(properties).show();
            });
    }
    else
    //show the new tooltip
        $tooltip.css(properties).show();
}

function removeTooltip(){
    $('#tour_tooltip').remove();

    /*custom. removing highlighting*/
    $('.tour').removeClass('active-tour');
}

function showControls(){
    /*
     we can restart or stop the tour,
     and also navigate through the steps
     */
    var $tourcontrols  = '<div id="tourcontrols" class="tourcontrols">';
    $tourcontrols += '<p>First time here?</p>';
    $tourcontrols += '<span class="button" id="activatetour">Start the tour</span>';
    if(!autoplay){
        $tourcontrols += '<div class="nav"><span class="button" id="prevstep" style="display:none;">< Previous</span>';
        $tourcontrols += '<span class="button" id="nextstep" style="display:none;">Next ></span></div>';
    }
    $tourcontrols += '<a id="restarttour" style="display:none;">Restart the tour</span>';
    $tourcontrols += '<a id="endtour" style="display:none;">End the tour</a>';
    $tourcontrols += '<span class="close" id="canceltour"></span>';
    $tourcontrols += '</div>';

    $('BODY').prepend($tourcontrols);
    $('#tourcontrols').animate({'right':'30px'},500);
}

function hideControls(){
    $('#tourcontrols').remove();
}

function showOverlay(){
    var $overlay	= '<div id="tour_overlay" class="overlay"></div>';
    $('BODY').prepend($overlay);
}

function hideOverlay(){
    $('#tour_overlay').remove();
}