<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecommendationResource\Pages;
use App\Models\Recommendation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class RecommendationResource extends Resource
{
    protected static ?string $model = Recommendation::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    
    protected static ?string $navigationGroup = 'CMS Zarządzanie';

    protected static ?string $navigationLabel = 'Rekomendacje';
    protected static ?string $modelLabel = 'Polecamy';
    protected static ?string $pluralModelLabel = 'Polecamy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category')
                    ->options(function() {
                        // Get existing categories or allow creating new ones
                        $categories = Recommendation::distinct()->pluck('category', 'category')->toArray();
                        return $categories ?: [];
                    })
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('value')
                            ->required()
                            ->label('Category name'),
                    ])
                    ->createOptionUsing(function ($data) {
                        return $data['value'];
                    }),
                Forms\Components\FileUpload::make('logo_image')
                    ->label('Logo Image')
                    ->image()
                    ->directory('recommendations/logos')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('100'),
                Forms\Components\FileUpload::make('left_image')
                    ->label('Left Image')
                    ->image()
                    ->directory('recommendations/images')
                    ->visibility('public')
                    ->required(),
                Forms\Components\TextInput::make('primary_button_text')
                    ->label('Primary Button Text (Title)')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Textarea::make('main_offer_text')
                    ->label('Main Offer Text')
                    ->required()
                    ->rows(4)
                    ->maxLength(500),
                Forms\Components\TextInput::make('cta_button_text')
                    ->label('CTA Button Text (Phone)')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Section::make('Social Media Links')
                    ->schema([
                        Forms\Components\TextInput::make('website_url')
                            ->label('Website URL')
                            ->prefix('https://')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->prefix('https://instagram.com/')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->prefix('https://youtube.com/')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->prefix('https://facebook.com/')
                            ->maxLength(255),
                    ])->columns(2),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('primary_button_text')
                    ->label('Title')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Recommendation $record) {
                        // Logic to delete files is already in model boot method
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecommendations::route('/'),
            'create' => Pages\CreateRecommendation::route('/create'),
            'edit' => Pages\EditRecommendation::route('/{record}/edit'),
        ];
    }
}