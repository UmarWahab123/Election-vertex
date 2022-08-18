@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.polling-detail.actions.edit', ['name' => $pollingDetail->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <polling-detail-form
                :action="'{{ $pollingDetail->resource_url }}'"
                :data="{{ $pollingDetail->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.polling-detail.actions.edit', ['name' => $pollingDetail->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.polling-detail.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </polling-detail-form>

        </div>
    
</div>

@endsection