<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'name', 'email', 'password',
        'google_id', 'facebook_id', 'avatar',
        'phone', 'address', 'city', 'pincode', 'country',
        'is_active',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'name'  => 'required|min_length[2]|max_length[120]',
        'email' => 'required|valid_email|max_length[160]',
    ];

    // ── Helpers ───────────────────────────────────────────────

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Find or create user via Google OAuth.
     */
    public function findOrCreateFromGoogle(array $googleUser): array
    {
        // Try to find by google_id first
        $user = $this->where('google_id', $googleUser['google_id'])->first();

        if ($user) {
            return $user;
        }

        // Try to match existing account by email
        $user = $this->findByEmail($googleUser['email']);
        if ($user) {
            $this->update($user['id'], [
                'google_id' => $googleUser['google_id'],
                'avatar'    => $googleUser['avatar'] ?? $user['avatar'],
            ]);
            return $this->find($user['id']);
        }

        // Create new user
        $id = $this->insert([
            'name'      => $googleUser['name'],
            'email'     => $googleUser['email'],
            'google_id' => $googleUser['google_id'],
            'avatar'    => $googleUser['avatar'] ?? null,
            'is_active' => 1,
        ]);

        return $this->find($id);
    }

    /**
     * Find or create user via Facebook OAuth.
     */
    public function findOrCreateFromFacebook(array $fbUser): array
    {
        $user = $this->where('facebook_id', $fbUser['facebook_id'])->first();

        if ($user) {
            return $user;
        }

        $user = $this->findByEmail($fbUser['email']);
        if ($user) {
            $this->update($user['id'], [
                'facebook_id' => $fbUser['facebook_id'],
                'avatar'      => $fbUser['avatar'] ?? $user['avatar'],
            ]);
            return $this->find($user['id']);
        }

        $id = $this->insert([
            'name'        => $fbUser['name'],
            'email'       => $fbUser['email'],
            'facebook_id' => $fbUser['facebook_id'],
            'avatar'      => $fbUser['avatar'] ?? null,
            'is_active'   => 1,
        ]);

        return $this->find($id);
    }
}
