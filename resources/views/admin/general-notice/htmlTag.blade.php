@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.general-notice.actions.index'))

@section('body')

    <div class="container-xl">

        <div class="card">

            <asset-form
                :action="'{{ url('admin/general-notices') }}'"
                v-cloak
                inline-template>

                <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="action" novalidate>

                    <div class="card-header">
                        <i class="fa fa-plus"></i> Add Html Tag
                    </div>

                    <div class="card-body">
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

            </asset-form>

        </div>

    </div>


@endsection
