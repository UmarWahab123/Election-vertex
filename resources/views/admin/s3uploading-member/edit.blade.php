@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.s3uploading-member.actions.edit', ['name' => $s3uploadingMember->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <s3uploading-member-form
                :action="'{{ $s3uploadingMember->resource_url }}'"
                :data="{{ $s3uploadingMember->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.s3uploading-member.actions.edit', ['name' => $s3uploadingMember->name]) }}
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