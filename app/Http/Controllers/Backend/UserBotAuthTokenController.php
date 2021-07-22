<?php

namespace App\Http\Controllers\Backend;

use App\Models\UserBotAuthToken;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserBotAuthTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        return response()->json([
            'status' => 'Successfully!',
            'data'   => UserBotAuthToken::makeForUser($request->user_id)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param UserBotAuthToken $userBotAuthToken
     * @param string $token
     * @return JsonResponse
     */
    public function show(UserBotAuthToken $userBotAuthToken, string $token)
    {
        return response()->json([
            'status' => 'Successfully!',
            'data'   => UserBotAuthToken::findByToken($token)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param UserBotAuthToken $userBotAuthToken
     * @return void
     */
    public function update(Request $request, UserBotAuthToken $userBotAuthToken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserBotAuthToken $userBotAuthToken
     * @return void
     */
    public function destroy(UserBotAuthToken $userBotAuthToken)
    {
        //
    }
}
