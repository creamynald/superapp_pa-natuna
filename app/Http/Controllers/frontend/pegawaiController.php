<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kesekretariatan\Pegawai;

class pegawaiController extends Controller
{
   public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $pegawais = Pegawai::query()
            ->with('user')
            // join ke users supaya bisa order by users.name
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            // cari: grupkan semua kondisi OR di satu where()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"))
                        ->orWhere('employees.nip', 'like', "%{$q}%")
                        ->orWhere('employees.jabatan', 'like', "%{$q}%")
                        ->orWhere('employees.pangkat_golongan', 'like', "%{$q}%")
                        ->orWhere('employees.tempat_lahir', 'like', "%{$q}%");
                });
            })
            // urutan utama: ketua, wakil, hakim, panitera*, sekretaris*, lainnya
            ->orderByRaw("
                CASE
                    WHEN LOWER(employees.jabatan) LIKE '%ketua%' THEN 0
                    WHEN LOWER(employees.jabatan) LIKE '%wakil%' THEN 1
                    WHEN LOWER(employees.jabatan) LIKE '%hakim%' THEN 2
                    WHEN LOWER(employees.jabatan) LIKE 'panitera%' OR LOWER(employees.jabatan) LIKE '%panitera%' THEN 3
                    WHEN LOWER(employees.jabatan) LIKE 'sekretaris%' OR LOWER(employees.jabatan) LIKE '%sekretaris%' THEN 4
                    ELSE 5
                END
            ")
            // urutan kedua: nama jabatan lalu nama user
            ->orderBy('employees.jabatan')
            ->orderBy('users.name')
            // penting: pilih kolom employees.* agar model tetap Pegawai
            ->select('employees.*')
            ->paginate(50)
            ->withQueryString();

        return view('frontend.pegawai.index', compact('pegawais', 'q'));
    }

}
