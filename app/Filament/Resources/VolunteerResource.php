<?php
namespace App\Filament\Resources;

use App\Filament\Resources\VolunteerResource\Pages;
use App\Models\Volunteer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Volunteer Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Volunteer Info')->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()->required(),
                Forms\Components\TextInput::make('phone'),
                Forms\Components\Textarea::make('address'),
                Forms\Components\DatePicker::make('date_of_birth'),
                Forms\Components\TagsInput::make('skills')
                    ->placeholder('Add a skill'),
                Forms\Components\Textarea::make('bio'),
                Forms\Components\Select::make('status')
                    ->options(['pending' => 'Pending', 'approved' => 'Approved', 'suspended' => 'Suspended'])
                    ->default('pending')->required(),
                Forms\Components\TextInput::make('total_hours')
                    ->numeric()->default(0)->disabled()->dehydrated(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('user.email')->searchable(),
            Tables\Columns\BadgeColumn::make('status')->colors([
                'warning' => 'pending',
                'success' => 'approved',
                'danger'  => 'suspended',
            ]),
            Tables\Columns\TextColumn::make('total_hours')->suffix(' hrs')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->date()->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options(['pending' => 'Pending', 'approved' => 'Approved', 'suspended' => 'Suspended']),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn (Volunteer $record) => $record->status === 'pending')
                ->action(fn (Volunteer $record) => $record->update(['status' => 'approved'])),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVolunteers::route('/'),
            'create' => Pages\CreateVolunteer::route('/create'),
            'edit'   => Pages\EditVolunteer::route('/{record}/edit'),
        ];
    }
}