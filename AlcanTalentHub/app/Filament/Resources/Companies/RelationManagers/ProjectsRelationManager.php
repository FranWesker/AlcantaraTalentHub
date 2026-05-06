<?php
namespace App\Filament\Resources\Companies\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Título del Proyecto'),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->label('Descripción'),
                Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Abierto',
                        'closed' => 'Cerrado',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Proyecto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'success',
                        'closed' => 'danger',
                    }),
            ])
            ->filters([])
            ->headerActions([
                // 2. Modificado a Actions
                Actions\CreateAction::make(),
            ])
            ->actions([
                // 3. Modificado a Actions
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // 4. Modificado a Actions
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
