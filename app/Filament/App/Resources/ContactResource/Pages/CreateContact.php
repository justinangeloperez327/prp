<?php

namespace App\Filament\App\Resources\ContactResource\Pages;

use App\Filament\App\Resources\ContactResource;
use App\Models\Contact;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Create the user first
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
        ]);

        // Create the contact with the user_id set to the newly created user's ID
        return Contact::create([
            'title' => $data['title'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'direct_phone' => $data['direct_phone'],
            'mobile_phone' => $data['mobile_phone'],
            'notes' => $data['notes'],
            'user_id' => $user->id,
        ]);

    }
}
