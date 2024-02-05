<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function list(Request $request)
    {
        $query = User::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->input('user_type'));
        }

        if ($request->filled('is_delete')) {
            $query->where('is_delete', $request->input('is_delete'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        // Filter by user type and deleted status
        $query->where('is_delete', 0)->where('user_type', 1);

        // Fetch filtered users with pagination
        $users = $query->orderBy('id', 'desc')->paginate(2);

        // Pass data to the view
        $data['header_title'] = "Admin List";
        return view('admin.list', $data, compact('users'));
    }

    public function add()
    {

        $data['header_title'] = "Add new admin";
        return view('admin.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        // User::create($request->all());
        $user = new User;
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->user_type = 1;
        $user->save();

        return redirect()->route('admin.list')
            ->with('success', 'Admin created successfully.');
    }

    public function edit($id)
    {
        $data['getRecord'] = User::getSingle($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = "Edit Admin";
            return view('admin.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::getSingle($id);
        $user->name = trim($request->name);
        $user->email = trim($request->email);

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->user_type = 1;
        $user->save();

        return redirect()->route('admin.list')
            ->with('success', 'Admin updated successfully.');
    }

    public function delete($id)
    {
        $user = User::getSingle($id);
        $user->is_delete = 1;
        $user->save();

        return redirect()->route('admin.list')
            ->with('success', 'Admin deleted successfully.');
    }
}
