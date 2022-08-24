@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.all-party.actions.edit', ['name' => $allParty->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <all-party-form
                :action="'{{ $allParty->resource_url }}'"
                :data="{{ $allParty->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.all-party.actions.edit', ['name' => $allParty->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.all-party.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </all-party-form>

        </div>
    
</div>

@endsection