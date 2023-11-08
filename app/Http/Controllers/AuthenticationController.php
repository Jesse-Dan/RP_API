<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;

class AuthenticationController extends Controller
{

    public function generateCsfr()
    {
        return csrf_token();
    }

    public function generateToken($user_)
    {
        // $user_->token->revoke();
        return $user_->createToken('XT')->plainTextToken;
    }

    public function login(Request $request)
    {

        try {
            $rules = [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ];

            // Create a validator instance
            $validator = Validator::make($request->all(), $rules);

            // Check if the validation fails
            if ($validator->fails()) {
                // Handle validation failure
                return response()->json(['message' => $validator->errors()], 422); // 422 Unprocessable Entity
            }

            $user = User::where('email', '=', $request->email)->first();

            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $request->session()->put("loginId", $user->id);
                    return response()->json([
                        'bearer_token' => $this->generateToken($user),
                        'message' => 'Login Successful',
                        'data' => $user,
                    ], 200);

                } else {
                    return response()->json(['message' => 'Password is wrong'], 400);

                }
            } else {
                return response()->json(['message' => 'User Not Found'], 404);
            }

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];

            return response()->json(['message' => 'Database error.'], $errorCode);

        } catch (Exception $e) {

            return response()->json([
                'message' => 'Database error.',
                'error Code' => $e,
            ], 500);
        }
    }

    public function register(Request $request)
    {

        try {
            $rules = [
                'email' => ['required', 'email', 'unique:users'],
                'first_name' => ['required'],
                'last_name' => ['required'],
                'password' => ['required', 'min:8'],
                'confirm_password' => ['required', 'min:8'],
                'secreat_key' => ['required', 'min:8'],
                'country' => ['required'],

            ];

            // Create a validator instance
            $validator = Validator::make($request->all(), $rules);

            // Check if the validation fails
            if ($validator->fails()) {
                // Handle validation failure
                return response()->json(['message' => $validator->errors()], 422); // 422 Unprocessable Entity
            }
            // Create the new user record
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'secreat_key' => bcrypt($request->input('secreat_key')),
                'country' => $request->input('country'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'confirm_password' => bcrypt($request->input('confirm_password')),
            ]);

            return response()->json(['message' => 'User created successfully'], 201);

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];

            if ($errorCode == 1062) {
                return response()->json(['message' => 'Email address is already in use.'], 409);
            }

            return response()->json(['message' => 'Database error.'], 500);

        } catch (Exception $e) {

            return response()->json([
                'message' => 'Database error.',
                'error Code' => $e,
            ], 500);
        }
    }

    public function logout($userId)
    {
        try {
            if ($userId) {
                if (Session::has('loginId')) {
                    $user = User::where('id', '=', $userId)->first();
                    Session::pull('loginId');
                    $user->tokens()->delete();
                    return response()->json(['message' => 'User Logged out Sucessfully'], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'status_code' => 303,
                        'message' => 'No Session Found, Proceed to login ',
                    ], 301);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'User not Provided.',
                ], 404);

            }

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 400,
                'message' => 'Error - [' . $e . ']',
            ], 400);
        }
    }
}
