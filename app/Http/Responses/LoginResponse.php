<?php

namespace App\Http\Responses;

use App\Filament\Admin\Resources\OrderResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if (Auth::user()->hasRole('customer')) {
            return redirect()->to(OrderResource::getUrl('index'));
        }

        return parent::toResponse($request);
    }
}
