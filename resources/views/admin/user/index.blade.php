@extends('brackets/admin-ui::admin.layout.default')
<style type="text/css">
    .row {
        display: flex;
        justify-content: space-around;
    }

    .rider img {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 20px;
    }

    .rider span {
        margin-top: 10px;
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 20px;
    }
    .container {
        margin-top: 100px
    }

    .section-title {
        margin-bottom: 38px
    }

    .shadow{
        box-shadow: 0px 15px 39px 0px rgba(8, 18, 109, 0.1) !important
    }

    .icon-primary {
        color: #062caf
    }

    .icon-bg-circle {
        position: relative
    }

    .icon-lg {
        font-size: -webkit-xxx-large;
    }

    .icon-bg-circle::before {
        z-index: 1;
        position: relative
    }

    .icon-bg-primary::after {
        background: #062caf !important
    }

    .icon-bg-circle::after {
        content: '';
        position: absolute;
        width: 68px;
        height: 68px;
        top: -35px;
        left: 15px;
        border-radius: 50%;
        background: inherit;
        opacity: .1
    }

    p

    .icon-bg-yellow::after {
        background: #f6a622 !important
    }

    .icon-bg-purple::after {
        background: #7952f5
    }

    .icon-yellow {
        color: #f6a622
    }

    .icon-purple {
        color: #7952f5
    }

    .icon-cyan {
        color: #02d0a1
    }

    .icon-bg-cyan::after {
        background: #02d0a1
    }

    .icon-bg-red::after {
        background: #ff4949
    }

    .icon-red {
        color: #ff4949
    }

    .icon-bg-green::after {
        background: #66cc33
    }

    .icon-green {
        color: #66cc33
    }

    .icon-bg-orange::after {
        background: #ff7c17
    }

    .icon-orange {
        color: #ff7c17
    }

    .icon-bg-blue::after {
        background: #3682ff
    }

    .icon-blue {
        color: #3682ff
    }
    .fa-users::before{
        font-size: 50px;
    }
    .div-link{
        cursor: pointer;
        text-decoration: none;
        color: #23282c;
    }
    .div-link:hover{
        cursor: pointer;
        text-decoration: none;
        color: #23282c;
    }

</style>
@section('title', trans('admin.user.actions.index'))

