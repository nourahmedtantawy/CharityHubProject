<?php
namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;
    protected static ?string $navigationIcon  = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Donation Details')->schema([
                Forms\Components\Select::make('campaign_id')
                    ->relationship('campaign', 'title')
                    ->searchable()->required(),
                Forms\Components\TextInput::make('donor_name')->required(),
                Forms\Components\TextInput::make('donor_email')->email()->required(),
                Forms\Components\TextInput::make('donor_phone'),
                Forms\Components\TextInput::make('amount')->numeric()->prefix('EGP')->required(),
                Forms\Components\Select::make('type')
                    ->options(['one_time' => 'One Time', 'recurring' => 'Recurring']),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'completed' => 'Completed',
                        'failed'    => 'Failed',
                        'refunded'  => 'Refunded',
                    ]),
                Forms\Components\Select::make('gateway')
                    ->options(['stripe' => 'Stripe', 'paymob' => 'PayMob']),
                Forms\Components\Toggle::make('is_anonymous')->label('Anonymous'),
                Forms\Components\Textarea::make('message'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('donor_name')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('donor_email')
                    ->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('campaign.title')
                    ->searchable()->limit(30),
                Tables\Columns\TextColumn::make('amount')
                    ->money('EGP')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger'  => 'failed',
                        'gray'    => 'refunded',
                    ]),
                Tables\Columns\BadgeColumn::make('gateway')
                    ->colors([
                        'primary' => 'stripe',
                        'success' => 'paymob',
                    ]),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_anonymous')
                    ->boolean()->label('Anon'),
                Tables\Columns\TextColumn::make('donated_at')
                    ->dateTime()->sortable(),
            ])
            ->defaultSort('donated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'completed' => 'Completed',
                        'failed'    => 'Failed',
                        'refunded'  => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('gateway')
                    ->options(['stripe' => 'Stripe', 'paymob' => 'PayMob']),
                Tables\Filters\SelectFilter::make('type')
                    ->options(['one_time' => 'One Time', 'recurring' => 'Recurring']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_completed')
                    ->label('Mark Completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Donation $r) => $r->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn (Donation $r) => $r->update([
                        'status'     => 'completed',
                        'donated_at' => now(),
                    ])),
                Tables\Actions\Action::make('mark_refunded')
                    ->label('Refund')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('danger')
                    ->visible(fn (Donation $r) => $r->status === 'completed')
                    ->requiresConfirmation()
                    ->action(fn (Donation $r) => $r->update(['status' => 'refunded'])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'view'   => Pages\ViewDonation::route('/{record}'),
            'edit'   => Pages\EditDonation::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }
}