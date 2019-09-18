<?php
	 namespace App\Http\Controllers;

	 use App\Game;
     use Illuminate\Http\Request;
     use App\Player;
	 use App\Product;
	 use App\User;
	 use App\WeekDay;
	 use Illuminate\Support\Facades\Auth;
	 use Illuminate\Support\Facades\Input;
	 use Illuminate\Support\Facades\Session;
	 use Illuminate\Support\Facades\Validator;
     use Illuminate\Support\Str;
     use Illuminate\Support\Facades\Hash;

     class UserController extends Controller {

			public function getIndex() {
				 return view('admin.users.index', ['player' => Player::getPlayerFullDetails()]);
			}

			public function getList() {
				 Session::flash('user_search', Input::has('ok') ? Input::get('search') : (Session::has('user_search') ? Session::get('user_search') : ''));
				 Session::flash('user_field', Input::has('field') ? Input::get('field') : (Session::has('user_field') ? Session::get('user_field') : 'last_access_at'));
				 Session::flash('user_sort', Input::has('sort') ? Input::get('sort') : (Session::has('user_sort') ? Session::get('user_sort') : 'desc'));
				 $users = User::with('player')
					 ->join('players', 'players.user_id', '=', 'users.id')
					 ->where('username', 'like', '%' . Session::get('user_search') . '%')
					 ->orderBy(Session::get('user_field'), Session::get('user_sort'))
					 ->paginate(10);
				 return view('admin.users.list', ['users' => $users]);
			}

			public function getUpdate($id) {
				 $user = User::find($id);
				 $player = $user->player()->first();
				 return view('admin.users.update', ['user' => $user, 'player' => $player]);
			}

			public function postUpdate($id) {
				 $user = User::find($id);
				 $validator = Validator::make(Input::all(), [
					 'name' => 'required|max:255',
					 'email' => 'required|email|max:255|unique:users,email,' . $id,
					 'username' => 'required|max:255',
				 ]);
				 if ($validator->fails()) {
						return array(
							'fail' => true,
							'errors' => $validator->getMessageBag()->toArray()
						);
				 }else
				 {
					 $user->update( [ 'email' => Input::get( 'email' ), 'name' => ucwords( Input::get( 'name' ) ), 'username' => ucwords( Input::get( 'username' ) ), 'role' => Input::get( 'role' ), ] );
					 if ( Input::get( 'status' ) == 'unavailable' )
					 {
						 $unavailable_for = "1 week";
					 } else
					 {
						 $unavailable_for = 0;
					 }
					 Player::where( 'user_id', $id )->update( [ 'player_position' => Input::get( 'position' ), 'skill_level' => Input::get( 'skill' ), 'status' => Input::get( 'status' ), 'unavailable_for' => $unavailable_for ] );
				 }
				return redirect('admin/players');
			}
			
			public function getCreate() {
				 return view('admin.users.create');
			}

			public function postCreate() {
				 $validator = Validator::make(Input::all(), [
					 'name' => 'required|max:255',
					 'email' => 'required|email|max:255|unique:users',
					 'username' => 'required|max:255|unique:users',
					 'password' => 'required|min:3|confirmed',
					 'password_confirmation' => 'min:3',
				 ]);
				 if ($validator->fails()) {
						return array(
							'fail' => true,
							'errors' => $validator->getMessageBag()->toArray()
						);
				 }
				 $user = User::create([
					 'email' => Input::get('email'),
					 'name' => ucwords(Input::get('name')),
					 'username' => ucfirst(Input::get('username')),
					 'password' => bcrypt(Input::get('password')),
					 'role' => Input::get('role'),
                     'remember_token' => Str::random(60),
                     'is_temp' => "Y"
				 ]);
				 Player::create([
					 'user_id' => $user->id,
					 'skill_level' => Input::get('skill'),
					 'status' => Input::get('status'),
				 ]);
				 return ['url' => 'users/list'];
			}

			public function getDelete($id) {
				 $player = Player::find($id);
					$player->active = false;
					$player->status = 'SUSPENDED';
				$player->save();
				 return Redirect('admin/users/list');
			}

			public function getActive($id) {
				 $player = Player::find($id);
					$player->active = true;
					$player->status = 'WAITING';
				$player->save();
				 return Redirect('admin/users/list');
			}

			public function updatePassword(Request $request)
            {
                $validator = \Validator::make($request->all(), [
                    'oldPassword' => 'required|min:3|max:30',
                    'newPassword' => 'required|min:3|max:30|confirmed',
                    'newPassword_confirmation' => 'min:3'
                ]);

                if (Hash::check($request->oldPassword, Auth::user()->password)) {
                    //good to go
                    if ($validator->fails()) {
                        return $validator->errors();
                    }
                    User::where('id', Auth::user()->id)
                        ->update([
                            'password' => bcrypt($request->newPassword),
                        ]);

                    return ["status"=>"success", "message"=>"Password updated", "title" => "Success"];
                } else {
                    // failed
                    return $validator->errors()->add('oldPassword', 'Your old password does\'t match. ');
                }
            }

			public function updateProfile(Request $request){
                $validator = \Validator::make($request->all(), [
                    'name' => 'required|max:50',
                    'email' => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
                    'phone' => 'required|digits_between:9,13', // phone number can't be unique, by default is "777"
                ]);
                if ($validator->fails()) {
                    return $validator->errors();
                }
                $player_id = User::find(Auth::user()->id)->player->id;
                $player = Player::find($player_id);
                
                $player->notify_places = $request->get('notify_places', false) ? $request->get('notify_places_val', 0) : 0;

                $player->save();
                User::where('id', Auth::user()->id)
                    ->update([
                        'name' => ucwords($request->name),
                        'email' => $request->email,
                        'phone' => $request->phone,
                    ]);

                return ["status"=>"success", "message"=>"Your account is updated", "title" => "Success"];
            }


     }
