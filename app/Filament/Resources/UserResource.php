<?php
namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model           = User::class;
    protected static ?string $navigationIcon  = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Account Details')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('role')
                    ->options([
                        'admin'     => 'Admin',
                        'donor'     => 'Donor',
                        'volunteer' => 'Volunteer',
                    ])
                    ->required()->default('donor'),
                Forms\Components\TextInput::make('phone'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($s) => Hash::make($s))
                    ->dehydrated(fn ($s) => filled($s))
                    ->required(fn (string $context) => $context === 'create')
                    ->label(fn (string $context) => $context === 'edit' ? 'New Password (leave blank to keep)' : 'Password'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'danger'  => 'admin',
                        'primary' => 'donor',
                        'success' => 'volunteer',
                    ]),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('donations_count')
                    ->label('Donations')
                    ->counts('donations')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin'     => 'Admin',
                        'donor'     => 'Donor',
                        'volunteer' => 'Volunteer',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('make_admin')
                    ->label('Make Admin')
                    ->icon('heroicon-o-shield-check')
                    ->color('danger')
                    ->visible(fn (User $r) => $r->role !== 'admin')
                    ->requiresConfirmation()
                    ->action(fn (User $r) => $r->update(['role' => 'admin'])),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $r) => !$r->isAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view'   => Pages\ViewUser::route('/{record}'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}