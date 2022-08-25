@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.auditable.actions.edit', ['name' => $auditable->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <auditable-form
                :action="'{{ $auditable->resource_url }}'"
                :data="{{ $auditable->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.auditable.actions.edit', ['name' => $auditable->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.auditable.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </auditable-form>

        </div>
    
</div>

@endsection