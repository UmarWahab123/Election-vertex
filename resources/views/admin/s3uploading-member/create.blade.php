@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.s3uploading-member.actions.create'))

@section('body')

    <div class="container-xl">

                <div class="card">
        
        <s3uploading-member-form
            :action="'{{ url('admin/s3uploading-members') }}'"
            v-cloak
            inline-template>

            <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="action" novalidate>
                
                <div class="card-header">
                    <i class="fa fa-plus"></i> {{ trans('admin.s3uploading-member.actions.create') }}
                </div>

                <div class="card-body">
                    @include('admin.s3uploading-member.components.form-elements')
                </div>
                                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" :disabled="submiting">
                        <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                        {{ trans('brackets/admin-ui::admin.btn.save') }}
                    </button>
                </div>
                
            </form>

        </s3uploading-member-form>

        </div>

        </div>

    
@endsection