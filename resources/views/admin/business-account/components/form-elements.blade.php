<div class="form-group row align-items-center" :class="{'has-danger': errors.has('business_id'), 'has-success': fields.business_id && fields.business_id.valid }">
    <label for="business_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.business-account.columns.business_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.business_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('business_id'), 'form-control-success': fields.business_id && fields.business_id.valid}" id="business_id" name="business_id" placeholder="{{ trans('admin.business-account.columns.business_id') }}">
        <div v-if="errors.has('business_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('business_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('ref_id'), 'has-success': fields.ref_id && fields.ref_id.valid }">
    <label for="ref_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.business-account.columns.ref_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.ref_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('ref_id'), 'form-control-success': fields.ref_id && fields.ref_id.valid}" id="ref_id" name="ref_id" placeholder="{{ trans('admin.business-account.columns.ref_id') }}">
        <div v-if="errors.has('ref_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('ref_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('credit'), 'has-success': fields.credit && fields.credit.valid }">
    <label for="credit" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.business-account.columns.credit') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.credit" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('credit'), 'form-control-success': fields.credit && fields.credit.valid}" id="credit" name="credit" placeholder="{{ trans('admin.business-account.columns.credit') }}">
        <div v-if="errors.has('credit')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('credit') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('debit'), 'has-success': fields.debit && fields.debit.valid }">
    <label for="debit" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.business-account.columns.debit') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.debit" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('debit'), 'form-control-success': fields.debit && fields.debit.valid}" id="debit" name="debit" placeholder="{{ trans('admin.business-account.columns.debit') }}">
        <div v-if="errors.has('debit')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('debit') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('balance'), 'has-success': fields.balance && fields.balance.valid }">
    <label for="balance" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.business-account.columns.balance') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.balance" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('balance'), 'form-control-success': fields.balance && fields.balance.valid}" id="balance" name="balance" placeholder="{{ trans('admin.business-account.columns.balance') }}">
        <div v-if="errors.has('balance')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('balance') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('img_url'), 'has-success': fields.img_url && fields.img_url.valid }">
    <label for="img_url" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.business-account.columns.img_url') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.img_url" v-validate="''" id="img_url" name="img_url"></textarea>
        </div>
        <div v-if="errors.has('img_url')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('img_url') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.business-account.columns.status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.status" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('admin.business-account.columns.status') }}">
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('meta'), 'has-success': fields.meta && fields.meta.valid }">
    <label for="meta" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.business-account.columns.meta') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.meta" v-validate="''" id="meta" name="meta"></textarea>
        </div>
        <div v-if="errors.has('meta')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('meta') }}</div>
    </div>
</div>


