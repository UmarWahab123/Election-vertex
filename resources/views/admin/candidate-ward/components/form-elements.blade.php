<div class="form-group row align-items-center" :class="{'has-danger': errors.has('user_id'), 'has-success': fields.user_id && fields.user_id.valid }">
    <label for="user_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.candidate-ward.columns.user_id') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <multiselect v-model="form.user_id" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" label="first_name" :options="{{ $users->toJson() }}"  open-direction="bottom"></multiselect>
        </div>
        <div v-if="errors.has('user_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('user_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('ward_id'), 'has-success': fields.ward_id && fields.ward_id.valid }">
    <label for="ward_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.candidate-ward.columns.ward_id') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <multiselect v-model="form.ward_id" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_an_option') }}" label="sector" :options="{{ $sectors->toJson() }}" :multiple="true" open-direction="bottom"></multiselect>
        </div>
        <div v-if="errors.has('ward_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('ward_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.candidate-ward.columns.status') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <select class="form-control" v-model="form.status" v-validate="''" id="status" name="status">
                <option value="ACTIVE">ACTIVE</option>
                <option value="DISABLE">DISABLE</option>
            </select>
        </div>
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>


