<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\GetUserIdRequest;
use App\Http\Requests\ChangeUserNameRequest;
use App\Http\Requests\ChangeUserPassRequest;
use App\Http\Requests\GetUserDataRequest;
use App\Http\Requests\RestoreUserPassRequest;
use App\Models\User;
use App\Models\UserToken;

class UserController extends Controller
{
    public function signInAction(SignInRequest $request)
    {
        $user = new User;
        $validatedRequest = $request->validated();
        $data = $user->signIn($validatedRequest['email'], $validatedRequest['pass']);
        if($data["status"] === 200) {
            $token = new UserToken;
            return response()->json(["status" => $data["status"], "id" => $data["id"], "token" => $token->addToken($data["id"]), "msg" => ""]);
        }
        else {
            return response()->json(["status" => $data["status"], "id" => null, "token" => "", "msg" => "Wrong email or password"]);
        }
    }

    public function signUpAction(SignUpRequest $request)
    {
        $user = new User;
        $validatedRequest = $request->validated();
        $data = $user->signUp($validatedRequest['email'], $validatedRequest['pass'], $validatedRequest['name']);
        if($data["status"] === 202) {
            $token = new UserToken;
            return response()->json(["status" => $data["status"], "data" => ["id" => $data["id"],"token" => $token->addToken($data["id"]), "msg" => ""]]);
        }
        else if($data["status"] === 409) {
            return response()->json(["status" => $data["status"], "data" => ["id" => null,"token" => "", "msg" => "User with such email is exist"]]);
        }
        else {
            return response()->json(["status" => $data["status"], "data" => ["id" => null,"token" => "", "msg" => ""]]);
        }
    }

    public function returnUserIdAction(GetUserIdRequest $request)
    {
        $validatedRequest = $request->validated();
        $userId = $this->getIdFromToken($validatedRequest['token']);
        if($userId) return response()->json(["status" => 200, "data" => ["id" => $userId, "token" => $userId, "msg" => ""]]);
        return response()->json(["status" => 404, "data" => ["id" => null, "token" => $validatedRequest['token'], "msg" => "Wrong token"]]);
    }

    public function changeUserNameAction(ChangeUserNameRequest $request)
    {
        $validatedRequest = $request->validated();

        $userId = $this->getIdFromToken($validatedRequest['token']);
        if(!$userId) return response()->json(["status" => 404, "data" => ["isUpdated" => false,"msg" => "Wrong token"]]);

        $user = new User;
        $data = $user->changeUserName($validatedRequest['name'], $userId);
        return response()->json(["status" => $data["status"], "data" => ["isUpdated" => $data["status"] === 200, "msg" => $data["msg"]]]);
    }

    public function changeUserPassAction(ChangeUserPassRequest $request)
    {
        $validatedRequest = $request->validated();

        $userId = $this->getIdFromToken($validatedRequest['token']);
        if(!$userId) return response()->json(["status" => 404, "data" => ["isUpdated" => false,"msg" => "Wrong token"]]);

        $user = new User;
        $data = $user->changeUserPass($validatedRequest['oldPass'], $validatedRequest['newPass'], $userId);
        return response()->json($data);
    }

    public function returnUserDataAction(GetUserDataRequest $request) {
        $validatedRequest = $request->validated();

        $userId = $this->getIdFromToken($validatedRequest['token']);
        if(!$userId) return response()->json(["status" => 404, "data" => ["isUpdated" => false,"msg" => "Wrong token"]]);
        $user = new User;
        $data = $user->returnUserData($userId);
        return response()->json($data);
    }

    public function restoreUserPassAction(RestoreUserPassRequest $request) {
        $validatedRequest = $request->validated();

        $user = new User;
        $data = $user->restorePass($validatedRequest['email']);
        return response()->json($data);
    }

    protected function getIdFromToken(string $token): int | null
    {
        $userByToken = UserToken::where("token", $token)->first();
        if(!$userByToken) null;
        return $userByToken->user_id;
    }
}