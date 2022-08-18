@extends('brackets/admin-ui::admin.layout.default')
@section('body')


    <div class="container">
        <h1 class="text-center m-4">Election Expert Download Single Report</h1>
        <form id="SearchCard">
        <div class="row">
            <div class="col-4"></div>
             <div class="col-4"><input type="text" class="form-control" id="idCard" name="Cnic Number" placeholder="Enter CNIC .."></div>
            <div class="col-2"><button type="submit" class="btn btn-primary" name="Polling Station">Search</button></div>
        </div>

        </form>
        <div id="preloader" style="text-align: center; display: none;" >
            <img src="https://be.onecallapp.com/apps/onecall/tpl/home/images/loading2.gif" width="20%" alt="">
        </div>

<div class="container">
        <div class="voter-class" id="showResult" style="display: none;">
            <div class="main-voter-class">
                <div style="display: flex; justify-content: space-between;">
                    <div class="voter-detail">
                        <h4>Name: </h4> <p id="name"></p>
                        <h4>CNIC: </h4><p id="id_card"></p>
                        <h4>Mobile: </h4><p id="Mobile"></p>
                        <h4>Address</h4><p id="address"></p>
                        <h4>Polling Station</h4><p id="polling"></p>
                    </div>
                    <div class="party-flag">
                        <button class="download  btn btn-dark" name="download" id="download">
                            Download PDF
                        </button>
                    </div>
                </div>
                <div  class="button-tree">

                    <input type="hidden" id="familytree">
                </div>
            </div>

        </div>
</div>
        <div class="voter-class" id="show" style="display: none;">
            <div class="main-voter-class">
                <div style="display: flex; justify-content: space-between;">
                    <div class="voter-detail">
                        We are processing on data, it will be live soon ..
                    </div>
                </div>
            </div>
        </div>

@endsection
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script>
            $(document).ready(function () {

            $('#SearchCard').submit(function(e) {
                e.preventDefault();
                fieldClear();
                let idcard=$('#idCard').val();
            $.ajax({
                url: '{{url('admin/election/getIdcardResult')}}/' + idcard,
                type: "get",
                beforeSend: function () {
                      document.getElementById('preloader').style.display = "block";
                      document.getElementById('showResult').style.display = "none";
                      document.getElementById('show').style.display = "none";
                },
                success: function (response) {
                    if (response['message'] == 'R') {
                        $('#name').text(response['name']);
                        $('#id_card').text(response['idcard']);
                        $('#address').text(response['address']);
                        $('#polling').text(response['polling']);
                        $('#Mobile').text(response['Mobile']);
                        document.getElementById('showResult').style.display = "block";
                        document.getElementById('preloader').style.display = "none";
                        document.getElementById('show').style.display = "none";
                    }
                    else if((response['message'] == 'N'))
                    {
                        $('#id_card').text(response['idcard']);
                        $('#polling').text(response['polling']);
                        document.getElementById('showResult').style.display="block";
                        document.getElementById('preloader').style.display = "none";
                    }
                    else {
                        document.getElementById('show').style.display = "block";
                        document.getElementById('showResult').style.display="none";
                        document.getElementById('preloader').style.display = "none";
                    }
                }
            });
            });

            /*Download PDF*/

                $('#download').click(function(e) {
                    e.preventDefault();

                    let idcard=$('#idCard').val();
                    $.ajax({
                        url: '{{url('admin/election/downloadPdfuser')}}/' + idcard,
                        type: "get",
                        success: function (response) {

                        }
                    });
                });


            });

            function  fieldClear()
            {
                $('#name').text('');
                $('#id_card').text('');
                $('#address').text('');
                $('#polling').text('');
                $('#Mobile').text('');

            }
        </script>
