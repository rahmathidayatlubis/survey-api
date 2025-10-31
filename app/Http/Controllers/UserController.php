<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $search = $request->input('search');

        // Gabungkan query pencarian dan filter role dalam satu query
        $data = User::whereIn('role', ['mahasiswa', 'dosen'])
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nim', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('jurusan', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.user', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'nim' => 'required|string|unique:users,nim',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'nullable|string|max:255',
            'role' => 'string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        // Jika password tidak diisi, gunakan tanggal lahir (format ddmmyyyy)
        if (!isset($validated['password'])) {
            $tglLahir = date('dmY', strtotime($validated['tanggal_lahir']));
            $validated['password'] = $tglLahir;
        }

        // Simpan data user
        User::create([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jurusan' => $validated['jurusan'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role']
        ]);

        // Redirect ke halaman user index dengan pesan sukses
        return redirect()
            ->route('admin.user')
            ->with('success', 'Data mahasiswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $user = User::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'nim')->ignore($user->id)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'required|string|max:255',
            'role' => 'required|in:mahasiswa,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            // Custom error messages
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah digunakan oleh mahasiswa lain.',
            'nim.max' => 'NIM maksimal 20 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh mahasiswa lain.',
            'email.max' => 'Email maksimal 255 karakter.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal tidak valid.',
            'jurusan.required' => 'Program studi wajib dipilih.',
            'jurusan.max' => 'Program studi maksimal 255 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            // Data yang akan diupdate
            $dataToUpdate = [
                'nama' => $validated['nama'],
                'nim' => $validated['nim'],
                'email' => $validated['email'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jurusan' => $validated['jurusan'],
                'role' => $validated['role'],
            ];

            // Jika password diisi, maka update password juga
            if (!empty($validated['password'])) {
                $dataToUpdate['password'] = Hash::make($validated['password']);
            }

            // Update data user
            $user->update($dataToUpdate);

            return redirect()->route('admin.user')
                ->with('success', 'Data mahasiswa berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari user berdasarkan ID
        $user = User::find($id);
        // Jika user tidak ditemukan
        if (!$user) {
            return redirect()->back()->with('error', 'Data user tidak ditemukan.');
        }
        // Hapus user
        $user->delete();
        // Kembali dengan pesan sukses
        return redirect()->route('admin.user')->with('success', 'User berhasil dihapus.');
    }
}
