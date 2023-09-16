function leftScroll(elementId) {
   const container = document.querySelector(`#${elementId}`);
   container.scrollBy(-200, 0);
}

function rightScroll(elementId) {
   const container = document.querySelector(`#${elementId}`);
   container.scrollBy(200, 0);
}


$(document).ready(function() {
   var previousHTML = $('#showBoolDetails').html();
   $('#getSearch').on("keyup", function() {
       var getBookdetails = $(this).val();
       var dropdownValue = $('#search_type').val();
       if(getBookdetails===''){
           $(this).val('');
           $('#showBoolDetails').empty().html(previousHTML);
           $("#showBoolDetails").removeClass("flexContent");
       }
       else{
           var valueToBeFetched = getBookdetails + ' ' + dropdownValue;
     $.ajax({
           method: 'POST',
           url: 'searchBook.php',
           data: {
               book_name: valueToBeFetched
           },
           success: function(response) {
               $("#showBoolDetails").addClass("flexContent");
               $("#showBoolDetails").html(response);
           }
       });

       }
   });
});