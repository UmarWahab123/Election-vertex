<div class="form-group row align-items-center" :class="{'has-danger': errors.has('user_id'), 'has-success': fields.user_id && fields.user_id.valid }">
    <label for="user_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.parchi-image.columns.user_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.user_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('user_id'), 'form-control-success': fields.user_id && fields.user_id.valid}" id="user_id" name="user_id" placeholder="{{ trans('admin.parchi-image.columns.user_id') }}">
        <div v-if="errors.has('user_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('user_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('candidate_name'), 'has-success': fields.candidate_name && fields.candidate_name.valid }">
    <label for="candidate_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('Candidate Name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.candidate_name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('candidate_name'), 'form-control-success': fields.candidate_name && fields.candidate_name.valid}" id="candidate_name" name="candidate_name" placeholder="{{ trans('Candidate Name') }}">
        <div v-if="errors.has('candidate_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('candidate_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('block_code'), 'has-success': fields.block_code && fields.block_code.valid }">
    <label for="block_code" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('Block Code') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.block_code" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('block_code'), 'form-control-success': fields.block_code && fields.block_code.valid}" id="block_code" name="block_code" placeholder="{{ trans('Candidate Name') }}">
        <div v-if="errors.has('block_code')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('Block Code') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('candidate_image_url'), 'has-success': fields.candidate_image_url && fields.candidate_image_url.valid }">
    <label for="candidate_image_url" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('candidate_image_url') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.candidate_image_url" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('candidate_image_url'), 'form-control-success': fields.candidate_image_url && fields.candidate_image_url.valid}" id="candidate_image_url" name="candidate_image_url" placeholder="{{ trans('candidate_image_url') }}">
        <div v-if="errors.has('candidate_image_url')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('candidate_image_url') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('Party'), 'has-success': fields.Party && fields.Party.valid }">
    <label for="Party" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.parchi-image.columns.Party') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <input class="form-control" v-model="form.Party" v-validate="''" id="Party" name="Party">
        </div>
        <div v-if="errors.has('Party')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('Party') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('image_url'), 'has-success': fields.image_url && fields.image_url.valid }">
    <label for="image_url" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.parchi-image.columns.image_url') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <input class="form-control" v-model="form.image_url" v-validate="''" id="image_url" name="image_url">
        </div>
        <div v-if="errors.has('image_url')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('image_url') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.parchi-image.columns.status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.status" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('admin.parchi-image.columns.status') }}">
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>


