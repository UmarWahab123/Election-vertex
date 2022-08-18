<?php
namespace App\Http\Controllers\General;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class SettingController extends Controller
{
   public function getTableSchema(Request $request)
   {
        $table = $request->query('table');
        return DB::getSchemaBuilder()->getColumnListing($table);
      //   dd($table);
   }
}
