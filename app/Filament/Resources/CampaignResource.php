<?php
namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Campaign Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('slug', Str::slug($state))
                        ),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Campaign::class, 'slug', ignoreRecord: true)
                        ->prefix('campaigns/'),

                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('content')
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'bulletList',
                            'orderedList', 'h2', 'h3', 'link', 'blockquote',
                        ]),

                    Forms\Components\Select::make('category')
                        ->options([
                            'education'    => 'Education',
                            'health'       => 'Health',
                            'environment'  => 'Environment',
                            'food'         => 'Food & Hunger',
                            'shelter'      => 'Shelter',
                            'orphans'      => 'Orphans',
                            'disaster'     => 'Disaster Relief',
                            'other'        => 'Other',
                        ])
                        ->searchable(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'draft'     => 'Draft',
                            'active'    => 'Active',
                            'paused'    => 'Paused',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('draft')
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Fundraising Details')
                ->schema([
                    Forms\Components\TextInput::make('goal_amount')
                        ->required()
                        ->numeric()
                        ->prefix('EGP')
                        ->minValue(1),

                    Forms\Components\TextInput::make('raised_amount')
                        ->numeric()
                        ->prefix('EGP')
                        ->default(0)
                        ->disabled()
                        ->dehydrated(),

                    Forms\Components\Select::make('currency')
                        ->options(['EGP' => 'EGP', 'USD' => 'USD', 'EUR' => 'EUR'])
                        ->default('EGP')
                        ->required(),

                    Forms\Components\DatePicker::make('deadline')
                        ->required()
                        ->minDate(now()->addDay()),
                ])->columns(2),

            Forms\Components\Section::make('Media & SEO')
                ->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->image()
                        ->directory('campaigns')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('meta_title')
                        ->maxLength(60)
                        ->hint('Max 60 characters'),

                    Forms\Components\Textarea::make('meta_description')
                        ->maxLength(160)
                        ->hint('Max 160 characters')
                        ->rows(2),
                ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->square()
                    ->size(48),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'success'   => 'active',
                        'warning'   => 'paused',
                        'primary'   => 'completed',
                        'danger'    => 'cancelled',
                    ]),

                Tables\Columns\TextColumn::make('goal_amount')
                    ->money('EGP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('raised_amount')
                    ->money('EGP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->getStateUsing(fn (Campaign $record) => $record->progress_percentage . '%'),

                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable()
                    ->color(fn (Campaign $record) => $record->is_expired ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'active'    => 'Active',
                        'paused'    => 'Paused',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'education'   => 'Education',
                        'health'      => 'Health',
                        'environment' => 'Environment',
                        'food'        => 'Food & Hunger',
                        'shelter'     => 'Shelter',
                        'orphans'     => 'Orphans',
                        'disaster'    => 'Disaster Relief',
                        'other'       => 'Other',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'view'   => Pages\ViewCampaign::route('/{record}'),
            'edit'   => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }
}