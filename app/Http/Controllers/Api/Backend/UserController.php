<?php
namespace App\Http\Controllers\Api\Backend;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminsResource;
use App\Http\Resources\UsersResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class UserController extends Controller
{
    public function login(Request $request) {
         $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails())
            return response()->error($validator->errors()->all());
        $admin = Admin::where('email', $request->email)->first();
        if (!is_object($admin) || !Hash::check($request->password, $admin->password)) {
            return response()->error(['incorrect email or password']);
        }

        $tokenResult = $admin->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $data = [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'remember' => $request->remember_me ? true : false,
            'admin' => new AdminsResource($admin)
        ];
        
        return response()->success($data, 'success');
    }
    function users(Request $request){
        $users=User::latest('id')->get();
        return response()->success(UsersResource::collection($users), 'success');
    }
}
