@extends('brackets/admin-ui::admin.layout.default')
@section('body')

    <div class="container-fluid">
        <div class="form-group">
            <center>
                <h1 class="text-center">Election Expert</h1>
                <br>
                <h3 class="text-center">Add <u>Sector</u> with respect to <u>Block-code</u></h3>
            </center>
        </div>
        <div class="mt-2">
            <div id="NewRecord" class="text-center text-dark " style="display: none;"><h4>User Record Added Susccessfully</h4></div>
            {{--Add Ward With respect to Block code--}}
            <form id="electionform">
                <input type="hidden" id="userId" value="{{$userId}}" class="userId">

                <div class="row p-3">
                    <div class="col-4"></div>
                    <div class="col-md-4 col-sm-12">
                        <label for="ward">Constituency</label>

                        <input type="text" id="ward" class="form-control" required="required" name="ward" placeholder="Constituency">
                    </div>
                </div>
                <div class="row p-3">

                <div class="col-4"></div>

                    <div class="col-md-4 col-sm-12">

                    <label>Block Code</label>
                        <select class="form-control" required name="block_code">
                            <option selected disabled>Choose Block Code</option>
                            @foreach($blockcode as $row)
                                <option value="{{@$row->polling_station_number}}">{{@$row->polling_station_number}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row p-3">

                <div class="col-4"></div>

                    <div class="col-md-4 col-sm-12">

                    <label>Male Voter</label>
                        <input type="number" required="required" class="form-control" name="male" placeholder="Male Voter">
                    </div>
                </div>
                <div class="row p-3">

                <div class="col-4"></div>

                    <div class="col-md-4 col-sm-12">

                    <label>Female Voter</label>

                        <input type="number" required="required" class="form-control" name="female" placeholder="Female Voter">
                    </div>
                </div>
                <div class="row p-3">

                <div class="col-6"></div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary">Add New</button>
                    </div>

                </div>
            </form>
        </div>

    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModals" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onClick="window.location.reload();">&times;</button>
                </div>
                <div class="modal-body">
                    <h2 class="text-center" id="messageResponse"></h2>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onClick="window.location.reload();">Close</button>
                </div>
            </div>

        </div>
    </div>


@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function (){

        $('#electionform').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            console.log(formData);
            $.ajax({
                url: '{{url('admin/firebase/halqa-save')}}',
                type:"POST",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success:function(response){
                    console.log(response);
                    if(response['MESSAGE'] == 'NEW') {
                        document.getElementById('NewRecord').style.display="block";
                        reload();
                    }
                    else if(response['MESSAGE'] == 'ALREADY') {
                        $("#myModals").modal('show');
                        $("#messageResponse").text(`Block-Code Already Exist!`);
                    }
                },
                error: function()
                {
                    $("#myModals").modal('show');
                    $("#messageResponse").text(`Something Went Wrong Try Again!`);
                }
            });
        });
    });
    function reload()
    {
        window.location.href="https://vertex.plabesk.com/admin/firebase/halqa-index";
    }
</script>
