<?php

namespace App\Policies;

use App\Models\KisUser;

class KisUserPolicy
{
    /**
     * Determine whether the user can view any models.
     * (Melihat Daftar User di Tabel)
     */
    public function viewAny(KisUser $kisUser): bool
    {
        // Superadmin dan Admin boleh lihat daftar user
        return $kisUser->role === 'superadmin' || $kisUser->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     * (Melihat Detail User)
     */
    public function view(KisUser $kisUser, KisUser $model): bool
    {
        // Superadmin boleh lihat siapa saja
        if ($kisUser->role === 'superadmin') {
            return true;
        }

        // Admin:
        if ($kisUser->role === 'admin') {
            // 1. Boleh lihat diri sendiri
            if ($kisUser->id === $model->id) return true;
            // 2. Boleh lihat detail operator
            if ($model->role === 'operator') return true;
            // 3. Boleh lihat sesama admin (opsional, biasanya boleh untuk koordinasi, tapi readonly)
            // Jika ingin STRICT "tidak boleh lihat sesama admin", hapus baris bawah ini.
            if ($model->role === 'admin') return true;
            
            return false;
        }

        // Operator hanya boleh lihat profilnya sendiri
        return $kisUser->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(KisUser $kisUser): bool
    {
        // Superadmin dan Admin boleh membuat user baru
        // (Catatan: Di Controller pastikan Admin tidak bisa bikin user dengan role 'superadmin' atau 'admin')
        return $kisUser->role === 'superadmin' || $kisUser->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(KisUser $kisUser, KisUser $model): bool
    {
        // 1. Superadmin bebas edit siapa saja
        if ($kisUser->role === 'superadmin') {
            return true;
        }

        // 2. Admin
        if ($kisUser->role === 'admin') {
            // Boleh edit diri sendiri (Ganti password/profil sendiri)
            if ($kisUser->id === $model->id) {
                return true;
            }

            // Boleh edit Operator
            if ($model->role === 'operator') {
                return true;
            }

            // DILARANG edit sesama Admin atau Superadmin
            return false;
        }

        // 3. Operator hanya boleh edit diri sendiri
        return $kisUser->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(KisUser $kisUser, KisUser $model): bool
    {
        // 1. Superadmin bebas hapus siapa saja
        if ($kisUser->role === 'superadmin') {
            return true;
        }

        // 2. Admin
        if ($kisUser->role === 'admin') {
            // HANYA boleh hapus Operator
            if ($model->role === 'operator') {
                return true;
            }

            // DILARANG hapus sesama Admin, Superadmin, atau Diri Sendiri (biasanya akun aktif tidak boleh hapus diri sendiri)
            return false;
        }

        // Operator tidak boleh menghapus akun (biasanya request ke admin)
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(KisUser $kisUser, KisUser $model): bool
    {
        // Logikanya sama dengan Delete
        if ($kisUser->role === 'superadmin') {
            return true;
        }

        if ($kisUser->role === 'admin') {
            return $model->role === 'operator';
        }

        return false;
    }

    /**
     * Determine whether the user can force delete the model.
     */
    public function forceDelete(KisUser $kisUser, KisUser $model): bool
    {
        // Biasanya Force Delete (hapus permanen) hanya Superadmin
        return $kisUser->role === 'superadmin';
    }
}