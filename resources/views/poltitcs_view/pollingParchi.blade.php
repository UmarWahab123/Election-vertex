@extends('brackets/admin-ui::admin.layout.default')
@section('body')
    <div class="container">
        <h1 class="text-center">Election Expert Download Block Parchi</h1>
        <br>
        <br>
        <br>
        <form id="SearchCard">
            @csrf
            <div class="row">
                <div class="col-1"></div>
                <div class="col-1" style="display: none">
                    <input type="email" id="email" class="form-control" name="email" value="{{@$email}}" placeholder="Enter Email Address"></div>
                <div class="col-3">
                    <label >Enter Block Code</label>

                    <input type="number" id="block" required="required" class="form-control" name="block" placeholder="Enter block code ..">
                </div>
                <div class="col-3">
                    <label >Select Parchi Image</label>
                    <select class="form-control" name="parchi_image">
                        <option value="PMLN">PMLN</option>
                        <option value="PMLNQ">PMLNQ</option>
                        <option value="PTI">PTI</option>
                        <option value="JUI" >JUI</option>
                        <option value="JI" >JI</option>
                        <option value="MQM" >MQM</option>
                        <option value="TLP" >TLP</option>
                        <option value="PPP" >PPP</option>
                        <option value="AllahHoAkbar">AllahHoAkbar</option>
                        <option value="AwamiDostPanel">AwamiDostPanel</option>
                        <option value="MukhlasabadPanel">Mukhlasabad Panel</option>

                    </select>
                </div>
                <div class="col-3">
                    <label >Select Type</label>
                    <select class="form-control" name="type">
                        <option value="PARCHI">PARCHI</option>
                        <option value="LIST">LIST</option>
                    </select>
                </div>
                <br>
                <div class="col-2">
                    <label >&nbsp; </label><br>
                    <input type="submit" class="btn btn-primary">
                </div>
            </div>
        </form>
        <div id="preloader" style="text-align: center; display: none;" >
            <img src="https://be.onecallapp.com/apps/onecall/tpl/home/images/loading2.gif" width="20%" alt="">
        </div>
        <div id="show" class="mt-5" style="text-align: center; display: none;" >
            <h5>This block data sent to your email address in 20 Minutes</h5>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModals" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-danger justify-content-center">
                    <h4>
                        Input Warning ! Try Again.
                    </h4>
                </div>
                <div class="modal-body">
                    <h2 class="text-center" id="messageResponse"></h2>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning col-md-12" data-dismiss="modal">Close</button>
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
            var formData = new FormData(this);
            // console.log(formData)
            $.ajax({
                type: "POST",
                url: '{{url('admin/election/parchi-block-pdf-download')}}',
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    document.getElementById('preloader').style.display = "block";
                },
                success: function (response) {
                    document.getElementById('preloader').style.display = "none";

                    if (response['message'] == 'ADD_NEW')
                    {
                        document.getElementById('show').style.display = "block";
                    }
                    else if (response['message'] == 'ELECTION_SECTOR')
                    {
                        $("#myModals").modal('show');
                        $("#messageResponse").html(`Sector not found! Please add this sector before download.<br> <a href="https://vertex.plabesk.com/admin/election-sectors/create"> Click </a>to Add sector. `);
                    }
                    else if (response['message'] == 'BLOCK_CODE')
                    {
                        $("#myModals").modal('show');
                        $("#messageResponse").html(`Block Code are not found! Please add this block code before download. <br> <a href="https://vertex.plabesk.com/admin/firebase/imageUpload"> Click </a>to Add Block Code. `);

                    }
                    // else if (response['message'] == 'POLLING_SCHEME')
                    // {
                    //     $("#myModals").modal('show');
                    //     $("#messageResponse").html(`Polling scheme are not found! Please add this Polling scheme before download.<br> <a href="https://vertex.plabesk.com/admin/polling-schemes/create"> Click </a>to Add Polling scheme. `);
                    //
                    // }
                    else if (response['message'] == 'PARCHI_IMAGE')
                    {
                        $("#myModals").modal('show');
                        $("#messageResponse").html(`Candidate details are not found! Please add this Candidate details before download.<br> <a href="https://vertex.plabesk.com/admin/firebase/parchi-logo-image"> Click </a>to Add Candidate details. `);
                    }
                }
            });
        });
    });

</script>
