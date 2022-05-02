<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionsResource;
use App\Http\Resources\UsersResource;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
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
        $user = User::where('email', $request->email)->first();

        if (!is_object($user) || !Hash::check($request->password, $user->password)) {
            return response()->error(['incorrect email or password']);
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $data = [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'remember' => $request->remember_me ? true : false,
            'admin' => new UsersResource($user)
        ];
        
        return response()->success($data, 'success');
    }
  
        function transactions(Request $request){
            $transactions=Transaction::where('user_id',$request->user()->id);
            if($request->has('search')){
                $transactions=$this->search($transactions,$request);
            }
            $transactions=$transactions->latest('id')->paginate(20);
            return response()->success(['data'=>TransactionsResource::collection($transactions),'current_page'=>$transactions->currentPage(),'lastpage'=>$transactions->lastPage()], 'success'); 
        }
        private function search($object,$request){
            foreach($request->search as $key=>$value){
              if($value!='')
            $object=$object->where($key,$value);
            }
            return $object;
    
        }
    
}
