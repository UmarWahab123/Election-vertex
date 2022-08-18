@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.parchi-image.actions.edit', ['name' => $parchiImage->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <parchi-image-form
                :action="'{{ $parchiImage->resource_url }}'"
                :data="{{ $parchiImage->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.parchi-image.actions.edit', ['name' => $parchiImage->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.parchi-image.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </parchi-image-form>

        </div>
    
</div>

@endsection