@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.business-account.actions.edit', ['name' => $businessAccount->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <business-account-form
                :action="'{{ $businessAccount->resource_url }}'"
                :data="{{ $businessAccount->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.business-account.actions.edit', ['name' => $businessAccount->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.business-account.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </business-account-form>

        </div>
    
</div>

@endsection