<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'last_login_at' => $this->last_login_at?->toISOString(),
            
            // Include roles information
            'roles' => $this->when(
                $this->relationLoaded('roles'),
                $this->roles->pluck('name')
            ),
            
            // Include permissions (only for authenticated user or admins)
            'permissions' => $this->when(
                $request->user() && ($request->user()->id === $this->id || $request->user()->hasRole('admin')),
                $this->getAllPermissions()->pluck('name')
            ),
            
            // Conditional sensitive data (only for the user themselves or admins)
            'phone' => $this->when(
                $request->user() && ($request->user()->id === $this->id || $request->user()->hasRole('admin')),
                $this->phone
            ),
            
            // Basic capabilities that frontend can use
            'can' => [
                'update_profile' => $request->user() && $request->user()->id === $this->id,
                'delete_account' => $request->user() && ($request->user()->id === $this->id || $request->user()->hasRole('admin')),
            ],
            
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}