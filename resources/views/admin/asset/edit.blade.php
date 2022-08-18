@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.asset.actions.edit', ['name' => $asset->title]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <asset-form
                :action="'{{ $asset->resource_url }}'"
                :data="{{ $asset->toJson() }}"
                v-cloak
                inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.asset.actions.edit', ['name' => $asset->title]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.asset.components.form-elements')
                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('htmlload'), 'has-success': fields.htmlload && fields.htmlload.valid }">
                            <label for="htmlload" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.asset.columns.htmlload') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
                                {{--        <input type="text" v-model="form.htmlload" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('htmlload'), 'form-control-success': fields.htmlload && fields.htmlload.valid}" id="urlhtmlload" name="htmlload" placeholder="{{ trans('admin.asset.columns.htmlload') }}">--}}
                                <wysiwyg v-model="form.htmlload" v-validate="''" id="htmlload" name="htmlload" :config="mediaWysiwygConfig"></wysiwyg>
                                <div v-if="errors.has('htmlload')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('htmlload') }}</div>
                            </div>
                        </div>

                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>

                </form>

        </asset-form>

        </div>

</div>

@endsection
