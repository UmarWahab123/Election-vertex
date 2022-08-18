<div class="form-group row align-items-center" :class="{'has-danger': errors.has('sector'), 'has-success': fields.sector && fields.sector.valid }">
    <label for="sector" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.election-sector.columns.sector') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.sector" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('sector'), 'form-control-success': fields.sector && fields.sector.valid}" id="sector" name="sector" placeholder="{{ trans('admin.election-sector.columns.sector') }}">
        <div v-if="errors.has('sector')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('sector') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('block_code'), 'has-success': fields.block_code && fields.block_code.valid }">
    <label for="block_code" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.election-sector.columns.block_code') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.block_code" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('block_code'), 'form-control-success': fields.block_code && fields.block_code.valid}" id="block_code" name="block_code" placeholder="{{ trans('admin.election-sector.columns.block_code') }}">
        <div v-if="errors.has('block_code')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('block_code') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('male_vote'), 'has-success': fields.male_vote && fields.male_vote.valid }">
    <label for="male_vote" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.election-sector.columns.male_vote') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.male_vote" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('male_vote'), 'form-control-success': fields.male_vote && fields.male_vote.valid}" id="male_vote" name="male_vote" placeholder="{{ trans('admin.election-sector.columns.male_vote') }}">
        <div v-if="errors.has('male_vote')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('male_vote') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('female_vote'), 'has-success': fields.female_vote && fields.female_vote.valid }">
    <label for="female_vote" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.election-sector.columns.female_vote') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.female_vote" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('female_vote'), 'form-control-success': fields.female_vote && fields.female_vote.valid}" id="female_vote" name="female_vote" placeholder="{{ trans('admin.election-sector.columns.female_vote') }}">
        <div v-if="errors.has('female_vote')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('female_vote') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('total_vote'), 'has-success': fields.total_vote && fields.total_vote.valid }">
    <label for="total_vote" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.election-sector.columns.total_vote') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.total_vote" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('total_vote'), 'form-control-success': fields.total_vote && fields.total_vote.valid}" id="total_vote" name="total_vote" placeholder="{{ trans('admin.election-sector.columns.total_vote') }}">
        <div v-if="errors.has('total_vote')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('total_vote') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.election-sector.columns.status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.status" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('admin.election-sector.columns.status') }}">
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>


