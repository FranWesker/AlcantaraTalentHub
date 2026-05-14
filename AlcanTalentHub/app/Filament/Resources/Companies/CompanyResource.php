<?php

namespace App\Filament\Resources\Companies;

use App\Filament\Resources\Companies\RelationManagers\PublishedProjectsRelationManager;
use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\EditCompany;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Filament\Resources\Companies\Pages\ViewCompany;
use App\Filament\Resources\Companies\Schemas\CompanyInfolist;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Hash;

class CompanyResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon ="heroicon-o-building-office-2";

    protected static ?string $navigationLabel = 'Empresas';
    protected static ?string $modelLabel = 'Empresa';
    protected static ?string $pluralModelLabel = 'Empresas';
    protected static ?string $recordTitleAttribute = 'id';

    /**
     * Formulario para crear o editar una empresa.
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

                Forms\Components\TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->revealable()
                    // Encripta la contraseña antes de guardarla
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    // Solo envía el campo al modelo si tiene contenido (evita borrar la actual si se deja vacío)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    // Obligatorio solo al crear una empresa nueva
                    ->required(fn (string $context): bool => $context === 'create')
                    ->placeholder(fn (string $context): string =>
                        $context === 'edit' ? 'Dejar en blanco para mantener la actual' : ''
                    ),
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

                Tables\Columns\TextColumn::make('published_projects_count')
                    ->label('Proyectos Subidos')
                    ->counts('publishedProjects')
                    ->badge()
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'empresa');
    }
}
