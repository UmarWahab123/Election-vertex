<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-app.js"></script>
<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-analytics.js"></script>
<!-- Add Firebase products that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-storage.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

<input type="hidden" value="{{Auth::user()->id}}" id="userid" name="user_id">
<div class="modal" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
         <div class="payment-modal">

         </div>
        </div>
       
      </div>
    </div>
  </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        var user_id = $('#userid').val();
          $.ajax({
               type: 'GET',
               url: "{{ url('admin/paymentpopup') }}/"+user_id,
               data: user_id,
               success: function(data){
                 console.log('status',data.response);
                 if(data.response == '1'){
                   $("#paymentModal").modal('show');
                   $('.payment-modal').append(data.modal);
                 }else{
                   $("#paymentModal").modal('hide');
                 }
   
               }
           });
    $(document).on('submit','#paymentForm',function(e){
        e.preventDefault();
        var token = $('input[name=_token]').val();
        var pendingAmount=$(".pending-amount").text();
        var payAmount=$('input[name="amount"]').val();
        if(payAmount==pendingAmount){
          $(".errortype").addClass('d-none');
        }else{
          $(".errortype").html("Incorrect Amount ! amount paid must be the same is amount pending.");
          $(".errortype").removeClass('d-none');
          setTimeout(function(){
            $(".errortype").addClass('d-none');
         },5000);
          return false;
        }
        $(".upload-btn").attr("disabled", true).html('Uploading...');
        var formdata=$('#paymentForm').serialize();
        $.ajax({
                type:"post",
                headers: {'X-CSRF-TOKEN': token},
                url: "{{url('/admin/savepayment')}}",
                data:formdata,
                success:function(data)
                 {
                  if(status==0){
                //   $(".checkPayment").html(data.response);
                //   $(".checkPayment").removeClass('d-none');
                  $(".upload-btn").attr("disabled", false).html('Upload');
                  Swal.fire(data.response)
                  $('#paymentForm').trigger("reset");
                  return false;
                  }else{
                //    $(".checkPayment").addClass('d-none');
                   Swal.fire(data.response)
                   $('#paymentForm').trigger("reset");
                   $(".upload-btn").attr("disabled", false).html('Upload');
                  }
                 
                }
            });

        });
    });
  
</script>