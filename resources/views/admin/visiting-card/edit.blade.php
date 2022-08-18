@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.visiting-card.actions.edit', ['name' => $visitingCard->name]))

@section('body')

    <div class="container-xl">
        <div class="card">
            <visiting-card-form
                :action="'{{ $visitingCard->resource_url }}'"
                :data="{{ $visitingCard->toJson() }}"
                v-cloak
                inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.visiting-card.actions.edit', ['name' => $visitingCard->name]) }}
                    </div>

                    <div class="card-header" style="text-align-last: center;">
                        <img src="{{$image}}" alt="" width="50%" height="100%">
                    </div>


                    <div class="card-header" style="text-align-last: center;">
                        <textarea rows="5" class="col-md-9 col-xl-8" readonly>{{$visitingCard->meta}}</textarea>
                    </div>

                    <div class="card-body">
                        @include('admin.visiting-card.components.form-elements')
                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>

                </form>

        </visiting-card-form>

        </div>

</div>

@endsection
