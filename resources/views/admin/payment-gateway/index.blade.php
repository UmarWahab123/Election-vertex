@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.payment-gateway.actions.index'))

@section('body')

    <payment-gateway-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('admin/payment-gateways') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ trans('admin.payment-gateway.actions.index') }}
                        <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('admin/payment-gateways/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.payment-gateway.actions.create') }}</a>
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

                                        <th is='sortable' :column="'id'">{{ trans('admin.payment-gateway.columns.id') }}</th>
                                        <th is='sortable' :column="'business_id'">{{ trans('admin.payment-gateway.columns.business_id') }}</th>
                                        <th is='sortable' :column="'ref_id'">{{ trans('admin.payment-gateway.columns.ref_id') }}</th>
                                        <th is='sortable' :column="'service_charges'">{{ trans('admin.payment-gateway.columns.service_charges') }}</th>
                                        <th is='sortable' :column="'expiry_date'">{{ trans('admin.payment-gateway.columns.expiry_date') }}</th>
                                        <th is='sortable' :column="'on_demand_cloud_computing'">{{ trans('admin.payment-gateway.columns.on_demand_cloud_computing') }}</th>
                                        <th is='sortable' :column="'multi_bit_visual_redux'">{{ trans('admin.payment-gateway.columns.multi_bit_visual_redux') }}</th>
                                        <th is='sortable' :column="'scan_reading'">{{ trans('admin.payment-gateway.columns.scan_reading') }}</th>
                                        <th is='sortable' :column="'googly'">{{ trans('admin.payment-gateway.columns.googly') }}</th>
                                        <th is='sortable' :column="'status'">{{ trans('admin.payment-gateway.columns.status') }}</th>

                                        <th></th>
                                    </tr>
                                    <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                        <td class="bg-bulk-info d-table-cell text-center" colspan="12">
                                            <span class="align-middle font-weight-light text-dark">{{ trans('brackets/admin-ui::admin.listing.selected_items') }} @{{ clickedBulkItemsCount }}.  <a href="#" class="text-primary" @click="onBulkItemsClickedAll('/admin/payment-gateways')" v-if="(clickedBulkItemsCount < pagination.state.total)"> <i class="fa" :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> {{ trans('brackets/admin-ui::admin.listing.check_all_items') }} @{{ pagination.state.total }}</a> <span class="text-primary">|</span> <a
                                                        href="#" class="text-primary" @click="onBulkItemsClickedAllUncheck()">{{ trans('brackets/admin-ui::admin.listing.uncheck_all_items') }}</a>  </span>

                                            <span class="pull-right pr-2">
                                                <button class="btn btn-sm btn-danger pr-3 pl-3" @click="bulkDelete('/admin/payment-gateways/bulk-destroy')">{{ trans('brackets/admin-ui::admin.btn.delete') }}</button>
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
                                        <td>@{{ item.business_id }}</td>
                                        <td>@{{ item.ref_id }}</td>
                                        <td>$@{{ item.service_charges }}</td>
                                        <td>@{{ item.expiry_date }}</td>
                                        <td v-if="item.on_demand_cloud_computing == 1">
                                            <label class="switch switch-3d switch-danger">
                                            <input type="checkbox" class="switch-input services"  checked="checked" data-type="on_demand_cloud_computing" :data-id="item.id" v-model="collection[index].activated" @change="toggleSwitch(item.resource_url, 'activated', collection[index])">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>
                                        <td v-else="item.on_demand_cloud_computing == 0">
                                            <label class="switch switch-3d switch-success">
                                                <input type="checkbox" class="switch-input services"  data-type="on_demand_cloud_computing" :data-id="item.id" v-model="collection[index].activated" @change="toggleSwitch(item.resource_url, 'activated', collection[index])">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>
                                        <td v-if="item.multi_bit_visual_redux == 1 ">
                                            <label class="switch switch-3d switch-danger">
                                                <input type="checkbox" class="switch-input services"  checked="checked" data-type="multi_bit_visual_redux" :data-id="item.id" v-model="multi_bit_visual_redux_activated" @change="toggleSwitch(item.resource_url, 'forbidden', collection[index])">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>
                                        <td v-else="item.multi_bit_visual_redux == 0">
                                            <label class="switch switch-3d switch-success">
                                                <input type="checkbox" class="switch-input services"  data-type="multi_bit_visual_redux" :data-id="item.id"  v-model="multi_bit_visual_redux_activated" @change="toggleSwitch(item.resource_url, 'forbidden', collection[index])">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>

                                        <td v-if="item.scan_reading == 1">
                                            <label class="switch switch-3d switch-danger">
                                                <input type="checkbox" class="switch-input services"  checked="checked" :data-id="item.id" data-type="scan_reading" v-model="scan_reading_activated" @change="toggleSwitch(item.resource_url, 'activated', collection[index])">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>
                                        <td v-else="item.scan_reading == 0">
                                            <label class="switch switch-3d switch-success">
                                                <input type="checkbox" class="switch-input services"  :data-id="item.id" data-type="scan_reading">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>

                                        <td v-if="item.googly == 1">
                                            <label class="switch switch-3d switch-danger">
                                                <input type="checkbox" class="switch-input services"  checked="checked" :data-id="item.id" data-type="googly" v-model="googly_activated" @change="toggleSwitch(item.resource_url, 'forbidden', collection[index])">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>
                                        <td v-else="item.googly == 0">
                                            <label class="switch switch-3d switch-success">
                                                <input type="checkbox" class="switch-input services" :data-id="item.id" data-type="googly">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>

                                        <td v-if="item.status == 1">
                                            <label class="switch switch-3d switch-danger">
                                                <input type="checkbox" class="switch-input services" checked="checked" :data-id="item.id" data-type="status" v-model="status_activated" @change="toggleSwitch(item.resource_url, 'forbidden', collection[index])">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>
                                        <td v-else="item.status == 0">
                                            <label class="switch switch-3d switch-success">
                                                <input type="checkbox" class="switch-input services"  :data-id="item.id" data-type="status">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>

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
                                <a class="btn btn-primary btn-spinner" href="{{ url('admin/payment-gateways/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.payment-gateway.actions.create') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </payment-gateway-listing>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $(document).on('change', '.services', function(e){
        var id = $(this).data('id');
        var type = $(this).data('type');
         $.ajax({
            method:"GET",
            data: id,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            modal: true,
            url:'{{url('admin/payment-gateways/change-service')}}/'+ id +'/'+ type,
            success:function(data){
                console.log($(this));

            },
            error: function()
            {
                console.log('Something Went Wrong. Please try again later. If the issue persists contact support');
            }
        });
    });
    });
</script>
