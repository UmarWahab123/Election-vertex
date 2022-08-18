<!-- <div class="form-group row align-items-center" :class="{'has-danger': errors.has('bussiness_id'), 'has-success': fields.bussiness_id && fields.bussiness_id.valid }">
    <label for="bussiness_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.general-notice.columns.bussiness_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.bussiness_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('bussiness_id'), 'form-control-success': fields.bussiness_id && fields.bussiness_id.valid}" id="bussiness_id" name="bussiness_id" placeholder="{{ trans('admin.general-notice.columns.bussiness_id') }}">
        <div v-if="errors.has('bussiness_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('bussiness_id') }}</div>
    </div>
</div>
 -->
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('title'), 'has-success': fields.title && fields.title.valid }">
    <label for="title" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.general-notice.columns.title') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.title" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('title'), 'form-control-success': fields.title && fields.title.valid}" id="title" name="title" placeholder="{{ trans('admin.general-notice.columns.title') }}">
        <div v-if="errors.has('title')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('admin.general-notice.columns.title') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('content'), 'has-success': fields.content && fields.content.valid }">
    <label for="content" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.general-notice.columns.content') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.content" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('content'), 'form-control-success': fields.content && fields.content.valid}" id="content" name="content" placeholder="{{ trans('admin.general-notice.columns.content') }}">
        <div v-if="errors.has('content')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('content') }}</div>
    </div>
</div>


