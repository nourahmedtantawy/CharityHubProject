<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ImpactReportResource\Pages;
use App\Models\ImpactReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ImpactReportResource extends Resource
{
    protected static ?string $model = ImpactReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Impact';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Report Details')->schema([
                Forms\Components\Select::make('campaign_id')
                    ->relationship('campaign', 'title')->required()->searchable(),
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\Textarea::make('summary')->required()->rows(3),
                Forms\Components\RichEditor::make('content')->columnSpanFull(),
                Forms\Components\TextInput::make('beneficiaries_count')->numeric()->default(0),
                Forms\Components\DatePicker::make('report_date')->required(),
                Forms\Components\Toggle::make('is_published')->label('Published'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('campaign.title')->searchable(),
            Tables\Columns\TextColumn::make('title')->searchable(),
            Tables\Columns\TextColumn::make('beneficiaries_count')->label('Beneficiaries'),
            Tables\Columns\IconColumn::make('is_published')->boolean(),
            Tables\Columns\TextColumn::make('report_date')->date()->sortable(),
        ])
        ->actions([Tables\Actions\EditAction::make()])
        ->defaultSort('report_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListImpactReports::route('/'),
            'create' => Pages\CreateImpactReport::route('/create'),
            'edit'   => Pages\EditImpactReport::route('/{record}/edit'),
        ];
    }
}