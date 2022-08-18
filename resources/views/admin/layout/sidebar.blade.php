<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">{{ trans('brackets/admin-ui::admin.sidebar.content') }}</li>
            @can('admin.super-admin')
                {{--Super Admin--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users/dashboard') }}"><i class="nav-icon icon-flag"></i> Dashboard</a></li>
                {{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/user-chats') }}"><i class="nav-icon icon-user"></i> User Chat</a></li>--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/roles') }}"><i class="nav-icon icon-flag"></i> {{ trans('admin.role.title') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/translations') }}"><i class="nav-icon icon-location-pin"></i> {{ __('Translations') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users') }}"><i class="nav-icon icon-user"></i> {{ __('Manage access') }}</a></li>
            @endcan
            @can('admin.administrator')
                {{--Over All Business Admins--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users/dashboard') }}"><i class="nav-icon icon-flag"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/assets') }}"><i class="nav-icon icon-umbrella"></i> {{ trans('admin.asset.title') }}</a></li>
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/user-images') }}"><i class="nav-icon icon-drop"></i> {{ trans('admin.user-image.title') }}</a></li>--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/users') }}"><i class="nav-icon icon-compass"></i> {{ trans('admin.user.title') }}</a></li>
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/tags') }}"><i class="nav-icon icon-puzzle"></i> {{ trans('admin.tag.title') }}</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/general-notices') }}"><i class="nav-icon icon-umbrella"></i> {{ trans('admin.general-notice.title') }}</a></li>--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users') }}"><i class="nav-icon icon-user"></i> {{ __('Manage access') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/page-settings') }}"><i class="nav-icon icon-magnet"></i> {{ trans('admin.page-setting.title') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/pdf-pollings') }}"><i class="nav-icon icon-magnet"></i> {{ trans('Pdfs-Block-code') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/pdf-polling-logs') }}"><i class="nav-icon icon-magnet"></i> {{ trans('Pdf-polling-logs') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/parchi-images') }}"><i class="nav-icon icon-user"></i>{{ trans('admin.parchi-image.title') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase/halqa-index') }}"><i class="nav-icon icon-flag"></i>Add constituency</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election-sectors') }}"><i class="nav-icon icon-user"></i>constituency</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-schemes') }}"><i class="nav-icon icon-user"></i>Polling Scheme</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/polling-parchi-view') }}"><i class="nav-icon icon-user"></i>Parchi Download</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase/categorizeImageUpload?province=punjab') }}"><i class="nav-icon icon-user"></i>Punjab Image Upload</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase/categorizeImageUpload?province=sindh') }}"><i class="nav-icon icon-user"></i>Sindh Image Upload</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-stations/') }}"><i class="nav-icon icon-magnet"></i>Polling Station</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-details/') }}"><i class="nav-icon icon-user"></i>Polling Details</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/') }}"><i class="nav-icon icon-puzzle"></i>Picture Urls</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/remaining') }}"><i class="nav-icon icon-user"></i>Pending Urls</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/invalid') }}"><i class="nav-icon icon-user"></i>Invalid Urls</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/per-page-entities') }}"><i class="nav-icon icon-magnet"></i>Missing Block Codes</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/manually-entered') }}"><i class="nav-icon icon-user"></i>Manually Entered Pages</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/polling-parchi-view') }}"><i class="nav-icon icon-user"></i>Parchi Download</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/list-code-serial') }}"><i class="nav-icon icon-umbrella"></i>List View</a></li>


            @endcan
            @can('admin.staff')
                {{--Vertex Election Admin--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users/dashboard') }}"><i class="nav-icon icon-flag"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/assets') }}"><i class="nav-icon icon-umbrella"></i> {{ trans('admin.asset.title') }}</a></li>
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/user-images') }}"><i class="nav-icon icon-drop"></i> {{ trans('admin.user-image.title') }}</a></li>--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/users') }}"><i class="nav-icon icon-compass"></i> {{ trans('admin.user.title') }}</a></li>
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/tags') }}"><i class="nav-icon icon-puzzle"></i> {{ trans('admin.tag.title') }}</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/general-notices') }}"><i class="nav-icon icon-umbrella"></i> {{ trans('admin.general-notice.title') }}</a></li>--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users') }}"><i class="nav-icon icon-user"></i> {{ __('Manage access') }}</a></li>
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/page-settings') }}"><i class="nav-icon icon-magnet"></i> {{ trans('admin.page-setting.title') }}</a></li>--}}
                {{--Election Related data--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/pdf-pollings') }}"><i class="nav-icon icon-magnet"></i> {{ trans('Pdfs-Block-code') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/pdf-polling-logs') }}"><i class="nav-icon icon-magnet"></i> {{ trans('Pdf-polling-logs') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/parchi-images') }}"><i class="nav-icon icon-user"></i>{{ trans('admin.parchi-image.title') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase/halqa-index') }}"><i class="nav-icon icon-flag"></i>Add constituency</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election-sectors') }}"><i class="nav-icon icon-user"></i>constituency</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-schemes') }}"><i class="nav-icon icon-user"></i>Polling Scheme</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/polling-parchi-view') }}"><i class="nav-icon icon-user"></i>Parchi Download</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase/categorizeImageUpload?province=punjab') }}"><i class="nav-icon icon-user"></i>Punjab Image Upload</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase/categorizeImageUpload?province=sindh') }}"><i class="nav-icon icon-user"></i>Sindh Image Upload</a></li>
                <l                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-stations/') }}"><i class="nav-icon icon-magnet"></i>Polling Station</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-details/') }}"><i class="nav-icon icon-user"></i>Polling Details</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/') }}"><i class="nav-icon icon-puzzle"></i>Picture Urls</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/remaining') }}"><i class="nav-icon icon-user"></i>Pending Urls</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/invalid') }}"><i class="nav-icon icon-user"></i>Invalid Urls</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/per-page-entities') }}"><i class="nav-icon icon-magnet"></i>Missing Block Codes</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/manually-entered') }}"><i class="nav-icon icon-user"></i>Manually Entered Pages</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/polling-parchi-view') }}"><i class="nav-icon icon-user"></i>Parchi Download</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/list-code-serial') }}"><i class="nav-icon icon-umbrella"></i>List View</a></li>
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/searchIdCardReport') }}"><i class="nav-icon icon-compass"></i>Search Idcard</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/mobileView/azzure/search') }}"><i class="nav-icon icon-puzzle"></i>Azzure Search</a></li>--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/curl-switches') }}"><i class="nav-icon icon-user"></i>Curl Switches</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/all-parties') }}"><i class="nav-icon icon-umbrella"></i>All Parties</a></li>


            @endcan

            @can('admin.politics.imageuplaod')
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users/dashboard') }}"><i class="nav-icon icon-flag"></i> Dashboard</a></li>
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/users') }}"><i class="nav-icon icon-compass"></i> {{ trans('admin.user.title') }}</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users') }}"><i class="nav-icon icon-user"></i> {{ __('Manage access') }}</a></li>--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase/categorizeImageUpload?province=punjab') }}"><i class="nav-icon icon-user"></i>Punjab Image Upload</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase/categorizeImageUpload?province=sindh') }}"><i class="nav-icon icon-user"></i>Sindh Image Upload</a></li>
                <l                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election-sectors') }}"><i class="nav-icon icon-user"></i>Election Sectors</a></li>

                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase/halqa-index') }}"><i class="nav-icon icon-flag"></i>Add constituency</a></li>
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-schemes') }}"><i class="nav-icon icon-user"></i>Polling Scheme</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/parchi-images') }}"><i class="nav-icon icon-user"></i>{{ trans('admin.parchi-image.title') }}</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-stations/') }}"><i class="nav-icon icon-magnet"></i>Polling Station</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-details/') }}"><i class="nav-icon icon-user"></i>Polling Details</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/') }}"><i class="nav-icon icon-puzzle"></i>Picture Urls</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/remaining') }}"><i class="nav-icon icon-user"></i>Pending Urls</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/invalid') }}"><i class="nav-icon icon-user"></i>Invalid Urls</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/per-page-entities') }}"><i class="nav-icon icon-magnet"></i>Missing Block Codes</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase-urls/manually-entered') }}"><i class="nav-icon icon-user"></i>Manually Entered Pages</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/polling-parchi-view') }}"><i class="nav-icon icon-user"></i>Parchi Download</a></li>--}}

            @endcan
            @can('admin.politics.dataview')
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/polling-parchi-view') }}"><i class="nav-icon icon-user"></i>Parchi Download</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/list-code-serial') }}"><i class="nav-icon icon-umbrella"></i>List View</a></li>
{{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/searchIdCardReport') }}"><i class="nav-icon icon-compass"></i>Search Idcard</a></li>--}}
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election-sectors') }}"><i class="nav-icon icon-user"></i>constituency</a></li>

                {{--                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election/searchPollingReport') }}"><i class="nav-icon icon-puzzle"></i>BlockCode List Download</a></li>--}}
            @endcan

            @can('admin.visiting-card.show')

                <li class="nav-item"><a class="nav-link" href="{{ url('admin/visiting-cards') }}"><i class="nav-icon icon-puzzle"></i>Visiting Cards</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-schemes') }}"><i class="nav-icon icon-compass"></i>Polling Scheme</a></li>

                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-stations/all-sectors') }}"><i class="nav-icon icon-puzzle"></i>Sectors</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('voterDetailEntry/188110503') }}"><i class="nav-icon icon-user"></i>Update Pollings</a></li>
            @endcan

            @can('admin.data-entry')
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-stations/all-sectors') }}"><i class="nav-icon icon-puzzle"></i>Sectors</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/firebase/update-address-form') }}"><i class="nav-icon icon-user"></i>Update Form</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/polling-schemes') }}"><i class="nav-icon icon-compass"></i>Polling Scheme</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('voterDetailEntry/188110503') }}"><i class="nav-icon icon-user"></i>Update Pollings</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/election-sectors') }}"><i class="nav-icon icon-user"></i>Block Codes</a></li>
            @endcan
            @can('admin.candidate')
                <li class="nav-item"><a class="nav-link" href="{{ url('admin/candidate-wards/sectors') }}"><i class="nav-icon icon-puzzle"></i>Wards</a></li>
            @endcan


        </ul>
    </nav>
    {{--    <button class="sidebar-minimizer brand-minimizer" type="button"></button>--}}
</div>
