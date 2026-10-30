enum Role: string {
    case SuperAdmin = 'super_admin'; case SchoolAdmin = 'school_admin';
    case Teacher = 'teacher';        case Student = 'student';
    public function home(): string {
        return match ($this) {
            self::SuperAdmin  => route('super-admin.dashboard'),
            self::SchoolAdmin => route('admin-sekolah.dashboard'),
            self::Teacher     => route('guru.dashboard'),
            self::Student     => route('siswa.dashboard'),
        };
    }
}

// Support/Tenancy/BelongsToTenant.php — fail-closed
static::addGlobalScope('tenant', function (Builder $q) {
    $ctx = app(TenantContext::class);
    if ($ctx->isPlatform()) return;                       // hanya super admin, eksplisit
    $q->where($q->getModel()->getTable().'.tenant_id', $ctx->id() ?? 0); // tanpa tenant = kosong
});

// Support/Tenancy/SetTenantContext.php (didaftarkan sebagai alias 'tenant')
$user = $request->user();
$user->role === Role::SuperAdmin ? $ctx->asPlatform() : $ctx->set($user->tenant_id ?? abort(403));