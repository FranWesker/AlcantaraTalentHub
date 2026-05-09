<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Pages\ViewProject;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Schemas\ProjectInfolist;
use App\Filament\Resources\Projects\Tables\ProjectsTable;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ProjectResource extends Resource
{
    // Vinculamos este recurso a tu modelo Project
    protected static ?string $model = Project::class;

    // Puedes cambiar el icono del menú lateral aquí
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Proyectos';
    protected static ?string $modelLabel = 'Proyecto';
    protected static ?string $pluralModelLabel = 'Proyectos';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $form
            ->schema([
                // Aquí irían los campos para crear/editar un proyecto
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Nombre del proyecto
                TextColumn::make('title')
                    ->label('Nombre del Proyecto')
                    ->searchable()
                    ->sortable(),

                // Descripción del proyecto
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50) // Limitamos a 50 caracteres para no desbordar la tabla visualmente
                    ->searchable(),

                // Estado: ¿Está activo o no?
                IconColumn::make('is_active') // Cámbialo a 'status' o 'active' según tu base de datos
                    ->label('¿Activo?')
                    ->boolean(), // Muestra un check verde o una X roja

                // 4. Nombre de la empresa dueña
                TextColumn::make('user.name') // Si la relación se llama 'company', cambia 'user.name' por 'company.name'
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),

                // 5. Número de alumnos postulados
                TextColumn::make('applications_count') // Esto cuenta automáticamente la relación
                    ->counts('applications')
                    ->label('Nº Alumnos Postulados')
                    ->sortable(),
            ])
            ->filters([
                // Aquí podrías añadir filtros en el futuro
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
