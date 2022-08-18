@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.general-notice.actions.edit', ['name' => $generalNotice->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <general-notice-form
                :action="'{{ $generalNotice->resource_url }}'"
                :data="{{ $generalNotice->toJson() }}"
                v-cloak
                inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.general-notice.actions.edit', ['name' => $generalNotice->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.general-notice.components.form-elements')
                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('html_tag'), 'has-success': fields.html_tag && fields.html_tag.valid }">
                            <label for="html_tag" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.general-notice.columns.html_tag') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
                                <wysiwyg v-model="form.html_tag" v-validate="''" id="html_tag" name="html_tag" :config="mediaWysiwygConfig"></wysiwyg>
                                <div v-if="errors.has('html_tag')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('html_tag') }}</div>
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

        </general-notice-form>

        </div>

</div>

@endsection
