@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.create-cnic-detail-table.actions.edit', ['name' => $createCnicDetailTable->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <create-cnic-detail-table-form
                :action="'{{ $createCnicDetailTable->resource_url }}'"
                :data="{{ $createCnicDetailTable->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.create-cnic-detail-table.actions.edit', ['name' => $createCnicDetailTable->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.create-cnic-detail-table.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </create-cnic-detail-table-form>

        </div>
    
</div>

@endsection