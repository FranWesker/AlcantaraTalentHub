<?php
namespace App\Filament\Resources\StudentProfiles\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class SkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'skills';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Habilidad')
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([
                // 2. Usamos Actions en lugar de Tables\Actions
                Actions\AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->actions([
                // 3. Modificado a Actions
                Actions\DetachAction::make(),
            ])
            ->bulkActions([
                // 4. Modificado a Actions
                Actions\BulkActionGroup::make([
                    Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
