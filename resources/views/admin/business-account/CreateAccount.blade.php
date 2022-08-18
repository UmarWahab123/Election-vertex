@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('Create'))

@section('body')
    <div class="container-xl">
        <div class="card">
            <business-account-form
                :action="'{{ url('admin/business-accounts/store-account') }}'"
                v-cloak
                inline-template>

                <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="action" novalidate>

                    <div class="card-header">
                        <i class="fa fa-plus"></i> {{ trans('Create') }}
                    </div>

                    <div class="card-body">
                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('ref_id'), 'has-success': fields.ref_id && fields.ref_id.valid }">
                            <label for="ref_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('Refernce Id') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
                                <input type="text" v-model="form.ref_id" v-validate="''" required="required" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('ref_id'), 'form-control-success': fields.ref_id && fields.ref_id.valid}" id="ref_id" name="ref_id" placeholder="{{ trans('Reference Id') }}">
                                <div v-if="errors.has('ref_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('ref_id') }}</div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('balance_type'), 'has-success': fields.balance_type && fields.balance_type.valid }">
                            <label for="balance_type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('Balance Type') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
                                <select  v-model="form.balance_type" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('balance_type'), 'form-control-success': fields.balance_type && fields.balance_type.valid}" id="balance_type" name="balance_type" placeholder="{{ trans('balance_type') }}">
                                    <option value="credit" selected="selected">Credit</option>
                                    <option value="debit">Debit</option>
                                </select>
                                <div v-if="errors.has('credit')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('credit') }}</div>
                            </div>
                        </div>
                        <div style="display: none" id="creditDiv" class="form-group row align-items-center" :class="{'has-danger': errors.has('credit'), 'has-success': fields.credit && fields.credit.valid }">
                            <label for="credit" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('Credit') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
                                <input type="number"   onKeyPress="if(this.value.length==6) return false;" v-model="form.credit" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('credit'), 'form-control-success': fields.credit && fields.credit.valid}" id="credit" name="credit" placeholder="{{ trans('Credit') }}">
                                <div v-if="errors.has('credit')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('credit') }}</div>
                            </div>
                        </div>
                        <div style="display: none" id="debitDiv" class="form-group row align-items-center" :class="{'has-danger': errors.has('debit'), 'has-success': fields.debit && fields.debit.valid }">
                            <label for="debit" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('Debit') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
                                <input type="number"  onKeyPress="if(this.value.length==5) return false;" v-model="form.debit" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('debit'), 'form-control-success': fields.debit && fields.debit.valid}" id="debit" name="debit" placeholder="{{ trans('debit') }}">
                                <div v-if="errors.has('debit')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('debit') }}</div>
                            </div>
                        </div>
                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('details'), 'has-success': fields.details && fields.details.valid }">
                            <label for="details" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('Details') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
                                <div>
                                    <textarea class="form-control" v-model="form.details" v-validate="''" id="details" name="details"></textarea>
                                </div>
                                <div v-if="errors.has('details')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('details') }}</div>
                            </div>
                        </div>

                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                </form>
            </business-account-form>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#balance_type').change(function(e) {
            if ($(this).val() === 'credit') {
                // console.log($(this).val())
                document.getElementById('creditDiv').style.display = 'flex';
                document.getElementById('debitDiv').style.display = 'none';
                document.getElementById('debit').value('');

            }
            else if ($(this).val() === 'debit') {
                // console.log($(this).val())
                document.getElementById('debitDiv').style.display = 'flex';
                document.getElementById('creditDiv').style.display = 'none';
                document.getElementById('credit').value('');
            } else {
                $('#balance_type').val('');
                document.getElementById('creditDiv').style.display = 'none';
                document.getElementById('debitDiv').style.display = 'none';
            }

        });

    });
</script>
