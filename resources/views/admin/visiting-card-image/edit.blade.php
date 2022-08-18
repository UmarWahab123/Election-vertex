@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.visiting-card-image.actions.edit', ['name' => $visitingCardImage->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <visiting-card-image-form
                :action="'{{ $visitingCardImage->resource_url }}'"
                :data="{{ $visitingCardImage->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.visiting-card-image.actions.edit', ['name' => $visitingCardImage->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.visiting-card-image.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </visiting-card-image-form>

        </div>
    
</div>

@endsection