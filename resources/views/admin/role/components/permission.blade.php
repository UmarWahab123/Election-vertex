<div class="form-group row align-items-center" >
    <label for="guard_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">&nbsp;</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">

        <div class="row">

        @foreach($permissions as $permission)
                <div class="col-4">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" {{ in_array($permission->id, $assignPermission) ? 'checked="checked"' : '' }} id="permission_id_{{ $permission->id }}" v-model="form.permissions" name="permissions[]" value="{{ $permission->id }}">
                        <label class="form-check-label" for="permission_id_{{ $permission->id }}">{{ ucwords(str_replace('-',' ',str_replace('.',' ',$permission->name))) }}</label>
                    </div>
                </div>
        @endforeach

        </div>
    </div>
</div>
