<?php

namespace App\Filament\Resources\Companies;

use App\Filament\Resources\CompanyResource\RelationManagers\PublishedProjectsRelationManager;
use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\EditCompany;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Filament\Resources\Companies\Pages\ViewCompany;
use App\Filament\Resources\Companies\Schemas\CompanyForm;
use App\Filament\Resources\Companies\Schemas\CompanyInfolist;
use App\Filament\Resources\Companies\Tables\CompaniesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;


class CompanyResource extends Resource
{
    // Vinculamos este recurso al modelo User
    protected static ?string $model = User::class;
    // Icono de la empresa para el panel de navegación
    protected static string|BackedEnum|null $navigationIcon ="heroicon-o-building-office-2";

    // Nombres para mostrar en el panel de navegación
    protected static ?string $navigationLabel = 'Empresas';
    protected static ?string $modelLabel = 'Empresa';
    protected static ?string $pluralModelLabel = 'Empresas';
    protected static ?string $recordTitleAttribute = 'id';

    /**
     * Formulario para crear o editar una empresa. Editamos el nombre y correo del usuario, que se mantienen en la tabla 'users'
      * @param Schema $schema
      * @return Schema
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre de la Empresa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CompanyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),

                // Aquí mostramos el número de proyectos subidos
                Tables\Columns\TextColumn::make('published_projects_count')
                    ->label('Proyectos Subidos')
                    // 'publishedProjects' es el nombre exacto de la relación en tu modelo User.php
                    ->counts('publishedProjects')
                    ->badge() // Le da un diseño de "etiqueta" circular muy visual
                    ->color('success')
                    ->sortable(),
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
            PublishedProjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'view' => ViewCompany::route('/{record}'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }
    /**
     * Sobrescribimos la consulta para mostrar SOLO a las empresas.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'empresa');
    }
}
