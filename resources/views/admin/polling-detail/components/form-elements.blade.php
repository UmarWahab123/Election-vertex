<div class="form-group row align-items-center" :class="{'has-danger': errors.has('polling_station_id'), 'has-success': fields.polling_station_id && fields.polling_station_id.valid }">
    <label for="polling_station_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-detail.columns.polling_station_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.polling_station_id" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('polling_station_id'), 'form-control-success': fields.polling_station_id && fields.polling_station_id.valid}" id="polling_station_id" name="polling_station_id" placeholder="{{ trans('admin.polling-detail.columns.polling_station_id') }}">
        <div v-if="errors.has('polling_station_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('polling_station_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('serial_no'), 'has-success': fields.serial_no && fields.serial_no.valid }">
    <label for="serial_no" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Serial No</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.serial_no" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('serial_no'), 'form-control-success': fields.serial_no && fields.serial_no.valid}" id="serial_no" name="serial_no" placeholder="{{ trans('admin.polling-detail.columns.serial_no') }}">
        <div v-if="errors.has('serial_no')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('serial_no') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('family_no'), 'has-success': fields.family_no && fields.family_no.valid }">
    <label for="family_no" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Family No</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.family_no" class="form-control" :class="{'form-control-danger': errors.has('family_no'), 'form-control-success': fields.family_no && fields.family_no.valid}" id="family_no" name="family_no" placeholder="{{ trans('admin.polling-detail.columns.family_no') }}">
        <div v-if="errors.has('family_no')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('family_no') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('gender'), 'has-success': fields.gender && fields.gender.valid }">
    <label for="gender" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Family No</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.gender" class="form-control" :class="{'form-control-danger': errors.has('gender'), 'form-control-success': fields.gender && fields.gender.valid}" id="gender" name="gender" placeholder="{{ trans('admin.polling-detail.columns.gender') }}">
        <div v-if="errors.has('gender')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('gender') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('polling_station_number'), 'has-success': fields.polling_station_number && fields.polling_station_number.valid }">
    <label for="polling_station_number" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-detail.columns.polling_station_number') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.polling_station_number" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('polling_station_number'), 'form-control-success': fields.polling_station_number && fields.polling_station_number.valid}" id="polling_station_number" name="polling_station_number" placeholder="{{ trans('admin.polling-detail.columns.polling_station_number') }}">
        <div v-if="errors.has('polling_station_number')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('polling_station_number') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cnic'), 'has-success': fields.cnic && fields.cnic.valid }">
    <label for="cnic" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-detail.columns.cnic') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.cnic" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cnic'), 'form-control-success': fields.cnic && fields.cnic.valid}" id="cnic" name="cnic" placeholder="{{ trans('admin.polling-detail.columns.cnic') }}">
        <div v-if="errors.has('cnic')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cnic') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('page_no'), 'has-success': fields.page_no && fields.page_no.valid }">
    <label for="page_no" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-detail.columns.page_no') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.page_no" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('page_no'), 'form-control-success': fields.page_no && fields.page_no.valid}" id="page_no" name="page_no" placeholder="{{ trans('admin.polling-detail.columns.page_no') }}">
        <div v-if="errors.has('page_no')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('page_no') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('url'), 'has-success': fields.url && fields.url.valid }">
    <label for="url" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-detail.columns.url') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.url" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('url'), 'form-control-success': fields.url && fields.url.valid}" id="url" name="url" placeholder="{{ trans('admin.polling-detail.columns.url') }}">
        <div v-if="errors.has('url')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('url') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('url_id'), 'has-success': fields.url_id && fields.url_id.valid }">
    <label for="url_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-detail.columns.url_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.url_id" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('url_id'), 'form-control-success': fields.url_id && fields.url_id.valid}" id="url_id" name="url_id" placeholder="{{ trans('admin.polling-detail.columns.url_id') }}">
        <div v-if="errors.has('url_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('url_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('boundingBox'), 'has-success': fields.boundingBox && fields.boundingBox.valid }">
    <label for="boundingBox" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-detail.columns.boundingBox') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.boundingBox" v-validate="''" id="boundingBox" name="boundingBox"></textarea>
        </div>
        <div v-if="errors.has('boundingBox')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('boundingBox') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('polygon'), 'has-success': fields.polygon && fields.polygon.valid }">
    <label for="polygon" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-detail.columns.polygon') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.polygon" v-validate="''" id="polygon" name="polygon"></textarea>
        </div>
        <div v-if="errors.has('polygon')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('polygon') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-detail.columns.status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.status" v-validate="'required|integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('admin.polling-detail.columns.status') }}">
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>


