@extends('brackets/admin-ui::admin.layout.default')
@section('body')
    <div class="container">
        <h1 class="text-center">Election Expert Download Block Details Report</h1>
        <form id="SearchCard">
            @csrf
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4" style="display: none"><input type="email" id="email" class="form-control" name="email" value="{{$email}}" placeholder="Enter Email Address"></div>
                <div class="col-4"><input type="text" id="block" class="form-control" name="block" placeholder="Enter block code .."></div>
                <div class="col-2"><select id="record_type" class="form-control" name="record_type">
                        <option value="ALL">ALL</option>
                        <option value="SELECTED">SELECTED</option>
                    </select>

                </div>
{{--                @if($email != 'mpa13ias@gmail.com')--}}
                    <div class="col-2"><button type="submit" class="btn btn-primary">Download</button></div>
{{--                @endif--}}
            </div>
        </form>
        <div id="preloader" style="text-align: center; display: none;" >
            <img src="https://be.onecallapp.com/apps/onecall/tpl/home/images/loading2.gif" width="20%" alt="">
        </div>
        <div id="processing" style="text-align: center; display: none;" >
            <h6>we are processing on this data</h6>
        </div>
        <div id="show" style="text-align: center; display: none;" >
            <h6>This block data sent to your email address in 20 Minutes</h6>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
    $(document).ready(function () {

        $('#SearchCard').submit(function(e) {
            e.preventDefault();
            let block=$('#block').val();
            let email=$('#email').val();

            var formData = new FormData(this);
            // console.log(formData)
            $.ajax({
                type: "POST",
                url: '{{url('admin/election/blockDetailSearchPdf')}}',
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    document.getElementById('preloader').style.display = "block";
                },
                success: function (response) {
                    if (response['message'] == 'R') {
                        document.getElementById('show').style.display = "block";
                        document.getElementById('preloader').style.display = "none";

                    }
                    else
                    {
                        document.getElementById('preloader').style.display = "none";
                        document.getElementById('processing').style.display="block";
                    }
                }
            });
        });
    });

</script>
