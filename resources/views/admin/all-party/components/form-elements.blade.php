<div class="form-group row align-items-center" :class="{'has-danger': errors.has('party_name'), 'has-success': fields.party_name && fields.party_name.valid }">
    <label for="party_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.all-party.columns.party_name') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.party_name" v-validate="''" id="party_name" name="party_name"></textarea>
        </div>
        <div v-if="errors.has('party_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('party_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('party_image_url'), 'has-success': fields.party_image_url && fields.party_image_url.valid }">
    <label for="party_image_url" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.all-party.columns.party_image_url') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.party_image_url" v-validate="''" id="party_image_url" name="party_image_url"></textarea>
        </div>
        <div v-if="errors.has('party_image_url')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('party_image_url') }}</div>
    </div>
</div>



