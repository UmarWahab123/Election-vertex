<a href="{{ url('admin') }}" class="navbar-brand">
    {{-- You may use plain text as a logo instead of image --}}
    <div style="margin-left: 60px !important;">
<img src="https://crm.plabesk.com/usermodule/images/onecall.png" style="max-height: 46px !important;">
    	
    </div>
    {{--Text Logo--}}

</a>
{{-- <script>
 $(document).ready(function(){
     var user_id = $('#userid').val();
       $.ajax({
            type: 'GET',
            url: "{{ url('admin/paymentpopup') }}/"+user_id,
            data: user_id,
            success: function(data){
            //   console.log('status',data.response);
              if(data.response == '1'){
                window.location.href = "{{ url('admin/payment-receipt') }}";
              }else{
            //    alert('Your account is Active');
              }

            }
        });
    });
   
</script> --}}