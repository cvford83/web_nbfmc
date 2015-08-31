/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function show_thanks()
{
    //var e = document.getElementById("seedtype");
    var stype = $('#seedtype');
    var stval = stype.val();
    var MESSAGE = $('.message_display');

    var seed = parseFloat($('#amount').val());
    var seedInt = parseInt($('#amount').val());

    switch (true) {
        case(seedInt < 30):
            seed = seed + 1.00;
            break;
        case(30 <= seedInt && seedInt < 50):
            seed = seed + 1.50;
            break;
        case(50 <= seedInt && seedInt < 100):
            seed = seed + 2.50;
            break;
        case(100 <= seedInt && seedInt < 150):
            seed = seed + (seed * .0275);
            break;
        default:
            seed = seed + (seed * .03);
            break;
    }
    seed = seed.toFixed(2);
    $('#amount').val(seed);

    MESSAGE.empty();
    MESSAGE.toggleClass('alert alert-success');
    MESSAGE.append('Adding fee...' + '</br>');
    MESSAGE.append('Please wait, you are being re-directed for transaction completion...');
    MESSAGE.fadeIn(500);

    switch (stval) {
        case 'Other':
            alert("Thank you! May your seed yield a great return!!!");
            break;
        case 'Seed Offering':
            alert("Thank you! May your Overflow seed yield a great return!!!");
            break;
        case 'Tithe':
            alert("Thank you! May your Tithe yield a great return!!!");
            break;
        default:
            alert("Thank you! May your " + stval + " seed yield a great return!!!");
    }

    stype.prop("value", stval + ' fee');
}

function show_alert()
{
    //var hr = new XMLHttpRequest();
    //var url = "alert_message.php?auth=nbfmc_message";
    var MESSAGE = $('.alert_message');

    MESSAGE.empty();
//    var resp;
//    hr.open("GET", url, true);
//    hr.setRequestHeader("Content-type", "application/json");
//    hr.onreadystatechange = function() {
//        if(hr.readyState == 4 && hr.status == 200) {
//            resp = hr.responseText;
//            if(resp.indexOf("Auth") < 0){
//                var jsonObj = JSON.parse(resp);
//                $.each(jsonObj, function (i,message){
//                   MESSAGE.append(message); 
//                });
//            }
//            MESSAGE.toggleClass('alert alert-danger');
//            MESSAGE.fadeIn(500);
//        }
//        
//    }
//    MESSAGE.append("Please note Sunday Morning Service for July 26 will not be in Newnan, see events Page for more details");
//    MESSAGE.toggleClass('alert alert-danger');
//    MESSAGE.fadeIn(500);
}

function validate_currency() {

    var currency = $("#amount").val();
    var pattern = /^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$/;
    if (!pattern.test(currency)) {
        alert("Currency is not in valid format!\nEnter in 00.00 format");
        $("#amount").val('');
    }
}


function display_errors(errors) {
    var MESSAGE = $('#info');
    if (errors.length > 0) {
        MESSAGE.empty();
        // Show the errors
        var errorString = '';

        for (var i = 0, errorLength = errors.length; i < errorLength; i++) {
            errorString += errors[i].message + '\n';
            MESSAGE.append(errors[i].message + '<br />');
        }
        //alert(errorString);
        MESSAGE.addClass('alert alert-danger alert-dismissible');
        MESSAGE.fadeIn(200);
    }
    else {
        MESSAGE.empty();

        console.log('Request Sent');
        //Create HttpRequest object and url variable
        var hr = new XMLHttpRequest();
        var url = "captchavalidateboot.php";
        var form = $('#contactform');
        var return_data;
        console.log("Variables created");
        hr.open("POST", url, true);
        // Set content type header information for sending url encoded variables in the request
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // Access the onreadystatechange event for the XMLHttpRequest object
        hr.onreadystatechange = function () {
            if (hr.readyState == 4 && hr.status == 200) {
                return_data = hr.responseText;
                console.log("Return Message: " + return_data);
                MESSAGE.empty();
                MESSAGE.append(return_data + '</br>');
                if (return_data === 'Message sent successfully') {
                    MESSAGE.toggleClass();
                    MESSAGE.toggleClass('alert alert-success');
                    console.log("Resetting form");
                    $('input, textarea').val('');
                }
                else {
                    MESSAGE.toggleClass();
                    MESSAGE.toggleClass('alert alert-danger alert-dismissible');
                }
                $('#security').empty();
                $('#security').append('<div class="g-recaptcha" data-sitekey="6LfyswATAAAAAK3e_XlyMnty1lUAII3v3OINFmw2" ></div><br/> ');
                $.getScript("https://www.google.com/recaptcha/api.js");
                MESSAGE.fadeIn(200);
                //alert(return_data);
            }
        };
        // Send the data to PHP now... and wait for response to update the status div
        hr.send(form.serialize()); // Actually execute the request
        MESSAGE.append('Sending message...');
        MESSAGE.toggleClass();
        MESSAGE.toggleClass('alert alert-info');
        MESSAGE.fadeIn(200);
        /*
         (function($) {
         $('#contactform').on('submit', function(e) {
         // Prevent the browser submitting the form
         evt.preventDefault();
         
         // Put the form in variable form
         var form = $('#contactform');
         console.log("loading form \n")
         
         
         
         // Do a AJAX post with the form data and check the response
         /*$.post(form.attr('action'), form.serialize(), function(data) {
         if(data === 'Information sent successfully') {
         // Captcha passed!
         MESSAGE.append("Message Sent Successfully")
         } else {
         // Captcha failed!
         MESSAGE.append("Message Not Sent <br />")
         MESSAGE.append("Security Code did not match <br />")
         }
         MESSAGE.fadeIn(300);
         });
         
         });
         })(jQuery);
         
         
         **/


    }
}

