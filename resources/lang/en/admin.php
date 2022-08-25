<?php

return [
    'admin-user' => [
        'title' => 'Users',

        'actions' => [
            'index' => 'Users',
            'create' => 'New User',
            'edit' => 'Edit :name',
            'edit_profile' => 'Edit Profile',
            'edit_password' => 'Edit Password',
        ],

        'columns' => [
            'id' => 'ID',
            'last_login_at' => 'Last login',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Password Confirmation',
            'activated' => 'Activated',
            'forbidden' => 'Forbidden',
            'language' => 'Language',

            //Belongs to many relations
            'roles' => 'Roles',

        ],
    ],

    'role' => [
        'title' => 'Roles',

        'actions' => [
            'index' => 'Roles',
            'create' => 'New Role',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'guard_name' => 'Guard name',

        ],
    ],




    'asset' => [
        'title' => 'Asset',

        'actions' => [
            'index' => 'Asset',
            'create' => 'New Asset',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'tag_id' => 'Tag',
            'url' => 'Url',
            'title' => 'Title',
            'content' => 'Content',
            'status' => 'Status',
            'htmlload' => 'HTML',

        ],
    ],


    'user-image' => [
        'title' => 'User Image',

        'actions' => [
            'index' => 'User Image',
            'create' => 'New User Image',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'user_id' => 'User',
            'file_url' => 'File url',

        ],
    ],


    'tag' => [
        'title' => 'Tags',

        'actions' => [
            'index' => 'Tags',
            'create' => 'New Tag',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'business_id' => 'Business',
            'tag_name' => 'Tag name',
            'status' => 'Status',

        ],
    ],

    'general-notice' => [
        'title' => 'General Notice',

        'actions' => [
            'index' => 'General Notice',
            'create' => 'New General Notice',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'bussiness_id' => 'Bussiness',
            'title'=>'Title',
            'content' => 'Content',
            'html_tag' => 'HTML',

        ],
    ],

    'user' => [
        'title' => 'Users',

        'actions' => [
            'index' => 'Users',
            'create' => 'New User',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'business_id' => 'Business',
            'tag_id' => 'Tag',
            'name' => 'Name',
            'phone' => 'Phone',
            'latlng' => 'Latlng',
            'status' => 'Status',

        ],
    ],

    'general-setting' => [
        'title' => 'General Setting',

        'actions' => [
            'index' => 'General Setting',
            'create' => 'New General Setting',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'business_id' => 'Business',
            'general_tag' => 'General tag',
            'status' => 'Status',

        ],
    ],

    'page-setting' => [
        'title' => 'Page Setting',

        'actions' => [
            'index' => 'Page Setting',
            'create' => 'New Page Setting',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'business_id' => 'Business',
            'tag_name' => 'Tag name',
            'block1' => 'Block1',
            'block2' => 'Block2',
            'block3' => 'Block3',
            'status' => 'Status',

        ],
    ],

    'visiting-card' => [
        'title' => 'Visiting Cards',

        'actions' => [
            'index' => 'Visiting Cards',
            'create' => 'New Visiting Card',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'user_id' => 'User',
            'user_type' => 'User Type',
            'name' => 'Name',
            'phone' => 'Phone',
            'address' => 'Address',
            'latlng' => 'Latlng',
            'category' => 'Category',
            'status' => 'Status',

        ],
    ],

    'visiting-card-image' => [
        'title' => 'Visiting Card Images',

        'actions' => [
            'index' => 'Visiting Card Images',
            'create' => 'New Visiting Card Image',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'visiting_card_id' => 'Visiting card',
            'image_link' => 'Image link',

        ],
    ],

    'create-cnic-detail-table' => [
        'title' => 'Createcnicdetailtable',

        'actions' => [
            'index' => 'Createcnicdetailtable',
            'create' => 'New Createcnicdetailtable',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',

        ],
    ],

    'firebase-url' => [
        'title' => 'Firebase Urls',

        'actions' => [
            'index' => 'Firebase Urls',
            'create' => 'New Firebase Url',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'image_url' => 'Image url',
            'status' => 'Status',
            'cron' => 'Cron',

        ],
    ],

    'polling-detail' => [
        'title' => 'Polling Details',

        'actions' => [
            'index' => 'Polling Details',
            'create' => 'New Polling Detail',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'polling_station_id' => 'Polling station',
            'polling_station_number' => 'Polling station number',
            'cnic' => 'Cnic',
            'page_no' => 'Page no',
            'url' => 'Url',
            'url_id' => 'Url',
            'boundingBox' => 'BoundingBox',
            'polygon' => 'Polygon',
            'status' => 'Status',

        ],
    ],

    'polling-station' => [
        'title' => 'Polling Station',

        'actions' => [
            'index' => 'Polling Station',
            'create' => 'New Polling Station',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'polling_station_number' => 'Polling station number',
            'meta' => 'Meta',
            'url_id' => 'Url',

        ],
    ],

    'pdf-polling' => [
        'title' => 'Pdf Polling',

        'actions' => [
            'index' => 'Pdf Polling',
            'create' => 'New Pdf Polling',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'email' => 'Email',
            'block_code' => 'Block code',
            'status' => 'Status',

        ],
    ],

    'election-sector' => [
        'title' => 'Pdf Polling',

        'actions' => [
            'index' => 'Election Sector',
            'create' => 'New Election Sector',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'sector' => 'Sector',
            'block_code' => 'Block code',
            'male_vote' => 'Male Vote',
            'female_vote' => 'Female Vote',
            'total_vote' => 'Total Vote',

        ],
    ],

    'polling-scheme' => [
        'title' => 'Polling Scheme',

        'actions' => [
            'index' => 'Polling Scheme',
            'create' => 'New Polling Scheme',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'ward' => 'Ward',
            'polling-station-area' => 'Polling-station-area',
            'block-code-area' => 'Block-code-area',
            'block-code' => 'Block-code',
            'latlng' => 'Latlng',
            'status' => 'Status',

        ],
    ],

    'parchi-image' => [
        'title' => 'Parchi Image',

        'actions' => [
            'index' => 'Parchi Image',
            'create' => 'New Parchi Image',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'user_id' => 'User',
            'Party' => 'Party',
            'image_url' => 'Image url',
            'status' => 'Status',

        ],
    ],

    'polling-scheme' => [
        'title' => 'Polling Scheme',

        'actions' => [
            'index' => 'Polling Scheme',
            'create' => 'New Polling Scheme',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'ward' => 'Ward',
            'polling-station-area' => 'Polling-station-area',
            'block-code-area' => 'Block-code-area',
            'block-code' => 'Block-code',
            'latlng' => 'Latlng',
            'status' => 'Status',

        ],
    ],

    'polling-scheme' => [
        'title' => 'Polling Scheme',

        'actions' => [
            'index' => 'Polling Scheme',
            'create' => 'New Polling Scheme',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'ward' => 'Ward',
            'polling_station_area' => 'Polling station area',
            'block_code_area' => 'Block code area',
            'block_code' => 'Block code',
            'latlng' => 'Latlng',
            'status' => 'Status',

        ],
    ],

    'pdf-polling-log' => [
        'title' => 'Pdf Polling Log',

        'actions' => [
            'index' => 'Pdf Polling Log',
            'create' => 'New Pdf Polling Log',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'key' => 'Key',
            'value' => 'Value',
            'meta' => 'Meta',
            'log' => 'Log',

        ],
    ],

    'candidate-ward' => [
        'title' => 'Candidate Ward',

        'actions' => [
            'index' => 'Candidate Ward',
            'create' => 'New Candidate Ward',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'user_id' => 'User',
            'ward_id' => 'Ward',
            'status' => 'Status',

        ],
    ],

    'data-set' => [
        'title' => 'Data Set',

        'actions' => [
            'index' => 'Data Set',
            'create' => 'New Data Set',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'phone' => 'Phone',
            'address' => 'Address',
            'tag' => 'Tag',
            'meta' => 'Meta',
            'status' => 'Status',

        ],
    ],

    'customer' => [
        'title' => 'Customer',

        'actions' => [
            'index' => 'Customer',
            'create' => 'New Customer',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'phone' => 'Phone',
            'status' => 'Status',

        ],
    ],

    'election-setting' => [
        'title' => 'Election Setting',

        'actions' => [
            'index' => 'Election Setting',
            'create' => 'New Election Setting',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'meta_key' => 'Meta key',
            'meta_value' => 'Meta value',

        ],
    ],

    'curl-switch' => [
        'title' => 'Curl Switches',

        'actions' => [
            'index' => 'Curl Switches',
            'create' => 'New Curl Switch',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',

        ],
    ],

    'offline-data-file' => [
        'title' => 'Offline Data Files',

        'actions' => [
            'index' => 'Offline Data Files',
            'create' => 'New Offline Data File',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',

        ],
    ],

    'voter-detail' => [
        'title' => 'Voter Details',

        'actions' => [
            'index' => 'Voter Details',
            'create' => 'New Voter Detail',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'id_card' => 'Id card',
            'serial_no' => 'Serial no',
            'family_no' => 'Family no',
            'block_code' => 'Block code',
            'age' => 'Age',
            'name' => 'Name',
            'father_name' => 'Father name',
            'address' => 'Address',
            'cron' => 'Cron',
            'status' => 'Status',
            'meta' => 'Meta',

        ],
    ],

    'business-account' => [
        'title' => 'Business Account',

        'actions' => [
            'index' => 'Business Account',
            'create' => 'New Business Account',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'business_id' => 'Business',
            'ref_id' => 'Reference Id',
            'credit' => 'Credit',
            'details' => 'Details',
            'debit' => 'Debit',
            'balance' => 'Balance',
            'img_url' => 'Image url',
            'expiry_date' => 'Expiry date',
            'status' => 'Status',
            'meta' => 'Meta',

        ],
    ],

    'payment-gateway' => [
        'title' => 'Payment Gateway',

        'actions' => [
            'index' => 'Payment Gateway',
            'create' => 'New Payment Gateway',
            'edit' => 'Edit :Payment Gateway',
        ],

        'columns' => [
            'id' => 'ID',
            'business_id' => 'Business Id',
            'ref_id' => 'Reference Id',
            'service_charges' => 'Service Charges',
            'expiry_date' => 'Expiry Date',
            'on_demand_cloud_computing' => 'On Demand Cloud Computing',
            'multi_bit_visual_redux' => 'Multi Bit Visual Redux',
            'scan_reading' => 'Scan Reading',
            'googly' => 'Googly',
            'img_url' => 'Image Url',
            'status' => 'Status',

            //Belongs to many relations
            'meta' => 'Meta',

        ],
    ],
    'payment-gateway' => [
        'title' => 'Payment Gateway',

        'actions' => [
            'index' => 'Payment Gateway',
            'create' => 'New Payment Gateway',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'business_id' => 'Business',
            'ref_id' => 'Ref',
            'service_charges' => 'Service charges',
            'expiry_date' => 'Expiry date',
            'on_demand_cloud_computing' => 'On demand cloud computing',
            'multi_bit_visual_redux' => 'Multi bit visual redux',
            'scan_reading' => 'Scan reading',
            'googly' => 'Googly',
            'img_url' => 'Img url',
            'status' => 'Status',
            'meta' => 'Meta',
            
        ],
    ],

    's3uploading-member' => [
        'title' => 'S3Uploading Members',

        'actions' => [
            'index' => 'S3Uploading Members',
            'create' => 'New S3Uploading Member',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'phone' => 'Phone',
            'party' => 'Party',
            'last_login' => 'Last login',
            'ip_address' => 'Ip address',
            'is_loggedin' => 'Is loggedin',
            'status' => 'Status',
            
        ],
    ],

    'product' => [
        'title' => 'Products',

        'actions' => [
            'index' => 'Products',
            'create' => 'New Product',
            'edit' => 'Edit :name',
            'will_be_published' => 'Product will be published at',
        ],

        'columns' => [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'perex' => 'Perex',
            'published_at' => 'Published at',
            'enabled' => 'Enabled',
            
        ],
    ],

    'post' => [
        'title' => 'Posts',

        'actions' => [
            'index' => 'Posts',
            'create' => 'New Post',
            'edit' => 'Edit :name',
            'will_be_published' => 'Post will be published at',
        ],

        'columns' => [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'perex' => 'Perex',
            'published_at' => 'Published at',
            'enabled' => 'Enabled',
            
        ],
    ],

    'client-setting' => [
        'title' => 'Client Settings',

        'actions' => [
            'index' => 'Client Settings',
            'create' => 'New Client Setting',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'user_id' => 'User',
            'status' => 'Status',
            
        ],
    ],

    'client-setting' => [
        'title' => 'Client Setting',

        'actions' => [
            'index' => 'Client Setting',
            'create' => 'New Client Setting',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'user_id' => 'User',
            'status' => 'Status',
            
        ],
    ],

    'clients-setting' => [
        'title' => 'Clients Setting',

        'actions' => [
            'index' => 'Clients Setting',
            'create' => 'New Clients Setting',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'user_id' => 'User',
            'status' => 'Status',
            
        ],
    ],

    'client-setting' => [
        'title' => 'Client Setting',

        'actions' => [
            'index' => 'Client Setting',
            'create' => 'New Client Setting',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'user_id' => 'User',
            'status' => 'Status',
            
        ],
    ],

    'all-party' => [
        'title' => 'All Parties',

        'actions' => [
            'index' => 'All Parties',
            'create' => 'New All Party',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'party_name' => 'Party name',
            'party_image_url' => 'Party image url',
            'created_by' => 'Created by',
            
        ],
    ],

    'auditable' => [
        'title' => 'Auditable',

        'actions' => [
            'index' => 'Auditable',
            'create' => 'New Auditable',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'city' => 'City',
            'status' => 'Status',
            
        ],
    ],

    // Do not delete me :) I'm used for auto-generation
];
