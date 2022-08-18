@extends('brackets/admin-ui::admin.layout.default')
@section('title', trans('Voter Details Importer'))

@section('body')
    <h3 class="text-center">Import Voter Details</h3>
    <div class="row">
        <div class="col-md-4"></div>
    <div class="d-flex col-md-4 border border-info p-5 justify-content-center rounded">
        <form method="post" enctype="multipart/form-data" action="{{url('admin/voter-details/import-data')}}"
            class="col-md-8">
            @csrf
            <div class="col-md-12 ">
                <input type="file"  id="myfile" name="myfile" multiple required><br><br>
            </div>

            <div class="col-md-12 mt-5">
                <input disabled id="import_btn" type="submit" class="btn btn-primary" value="Upload File" style="width: 100%">
            </div>

        </form>
    </div>
</div>
    <!-- Modal  Validate-->
    <div class="modal fade" id="myModalValidate" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-danger justify-content-center">
                    <h3 class="text-center">Import Voter Details</h3>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <h4>
                            Have you recheck this file and want to continue import ?
                        </h4>
                        <p>Please Type 'YES' in below box</p>
                        <input type="text" name="validate_import" id="validate_import">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success col-md-6" id="validation_true">Yes</button>
                    <button type="button" class="btn btn-Danger col-md-6" data-dismiss="modal">No</button>
                </div>
            </div>

        </div>
    </div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#myfile').change(function () {
            $("#myModalValidate").modal('show');
            document.querySelector('#validation_true').addEventListener('click', e => {
                if($('#validate_import').val() === 'YES' && $('#myfile').val()){
                    $("#myModalValidate").modal('hide');
                    $("#import_btn")[0].removeAttribute("disabled");
                }else{
                    $('#myfile').val('');
                }
            })
        });
    })
</script>
