@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.polling-station.actions.edit', ['name' => $pollingStation->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <polling-station-form
                :action="'{{ $pollingStation->resource_url }}'"
                :data="{{ $pollingStation->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.polling-station.actions.edit', ['name' => $pollingStation->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.polling-station.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </polling-station-form>

        </div>
    
</div>

@endsection