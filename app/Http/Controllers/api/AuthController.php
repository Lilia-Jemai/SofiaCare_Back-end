<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Medic;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function Index()
    {
        return User::all();
    }

    //get user details
    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }


    public function Register(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'num_tel' => 'integer',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation failed',
                    'errors' => $validate->errors()
                ], 401);
            }
            $verification_code = rand(1000, 9999);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'verified'=> false
            ]);

            if ($request->role === 'patient') {
                $patient = new Patient();
               
                
                // $patient->kfctoken = $request->kfctoken;
                $user->patient()->save($patient);
            } elseif ($request->input('role') === 'medecin') {
                $medic = new Medic();
                $medic->adresse = $request->adresse;
                $medic->num_tel = $request->num_tel;
                // $medic->kfctoken = $request->kfctoken;
                $medic->spec_id = $request->spec_id;
                $user->medic()->save($medic);
            }
/*
            $mail_data = [
                'recipient' => $request->email,
                'fromEmail' => "SofiaCareapp@gmail.com",
                'fromName' => 'SofiaCare',
                'subject' => 'Verifier Mail',
                'body' => 'Mail Body',
                'code' => $verification_code
            ];

            Mail::send('validate_template', $mail_data, function ($message) use ($mail_data) {
                $message->to($mail_data['recipient'])
                    ->from($mail_data['fromEmail'])
                    ->subject($mail_data['subject']);
            });
*/
            return response()->json([
                'status' => true,
                'message' => 'User created succf',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'data' => $user
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function Login(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if ($user->role === 'patient') {
                $patient = $user->patient;
                //$patient->fcmtoken = $request->fcmtoken;
                $patient->save();
            } elseif ($user->role === 'medic') {
                $medic = $user->medic;
                $medic->fcmtoken = $request->fcmtoken;
                $medic->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'data' => $user

            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function Logout(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'User Logged Out Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function Update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validate = Validator::make($request->all(), [
                'name' => 'sometimes|required',
                'num_tel' => 'sometimes|integer',
                'adresse' => 'sometimes|string',
                'ville' => 'sometimes|string',
                'sexe' => 'sometimes|string',
                'num_cnam' => 'sometimes|integer',
                'email' => 'sometimes|required|email|unique:users,email,' . $id,
                'role' => 'sometimes|required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validate->errors()
                ], 401);
            }

            $data = [];

            if ($request->has('name')) {
                $user->name = $request->name;
            }


            if ($request->has('num_tel')) {
                $user->num_tel = $request->num_tel;
            }

            if ($request->has('adresse')) {
                $user->adresse = $request->adresse;
            }

            if ($request->has('ville')) {
                $user->ville = $request->ville;
            }

            if ($request->has('sexe')) {
                $user->sexe = $request->sexe;
            }

            if ($request->has('num_cnam')) {
                $user->num_cnam = $request->num_cnam;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('role')) {
                $user->role = $request->role;
            }

            $user->update($data);

            return response()->json([
                'status' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function Validate_Mail(Request $request)
    {

        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation failed',
                    'errors' => $validate->errors()
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'user doesn"t exist',
                ], 401);
            }
            // $password = Str::password(8);
            $password = rand(1000, 9999);
            // $hashed_random_password = Hash::make(str_random(8));
            // $user->password = Hash::make($request->password);
            $user->verification_code = $password;
            $user->save();

            $mail_data = [
                'recipient' => $request->email,
                'fromEmail' => "SofiaCareapp@gmail.com",
                'fromName' => 'SofiaCare',
                'subject' => 'Verifier Mail',
                'body' => 'Mail Body',
                'code' => $password
            ];

            Mail::send('email_template', $mail_data, function ($message) use ($mail_data) {
                $message->to($mail_data['recipient'])
                    ->from($mail_data['fromEmail'])
                    ->subject($mail_data['subject']);
            });

            return response()->json([
                'status' => true,
                'message' => 'Email was sent',
                'data' => $user,

            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function Verif_code(Request $request)
    {

        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|email',
                'verification_code' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation failed',
                    'errors' => $validate->errors()
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'user doesn"t exist',
                ], 401);
            }

            if ($user->verification_code !== $request->verification_code) {
                return response()->json([
                    'status' => false,
                    'message' => 'incorect verification code',
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'Verified succefully',
                'data' => $user,

            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function Update_password(Request $request)
    {

        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation failed',
                    'errors' => $validate->errors()
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'user doesn"t exist',
                ], 401);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Email was sent & password was updated',
                'data' => $user

            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $text = $request->input('text');

        $results = User::where(function ($query) use ($text) {
            $columns = Schema::getColumnListing('users');
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $text . '%');
            }
        })->get();

        return response()->json($results);
    }
}