function display_carousel() {
    var fb_events = null;
    var hr = new XMLHttpRequest();
    var url = "evtcapture.php";
    var mapUrl = "https://maps.google.com/maps?q=";
    if (arguments[0]) {
        url = "evtcapture.php?display=past";
        console.log('getting past events')
        $('#eventType').text("See Upcoming Events")
        $('#eventType').attr("onclick", "display_carousel();")
    } else {
        console.log('getting upcoming events')
        $('#eventType').text("See Past Events")
        $('#eventType').attr("onclick", "display_carousel(1);")
    }
    // If it's an iPhone..
    if ((navigator.platform.indexOf("iPhone") != -1)
            || (navigator.platform.indexOf("iPod") != -1)
            || (navigator.platform.indexOf("iPad") != -1)) {
        mapUrl = "maps://maps.google.com/maps?q=";
    }

    hr.open("GET", url, true);
    hr.setRequestHeader("Content-type", "application/json");
    hr.onreadystatechange = function () {
        if (hr.readyState == 4 && hr.status == 200) {
            //console.log("Received: "+hr.responseText)
            fb_events = hr.responseText;
            var carousel = $('#myCarousel');
            carousel.empty();
            //Indicator class
            carousel.append('<ol class="carousel-indicators" id="indicate"></ol>');
            var indicator = $('#indicate');
            indicator.css('top', '97%');
            //slides wrapper
            carousel.append('<div class="carousel-inner" id="listbox" role="listbox"></div>');
            var listbox = $("#listbox");

            //console.log(fb_events);
            var jsonObj = JSON.parse(fb_events);
            $.each(jsonObj, function (i, event)
            {
                //console.log("index "+i+" adding: "+event['name']);
                //add indicators per event captured
                indicator.append(' <li data-target="#myCarousel" data-slide-to=' + i + ' class=""></li>');
                $('#myCarousel').on('slid.bs.carousel', function () {
                    $holder = $("ol li.active");
                    $holder.removeClass('active');
                    var idx = $('div.active').index('div.item');
                    $('ol.carousel-indicators li[data-slide-to="' + idx + '"]').addClass('active');
                });
                $('ol.carousel-indicators  li').on("click", function () {
                    $('ol.carousel-indicators li.active').removeClass("active");
                    $(this).addClass("active");
                });
                //console.log(event['name'])
                //Iterate event info and add slide
                listbox.append('<div class="item center-block" id="cslide' + i + '">');
                var slide = $('#cslide' + i);
                //console.log(event['event_lat']+','+event['event_long']);
                var evtLocMap = mapUrl + event['event_lat'] + ',' + event['event_long'];

                slide.append('<a href=' + event['event_img'] + '><img id="slide_img" alt="flyer" src="' + event['event_img'] + '" ></a>');

                slide.append('<h2>' + event['name'] + '</h2>');
                slide.append('<a href=' + evtLocMap + '>\n\
                    <p class=lead>' + event['location'] + ' <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></p></a>');
                slide.append('<p class=lead>' + event['start_date'] + ' ' + event['start_time'] + '</p>')
                slide.css('margin', 'auto');
                if (i === "0") {
                    $(".item").addClass("active");
                    $("ol li").addClass("active");
                }
            });

            // Left and right controls
            carousel.append('<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">\n\
                 <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>\n\
                <span class="sr-only">Previous</span>\n\
                </a>');
            carousel.append('<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">\n\
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>\n\
                <span class="sr-only">Next</span>\n\
                </a>');

            carousel.carousel();
            carousel.touchwipe({
                wipeLeft: function () {
                    carousel.carousel('next');
                },
                wipeRight: function () {
                    carousel.carousel('prev');
                },
                min_move_x: 20,
                preventDefaultEvents: false
            });
        }
    };

    hr.send();


//    if(fb_events !== null){
//        
////        
//        console.log(fb_events);
//        
//        
//        
//        
//        
//        
//        //Iterate slides
//        var index = 0;
//        $.each(event_obj, function(i, val) {
//            //add indicator
//            
//            
//            //add slide
//            listbox.append('<div class="item">')
//            
//            index = index + 1;
//          });
//        



//    }
}

