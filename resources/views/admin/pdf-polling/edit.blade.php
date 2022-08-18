@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.pdf-polling.actions.edit', ['name' => $pdfPolling->email]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <pdf-polling-form
                :action="'{{ $pdfPolling->resource_url }}'"
                :data="{{ $pdfPolling->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.pdf-polling.actions.edit', ['name' => $pdfPolling->email]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.pdf-polling.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </pdf-polling-form>

        </div>
    
</div>

@endsection