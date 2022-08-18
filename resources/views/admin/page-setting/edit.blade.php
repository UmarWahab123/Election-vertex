@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.page-setting.actions.edit', ['name' => $pageSetting->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <page-setting-form
                :action="'{{ $pageSetting->resource_url }}'"
                :data="{{ $pageSetting->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.page-setting.actions.edit', ['name' => $pageSetting->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.page-setting.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </page-setting-form>

        </div>
    
</div>

@endsection