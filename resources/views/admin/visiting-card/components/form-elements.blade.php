<div class="form-group row align-items-center" :class="{'has-danger': errors.has('user_id'), 'has-success': fields.user_id && fields.user_id.valid }">
    <label for="user_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.user_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input readonly type="text" v-model="form.user_id" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('user_id'), 'form-control-success': fields.user_id && fields.user_id.valid}" id="user_id" name="user_id" placeholder="{{ trans('admin.visiting-card.columns.user_id') }}">
        <div v-if="errors.has('user_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('user_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('user_type'), 'has-success': fields.user_type && fields.user_type.valid }">
    <label for="user_type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.user_type') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input readonly type="text" v-model="form.user_type" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('user_type'), 'form-control-success': fields.user_type && fields.user_type.valid}" id="user_type" name="user_type" placeholder="{{ trans('admin.visiting-card.columns.user_type') }}">
        <div v-if="errors.has('user_type')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('user_type') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}" id="name" name="name" placeholder="{{ trans('admin.visiting-card.columns.name') }}">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('phone'), 'has-success': fields.phone && fields.phone.valid }">
    <label for="phone" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.phone') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.phone" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('phone'), 'form-control-success': fields.phone && fields.phone.valid}" id="phone" name="phone" placeholder="{{ trans('admin.visiting-card.columns.phone') }}">
        <div v-if="errors.has('phone')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('phone') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('address'), 'has-success': fields.address && fields.address.valid }">
    <label for="address" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.address') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.address" v-validate="''" id="address" name="address"></textarea>
        </div>
        <div v-if="errors.has('address')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('address') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('category'), 'has-success': fields.category && fields.category.valid }">
    <label for="category" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.category') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <select v-model="form.category" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('category'), 'form-control-success': fields.category && fields.category.valid}" id="category" name="category">
            <option value="general">general</option>
            <option value="groceries">groceries</option>
            <option value="referral">affiliate</option>
            <option value="pharmacies">pharmacies</option>
            <option value="bakery">bakery</option>
            <option value="car washer">car washer</option>
            <option value="cooking services">cooking services</option>
            <option value="cleaning">cleaning</option>
            <option value="driver and cab">driver and cab</option>
            <option value="stationary">stationary</option>
            <option value="household services">household services</option>
            <option value="ac service">ac service</option>
            <option value="blood bank">blood bank</option>
            <option value="carpenter">carpenter</option>
            <option value="doctor">doctor</option>
            <option value="electrician">electrician</option>
            <option value="carpenter">carpenter</option>
            <option value="gardener">gardener</option>
            <option value="home-and-office-repair">home-and-office-repair</option>
            <option value="labor">labor</option>
            <option value="lock master">lock master</option>
            <option value="mechanic">mechanic</option>
            <option value="mobile shops">mobile shops</option>
            <option value="gym">gym</option>
            <option value="painter">painter</option>
            <option value="plumber">plumber</option>
            <option value="real estate">real estate</option>
            <option value="welder">welder</option>
            <option value="architect">architect</option>
            <option value="banquet hall">banquet hall</option>
            <option value="barber shop">barber shop</option>
            <option value="beautician">beautician</option>
            <option value="hardware store">hardware store</option>
            <option value="interior designer">interior designer</option>
            <option value="lawyers and legal advisor">lawyers and legal advisor</option>
            <option value="pest control">pest control</option>
            <option value="tailor">tailor</option>
            <option value="travel">travel</option>
            <option value="hr services">hr services</option>
            <option value="other">other</option>
            <option value="school">school</option>
        </select>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">
    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.status') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <select v-model="form.status" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status">
            <option value="PENDING"> PENDING </option>
            <option value="APPROVED"> APPROVED </option>
            <option value="REJECTED"> REJECTED </option>
        </select>
    </div>
</div>

<div class="form-group row align-items-center d-none" :class="{'has-danger': errors.has('meta'), 'has-success': fields.meta && fields.meta.valid }">
    <label for="meta" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.meta') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="hidden" v-model="form.meta" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('meta'), 'form-control-success': fields.meta && fields.meta.valid}" id="meta" name="meta" placeholder="Meta">
        <div v-if="errors.has('meta')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('meta') }}</div>
    </div>
</div>


{{--<div class="form-group row align-items-center" :class="{'has-danger': errors.has('latlng'), 'has-success': fields.latlng && fields.latlng.valid }">--}}
{{--    <label for="latlng" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.latlng') }}</label>--}}
{{--        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">--}}
{{--        <input type="text" v-model="form.latlng" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('latlng'), 'form-control-success': fields.latlng && fields.latlng.valid}" id="latlng" name="latlng" placeholder="{{ trans('admin.visiting-card.columns.latlng') }}">--}}
{{--        <div v-if="errors.has('latlng')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('latlng') }}</div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="form-group row align-items-center" :class="{'has-danger': errors.has('email'), 'has-success': fields.email && fields.email.valid }">--}}
{{--    <label for="email" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.email') }}</label>--}}
{{--        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">--}}
{{--        <input type="text" v-model="form.email" v-validate="'email'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': fields.email && fields.email.valid}" id="email" name="email" placeholder="{{ trans('admin.visiting-card.columns.email') }}">--}}
{{--        <div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="form-group row align-items-center" :class="{'has-danger': errors.has('status'), 'has-success': fields.status && fields.status.valid }">--}}
{{--    <label for="status" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.visiting-card.columns.status') }}</label>--}}
{{--        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">--}}
{{--        <input type="text" v-model="form.status" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('status'), 'form-control-success': fields.status && fields.status.valid}" id="status" name="status" placeholder="{{ trans('admin.visiting-card.columns.status') }}">--}}
{{--        <div v-if="errors.has('status')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('status') }}</div>--}}
{{--    </div>--}}
{{--</div>--}}


