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
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $schools = DB::table('schools')
            ->select('schools.*')
            // jumlah semua pengguna di sekolah tersebut
            ->selectSub(
                DB::table('users')->selectRaw('count(*)')->whereColumn('users.school_id', 'schools.id'),
                'users_count'
            )
            // nama admin sekolah (admin pertama yang terdaftar)
            ->selectSub(
                DB::table('users')->select('name')
                    ->whereColumn('users.school_id', 'schools.id')
                    ->where('role', 'school_admin')
                    ->orderBy('id')->limit(1),
                'admin_name'
            )
            ->when($q !== '', fn ($b) => $b->where('schools.name', 'like', '%'.$q.'%'))
            ->orderByDesc('schools.id')
            ->paginate(5)
            ->withQueryString();

        return view('SuperAdmin.tenants.index', compact('schools', 'q'));
    }

    // Tangguhkan / aktifkan sekolah
    public function toggleStatus(int $tenant)
    {
        $school = DB::table('schools')->where('id', $tenant)->first();
        abort_if(! $school, 404);

        $baru = $school->status === 'active' ? 'suspended' : 'active';

        DB::table('schools')->where('id', $tenant)->update(['status' => $baru, 'updated_at' => now()]);

        return back()->with('status', 'Status '.$school->name.' diubah menjadi '.($baru === 'active' ? 'Aktif' : 'Ditangguhkan').'.');
    }

    // Sementara: halaman detail dan edit belum dibuat
    public function show(string $tenant)
    {
        return redirect()->route('super-admin.tenants.index')->with('status', 'Halaman detail sekolah belum tersedia.');
    }

    public function edit(string $tenant)
    {
        return redirect()->route('super-admin.tenants.index')->with('status', 'Halaman edit sekolah belum tersedia.');
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
            'email'       => ['required', 'email', 'max:255', 'unique:schools,contact_email'],
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
                'contact_email' => $data['email'],
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