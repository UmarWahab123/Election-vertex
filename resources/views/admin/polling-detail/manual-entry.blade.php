@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.firebase-url.actions.index'))
<style>
    .hidden{
        display: none !important;
    }
    label {
        color: black !important;
    }
</style>
@section('body')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>
                    Missing Details From <b>{{$count}}</b> Entries
                </div>
                <div class="card-body">
                    <div class="card-block">

                        <table id="table_data" class="table table-hover table-listing">
                            <thead>
                            <tr>

                                <th> ID </th>
                                <th> CNIC </th>
                                <th> Image </th>

                            </tr>

                            </thead>

                            <tbody>
                            @foreach ($data as $key => $item)
                                <tr id="{{ @$item->id }}">
                                    <td>{{ @$item->id }}</td>
                                    <td>{{ @$item->cnic }}</td>
                                    <td style="justify-content: flex-start !important;">
                                        <a data-toggle="modal" data-target="#myModal"
                                           data-pic="{{ $item->url }}"
                                           data-id="{{@$item->id}}"
                                           data-cnic="{{@$item->cnic}}"
                                           data-serial="{{@$item->serial_no}}"
                                           data-age="{{@$item->age}}"
                                           data-family="{{@$item->family_no}}"
                                           data-picSlice="{{@$item->pic_slice}}"
                                           class="dataModal">
                                            <img src="{{ $item->url }}" alt="" width="200" height="350" loading="lazy">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header flex-column justify-content-between align-items-center ">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Block Code </h4>
                </div>
                <div class="modal-body">
                    <div style="
                        width: 100%;
                        height: 550px;
                        overflow: scroll;
                    ">
                        <img id="pic" width="200%" src="" alt="" loading="lazy">
                    </div>


                    <div id="form" style="
                                border: 1px solid gray;
                                padding: 10px;
                                text-align-last: center;
                            ">
                        <form class="col add-polling">
                            @csrf
                            <h3 id="cnic_number"></h3>
                            <input type="hidden" name="detail_id" value="" id="detail_id">

                            <label for="serial_no">Serial No</label>
                            <input type="text" name="serial_no" id="serial_no" placeholder="Enter Serial No" required>
                            <br>

                            <label for="fam_no">Family No</label>
                            <input type="text" name="fam_no" id="fam_no" placeholder="Enter Faimly No">
                            <br>

                            <label for="age">Age</label>
                            <input type="text" name="age" id="age" placeholder="Enter Age" required>
                            <br>

                            <input id="save-btn" type="submit"
                                   class="btn btn-sm btn-spinner btn-info mt-2"
                                   value="Save Block Code" style="color: white;">
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    $(document).ready( function (){
        $( '.add-polling' ).submit(function(e) {
            e.preventDefault();

            var dataString = new FormData(this);
            var row = $(this).serializeArray();
            console.log(row);
            var row_id = row[1]['value'];

            $.ajax({
                contentType: false,
                processData: false,
                cache: false,
                type: "POST",
                url: "/admin/polling-details/save-details",
                data: dataString,
                success: function (res) {

                    console.log(res)
                    if (res['message'] === 'ok'){
                        $('#'+row_id).fadeOut(1000);
                    }else
                    {

                    }

                },
                complete:function(data){
                    // Hide image container
                    $("#myModal").modal('hide');
                    $('#save-btn').css({ 'pointer-events' : ''});
                }

            });

        });

        $('.dataModal').click(function (e){
            var picLink = $(this).attr("data-pic");
            var detail_id = $(this).attr("data-id");
            var cnic_no = $(this).attr("data-cnic");
            var s_no = $(this).attr("data-serial");
            var age = $(this).attr("data-age");
            var f_no = $(this).attr("data-family");
            var picSlice = $(this).attr("data-picSlice");

            $('#pic').attr('src', picSlice);
            $('#detail_id').val(detail_id);
            $('#cnic_number').text(cnic_no);
            $('#serial_no').val(s_no);
            $('#age').val(age);
            $('#fam_no').val(f_no);

        });

    } );
</script>

