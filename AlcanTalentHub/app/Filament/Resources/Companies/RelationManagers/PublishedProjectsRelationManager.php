<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PublishedProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'publishedProjects';

    protected static ?string $title = 'Proyectos Publicados';

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

                Forms\Components\Select::make('applicants')
                    ->label('Alumnos Inscritos')
                    ->multiple()
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
                    ->limit(50),

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
                \Filament\Actions\CreateAction::make()
                    ->label('Nuevo Proyecto'),
            ])
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->label('Gestionar'),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
