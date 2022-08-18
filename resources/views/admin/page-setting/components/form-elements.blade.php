<div class="form-group row align-items-center" :class="{'has-danger': errors.has('business_id'), 'has-success': fields.business_id && fields.business_id.valid }">
    <label for="business_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.page-setting.columns.business_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.business_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('business_id'), 'form-control-success': fields.business_id && fields.business_id.valid}" id="business_id" name="business_id" placeholder="{{ trans('admin.page-setting.columns.business_id') }}">
        <div v-if="errors.has('business_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('business_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('tag_name'), 'has-success': fields.tag_name && fields.tag_name.valid }">
    <label for="tag_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.page-setting.columns.tag_name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.tag_name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('tag_name'), 'form-control-success': fields.tag_name && fields.tag_name.valid}" id="tag_name" name="tag_name" placeholder="{{ trans('admin.page-setting.columns.tag_name') }}">
        <div v-if="errors.has('tag_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('tag_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('businessHome_H1'), 'has-success': fields.businessHome_H1 && fields.businessHome_H1.valid }">
    <label for="businessHome_H1" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">BusinessHome_H1</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.businessHome_H1" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('businessHome_H1'), 'form-control-success': fields.businessHome_H1 && fields.businessHome_H1.valid}" id="businessHome_H1" name="businessHome_H1" placeholder="BusinessHome_H1">
        <div v-if="errors.has('businessHome_H1')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('businessHome_H1') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('businessHome_H2'), 'has-success': fields.businessHome_H2 && fields.businessHome_H2.valid }">
    <label for="businessHome_H2" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">BusinessHome_H2</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.businessHome_H2" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('businessHome_H2'), 'form-control-success': fields.businessHome_H2 && fields.businessHome_H2.valid}" id="businessHome_H2" name="businessHome_H2" placeholder="BusinessHome_H2">
        <div v-if="errors.has('businessHome_H2')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('businessHome_H2') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('businessHome_H3'), 'has-success': fields.businessHome_H3 && fields.businessHome_H3.valid }">
    <label for="businessHome_H3" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">BusinessHome_H3</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.businessHome_H3" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('businessHome_H3'), 'form-control-success': fields.businessHome_H3 && businessHome_H3.businessHome_H3.valid}" id="businessHome_H3" name="businessHome_H3" placeholder="BusinessHome_H3">
        <div v-if="errors.has('businessHome_H3')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('businessHome_H3') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('reg_title'), 'has-success': fields.reg_title && fields.reg_title.valid }">
    <label for="reg_title" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Registration Title</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.reg_title" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('reg_title'), 'form-control-success': fields.reg_title && reg_title.reg_title.valid}" id="reg_title" name="reg_title" placeholder="Registration Title">
        <div v-if="errors.has('reg_title')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('reg_title') }}</div>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('reg_img_title'), 'has-success': fields.reg_img_title && fields.reg_img_title.valid }">
    <label for="reg_img_title" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Image Title</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.reg_img_title" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('reg_img_title'), 'form-control-success': fields.reg_img_title && reg_img_title.reg_img_title.valid}" id="reg_img_title" name="reg_img_title" placeholder="Registration Image">
        <div v-if="errors.has('reg_img_title')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('reg_img_title') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.page-setting.columns.status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
            <select v-model="form.status" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" placeholder="Choose Status" id="status" name="status">
                <option value="PENDING">PENDING</option>
                <option value="ACTIVE">ACTIVE</option>
            </select>
       <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>


