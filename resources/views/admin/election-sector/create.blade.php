@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.election-sector.actions.create'))

@section('body')

    <div class="container-xl">

                <div class="card">
        
        <election-sector-form
            :action="'{{ url('admin/election-sectors') }}'"
            v-cloak
            inline-template>

            <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="action" novalidate>
                
                <div class="card-header">
                    <i class="fa fa-plus"></i> {{ trans('admin.election-sector.actions.create') }}
                </div>

                <div class="card-body">
                    @include('admin.election-sector.components.form-elements')
                </div>
                                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" :disabled="submiting">
                        <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                        {{ trans('brackets/admin-ui::admin.btn.save') }}
                    </button>
                </div>
                
            </form>

        </election-sector-form>

        </div>

        </div>

    
@endsection