
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('serial_no'), 'has-success': fields.serial_no && fields.serial_no.valid }">
    <label for="ward" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('Serial No') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="number" v-model="form.serial_no" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('serial_no'), 'form-control-success': fields.serial_no && fields.serial_no.valid}" id="ward" name="serial_no" placeholder="{{ trans('Serial No') }}">
        <div v-if="errors.has('serial_no')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('serial_no') }}</div>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('ward'), 'has-success': fields.ward && fields.ward.valid }">
    <label for="ward" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-scheme.columns.ward') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.ward" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('ward'), 'form-control-success': fields.ward && fields.ward.valid}" id="ward" name="ward" placeholder="{{ trans('admin.polling-scheme.columns.ward') }}">
        <div v-if="errors.has('ward')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('ward') }}</div>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('gender_type'), 'has-success': fields.gender_type && fields.gender_type.valid }">
    <label for="gender_type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('Gender Type') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.gender_type" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('gender_type'), 'form-control-success': fields.gender_type && fields.gender_type.valid}" id="ward" name="gender_type" placeholder="{{ trans('admin.polling-scheme.columns.gender_type') }}">
        <div v-if="errors.has('gender_type')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('gender_type') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('polling_station_area'), 'has-success': fields.polling_station_area && fields.polling_station_area.valid }">
    <label for="polling_station_area_urdu" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-scheme.columns.polling_station_area') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.polling_station_area" v-validate="''" id="polling_station_area" name="polling_station_area"></textarea>
        </div>
        <div v-if="errors.has('polling_station_area')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('polling_station_area_urdu') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('block_code_area'), 'has-success': fields.block_code_area && fields.block_code_area.valid }">
    <label for="block_code_area" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-scheme.columns.block_code_area') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.block_code_area" v-validate="''" id="block_code_area" name="block_code_area"></textarea>
        </div>
        <div v-if="errors.has('block_code_area')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('block_code_area') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('block_code'), 'has-success': fields.block_code && fields.block_code.valid }">
    <label for="block_code" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-scheme.columns.block_code') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.block_code" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('block_code'), 'form-control-success': fields.block_code && fields.block_code.valid}" id="block_code" name="block_code" placeholder="{{ trans('admin.polling-scheme.columns.block_code') }}">
        <div v-if="errors.has('block_code')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('block_code') }}</div>
    </div>
</div>

{{--<div class="form-group row align-items-center" :class="{'has-danger': errors.has('latlng'), 'has-success': fields.latlng && fields.latlng.valid }">--}}
{{--    <label for="latlng" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-scheme.columns.latlng') }}</label>--}}
{{--        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">--}}
{{--        <input type="text" v-model="form.latlng" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('latlng'), 'form-control-success': fields.latlng && fields.latlng.valid}" id="latlng" name="latlng" placeholder="{{ trans('admin.polling-scheme.columns.latlng') }}">--}}
{{--        <div v-if="errors.has('latlng')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('latlng') }}</div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-scheme.columns.status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.status" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('admin.polling-scheme.columns.status') }}">
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('polling_station_area_urdu'), 'has-success': fields.polling_station_area_urdu && fields.polling_station_area_urdu.valid }">
    <label for="polling_station_area_urdu" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Polling station area urdu</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.polling_station_area_urdu" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('polling_station_area_urdu'), 'form-control-success': fields.polling_station_area_urdu && fields.polling_station_area_urdu.valid}" id="polling_station_area_urdu" name="polling_station_area_urdu" placeholder="{{ trans('admin.polling-scheme.columns.polling_station_area_urdu') }}">
        <div v-if="errors.has('polling_station_area_urdu')" class="form-control-feedback form-text" v-cloak></div>
    </div>
</div>


<div class="form-group row align-items-center" :class="{'has-danger': errors.has('male_both'), 'has-success': fields.male_both && fields.male_both.valid }">
    <label for="male_both" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">male_both</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.male_both" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('male_both'), 'form-control-success': fields.male_both && fields.male_both.valid}" id="male_both" name="male_both" placeholder="male_both">
        <div v-if="errors.has('male_both')" class="form-control-feedback form-text" v-cloak></div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('female_both'), 'has-success': fields.female_both && fields.female_both.valid }">
    <label for="female_both" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">female_both</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.female_both" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('female_both'), 'form-control-success': fields.female_both && fields.female_both.valid}" id="female_both" name="polling_station_area_urdu" placeholder="female_both">
        <div v-if="errors.has('female_both')" class="form-control-feedback form-text" v-cloak></div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('total_both'), 'has-success': fields.total_both && fields.total_both.valid }">
    <label for="total_both" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">total_both</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.total_both" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('total_both'), 'form-control-success': fields.total_both && fields.total_both.valid}" id="total_both" name="polling_station_area_urdu" placeholder="total_both">
        <div v-if="errors.has('total_both')" class="form-control-feedback form-text" v-cloak></div>
    </div>
</div>
