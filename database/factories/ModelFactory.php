<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Brackets\AdminAuth\Models\AdminUser::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt($faker->password),
        'remember_token' => null,
        'activated' => true,
        'forbidden' => $faker->boolean(),
        'language' => 'en',
        'deleted_at' => null,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        'last_login_at' => $faker->dateTime,
        
    ];
});/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Role::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'guard_name' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Permission::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'guard_name' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\GeoVar::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\GeoVar::class, static function (Faker\Generator $faker) {
    return [
        'datetime' => $faker->sentence,
        'comments' => $faker->sentence,
        'name' => $faker->firstName,
        'description' => $faker->sentence,
        'lat1' => $faker->sentence,
        'lng1' => $faker->sentence,
        'lat2' => $faker->sentence,
        'lng2' => $faker->sentence,
        'tpl_var' => $faker->sentence,
        'tpl_val' => $faker->sentence,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Post::class, static function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'slug' => $faker->unique()->slug,
        'perex' => $faker->text(),
        'published_at' => $faker->date(),
        'enabled' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\CreateUsersTable::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\UserImage::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Tag::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\GeneralNotice::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Asset::class, static function (Faker\Generator $faker) {
    return [
        'tag_id' => $faker->sentence,
        'url' => $faker->sentence,
        'title' => $faker->sentence,
        'content' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\GeneralNotice::class, static function (Faker\Generator $faker) {
    return [
        'bussiness_id' => $faker->sentence,
        'content' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\UserImage::class, static function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->sentence,
        'file_url' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, static function (Faker\Generator $faker) {
    return [
        'bussiness_id' => $faker->sentence,
        'name' => $faker->firstName,
        'phone' => $faker->sentence,
        'latlong' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Tag::class, static function (Faker\Generator $faker) {
    return [
        'business_id' => $faker->sentence,
        'tag_name' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, static function (Faker\Generator $faker) {
    return [
        'business_id' => $faker->sentence,
        'tag_id' => $faker->sentence,
        'name' => $faker->firstName,
        'phone' => $faker->sentence,
        'latlng' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\GeneralSetting::class, static function (Faker\Generator $faker) {
    return [
        'business_id' => $faker->sentence,
        'general_tag' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\PageSetting::class, static function (Faker\Generator $faker) {
    return [
        'business_id' => $faker->sentence,
        'tag_name' => $faker->sentence,
        'block1' => $faker->sentence,
        'block2' => $faker->sentence,
        'block3' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\VisitingCard::class, static function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->sentence,
        'name' => $faker->firstName,
        'phone' => $faker->sentence,
        'address' => $faker->text(),
        'latlng' => $faker->sentence,
        'email' => $faker->email,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\VisitingCardImage::class, static function (Faker\Generator $faker) {
    return [
        'visiting_card_id' => $faker->sentence,
        'image_link' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\CreateCnicDetailTable::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\FirebaseUrl::class, static function (Faker\Generator $faker) {
    return [
        'image_url' => $faker->text(),
        'status' => $faker->randomNumber(5),
        'cron' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\PollingDetail::class, static function (Faker\Generator $faker) {
    return [
        'polling_station_id' => $faker->randomNumber(5),
        'polling_station_number' => $faker->randomNumber(5),
        'cnic' => $faker->sentence,
        'page_no' => $faker->sentence,
        'url' => $faker->sentence,
        'url_id' => $faker->randomNumber(5),
        'boundingBox' => $faker->text(),
        'polygon' => $faker->text(),
        'status' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\PollingStation::class, static function (Faker\Generator $faker) {
    return [
        'polling_station_number' => $faker->randomNumber(5),
        'meta' => $faker->text(),
        'url_id' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\PdfPolling::class, static function (Faker\Generator $faker) {
    return [
        'email' => $faker->email,
        'block_code' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\ElectionSector::class, static function (Faker\Generator $faker) {
    return [
        'sector' => $faker->sentence,
        'block_code' => $faker->sentence,
        'male_vote' => $faker->sentence,
        'female_vote' => $faker->sentence,
        'total_vote' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\UrlUploadLog::class, static function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(5),
        'files_count' => $faker->randomNumber(5),
        'url_meta' => $faker->text(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\PollingScheme::class, static function (Faker\Generator $faker) {
    return [
        'ward' => $faker->sentence,
        'polling-station-area' => $faker->text(),
        'block-code-area' => $faker->text(),
        'block-code' => $faker->randomNumber(5),
        'latlng' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\ParchiImage::class, static function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->sentence,
        'Party' => $faker->text(),
        'image_url' => $faker->text(),
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\PollingScheme::class, static function (Faker\Generator $faker) {
    return [
        'ward' => $faker->sentence,
        'polling_station_area' => $faker->text(),
        'block_code_area' => $faker->text(),
        'block_code' => $faker->randomNumber(5),
        'latlng' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\PdfPollingLog::class, static function (Faker\Generator $faker) {
    return [
        'key' => $faker->text(),
        'value' => $faker->text(),
        'meta' => $faker->text(),
        'log' => $faker->text(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\CandidateWard::class, static function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->text(),
        'ward_id' => $faker->text(),
        'status' => $faker->text(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\DataSet::class, static function (Faker\Generator $faker) {
    return [
        'phone' => $faker->randomNumber(5),
        'address' => $faker->text(),
        'tag' => $faker->sentence,
        'meta' => $faker->text(),
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Customer::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'phone' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\ElectionSetting::class, static function (Faker\Generator $faker) {
    return [
        'meta_key' => $faker->text(),
        'meta_value' => $faker->text(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\CurlSwitch::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'status' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\OfflineDataFile::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\VoterDetail::class, static function (Faker\Generator $faker) {
    return [
        'id_card' => $faker->sentence,
        'serial_no' => $faker->sentence,
        'family_no' => $faker->sentence,
        'block_code' => $faker->sentence,
        'age' => $faker->text(),
        'name' => $faker->text(),
        'father_name' => $faker->text(),
        'address' => $faker->text(),
        'cron' => $faker->sentence,
        'status' => $faker->sentence,
        'meta' => $faker->text(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\BusinessAccount::class, static function (Faker\Generator $faker) {
    return [
        'business_id' => $faker->sentence,
        'ref_id' => $faker->sentence,
        'credit' => $faker->sentence,
        'details' => $faker->text(),
        'debit' => $faker->sentence,
        'balance' => $faker->sentence,
        'img_url' => $faker->text(),
        'expiry_date' => $faker->sentence,
        'status' => $faker->sentence,
        'meta' => $faker->text(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\PaymentGateway::class, static function (Faker\Generator $faker) {
    return [
        'business_id' => $faker->sentence,
        'ref_id' => $faker->sentence,
        'service_charges' => $faker->sentence,
        'expiry_date' => $faker->sentence,
        'on_demand_cloud_computing' => $faker->sentence,
        'multi_bit_visual_redux' => $faker->sentence,
        'scan_reading' => $faker->sentence,
        'googly' => $faker->sentence,
        'img_url' => $faker->text(),
        'status' => $faker->sentence,
        'meta' => $faker->text(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\S3uploadingMember::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'email' => $faker->email,
        'password' => bcrypt($faker->password),
        'phone' => $faker->sentence,
        'party' => $faker->sentence,
        'last_login' => $faker->sentence,
        'ip_address' => $faker->sentence,
        'is_loggedin' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Product::class, static function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'slug' => $faker->unique()->slug,
        'perex' => $faker->text(),
        'published_at' => $faker->date(),
        'enabled' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Post::class, static function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'slug' => $faker->unique()->slug,
        'perex' => $faker->text(),
        'published_at' => $faker->date(),
        'enabled' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\ClientSetting::class, static function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\ClientsSetting::class, static function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->sentence,
        'status' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\AllParty::class, static function (Faker\Generator $faker) {
    return [
        'party_name' => $faker->text(),
        'party_image_url' => $faker->text(),
        'created_by' => $faker->text(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
