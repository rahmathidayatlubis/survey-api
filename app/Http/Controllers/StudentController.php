<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->where('role', 'mahasiswa')->get();

        return view('admin.user', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|unique:users,nim',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        if (!isset($validated['password'])) {
            $tglLahir = date('dmY', strtotime($validated['tanggal_lahir']));
            $validated['password'] = $tglLahir;
        }

        User::create([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jurusan' => $validated['jurusan'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
        ]);

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $student)
    {
        $validated = $request->validate([
            'nim' => 'required|string|unique:users,nim,' . $student->id,
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'nullable|string|max:255',
        ]);


        // Update data user
        $student->update([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jurusan' => $validated['jurusan'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Data mahasiswa berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $student)
    {
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil dihapus.'
        ], 200);
    }
}
