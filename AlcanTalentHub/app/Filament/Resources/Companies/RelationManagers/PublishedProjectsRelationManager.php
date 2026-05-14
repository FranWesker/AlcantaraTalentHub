<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Project;

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

                Forms\Components\Toggle::make('is_active')
                    ->label('Proyecto Activo')
                    ->default(true),
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
                    ->limit(50), // Muestra solo una breve parte de la descripción
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Eliminado CreateAction para evitar el error y forzar solo lectura
            ])
            ->actions([
                // Eliminadas acciones de edición y borrado
            ])
            ->bulkActions([
                // Eliminadas acciones masivas
            ]);
    }
}
