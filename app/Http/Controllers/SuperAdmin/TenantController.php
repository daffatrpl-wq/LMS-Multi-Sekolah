<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    // READ: Menampilkan daftar tenant dan statistik global (FR-SA-03)
    public function index()
    {
 $tenants = School::withCount('users')->latest()->get();
    
    // Menghitung statistik global untuk dashboard (FR-SA-03)
    $globalStats = [
        'total_tenants' => School::count(),
        'active_tenants' => School::where('status', 'active')->count(),
    ];

    return view('dashboard', compact('tenants', 'globalStats'));
    }

    // CREATE FORM: Menampilkan form tambah tenant
    public function create()
    {
        return view('create_tenant');
    }

    // STORE: Menyimpan tenant baru dan akun admin sekolah (FR-SA-01)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name'   => 'required|string|max:255|unique:schools,name',
            'address'       => 'required|string',
            'contact_email' => 'required|email|unique:schools,contact_email',
            'admin_name'    => 'required|string|max:255',
            'admin_email'   => 'required|email|unique:users,email',
            'max_users'     => 'required|integer|min:10',
            'max_classes'   => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $school = School::create([
                'name'          => $validated['school_name'],
                'address'       => $validated['address'],
                'contact_email' => $validated['contact_email'],
                'status'        => 'active',
                'max_users'     => $validated['max_users'],
                'max_classes'   => $validated['max_classes'],
            ]);

            User::create([
                'school_id' => $school->id,
                'name'      => $validated['admin_name'],
                'email'     => $validated['admin_email'],
                'password'  => Hash::make('password123'), // Default password sementara
                'role'      => 'school_admin',
                'is_active' => true,
            ]);
        });

        return redirect()->route('super-admin.tenants.index')
                         ->with('success', 'Tenant sekolah berhasil ditambahkan.');
    }

    // EDIT FORM: Form ubah data & kuota tenant (FR-SA-04)
    public function edit(School $tenant)
    {
        return view('super_admin.tenants.edit', compact('tenant'));
    }

    // UPDATE: Memperbarui data tenant & status (FR-SA-02 & FR-SA-04)
    public function update(Request $request, School $tenant)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:schools,name,' . $tenant->id,
            'address'     => 'required|string',
            'status'      => 'required|in:active,suspended,inactive',
            'max_users'   => 'required|integer|min:1',
            'max_classes' => 'required|integer|min:1',
        ]);

        $tenant->update($validated);

        return redirect()->route('super-admin.tenants.index')
                         ->with('success', 'Data tenant berhasil diperbarui.');
    }

    // DELETE: Menghapus tenant beserta data terkait di dalamnya
    public function destroy(School $tenant)
    {
        DB::transaction(function () use ($tenant) {
            User::where('school_id', $tenant->id)->delete();
            $tenant->delete();
        });

        return redirect()->route('super-admin.tenants.index')
                         ->with('success', 'Tenant berhasil dihapus.');
    }
}