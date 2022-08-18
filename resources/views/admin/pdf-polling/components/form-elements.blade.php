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
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cron_status'), 'has-success': fields.cron_status && fields.cron_status.valid }">
    <label for="Crone Status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Cron Status</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.cron_status" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cron_status'), 'form-control-success': fields.cron_status && fields.cron_status.valid}" id="cron_status" name="cron_status" placeholder="{{ trans('Crone Status') }}">
        <div v-if="errors.has('cron_status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cron_status') }}</div>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Status</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.status" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('Status') }}">
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('record_type'), 'has-success': fields.record_type && fields.record_type.valid }">
    <label for="record_type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Record type</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.record_type" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('record_type'), 'form-control-success': fields.record_type && fields.record_type.valid}" id="record_type" name="record_type" placeholder="{{ trans('record_type') }}">
        <div v-if="errors.has('record_type')" class="form-control-feedback form-text" v-cloak>@Record Type</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('type'), 'has-success': fields.type && fields.type.valid }">
    <label for="type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Type</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.type" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('type'), 'form-control-success': fields.type && fields.type.valid}" id="type" name="type" placeholder="{{ trans('admin.pdf-polling.columns.type') }}">
        <div v-if="errors.has('type')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('type') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('meta'), 'has-success': fields.meta && fields.meta.valid }">
    <label for="meta" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Meta</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.meta" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('meta'), 'form-control-success': fields.meta && fields.meta.valid}" id="meta" name="meta" placeholder="Meta">
        <div v-if="errors.has('meta')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('meta') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('party_type'), 'has-success': fields.party_type && fields.party_type.valid }">
    <label for="party_type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Party type</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.party_type" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('party_type'), 'form-control-success': fields.party_type && fields.party_type.valid}" id="party_type" name="party_type" placeholder="Party Type">
        <div v-if="errors.has('party_type')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('party_type') }}</div>
    </div>
</div>



<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cron_status'), 'has-success': fields.cron_status && fields.cron_status.valid }">
    <label for="cron_status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Party type</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.cron_status" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cron_status'), 'form-control-success': fields.cron_status && fields.cron_status.valid}" id="cron_status" name="cron_status" placeholder="Party Type">
        <div v-if="errors.has('cron_status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cron_status') }}</div>
    </div>
</div>

