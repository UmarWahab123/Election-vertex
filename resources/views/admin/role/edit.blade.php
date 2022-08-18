@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.role.actions.edit', ['name' => $role->name]))

@section('body')

    @php
        $newData =$role->toArray();
        $newData['permissions'] = $assignPermission;
    @endphp
    <div class="container-xl">
        <div class="card">
            <role-form
                :action="'{{ $role->resource_url }}'"
                :data="{{ json_encode($newData) }}"
                v-cloak
                inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.role.actions.edit', ['name' => $role->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.role.components.form-elements')
                        <div class="form-group row align-items-center" >
                            <label for="guard_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">&nbsp;</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        <div class="col-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" {{ in_array($permission->id, $assignPermission) ? 'checked="checked"' : '' }} v-model="form.permissions" id="permission_id_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}">
                                                <label class="form-check-label" for="permission_id_{{ $permission->id }}">{{ ucwords(str_replace('-',' ',str_replace('.',' ',$permission->name))) }}</label>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>

                </form>

        </role-form>

        </div>

</div>

@endsection
