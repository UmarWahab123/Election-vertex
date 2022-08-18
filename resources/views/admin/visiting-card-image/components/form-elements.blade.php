<div class="form-group row align-items-center" :class="{'has-danger': errors.has('visiting_card_id'), 'has-success': fields.visiting_card_id && fields.visiting_card_id.valid }">
    <label for="visiting_card_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card-image.columns.visiting_card_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.visiting_card_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('visiting_card_id'), 'form-control-success': fields.visiting_card_id && fields.visiting_card_id.valid}" id="visiting_card_id" name="visiting_card_id" placeholder="{{ trans('admin.visiting-card-image.columns.visiting_card_id') }}">
        <div v-if="errors.has('visiting_card_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('visiting_card_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('image_link'), 'has-success': fields.image_link && fields.image_link.valid }">
    <label for="image_link" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card-image.columns.image_link') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.image_link" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('image_link'), 'form-control-success': fields.image_link && fields.image_link.valid}" id="image_link" name="image_link" placeholder="{{ trans('admin.visiting-card-image.columns.image_link') }}">
        <div v-if="errors.has('image_link')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('image_link') }}</div>
    </div>
</div>


