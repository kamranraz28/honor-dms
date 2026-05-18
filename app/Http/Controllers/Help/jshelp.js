window.open("http://www.w3schools.com", "_blank");
window.location.href = "http://www.w3schools.com";
window.location.hostname


// jQuery Effects (hide,show,fade,slide,animate,callback,chaining,stope)
	https://www.w3schools.com/jquery/jquery_hide_show.asp

// jquery html (set,add,remove,css,class)
	https://www.w3schools.com/jquery/jquery_dom_get.asp


// details about Selectors
	https://www.w3schools.com/jquery/jquery_selectors.asp

// details about Events(Mouse Events,Keyboard Events,Form Events,Document/Window Events)
	https://www.w3schools.com/jquery/jquery_events.asp


// details about Events (All Events)
	https://www.w3schools.com/jquery/jquery_ref_events.asp


// details about Ajax (All Events)
	https://www.w3schools.com/jquery/jquery_ref_effects.asp


// ON event handeler example link
	https://www.w3schools.com/jquery/event_on.asp

// FIND event handeler example link
	https://www.w3schools.com/jquery/traversing_find.asp


$(".class").css("background-color", "yellow");
$("#id").css("background-color", "yellow");
$("input[name='attribute']").css("background-color", "yellow");

$('#upazila1').prop("disabled", true);
jQuery("input[type='text']").prop("disabled", true);

$("#id").attr("alt", "Setting New Image");
$( "img" ).attr({
  src: "/images/jquery.gif",
  title: "jQuery Image",
  alt: "Sample jQuery Image"
});

var attrValue = $("#id").attr("alt");

$("table").removeAttr("border");
$("p#pID").hasClass("myClass");
$("p#pID").addClass("myClass");
$("p#pID").removeClass("myClass");
$("p#pID").css("display","none");
$("p#pID").css({
	display: 'block',
	overflow: 'hidden'
});

var title = $("em").attr("title");
$("#divid").text(title);
$("#divid").html(title);


on('click', '.selector', function(event) {
	event.preventDefault();
	/* Act on the event */
});

bind('event name', eventData, function(event) {
	/* Act on the event */
});


$(document).ready(function() {

    var max_fields      = 20;
    var wrapper         = $(".container1"); 
    var add_button      = $(".add_form_field"); 

    var x = 1; 
    $(add_button).click(function(e){ 
        e.preventDefault();
        if(x < max_fields){ 
            x++; 
            $(wrapper).append('<div>'+
              '<div class="col-md-5">'+
                '<div class="form-group">'+
                  '<select name="accounts[]" class="form-control select2" id="account'+ x +'">'+
                  '<option selected="selected" value="">Select Product</option>'+
                  '@foreach ($accounts as $account)'+
                  '<option value="{{$account['id']}}">{{$account['name']}}-{{$account['code']}}</option>'+
                  '@endforeach'+
                '</select>'+
                '</div>'+
              '</div>'+
              '<div class="col-md-2">'+
                '<div class="form-group">'+
                  '<input type="number" id="debit'+ x +'" name="debit[]" step="any" class="form-control debit" placeholder="Dr. Amount">'+
                '</div>'+
              '</div>'+
              '<div class="col-md-2">'+
                '<div class="form-group">'+
                  '<input type="number" id="credit'+ x +'" name="credit[]" step="any" class="form-control credit" placeholder="Cr. Amount">'+
                '</div>'+
              '</div>'+
              '<button id="delete'+ x +'"  class="delete btn btn-danger btn-round col-md-2">Delete Field &nbsp;<span style="font-size:16px; font-weight:bold;"> - </span></button>'+
              '</div>');

        }
      else{
        alert('You Reached the limits')
      }

//========================================

//======================================================
        
        var accountArea = $("#account"+x);
        var debitArea   = $("#debit"+x);
        var creditArea  = $("#credit"+x);
        var deleteArea  = $("#delete"+x);

        var tdebit      = $("#tdebit");
        var tcredit     = $("#tcredit");

//======================================================
        accountArea.on('mouseenter', function(e) {
          e.preventDefault();
          $('.select2').select2();
        });

        accountArea.on('change', function(e) {
          e.preventDefault();
          var account_id = e.target.value;
          console.log(account_id);
        });

//======================================================

//======================================================
      $('#tdebit').css({'display':'block'});
      $('#tcredit').css({'display':'block'});
      $('#narration').css({'display':'block'});
//======================================================

      parseFloat(debitArea.val(0));
      parseFloat(creditArea.val(0));
      parseFloat(tdebit.val(0));
      parseFloat(tcredit.val(0));

      //parseFloat(deleteArea.attr('tdr', 0));
      //parseFloat(deleteArea.attr('tcr', 0));

//======================================================

    debitArea.bind('keyup mouseup mousewheel', function(e) {
      var debit = parseFloat(e.target.value);
      var totalDrAmoutn = 0;

        function getTotalDebit() {
          $(".container1").find(".debit").each(function() {
            totalDrAmoutn += parseFloat($(this).val());
          });
          $("#tdebit").val(totalDrAmoutn);
          deleteArea.val(debit);
          deleteArea.attr('tdr', debit);
        }
        getTotalDebit();
    });

//======================================================

//======================================================

    creditArea.bind('keyup mouseup mousewheel', function(e) {
      var credit = parseFloat(e.target.value);
      var totalCrAmoutn = 0;

        function getTotalDebit() {
          $(".container1").find(".credit").each(function() {
            totalCrAmoutn += parseFloat($(this).val());
          });
          $("#tcredit").val(totalCrAmoutn);
          deleteArea.val(credit);
          deleteArea.attr('tcr', credit);
        }
        getTotalDebit();
    });

//======================================================


    



});
    



    $(wrapper).on("click",".delete", function(e){ 
       e.preventDefault(); 
       $(this).parent('div').remove();x--;

      //=========================================
        var totalDrAmoutn = $("#tdebit").val();
        var restOfDrAmount = parseFloat($(this).attr('tdr'));
        totalDrAmoutn = totalDrAmoutn - restOfDrAmount;
        $("#tdebit").val( totalDrAmoutn );
      //=========================================
      
      //=========================================
        var totalCrAmoutn = $("#tcredit").val();
        var restOfCrAmount = parseFloat($(this).attr('tdr'));
        totalCrAmoutn = totalCrAmoutn - restOfCrAmount;
        $("#tcredit").val( totalCrAmoutn );
      //=========================================
       

      if (x == 1) {
        $('#tdebit').css({'display':'none'});
        $('#tcredit').css({'display':'none'});
        $('#narration').css({'display':'none'});
      }

      console.log(parseFloat($(this).attr('tdr')) );
      console.log(parseFloat($(this).attr('tcr')) );
      //console.log(x);
    })
//=========================================================
    

});


