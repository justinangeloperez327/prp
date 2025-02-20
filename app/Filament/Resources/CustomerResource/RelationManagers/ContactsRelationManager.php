<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentNestedResources\Concerns\NestedRelationManager;

class ContactsRelationManager extends RelationManager
{
    use NestedRelationManager;

    protected static string $relationship = 'contacts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Contact Name'),
                Tables\Columns\TextColumn::make('direct_phone')
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Address'),

            ]);
    }
}
