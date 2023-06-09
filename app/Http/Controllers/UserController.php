<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function create()
    {
        $title = 'Create';
        return view('dashboard.users.create', compact('title'));
    }
    public function index()
    {
        $datas = User::whereNotIn('level', ['admin', 'pelanggan'])->get();
        $title = 'Users';
        return view('dashboard.users.index', compact('datas', "title"));
    }

    public function detail(User $user)
    {
    }

    public function edit(User $user)
    {
        $title = 'Update';
        $data = $user;
        $enumLevel = [['key' => 'admin', 'value' => 'Admin'], ['key' => 'pemilik', 'value' => 'Pemilik'], ['key' => 'bendahara', 'value' => 'Bendahara']];
        $enumJabatan = [['key' => 'administrator', 'value' => 'Administrator'], ['key' => 'bendahara', 'value' => 'Bendahara'], ['key' => 'pemilik', 'value' => 'Pemilik']];
        return view('dashboard.users.update', compact('title', 'data', 'enumLevel', 'enumJabatan'));
    }

    public function store(Request $request)
    {
        $validationData = $request->validate([
            'nama' => 'required|string',
            'level' => 'required|string|in:admin,bendahar,pemilik',
            'email' => 'email:rfc,dns|unique:users',
            'aktif' => 'nullable|integer',
            'no_hp' => 'nullable|unique:pelanggans|string|min:11|max:14',
            'alamat' => 'required|string|',
            'jabatan' => 'required|string|in:administrator,bendahara,pemilik',
            'password' => 'required|min:6',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        /* Sotre data user */
        $user = new User();
        $user->email = $validationData['email'];
        $user->level = $validationData['level'];
        $user->password = bcrypt($validationData['password']);
        if ($request->aktif) {
            $user->aktif = $validationData['aktif'];
        }
        $user->save();

        /* Store data pelanggan */
        $karyawan = new Karyawan();
        $karyawan->nama_lengkap = $validationData['nama'];
        $karyawan->no_hp = $validationData['no_hp'];
        $karyawan->alamat = $validationData['alamat'];
        $karyawan->jabatan = $validationData['jabatan'];

        $user->karyawans()->save($karyawan);

        $result = $user->save();
        if ($result) {
            return redirect(Route('user.index'))->with('success', 'You Have Successfully Created an Account.');
        } else {
            return redirect(Route('user.index'))->with('error', 'You Have Failed Create an Account.');
        }
    }

    public function update(Request $request, User $user)
    {
        $validationData = $request->validate([
            'nama_lengkap' => 'string',
            'level' => 'string|in:admin,bendahar,pemilik',
            'aktif' => 'nullable|integer',
            'no_hp' => 'nullable|unique:pelanggans|string|min:11|max:14',
            'alamat' => 'string|',
            'jabatan' => 'string|in:administrator,bendahara,pemilik',
            'password' => 'nullable|min:6',
        ]);
        if ($request->email != $user->email) {
            $ruls['email'] = 'email:rfc,dns|unique:users';
        }

        $validationData['password'] = bcrypt($validationData['password']);

        $updateUser = $user->update($validationData);

        if ($updateUser) {
            $karyawan = $user->karyawans;
            $karyawan->nama_lengkap = $validationData['nama_lengkap'];
            $karyawan->no_hp = $validationData['no_hp'];
            $karyawan->alamat = $validationData['alamat'];
            $karyawan->jabatan = $validationData['jabatan'];

            $karyawan->save();

            return redirect(Route('user.index'))->with('success', 'User Updated Successfully.');
        } else {
            return redirect(Route('user.index'))->with('error', 'User Updated Failed.');
        }
    }

    public function delete(User $user)
    {
        $result = $user->destroy($user->id);

        if ($result) {
            Karyawan::destroy($user->karyawans->id);
            return redirect()->back()->with('success', 'User Delete Successfully.');
        } else {
            return redirect()->back()->with('error', 'User Delete Failed.');
        }
    }
}
