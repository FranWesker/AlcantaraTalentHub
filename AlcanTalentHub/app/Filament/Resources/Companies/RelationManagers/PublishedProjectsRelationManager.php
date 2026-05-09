<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PublishedProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'publishedProjects';

    protected static ?string $title = 'Proyectos Publicados';

    /**
     * Formulario para crear y editar proyectos
     * @param Schema $schema
     * @return Schema
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título del Proyecto')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->rows(3),

                // Aquí gestionamos los alumnos inscritos
                Forms\Components\Select::make('applicants')
                    ->label('Alumnos Inscritos')
                    ->multiple() // Permite ver y eliminar varios alumnos
                    ->relationship(
                        'applicants',
                        'name',
                        fn (Builder $query) => $query->where('role', 'estudiante')
                    )
                    ->preload()
                    ->helperText('Añade o quita alumnos de la lista para gestionar la inscripción.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50), // Muestra solo el inicio para no saturar la tabla

                // Columna para ver cuántos alumnos hay inscritos de un vistazo
                Tables\Columns\TextColumn::make('applicants_count')
                    ->label('Alumnos')
                    ->counts('applicants')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo Proyecto'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Gestionar'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
