@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.candidate-ward.actions.edit', ['name' => $candidateWard->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <candidate-ward-form
                :action="'{{ $candidateWard->resource_url }}'"
                :data="{{ $candidateWard->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.candidate-ward.actions.edit', ['name' => $candidateWard->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.candidate-ward.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </candidate-ward-form>

        </div>
    
</div>

@endsection