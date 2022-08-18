<div class="form-group row align-items-center" :class="{'has-danger': errors.has('meta_key'), 'has-success': fields.meta_key && fields.meta_key.valid }">
    <label for="meta_key" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.election-setting.columns.meta_key') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.meta_key" v-validate="'required'" id="meta_key" name="meta_key"></textarea>
        </div>
        <div v-if="errors.has('meta_key')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('meta_key') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('meta_value'), 'has-success': fields.meta_value && fields.meta_value.valid }">
    <label for="meta_value" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.election-setting.columns.meta_value') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.meta_value" v-validate="'required'" id="meta_value" name="meta_value"></textarea>
        </div>
        <div v-if="errors.has('meta_value')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('meta_value') }}</div>
    </div>
</div>