@section('body')
    <div class="container" style="margin-top: 50px !important;">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-title"> USERS</h2>
            </div>

            <div class="col-lg-6 col-sm-6 mb-4">
                <a href="{{ url('admin/users/create-sms') }}" class="div-link">
                    <div class="card border-0 shadow rounded-xs pt-5">
                        <div class="" style="text-align-last: center;"> <i class="fa fa-users icon-lg icon-primary icon-bg-primary icon-bg-circle mb-3"></i>
                            <h4 class="mt-4 mb-3">Send SMS To All<br>  </h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-6 col-sm-6 mb-4">
                <a href="{{ url('admin/users/create-notification') }}" class="div-link">
                    <div class="card border-0 shadow rounded-xs pt-5">
                        <div class="" style="text-align-last: center;"> <i class="fa fa-users icon-lg icon-yellow icon-bg-yellow icon-bg-circle mb-3"></i>
                            <h4 class="mt-4 mb-3">Send Notification To All <br> </h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <user-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('admin/users') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ trans('admin.user.actions.index') }}
                        <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('admin/users/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.user.actions.create') }}</a>
                    </div>
                    <div class="card-body" v-cloak>
                        <div class="card-block">
                            <form @submit.prevent="">
                                <div class="row justify-content-md-between">
                                    <div class="col col-lg-7 col-xl-5 form-group">
                                        <div class="input-group">
                                            <input class="form-control" placeholder="{{ trans('brackets/admin-ui::admin.placeholder.search') }}" v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-primary" @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; {{ trans('brackets/admin-ui::admin.btn.search') }}</button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto form-group ">
                                        <select class="form-control" v-model="pagination.state.per_page">

                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-hover table-listing">
                                <thead>
                                    <tr>
                                        <th class="bulk-checkbox">
                                            <input class="form-check-input" id="enabled" type="checkbox" v-model="isClickedAll" v-validate="''" data-vv-name="enabled"  name="enabled_fake_element" @click="onBulkItemsClickedAllWithPagination()">
                                            <label class="form-check-label" for="enabled">
                                                #
                                            </label>
                                        </th>

                                        <th is='sortable' :column="'id'">{{ trans('admin.user.columns.id') }}</th>
{{--                                        <th is='sortable' :column="'business_id'">{{ trans('admin.user.columns.business_id') }}</th>--}}
                                        <th is='sortable' :column="'tag_id'">{{ trans('admin.user.columns.tag_id') }}</th>
                                        <th is='sortable' :column="'name'">{{ trans('admin.user.columns.name') }}</th>
                                        <th is='sortable' :column="'phone'">{{ trans('admin.user.columns.phone') }}</th>
                                        <th is='sortable' :column="'latlng'">{{ trans('admin.user.columns.latlng') }}</th>
                                        <th is='sortable' :column="'status'">{{ trans('admin.user.columns.status') }}</th>

                                        <th></th>
                                    </tr>
                                    <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                        <td class="bg-bulk-info d-table-cell text-center" colspan="9">
                                            <span class="align-middle font-weight-light text-dark">{{ trans('brackets/admin-ui::admin.listing.selected_items') }} @{{ clickedBulkItemsCount }}.  <a href="#" class="text-primary" @click="onBulkItemsClickedAll('/admin/users')" v-if="(clickedBulkItemsCount < pagination.state.total)"> <i class="fa" :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> {{ trans('brackets/admin-ui::admin.listing.check_all_items') }} @{{ pagination.state.total }}</a> <span class="text-primary">|</span> <a
                                                        href="#" class="text-primary" @click="onBulkItemsClickedAllUncheck()">{{ trans('brackets/admin-ui::admin.listing.uncheck_all_items') }}</a>  </span>

                                            <span class="pull-right pr-2">
                                                <button class="btn btn-sm btn-danger pr-3 pl-3" @click="bulkDelete('/admin/users/bulk-destroy')">{{ trans('brackets/admin-ui::admin.btn.delete') }}</button>
                                            </span>

                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in collection" :key="item.id" :class="bulkItems[item.id] ? 'bg-bulk' : ''">
                                        <td class="bulk-checkbox">
                                            <input class="form-check-input" :id="'enabled' + item.id" type="checkbox" v-model="bulkItems[item.id]" v-validate="''" :data-vv-name="'enabled' + item.id"  :name="'enabled' + item.id + '_fake_element'" @click="onBulkItemClicked(item.id)" :disabled="bulkCheckingAllLoader">
                                            <label class="form-check-label" :for="'enabled' + item.id">
                                            </label>
                                        </td>

                                    <td>@{{ item.id }}</td>
{{--                                        <td>@{{ item.business_id }}</td>--}}
                                        <td>@{{ item.tag_name }}</td>
                                        <td>@{{ item.name }}</td>
                                        <td>@{{ item.phone }}</td>
                                        <td>@{{ item.latlng }}</td>
                                        <td>@{{ item.status }}</td>

                                        <td>
                                            <div class="row no-gutters">
                                                <div class="col-auto">
                                                    <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url + '/edit'" title="{{ trans('brackets/admin-ui::admin.btn.edit') }}" role="button"><i class="fa fa-edit"></i></a>
                                                </div>
                                                <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ trans('brackets/admin-ui::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row" v-if="pagination.state.total > 0">
                                <div class="col-sm">
                                    <span class="pagination-caption">{{ trans('brackets/admin-ui::admin.pagination.overview') }}</span>
                                </div>
                                <div class="col-sm-auto">
                                    <pagination></pagination>
                                </div>
                            </div>

                            <div class="no-items-found" v-if="!collection.length > 0">
                                <i class="icon-magnifier"></i>
                                <h3>{{ trans('brackets/admin-ui::admin.index.no_items') }}</h3>
                                <p>{{ trans('brackets/admin-ui::admin.index.try_changing_items') }}</p>
                                <a class="btn btn-primary btn-spinner" href="{{ url('admin/users/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.user.actions.create') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </user-listing>

@endsection
