@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.voter-detail.actions.edit', ['name' => $voterDetail->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <voter-detail-form
                :action="'{{ $voterDetail->resource_url }}'"
                :data="{{ $voterDetail->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.voter-detail.actions.edit', ['name' => $voterDetail->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.voter-detail.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </voter-detail-form>

        </div>
    
</div>

@endsection