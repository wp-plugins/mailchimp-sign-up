jQuery.noConflict();(function(e){e(function(){function t(){var t=e("input#address");var n=e("input#address").outerHeight(true)+e("#results_pointer").height()+e("#result").height();var r=e("input#address").width();var i=t.offset().top-n+"px";var s=t.offset().left+"px";e("#result").css({top:i,left:s});var o=e("#result");var u=function(){clearTimeout(a);o.fadeOut("slow")};var a=setTimeout(u,5e3)}e(document).click(function(t){if(t.target.id!="emailSignupBtn"&&!e("#emailSignupBtn").find(t.target).length){}});e("#close").click(function(){e(this).parent().fadeOut(300)});e(document).ready(function(){e("#result").appendTo("body");e("#invite").submit(function(n){n.preventDefault();e.ajax({url:"/wp-admin/admin-ajax.php",type:"POST",data:{action:"mailchimp_submit",email:e("#address").attr("value")},success:function(n){e("#result").html(n+'<div id="results_pointer"></div><div id="close"></div>');e("#result").html(n+'<div id="results_pointer"></div><div id="close"></div>');e("#result").fadeIn(300);e("#close").click(function(){e(this).parent().fadeOut(300)});t()},error:function(){e("#result").html("Sorry, an error occurred."+'<div id="close"></div>');e("#result").fadeIn(300);e("#close").click(function(){e(this).parent().fadeOut(300);t()})}});return false})})})})(jQuery)