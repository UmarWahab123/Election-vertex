<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartyName;

class PartyNameController extends Controller
{
    public function all_parties()

    {

        $data['page_title'] = "All Parties";

        $data['results'] =  PartyName::get();

        return view('admin.parties.index', compact('data'));

    }

    
    public function add_parties($id = -1)

    {

        $data['page_title'] = "Add Parties";

        if ($id != -1) {

            $data['page_title'] = "Update Parties";

            $data['results'] = PartyName::where('id', $id)->first();

        }

        return view('admin.parties.save', compact('data'));

    }


    public function saveparties(Request $request)
    {
        $id = $request->id;
        $data = $request->all();
        if($request->hasfile('logo')) {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . "." . $extension;
            $file->move('public/uploads/parties-logo', $filename);
            $data['logo'] = '/uploads/parties-logo/' . $filename;
            $request->logo = $data['logo'];
        }
        $data['party_image_url']=$data['logo'];
        unset($data['logo']);
        $action = "Added";
        if ($id) {

            $action = "Updated";

            $modal = PartyName::find($id);

            $affected_rows = $modal->update($data);

        } else {

            $affected_rows =  PartyName::create($data);

        }

        return Redirect('/admin/all-parties');

    }

 
    public function deleteparty($id)
    {
        $affected_rows = PartyName::find($id)->delete();
        return redirect()->back();
    }

}