function sign_up(errors) {
    var MESSAGE = $('#info');
    if (errors.length > 0) {
        MESSAGE.empty();
        // Show the errors
        var errorString = '';

        for (var i = 0, errorLength = errors.length; i < errorLength; i++) {
            errorString += errors[i].message + '\n';
            MESSAGE.append(errors[i].message + '<br />');
        }
        //alert(errorString);
        MESSAGE.addClass('alert alert-danger alert-dismissible');
        MESSAGE.fadeIn(200);
    }
    else {
        MESSAGE.empty();

        console.log('Request Sent');
        //Create HttpRequest object and url variable
        var hr = new XMLHttpRequest();
        var url = "sign-up-capture.php";
        var form = $('#signupform');
        var return_data;
        console.log("Variables created");
        hr.open("POST", url, true);
        // Set content type header information for sending url encoded variables in the request
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // Access the onreadystatechange event for the XMLHttpRequest object
        hr.onreadystatechange = function () {
            if (hr.readyState == 4 && hr.status == 200) {
                return_data = hr.responseText;
                console.log("Return Message: " + return_data);
                MESSAGE.empty();
                MESSAGE.append(return_data + '</br>');
                if (return_data === 'Registration Step: Directing you to payment') {
                    MESSAGE.toggleClass();
                    MESSAGE.toggleClass('alert alert-success');
                    window.location.href = "https://www.paypal.com/cgi-bin/webscr?" + form.serialize();
                    console.log("Resetting form");
                    $('input, textarea').val('');

                }
                else {
                    MESSAGE.toggleClass();
                    MESSAGE.toggleClass('alert alert-danger alert-dismissible');
                }
                $('#security').empty();
                $('#security').append('<div class="g-recaptcha" data-sitekey="6LfyswATAAAAAK3e_XlyMnty1lUAII3v3OINFmw2" ></div><br/> ');
                $.getScript("https://www.google.com/recaptcha/api.js");
                MESSAGE.fadeIn(200);
                //alert(return_data);
            }
        };
        // Send the data to PHP now... and wait for response to update the status div
        hr.send(form.serialize()); // Actually execute the request
        MESSAGE.append('Sending message...');
        MESSAGE.toggleClass();
        MESSAGE.toggleClass('alert alert-info');
        MESSAGE.fadeIn(200);
        /*
         (function($) {
         $('#contactform').on('submit', function(e) {
         // Prevent the browser submitting the form
         evt.preventDefault();
         
         // Put the form in variable form
         var form = $('#contactform');
         console.log("loading form \n")
         
         
         
         // Do a AJAX post with the form data and check the response
         /*$.post(form.attr('action'), form.serialize(), function(data) {
         if(data === 'Information sent successfully') {
         // Captcha passed!
         MESSAGE.append("Message Sent Successfully")
         } else {
         // Captcha failed!
         MESSAGE.append("Message Not Sent <br />")
         MESSAGE.append("Security Code did not match <br />")
         }
         MESSAGE.fadeIn(300);
         });
         
         });
         })(jQuery);
         
         
         **/


    }
}
