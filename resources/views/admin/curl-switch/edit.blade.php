@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.curl-switch.actions.edit', ['name' => $curlSwitch->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <curl-switch-form
                :action="'{{ $curlSwitch->resource_url }}'"
                :data="{{ $curlSwitch->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.curl-switch.actions.edit', ['name' => $curlSwitch->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.curl-switch.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </curl-switch-form>

        </div>
    
</div>

@endsection