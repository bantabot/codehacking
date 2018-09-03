<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UsersRequest;
use App\Http\Requests\UserEditRequest;

use App\User;
use App\Role;
use App\Photo;
use Illuminate\Support\Facades\Session;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $users = User::all();

        return view('admin/users/index', compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::lists('name', 'id')->all();
        return view('admin/users/create', compact('roles'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {
        //
      $input = $request->all();

      if($file = $request->file('photo_id')){

          $name = time() . $file->getClientOriginalName();
          $file->move('images',$name);
          $photo = Photo::create(['file'=>$name]);

          $input['photo_id'] = $photo->id;

      }

      $input['password'] = bcrypt($request->password);

      User::create($input);
        Session::flash('create_user',  $input['name'] . ' has been created');
        return redirect('/admin/users');

//        return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        $roles = Role::lists('name', 'id')->all();
        return view('admin.users.edit', compact('user', 'roles'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersRequest $request, $id)
    {
        //
        $user= User::findOrFail($id);

        $input = $request->all();

        if($file = $request->file('photo_id')){

            $name = time() . $file->getClientOriginalName();
            $file->move('images', $name);

            $photo = Photo::create(['file'=>$name]);
            $input['photo_id'] = $photo->id;
        }
        if($input['password']){
            $input['password'] = bcrypt($request->password);
        }
        else{
            unset($input['password']);

        }

       $user->update($input);
        Session::flash('update_user',  $user->name . ' has been updated');
        return redirect('/admin/users');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

       $user=  User::findOrFail($id);



       if($user->photo!= null){
       unlink( '/Applications/MAMP/htdocs'. $user->photo->file );}

        Session::flash('deleted_user', $user->name .' has been deleted');

        $user->delete();
        return redirect('/admin/users');
    }
}
