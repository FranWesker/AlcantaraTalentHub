<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\CreateStudent;
use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Pages\ViewStudent;
use App\Filament\Resources\Students\Schemas\StudentInfolist;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Illuminate\Support\Facades\Hash;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $recordTitleAttribute = 'id';

    // Nombres para mostrar en el panel de navegación
    protected static ?string $navigationLabel = 'Estudiantes';
    protected static ?string $modelLabel = 'Estudiante';
    protected static ?string $pluralModelLabel = 'Estudiantes';

    /**
     * Formulario donde se edita el usuario estudiante
     * @param Schema $schema
     * @return Schema
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
        ->schema([
            // Editamos el nombre y correo del usuario, que se mantienen en la tabla 'users'
            Section::make('Información de la Cuenta')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre Completo')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('Correo Electrónico')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),
                ])->columns(2),

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
            // Gestion de Links y CV del estudiante
            Section::make('Perfil del Estudiante')
                ->description('Gestiona los enlaces sociales y el currículum')
                ->schema([
                    Group::make()
                        ->relationship('profile')
                        ->schema([
                            Forms\Components\TextInput::make('github_url')
                                ->label('URL de GitHub')
                                ->url()
                                ->placeholder('https://github.com/usuario'),

                            Forms\Components\TextInput::make('linkedin_url')
                                ->label('URL de LinkedIn')
                                ->url()
                                ->placeholder('https://linkedin.com/in/usuario'),

                            Forms\Components\FileUpload::make('cv_pdf_path')
                                ->label('Currículum Vítae (PDF)')
                                ->disk('public')
                                ->directory('cvs')
                                ->visibility('public')
                                ->acceptedFileTypes(['application/pdf'])
                                ->downloadable()
                                ->openable()
                                ->previewable()
                                ->deletable()
                                ->maxSize(2048)
                                ->columnSpanFull(),
                        ])->columns(2)->columnSpanFull(),
                ]),

            // Gestión de Habilidades
            Section::make('Habilidades Técnicas')
                ->schema([
                    Forms\Components\Select::make('skills')
                        ->label('Habilidades')
                        ->relationship('skills', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required(),
                        ]),
                ]),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),

                // Extraemos datos de la relación 'profile'
                Tables\Columns\TextColumn::make('profile.github_url')
                    ->label('GitHub')
                    ->formatStateUsing(fn ($state) => $state ? 'Ver Perfil' : 'No añadido')
                    ->url(fn ($record) => $record->profile?->github_url)
                    ->openUrlInNewTab()
                    ->color('info'),

                Tables\Columns\TextColumn::make('profile.linkedin_url')
                    ->label('LinkedIn')
                    ->formatStateUsing(fn ($state) => $state ? 'Ver Perfil' : 'No añadido')
                    ->url(fn ($record) => $record->profile?->linkedin_url)
                    ->openUrlInNewTab()
                    ->color('info'),

                // Mostramos el CV como un enlace descargable
                Tables\Columns\TextColumn::make('profile.cv_pdf_path')
                    ->label('CV')
                    ->formatStateUsing(fn ($state) => $state ? 'Descargar CV' : 'Sin CV')
                    // Asumimos que el CV está guardado en el disco 'public' de storage
                    ->url(fn ($record) => $record->profile?->cv_pdf_path ? asset('storage/' . $record->profile->cv_pdf_path) : null)
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->icon('heroicon-o-document-arrow-down'),

                // Extraemos las habilidades a través de la relación 'skills'
                Tables\Columns\TextColumn::make('skills.name')
                    ->label('Habilidades')
                    ->badge() // Las muestra como pequeñas etiquetas
                    ->separator(',')
                    ->searchable(),
            ])
            ->filters([
                // Aquí puedes agregar filtros futuros
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
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'view' => ViewStudent::route('/{record}'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }

    /**
     * Sobrescribimos la consulta para mostrar SOLO a los estudiantes.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'estudiante');
    }
}
