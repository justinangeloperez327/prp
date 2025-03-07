<?php

namespace App\Filament\App\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Contact;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

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
                    ->mutateFormDataUsing(function(array $data) {
                        $user = User::create([
                            'name' => $data['first_name'] . ' ' . $data['last_name'],
                            'email' => $data['email'],
                            'password' => Hash::make($data['password']),
                        ]);
                        $data['user_id'] = $user->id;
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
}
