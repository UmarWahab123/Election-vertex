@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.asset.actions.create'))

@section('body')

    <div class="container-xl">

        <div class="card">

            <asset-form
                :action="'{{ url('admin/assets') }}'"
                v-cloak
                inline-template>

                <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="action" novalidate>

                    <div class="card-header">
                        <i class="fa fa-plus"></i> {{ trans('admin.asset.actions.create') }}
                    </div>

                    <div class="card-body">

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

                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('htmlload'), 'has-success': fields.htmlload && fields.htmlload.valid }">
                            <label for="htmlload" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.asset.columns.htmlload') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
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
