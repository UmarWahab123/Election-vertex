<div class="form-group row align-items-center" :class="{'has-danger': errors.has('id_card'), 'has-success': fields.id_card && fields.id_card.valid }">
    <label for="id_card" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.voter-detail.columns.id_card') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.id_card" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('id_card'), 'form-control-success': fields.id_card && fields.id_card.valid}" id="id_card" name="id_card" placeholder="{{ trans('admin.voter-detail.columns.id_card') }}">
        <div v-if="errors.has('id_card')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('id_card') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('serial_no'), 'has-success': fields.serial_no && fields.serial_no.valid }">
    <label for="serial_no" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.voter-detail.columns.serial_no') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.serial_no" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('serial_no'), 'form-control-success': fields.serial_no && fields.serial_no.valid}" id="serial_no" name="serial_no" placeholder="{{ trans('admin.voter-detail.columns.serial_no') }}">
        <div v-if="errors.has('serial_no')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('serial_no') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('family_no'), 'has-success': fields.family_no && fields.family_no.valid }">
    <label for="family_no" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.voter-detail.columns.family_no') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.family_no" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('family_no'), 'form-control-success': fields.family_no && fields.family_no.valid}" id="family_no" name="family_no" placeholder="{{ trans('admin.voter-detail.columns.family_no') }}">
        <div v-if="errors.has('family_no')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('family_no') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.voter-detail.columns.name') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.name" v-validate="'required'" id="name" name="name"></textarea>
        </div>
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('father_name'), 'has-success': fields.father_name && fields.father_name.valid }">
    <label for="father_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.voter-detail.columns.father_name') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.father_name" v-validate="'required'" id="father_name" name="father_name"></textarea>
        </div>
        <div v-if="errors.has('father_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('father_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('address'), 'has-success': fields.address && fields.address.valid }">
    <label for="address" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.voter-detail.columns.address') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.address" v-validate="'required'" id="address" name="address"></textarea>
        </div>
        <div v-if="errors.has('address')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('address') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cron'), 'has-success': fields.cron && fields.cron.valid }">
    <label for="cron" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.voter-detail.columns.cron') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.cron" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cron'), 'form-control-success': fields.cron && fields.cron.valid}" id="cron" name="cron" placeholder="{{ trans('admin.voter-detail.columns.cron') }}">
        <div v-if="errors.has('cron')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cron') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.voter-detail.columns.status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.status" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('admin.voter-detail.columns.status') }}">
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('meta'), 'has-success': fields.meta && fields.meta.valid }">
    <label for="meta" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.voter-detail.columns.meta') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.meta" v-validate="'required'" id="meta" name="meta"></textarea>
        </div>
        <div v-if="errors.has('meta')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('meta') }}</div>
    </div>
</div>


