<?php
namespace App\Services;

use App\Models\User;

class UserService {
    public function getUserById(int $id): User {
        // Logic to get user by ID
    }
    public function getAllUsers(): array {
        // Logic to get all users
    }
    public function getUsersWithAdminRole(): array {
        // Logic to get users with admin role
    }
}