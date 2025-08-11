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
    ->leftJoin('users', 'employees.user_id', '=', 'users.id')
    ->when($q !== '', function ($query) use ($q) {
        $query->where(function ($sub) use ($q) {
            $sub->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"))
                ->orWhere('employees.nip', 'like', "%{$q}%")
                ->orWhere('employees.jabatan', 'like', "%{$q}%")
                ->orWhere('employees.pangkat_golongan', 'like', "%{$q}%")
                ->orWhere('employees.tempat_lahir', 'like', "%{$q}%");
        });
    })

    // 1) Prioritas jabatan: Ketua → Wakil Ketua → Hakim → Panitera → Sekretaris → lainnya
    ->orderByRaw("
        CASE
            WHEN LOWER(employees.jabatan) REGEXP '(^|[^a-z])ketua([^a-z]|$)'            THEN 0
            WHEN LOWER(employees.jabatan) LIKE 'wakil ketua'                          THEN 1
            WHEN LOWER(employees.jabatan) LIKE 'hakim'                                THEN 2
            WHEN LOWER(employees.jabatan) LIKE 'panitera'                             THEN 3
            WHEN LOWER(employees.jabatan) LIKE 'sekretaris'                           THEN 4
            WHEN LOWER(employees.jabatan) LIKE '%panita%' OR LOWER(employees.jabatan) LIKE '%pegawai%' THEN 6
            WHEN LOWER(employees.jabatan) LIKE '%staf%' OR LOWER(employees.jabatan) LIKE '%staff%' THEN 7
            ELSE 5
        END
    ")

    // 2) Jika sama, urut berdasarkan teks jabatan (A–Z)
    // ->orderByRaw("
    //     CASE
    //         WHEN LOWER(employees.pangkat_golongan) REGEXP '(^|[^a-z])^(golongan|pangkat) ([ivx]+)$' THEN 0
    //         ELSE 1
    //     END
    // ")

    // 3) Terakhir, urut nama (dari users.name)
    // ->orderBy('users.name')

    ->select('employees.*')
    ->paginate(50)
    ->withQueryString();


        return view('frontend.pegawai.index', compact('pegawais', 'q'));
    }
}
