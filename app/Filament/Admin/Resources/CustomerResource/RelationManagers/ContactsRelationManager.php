<?php

namespace App\Filament\Admin\Resources\CustomerResource\RelationManagers;

use App\Models\Contact;
use App\Models\User;
use Exception;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contact Details')
                    ->columns([
                        'md' => 2,
                        'sm' => 1,
                    ])
                    ->schema([
                        Select::make('title')
                            ->options([
                                'Mr' => 'Mr',
                                'Mrs' => 'Mrs',
                                'Ms' => 'Ms',
                                'Miss' => 'Miss',
                                'Dr' => 'Dr',
                            ])
                            ->placeholder('Select Title')
                            ->columnSpan(1)
                            ->required(),
                        Hidden::make('hidden')
                            ->columnSpan(1),
                        TextInput::make('first_name')
                            ->required()
                            ->columnSpan(1)
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->required()
                            ->columnSpan(1)
                            ->maxLength(255),
                        TextInput::make('direct_phone')
                            ->mask('+61 9999 9999')
                            ->placeholder('+61 9999 9999')
                            ->required()
                            ->columnSpan(1)
                            ->maxLength(255),
                        TextInput::make('mobile_phone')
                            ->mask('+61 9999 9999')
                            ->placeholder('+61 9999 9999')
                            ->required()
                            ->columnSpan(1)
                            ->maxLength(255),
                        Textarea::make('notes')
                            ->maxLength(255),
                    ]),
                Section::make('Login Details')
                    ->columns([
                        'md' => 2,
                        'sm' => 1,
                    ])
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                TextColumn::make('first_name'),
                TextColumn::make('last_name'),
                TextColumn::make('direct_phone'),
                TextColumn::make('mobile_phone'),
                TextColumn::make('notes'),
                TextColumn::make('user.email'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        if (User::where('email', $data['email'])->exists()) {
                            throw new Exception('A user with this email already exists.');
                        }
                        $user = User::create([
                            'email' => $data['email'],
                            'password' => Hash::make($data['password']),
                        ]);

                        $data['user_id'] = $user->id;
                        $data['email'] = $data['email'];
                        $data['contact_code'] = $this->generateContactCode();

                        $user->assignRole('customer');

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    private function generateContactCode()
    {
        // Check last contact code and increment
        $lastContact = Contact::orderBy('contact_code', 'desc')->first();

        if ($lastContact) {
            $lastContactCode = $lastContact->contact_code;
            $lastContactCode = str_replace('CC', '', $lastContactCode);
            $lastContactCode = (int) $lastContactCode;
            $lastContactCode++;

            return 'CC'.str_pad($lastContactCode, 5, '0', STR_PAD_LEFT);
        }

        // Default to CC00001 if no previous contact exists
        return 'CC00001';
    }
}
