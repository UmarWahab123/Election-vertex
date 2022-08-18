<div class="form-group row align-items-center" :class="{'has-danger': errors.has('email'), 'has-success': fields.email && fields.email.valid }">
    <label for="email" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.pdf-polling.columns.email') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.email" v-validate="'email'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': fields.email && fields.email.valid}" id="email" name="email" placeholder="{{ trans('admin.pdf-polling.columns.email') }}">
        <div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('block_code'), 'has-success': fields.block_code && fields.block_code.valid }">
    <label for="block_code" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.pdf-polling.columns.block_code') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.block_code" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('block_code'), 'form-control-success': fields.block_code && fields.block_code.valid}" id="block_code" name="block_code" placeholder="{{ trans('admin.pdf-polling.columns.block_code') }}">
        <div v-if="errors.has('block_code')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('block_code') }}</div>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cron_status'), 'has-success': fields.block_code && fields.cron_status.valid }">
    <label for="block_code" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.pdf-polling.columns.cron_status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.cron_status" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cron_status'), 'form-control-success': fields.cron_status && fields.cron_status.valid}" id="cron_status" name="cron_status" placeholder="{{ trans('Crone Status') }}">
        <div v-if="errors.has('cron_status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cron_status') }}</div>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('block_code'), 'has-success': fields.block_code && fields.block_code.valid }">
    <label for="block_code" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Block Code</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.block_code" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('block_code'), 'form-control-success': fields.block_code && fields.block_code.valid}" id="block_code" name="block_code" placeholder="{{ trans('admin.pdf-polling.columns.block_code') }}">
        <div v-if="errors.has('block_code')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('block_code') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('record_type'), 'has-success': fields.status && fields.record_type.valid }">
    <label for="record_type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Record type</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.record_type" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('record_type'), 'form-control-success': fields.record_type && fields.record_type.valid}" id="record_type" name="record_type" placeholder="{{ trans('admin.pdf-polling.columns.record_type') }}">
        <div v-if="errors.has('record_type')" class="form-control-feedback form-text" v-cloak>Record type</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('type'), 'has-success': fields.type && fields.type.valid }">
    <label for="type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Type</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.type" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('type'), 'form-control-success': fields.type && fields.type.valid}" id="type" name="type" placeholder="{{ trans('admin.pdf-polling.columns.type') }}">
        <div v-if="errors.has('type')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('type') }}</div>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('type'), 'has-success': fields.type && fields.type.valid }">
    <label for="type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Type</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.type" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('type'), 'form-control-success': fields.type && fields.type.valid}" id="type" name="type" placeholder="{{ trans('admin.pdf-polling.columns.type') }}">
        <div v-if="errors.has('type')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('type') }}</div>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('type'), 'has-success': fields.type && fields.type.valid }">
    <label for="type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Type</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.type" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('type'), 'form-control-success': fields.type && fields.type.valid}" id="type" name="type" placeholder="{{ trans('admin.pdf-polling.columns.type') }}">
        <div v-if="errors.has('type')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('type') }}</div>
    </div>
</div>


