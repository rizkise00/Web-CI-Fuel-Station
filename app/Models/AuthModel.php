<?php 

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['full_name', 'username', 'email', 'password', 'role', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function verifyLogin($email, $password)
    {
        $user = $this->where('email', $email)->first();

        if (!$user || !isset($user['password']) || !password_verify($password, $user['password'])) {
            return [
				'status' => 401, 
				'message' => 'Invalid Credentials'
			];
        }

        return [
            'status' => 200,
            'message' => 'Login successfully',
            'user' => [
                'uid' => $user['id'],
                'full_name' => $user['full_name'],
                'role' => $user['role']
            ]
        ];
    }
}