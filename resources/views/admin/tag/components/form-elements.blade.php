<!--<div class="form-group row align-items-center" :class="{'has-danger': errors.has('business_id'), 'has-success': fields.business_id && fields.business_id.valid }">-->
<!--    <label for="business_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.tag.columns.business_id') }}</label>-->
<!--        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">-->
<!--        <input type="text" v-model="form.business_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('business_id'), 'form-control-success': fields.business_id && fields.business_id.valid}" id="business_id" name="business_id" placeholder="{{ trans('admin.tag.columns.business_id') }}">-->
<!--        <div v-if="errors.has('business_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('business_id') }}</div>-->
<!--    </div>-->
<!--</div>-->

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('tag_name'), 'has-success': fields.tag_name && fields.tag_name.valid }">
    <label for="tag_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.tag.columns.tag_name') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.tag_name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('tag_name'), 'form-control-success': fields.tag_name && fields.tag_name.valid}" id="tag_name" name="tag_name" placeholder="{{ trans('admin.tag.columns.tag_name') }}">
        <div v-if="errors.has('tag_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('tag_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.tag.columns.status') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <select v-model="form.status" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status">
            <option value="PENDING">PENDING</option>
            <option value="ACTIVE">ACTIVE</option>
        </select>
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>


