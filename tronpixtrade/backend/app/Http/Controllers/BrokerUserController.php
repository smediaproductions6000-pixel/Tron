<?php

namespace App\Http\Controllers;

use App\Models\BrokerUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BrokerUserController extends Controller
{
    public function index() {
        return BrokerUser::all();
    }

    public function approveKyc($id) {
        $user = BrokerUser::findOrFail($id);
        $user->status = 'active';
        $user->save();
        return response()->json($user);
    }

    public function rejectKyc($id) {
        $user = BrokerUser::findOrFail($id);
        $user->status = 'rejected';
        $user->save();
        return response()->json($user);
    }

    public function deleteKyc($id) {
        $user = BrokerUser::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Broker KYC deleted']);
    }

    public function credit(Request $request) {
        $request->validate(['email' => 'required|email', 'amount' => 'required|numeric']);
        $user = BrokerUser::where('email', $request->email)->firstOrFail();
        $user->balance += $request->amount;
        $user->save();
        return response()->json($user);
    }

    public function deduct(Request $request) {
        $request->validate(['email' => 'required|email', 'amount' => 'required|numeric']);
        $user = BrokerUser::where('email', $request->email)->firstOrFail();
        $user->balance -= $request->amount;
        $user->save();
        return response()->json($user);
    }
}