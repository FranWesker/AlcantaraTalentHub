<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SkillResource\Pages;
use App\Filament\Resources\SkillResource\RelationManagers;
use App\Models\Skill;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
