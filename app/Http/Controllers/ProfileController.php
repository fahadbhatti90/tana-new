<?php

namespace App\Http\Controllers;

use App\Model\Brand;
use App\Model\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $rules = array(
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'new_confirm_password' => Hash::make($request['new_confirm_password']),
        );

        User::find(auth()->user()->user_id)->update(['password' => $form_data['new_confirm_password']]);

        return response()->json(['success' => 'Password is updated']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeMode()
    {
        $mode = (auth()->user()->profile->profile_mode == "dark-layout")?"":"dark-layout";
        User::find(auth()->user()->user_id)->profile->update(['profile_mode' => $mode]);
        return response()->json(['success' => 'Layout Mode is Changed']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getBrands()
    {
        if(request()->ajax())
        {
            $data = auth()->user()->getUserBrand();
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switchBrand(Request $request)
    {
        $data = auth()->user()->getUserBrand()->pluck('brand_name','brand_id')->all();
        if(in_array($request->switch_brand_info, array_keys($data))) {
            $brand = Brand::findOrFail($request->switch_brand_info);
            setBrandSession($brand->brand_id , $brand->brand_name);
            return response()->json(['success' => 'Brand is successfully switched']);
        }else{
            return response()->json(['errors' => 'You are not allowed to use this Brand']);
        }
    }

}
