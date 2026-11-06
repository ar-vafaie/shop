<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::where('is_admin', true)->select('name', 'family', 'username', 'email')->paginate(10);
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $like = "%{$q}%";

        return User::where('is_admin', false)
            ->where(function ($w) use ($like) {
                $w->where('name', 'like', $like)
                    ->orWhere('family', 'like', $like)
                    ->orWhere('username', 'like', $like)
                    ->orWhere('email', 'like', $like);
            })
            ->select('id', 'name', 'family', 'username', 'email')
            ->orderBy('name')
            ->limit(10)
            ->get();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'is_admin' => ['required', 'boolean'],
        ]);

        // جلوگیری از اینکه ادمین خودش رو از ادمینی دربیاره
        if ($user->is(auth('admin')->user()) && ! $data['is_admin']) {
            return response()->json(['message' => 'You cannot remove your own admin access.'], 422);
        }

        if ($user->is_admin === (bool) $data['is_admin']) {
            return response()->json(['message' => 'No change needed.'], 422);
        }

        $user->is_admin = $data['is_admin'];   // در #[Fillable] نیست، پس مستقیم ست می‌شه
        $user->save();

        return response()->json([
            'message' => $user->is_admin ? 'User promoted to admin.' : 'Admin access removed.',
            'user'    => $user->only('id', 'name', 'family', 'username', 'email', 'is_admin'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
