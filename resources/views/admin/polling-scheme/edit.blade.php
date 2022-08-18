@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.polling-scheme.actions.edit', ['name' => $pollingScheme->id]))

@section('body')

    <div class="container-xl">
        <div class="card">
            <polling-scheme-form
                :action="'{{ $pollingScheme->resource_url }}'"
                :data="{{ $pollingScheme->toJson() }}"
                v-cloak
                inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.polling-scheme.actions.edit', ['name' => $pollingScheme->id]) }}
                    </div>


                    <div class="card-body">
                        @include('admin.polling-scheme.components.form-elements')
                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>

                </form>

        </polling-scheme-form>

        </div>

</div>

@endsection
