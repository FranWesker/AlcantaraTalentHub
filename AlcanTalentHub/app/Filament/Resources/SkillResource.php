<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SkillResource\Pages;
use App\Models\Skill;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Añadimos un campo de texto para el nombre
                Forms\Components\TextInput::make('name')
                    ->label('Nombre de la Habilidad')
                    ->required() // Lo hago obligatorio, coincidiendo asi con la base de datos
                    ->maxLength(255), // Limito la longitud a 255 caracteres
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Añadimos una columna para poder ver el nombre de la habilidad en la tabla
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable() // Hacemos que esta columna sea buscable
                    ->sortable(), // Hacemos que esta columna sea ordenable
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSkills::route('/'),
            'create' => Pages\CreateSkill::route('/create'),
            'edit' => Pages\EditSkill::route('/{record}/edit'),
        ];
    }
}
