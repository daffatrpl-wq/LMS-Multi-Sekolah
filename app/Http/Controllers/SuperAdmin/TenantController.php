<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    // Sementara: daftar sekolah belum dibuat, arahkan ke form tambah sekolah.
    public function index()
    {
        return redirect()->route('super-admin.tenants.create');
    }

    public function create()
    {
        return view('SuperAdmin.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:schools,name'],
            'address'     => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:schools,email'],
            'phone'       => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'admin_name'  => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'max_users'   => ['required', 'integer', 'min:1', 'max:100000'],
            'max_classes' => ['required', 'integer', 'min:1', 'max:10000'],
        ], [
            'name.required'        => 'Nama sekolah wajib diisi.',
            'name.unique'          => 'Nama sekolah sudah terdaftar.',
            'address.required'     => 'Alamat wajib diisi.',
            'email.required'       => 'Email sekolah wajib diisi.',
            'email.email'          => 'Format email sekolah tidak valid.',
            'email.unique'         => 'Email sekolah sudah terdaftar.',
            'phone.required'       => 'No. telepon wajib diisi.',
            'phone.regex'          => 'No. telepon tidak valid (8–20 digit, boleh diawali +).',
            'admin_name.required'  => 'Nama admin wajib diisi.',
            'admin_email.required' => 'Email admin wajib diisi.',
            'admin_email.email'    => 'Format email admin tidak valid.',
            'admin_email.unique'   => 'Email admin sudah dipakai akun lain.',
            'max_users.required'   => 'Maks. pengguna wajib diisi.',
            'max_users.min'        => 'Maks. pengguna minimal 1.',
            'max_classes.required' => 'Maks. kelas wajib diisi.',
            'max_classes.min'      => 'Maks. kelas minimal 1.',
        ]);

        $passwordSementara = Str::random(12);

        // Sekolah dan akun admin dibuat bersamaan: gagal satu, batal semua.
        DB::transaction(function () use ($data, $passwordSementara) {
            $schoolId = DB::table('schools')->insertGetId([
                'name'        => $data['name'],
                'address'     => $data['address'],
                'email'       => $data['email'],
                'phone'       => $data['phone'],
                'status'      => 'active',
                'max_users'   => $data['max_users'],
                'max_classes' => $data['max_classes'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // forceCreate: role, school_id, is_active tidak ada di $fillable (sengaja)
            User::forceCreate([
                'name'              => $data['admin_name'],
                'email'             => $data['admin_email'],
                'password'          => Hash::make($passwordSementara),
                'role'              => 'school_admin',
                'school_id'         => $schoolId,
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);
        });

        return redirect()
            ->route('super-admin.tenants.create')
            ->with('tenant_created', [
                'sekolah' => $data['name'],
                'email'   => $data['admin_email'],
                'password'=> $passwordSementara,
            ]);
    }
}