@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.firebase-url.actions.edit', ['name' => $firebaseUrl->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <firebase-url-form
                :action="'{{ $firebaseUrl->resource_url }}'"
                :data="{{ $firebaseUrl->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.firebase-url.actions.edit', ['name' => $firebaseUrl->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.firebase-url.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </firebase-url-form>

        </div>
    
</div>

@endsection