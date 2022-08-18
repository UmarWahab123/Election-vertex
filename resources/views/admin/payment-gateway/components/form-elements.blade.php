<div class="form-group row align-items-center" :class="{'has-danger': errors.has('business_id'), 'has-success': fields.business_id && fields.business_id.valid }">
    <label for="business_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.business_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.business_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('business_id'), 'form-control-success': fields.business_id && fields.business_id.valid}" id="business_id" name="business_id" placeholder="{{ trans('admin.payment-gateway.columns.business_id') }}">
        <div v-if="errors.has('business_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('business_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('ref_id'), 'has-success': fields.ref_id && fields.ref_id.valid }">
    <label for="ref_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.ref_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.ref_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('ref_id'), 'form-control-success': fields.ref_id && fields.ref_id.valid}" id="ref_id" name="ref_id" placeholder="{{ trans('admin.payment-gateway.columns.ref_id') }}">
        <div v-if="errors.has('ref_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('ref_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('service_charges'), 'has-success': fields.service_charges && fields.service_charges.valid }">
    <label for="service_charges" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.service_charges') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.service_charges" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('service_charges'), 'form-control-success': fields.service_charges && fields.service_charges.valid}" id="service_charges" name="service_charges" placeholder="{{ trans('admin.payment-gateway.columns.service_charges') }}">
        <div v-if="errors.has('service_charges')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('service_charges') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('expiry_date'), 'has-success': fields.expiry_date && fields.expiry_date.valid }">
    <label for="expiry_date" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.expiry_date') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="date" v-model="form.expiry_date" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('expiry_date'), 'form-control-success': fields.expiry_date && fields.expiry_date.valid}" id="expiry_date" name="expiry_date" placeholder="{{ trans('admin.payment-gateway.columns.expiry_date') }}">
        <div v-if="errors.has('expiry_date')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('expiry_date') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('on_demand_cloud_computing'), 'has-success': fields.on_demand_cloud_computing && fields.on_demand_cloud_computing.valid }">
    <label for="on_demand_cloud_computing" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.on_demand_cloud_computing') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.on_demand_cloud_computing" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('on_demand_cloud_computing'), 'form-control-success': fields.on_demand_cloud_computing && fields.on_demand_cloud_computing.valid}" id="on_demand_cloud_computing" name="on_demand_cloud_computing" placeholder="{{ trans('admin.payment-gateway.columns.on_demand_cloud_computing') }}">
        <div v-if="errors.has('on_demand_cloud_computing')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('on_demand_cloud_computing') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('multi_bit_visual_redux'), 'has-success': fields.multi_bit_visual_redux && fields.multi_bit_visual_redux.valid }">
    <label for="multi_bit_visual_redux" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.multi_bit_visual_redux') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.multi_bit_visual_redux" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('multi_bit_visual_redux'), 'form-control-success': fields.multi_bit_visual_redux && fields.multi_bit_visual_redux.valid}" id="multi_bit_visual_redux" name="multi_bit_visual_redux" placeholder="{{ trans('admin.payment-gateway.columns.multi_bit_visual_redux') }}">
        <div v-if="errors.has('multi_bit_visual_redux')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('multi_bit_visual_redux') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('scan_reading'), 'has-success': fields.scan_reading && fields.scan_reading.valid }">
    <label for="scan_reading" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.scan_reading') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.scan_reading" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('scan_reading'), 'form-control-success': fields.scan_reading && fields.scan_reading.valid}" id="scan_reading" name="scan_reading" placeholder="{{ trans('admin.payment-gateway.columns.scan_reading') }}">
        <div v-if="errors.has('scan_reading')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('scan_reading') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('googly'), 'has-success': fields.googly && fields.googly.valid }">
    <label for="googly" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.googly') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.googly" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('googly'), 'form-control-success': fields.googly && fields.googly.valid}" id="googly" name="googly" placeholder="{{ trans('admin.payment-gateway.columns.googly') }}">
        <div v-if="errors.has('googly')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('googly') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('img_url'), 'has-success': fields.img_url && fields.img_url.valid }">
    <label for="img_url" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.img_url') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.img_url" v-validate="''" id="img_url" name="img_url"></textarea>
        </div>
        <div v-if="errors.has('img_url')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('img_url') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.status" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('admin.payment-gateway.columns.status') }}">
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('meta'), 'has-success': fields.meta && fields.meta.valid }">
    <label for="meta" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.payment-gateway.columns.meta') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.meta" v-validate="''" id="meta" name="meta"></textarea>
        </div>
        <div v-if="errors.has('meta')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('meta') }}</div>
    </div>
</div>


