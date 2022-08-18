<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.s3uploading-member.columns.name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}" id="name" name="name" placeholder="{{ trans('admin.s3uploading-member.columns.name') }}">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('email'), 'has-success': fields.email && fields.email.valid }">
    <label for="email" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.s3uploading-member.columns.email') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.email" v-validate="'required|email'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': fields.email && fields.email.valid}" id="email" name="email" placeholder="{{ trans('admin.s3uploading-member.columns.email') }}">
        <div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('password'), 'has-success': fields.password && fields.password.valid }">
    <label for="password" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.s3uploading-member.columns.password') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="password" v-model="form.password" v-validate="'min:7'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('password'), 'form-control-success': fields.password && fields.password.valid}" id="password" name="password" placeholder="{{ trans('admin.s3uploading-member.columns.password') }}" ref="password">
        <div v-if="errors.has('password')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('password') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('password_confirmation'), 'has-success': fields.password_confirmation && fields.password_confirmation.valid }">
    <label for="password_confirmation" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.s3uploading-member.columns.password_repeat') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="password" v-model="form.password_confirmation" v-validate="'confirmed:password|min:7'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('password_confirmation'), 'form-control-success': fields.password_confirmation && fields.password_confirmation.valid}" id="password_confirmation" name="password_confirmation" placeholder="{{ trans('admin.s3uploading-member.columns.password') }}" data-vv-as="password">
        <div v-if="errors.has('password_confirmation')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('password_confirmation') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('phone'), 'has-success': fields.phone && fields.phone.valid }">
    <label for="phone" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.s3uploading-member.columns.phone') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.phone" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('phone'), 'form-control-success': fields.phone && fields.phone.valid}" id="phone" name="phone" placeholder="{{ trans('admin.s3uploading-member.columns.phone') }}">
        <div v-if="errors.has('phone')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('phone') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('party'), 'has-success': fields.party && fields.party.valid }">
    <label for="party" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.s3uploading-member.columns.party') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.party" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('party'), 'form-control-success': fields.party && fields.party.valid}" id="party" name="party" placeholder="{{ trans('admin.s3uploading-member.columns.party') }}">
        <div v-if="errors.has('party')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('party') }}</div>
    </div>
</div>

{{--<div class="form-group row align-items-center" :class="{'has-danger': errors.has('last_login'), 'has-success': fields.last_login && fields.last_login.valid }">--}}
{{--    <label for="last_login" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.s3uploading-member.columns.last_login') }}</label>--}}
{{--        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">--}}
{{--        <input type="text" v-model="form.last_login" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('last_login'), 'form-control-success': fields.last_login && fields.last_login.valid}" id="last_login" name="last_login" placeholder="{{ trans('admin.s3uploading-member.columns.last_login') }}">--}}
{{--        <div v-if="errors.has('last_login')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('last_login') }}</div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="form-group row align-items-center" :class="{'has-danger': errors.has('ip_address'), 'has-success': fields.ip_address && fields.ip_address.valid }">--}}
{{--    <label for="ip_address" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.s3uploading-member.columns.ip_address') }}</label>--}}
{{--        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">--}}
{{--        <input type="text" v-model="form.ip_address" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('ip_address'), 'form-control-success': fields.ip_address && fields.ip_address.valid}" id="ip_address" name="ip_address" placeholder="{{ trans('admin.s3uploading-member.columns.ip_address') }}">--}}
{{--        <div v-if="errors.has('ip_address')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('ip_address') }}</div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="form-group row align-items-center" :class="{'has-danger': errors.has('is_loggedin'), 'has-success': fields.is_loggedin && fields.is_loggedin.valid }">--}}
{{--    <label for="is_loggedin" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.s3uploading-member.columns.is_loggedin') }}</label>--}}
{{--        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">--}}
{{--        <input type="text" v-model="form.is_loggedin" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('is_loggedin'), 'form-control-success': fields.is_loggedin && fields.is_loggedin.valid}" id="is_loggedin" name="is_loggedin" placeholder="{{ trans('admin.s3uploading-member.columns.is_loggedin') }}">--}}
{{--        <div v-if="errors.has('is_loggedin')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('is_loggedin') }}</div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.s3uploading-member.columns.status') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">

           <select v-model="form.status" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('admin.s3uploading-member.columns.status') }}">
                <option value="Active">Active</option>
                <option value="Blocked">Blocked</option>
           </select>

            <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>
    </div>
</div>


