<?php

namespace App\Livewire;

use App\Models\Contact;
use App\Models\Customer;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ContactList extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Customer $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Contact::query()
                    ->where('customer_id', $this->customer->id)
            )
            ->columns([
                TextColumn::make('first_name', 'last_name')
                    ->formatStateUsing(fn (Contact $contact) => $contact->first_name.' '.$contact->last_name)
                    ->label('Contact Name')
                    ->sortable(),
                TextColumn::make('direct_phone')
                    ->label('Phone')
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email Address')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (Contact $contact): string => match ($contact->status) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->sortable(),
            ])->paginated(false);
    }

    public function render()
    {
        return view('livewire.contact-list');
    }
}
