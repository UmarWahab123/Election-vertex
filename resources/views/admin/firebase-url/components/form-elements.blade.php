<div class="form-group row align-items-center" :class="{'has-danger': errors.has('image_url'), 'has-success': fields.image_url && fields.image_url.valid }">
    <label for="image_url" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.firebase-url.columns.image_url') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.image_url" v-validate="''" id="image_url" name="image_url"></textarea>
        </div>
        <div v-if="errors.has('image_url')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('image_url') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.firebase-url.columns.status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.status" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('admin.firebase-url.columns.status') }}">
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cron'), 'has-success': fields.cron && fields.cron.valid }">
    <label for="cron" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.firebase-url.columns.cron') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.cron" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cron'), 'form-control-success': fields.cron && fields.cron.valid}" id="cron" name="cron" placeholder="{{ trans('admin.firebase-url.columns.cron') }}">
        <div v-if="errors.has('cron')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cron') }}</div>
    </div>
</div>


