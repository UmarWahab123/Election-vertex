<div class="form-group row align-items-center" :class="{'has-danger': errors.has('polling_station_number'), 'has-success': fields.polling_station_number && fields.polling_station_number.valid }">
    <label for="polling_station_number" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-station.columns.polling_station_number') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.polling_station_number" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('polling_station_number'), 'form-control-success': fields.polling_station_number && fields.polling_station_number.valid}" id="polling_station_number" name="polling_station_number" placeholder="{{ trans('admin.polling-station.columns.polling_station_number') }}">
        <div v-if="errors.has('polling_station_number')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('polling_station_number') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('meta'), 'has-success': fields.meta && fields.meta.valid }">
    <label for="meta" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-station.columns.meta') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.meta" v-validate="''" id="meta" name="meta"></textarea>
        </div>
        <div v-if="errors.has('meta')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('meta') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('url_id'), 'has-success': fields.url_id && fields.url_id.valid }">
    <label for="url_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.polling-station.columns.url_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.url_id" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('url_id'), 'form-control-success': fields.url_id && fields.url_id.valid}" id="url_id" name="url_id" placeholder="{{ trans('admin.polling-station.columns.url_id') }}">
        <div v-if="errors.has('url_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('url_id') }}</div>
    </div>
</div>


