/**
 * Created by whannah on 2/28/14.
 */
// Global placeholder
var pageTimeout = setTimeout( function() { }, 500);
var noticeTimer = setTimeout( function() { }, 500);
var count = 299000;
var countDownHolder;

$(document).on("mobileinit", function(){
    $.mobile.defaultPageTransition = 'slide';
    $.mobile.page.prototype.options.addBackBtn = true;
});

var init = function() {
    clearTimeout(pageTimeout);
    clearTimeout(noticeTimer);
    pageTimeout = setTimeout( function() {
        window.location.href = "http://library.clevelandart.org/checkin/";
    }, 300000); // 5 minutes

    var noticeTimer = setInterval(function(){
        count -= 1000;
        //jQuery('#defaultCountdown').html((count/1000)+"s");
        //console.log(count/1000);
    }, 1000);
};

$("#register").live('pageshow', function() { init(); });

    function createAlertWithMessage(msg) {
            //create a div for the popup
        var $popUp = $("<div/>").popup({
            dismissible : false,
            theme : "a",
            overlyaTheme : "a",
            transition : "pop"
        }).bind("popupafterclose", function() {
        //remove the popup when closing
            $(this).remove();
        });
        //create a message for the popup
        $("<p/>", {
            text : msg
        }).appendTo($popUp);

        //Create a submit button(fake)
        $("<a>", {
            text : "Continue"
        }).buttonMarkup({
            inline : true,
            icon : "check"
        }).bind("click", function() {
            $popUp.popup("close");
        }).appendTo($popUp);
        $popUp.popup("open").trigger("create");
    }

	function validateEmail(sEmail) {
    	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    	if (filter.test(sEmail)) {
    	    return true;
    	} else {
        	return false;
    	}
	}

    function checkRequired(pageID){
        var failed = false;
        jQuery("input[required='required']","#"+$.mobile.activePage.attr('id')).each(function(){
            if (jQuery(this).val().trim() == '' && jQuery(this).is(":visible")) {
                console.log(jQuery(this).val().trim()+"11");
                createAlertWithMessage("Missing required field: "+ jQuery(this).attr('placeholder'));
                failed = true;
            }
        });
        // validate email address
        jQuery("input[type='email']","#"+$.mobile.activePage.attr('id')).each(function(){
            if (!validateEmail(jQuery(this).val().trim()) && jQuery(this).is(":visible")) {
                createAlertWithMessage("Invalid email: "+ jQuery(this).attr('placeholder'));
                failed = true;
            }
        });       
        if (!failed && typeof pageID !== 'undefined'){
            $.mobile.changePage(pageID);
        } else if (!failed && typeof pageID === 'undefined') {
            var patronType = $("input[type=radio]:checked","#patronType").val();
            if (!patronType){
                createAlertWithMessage("Please select a patron type to continue.");
                return false;
            }
            $.mobile.changePage('#reg2');
        }
    }

    $("#reg2").live('pagebeforeshow', function() {
        init();
        $("#intern, #localAddress, #homeAddress, #licenseNumber, #cmaMemberID, #temporaryDate").hide();
        $("#staffFellow, #licenseNumber, #staffFellow").hide();

        var patronType = $("input[type=radio]:checked","#patronType").val();
        if (patronType == "Intern") {
            $("#intern").show();
            $("#temporaryDate").show();
        }

        if (jQuery.inArray(patronType,["Public","Academic","Member"]) >= 0){
            $("#idNumber").show();
            if (patronType == 'Member') {
            $("#cmaMemberID").show();
            } else {
            $("#licenseNumber").show();
            }
        }

        if (jQuery.inArray(patronType,["Staff","Fellow"]) >= 0) {
            $("#staffFellow").show();
            if ($("#isTemporary")[0].checked) {
                $("#temporaryDate").show();
            }
            $("#isTemporary").die('change').live('change', function(){
                if ($("#temporaryDate").css('display') == 'none'){
                    $("#temporaryDate").slideDown();
                } else {
                    $("#temporaryDate").slideUp();
                }
            });
        } else {
            $("#localAddress").show();
            if ($("#homeDifferent")[0].checked) {
                $("#homeAddress").show();
            }
        }
        $("#homeDifferent").die('change').live('change', function(){
            if ($("#homeAddress").css('display') == 'none'){
                $("#homeAddress").slideDown();
            } else {
                $("#homeAddress").slideUp();
            }
        });
    });

    $("#reg3").live('pagebeforeshow',function() {
        if ($("#signature canvas").hasClass("jSignature")) {
            $("#signature").jSignature('clear').html('');
        }
    });

    $("#reg3").live('pageshow', function() {
        init();
        $("#signature").jSignature({lineWidth:1});
    });

    function fullReset(){
        var resetTimer = setTimeout(function(){
        window.location.href = "/checkin";
        },1500);
    }

    function processForm(){
        var datapair = $("#signature").jSignature("getData", "image");
        var data = [{name: datapair[0], value: datapair[1]}];
        $('input, select').each(
            function(index){
                var input = $(this);
                if (input.is(':radio')) {
                  if (input.is(':checked')) {
                    data.push({name: input.attr('name'),
                    value: input.val()});
                  }
                } else {
                  data.push({name: input.attr('name'),
                  value: input.val()});
                }
            }
        );
        $.ajax
        ({
            type: "POST",
            url: "includes/process.php",
            dataType: 'json',
            async: false,
            data: data
            }).done(function(ret){
                console.log(ret);
        });
        $.mobile.changePage("#photo");
    }

    jQuery.fn.shake = function(intShakes, intDistance, intDuration) {
        this.each(function() {
            $(this).css("position","relative");
            for (var x=1; x<=intShakes; x++) {
                $(this).animate({left:(intDistance*-1)}, (((intDuration/intShakes)/4)))
                .animate({left:intDistance}, ((intDuration/intShakes)/2))
                .animate({left:0}, (((intDuration/intShakes)/4)));
            }
        });
        return this;
    };

    function alertBarcode(code,format){
        jQuery.ajax({
            type: "POST",
            url: "/checkin/partials/checkin.php",
            data: { code: code, format: format },
            dataType: "json"
        }).done(function( json ){
            jQuery("#confMsg").html(json.message);
            if (json.error){
                jQuery("#confMsg").css("color","red");
                jQuery("#confMsg").shake(5, 10, 1000);
            }
        });
        setTimeout(function(){
            jQuery("#confMsg").html(' ');
        },5000);
    }

    var confMsg = '';
    /*$('#formCheckIn').live('submit', function (e) {
        //cache the form element for use in this function
        var $this = $(this);

        //prevent the default submission of the form
        e.preventDefault();

        //run an AJAX post request to your server-side script, $this.serialize() is the data from your form being added to the request
        $.post("/checkin/includes/process.php", $this.serialize(), function (responseData) {
            var checkin = $.parseJSON(responseData);
            setTimeout(function() {
                $.dynamic_popup({
                content: "<h2>Welcome, "+checkin.username+"!  Thanks for checking in.</h2>"
            });
            }, 250);
            $("input").each(function (){ $(this).val(""); });
        });
    });*/

    $('.backButton').hide();
    $(document).on('pagecontainershow', function () {
        var activePage = $.mobile.activePage;
        if (activePage[0].id != 'home') {
            $('.backButton').show();
        } else {
            $('.backButton').hide();
        }
    });