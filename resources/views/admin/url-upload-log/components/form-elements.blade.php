<div class="form-group row align-items-center" :class="{'has-danger': errors.has('user_id'), 'has-success': fields.user_id && fields.user_id.valid }">
    <label for="user_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.url-upload-log.columns.user_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.user_id" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('user_id'), 'form-control-success': fields.user_id && fields.user_id.valid}" id="user_id" name="user_id" placeholder="{{ trans('admin.url-upload-log.columns.user_id') }}">
        <div v-if="errors.has('user_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('user_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('files_count'), 'has-success': fields.files_count && fields.files_count.valid }">
    <label for="files_count" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.url-upload-log.columns.files_count') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.files_count" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('files_count'), 'form-control-success': fields.files_count && fields.files_count.valid}" id="files_count" name="files_count" placeholder="{{ trans('admin.url-upload-log.columns.files_count') }}">
        <div v-if="errors.has('files_count')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('files_count') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('url_meta'), 'has-success': fields.url_meta && fields.url_meta.valid }">
    <label for="url_meta" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.url-upload-log.columns.url_meta') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.url_meta" v-validate="''" id="url_meta" name="url_meta"></textarea>
        </div>
        <div v-if="errors.has('url_meta')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('url_meta') }}</div>
    </div>
</div>


