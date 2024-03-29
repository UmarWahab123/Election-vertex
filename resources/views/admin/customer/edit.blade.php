@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.customer.actions.edit', ['name' => $customer->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <customer-form
                :action="'{{ $customer->resource_url }}'"
                :data="{{ $customer->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.customer.actions.edit', ['name' => $customer->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.customer.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </customer-form>

        </div>
    
</div>

@endsection