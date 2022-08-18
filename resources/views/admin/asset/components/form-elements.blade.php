{{--<div class="form-group row align-items-center" :class="{'has-danger': errors.has('tag_id'), 'has-success': fields.tag_id && fields.tag_id.valid }">--}}
{{--    <label for="tag_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.asset.columns.tag_id') }}</label>--}}
{{--        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">--}}
{{--        <input type="text" v-model="form.tag_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('tag_id'), 'form-control-success': fields.tag_id && fields.tag_id.valid}" id="tag_id" name="tag_id" placeholder="{{ trans('admin.asset.columns.tag_id') }}">--}}
{{--        <div v-if="errors.has('tag_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('tag_id') }}</div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('tag_id'), 'has-success': fields.tag_id && fields.tag_id.valid }">
    <label for="tag_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.asset.columns.tag_id') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <select v-model="form.tag_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('tag_id'), 'form-control-success': fields.tag_id && fields.tag_id.valid}" id="tag_id" name="tag_id">
            @foreach($tags as $tag)
                <option value="{{$tag->id}}">
                    {{ $tag->tag_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('url'), 'has-success': fields.url && fields.url.valid }">
    <label for="url" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.asset.columns.url') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.url" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('url'), 'form-control-success': fields.url && fields.url.valid}" id="url" name="url" placeholder="{{ trans('admin.asset.columns.url') }}">
        <div v-if="errors.has('url')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('url') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('title'), 'has-success': fields.title && fields.title.valid }">
    <label for="title" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.asset.columns.title') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.title" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('title'), 'form-control-success': fields.title && fields.title.valid}" id="title" name="title" placeholder="{{ trans('admin.asset.columns.title') }}">
        <div v-if="errors.has('title')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('title') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('content'), 'has-success': fields.content && fields.content.valid }">
    <label for="content" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.asset.columns.content') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.content" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('content'), 'form-control-success': fields.content && fields.content.valid}" id="content" name="content" placeholder="{{ trans('admin.asset.columns.content') }}">
        <div v-if="errors.has('content')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('content') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.asset.columns.status') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <select v-model="form.status" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status">
            <option value="PENDING">PENDING</option>
            <option value="ACTIVE">ACTIVE</option>
        </select>
        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>


