<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
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

        $user = User::create([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jurusan' => $validated['jurusan'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi mahasiswa berhasil',
            'data' => [
                'user' => $user
            ]
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', function ($attr, $value, $fail) {
                $val     = trim($value);
                $isEmail = filter_var($val, FILTER_VALIDATE_EMAIL);
                $isNim   = preg_match('/^\d{6,20}$/', $val);
                if (!$isEmail && !$isNim) {
                    $fail('Gunakan email yang valid atau NIM (6–20 digit).');
                }
            }],
            'password'   => ['required', 'string'],
        ]);

        $identifier = trim($validated['identifier']);
        $lowerEmail = mb_strtolower($identifier);

        // Cari user baik berdasarkan email (case-insensitive) maupun NIM
        $user = User::where(function ($q) use ($lowerEmail, $identifier) {
            $q->whereRaw('LOWER(email) = ?', [$lowerEmail])
                ->orWhere('nim', $identifier);
        })->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'identifier' => ['Akun tidak ditemukan.'],
            ]);
        }

        if (! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Kredensial tidak valid.'],
            ]);
        }

        // Opsional: putuskan token lama agar 1 sesi aktif per user
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user'  => $user,
                'token' => $token,
            ],
        ], Response::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Token tidak valid atau sudah kedaluwarsa.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($token = $user->currentAccessToken()) {
            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil keluar.'
        ], Response::HTTP_OK);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Token tidak valid atau sudah kedaluwarsa.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diambil.',
            'data' => [
                'user' => $user,
            ]
        ], Response::HTTP_OK);
    }
}