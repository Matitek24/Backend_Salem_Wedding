<?php
namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Banery';
    protected static ?string $slug = 'banery';
    protected static ?string $navigationGroup = 'CMS Zarządzanie';
    
    // Formularz od banerów
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            FileUpload::make('image')
                ->image()
                ->maxSize(8192)
                ->directory('banners')
                ->label('Zdjęcie')
                ->required(),
            Select::make('page')
                ->label('Podstrona')
                ->options([
                    'index' => 'Strona Główna',
                    'onas' => 'O Nas',
                    'kontakt' => 'Kontakt',
                    'oferta' => 'Oferta',
                    'testimonials' => 'Testimonials',
                    'historie' => 'Wasze Historie',
                    'blogMain' => 'Blog',
                    'portfolio' => 'Portfolio',
                    'polecamy' => 'Polecamy',
                    'film' => 'Filmy',
                ])
                ->required(),
            Hidden::make('sort_order')
                ->default(function () {
                    $page = request()->input('data.page');
                    if (!$page) return 1;
                    return Banner::where('page', $page)->count() + 1;
                }),
        ]);
    }
    
    // Tabela z banerami
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('image')
                    ->label('Miniatura')
                    ->formatStateUsing(fn ($state) => "
                        <div class='gallery-item' style='width: 80px; height: 80px; overflow: hidden; border-radius: 8px;'>
                            <img src='/storage/{$state}' alt='Zdjęcie' style='width: 100%; height: 100%; object-fit: cover;'>
                        </div>
                    ")
                    ->html(),
                TextColumn::make('page')
                    ->label('Podstrona')
                    ->sortable(),
                // TextColumn::make('sort_order')
                //     ->label('Kolejność')
                //     ->sortable(),
            ])
            ->defaultSort('page', 'asc')
            ->defaultSort('sort_order', 'asc')
            ->groups([
                Tables\Grouping\Group::make('page')
                    ->label('Podstrona')
                    ->collapsible()
            ])
            ->groupsInDropdownOnDesktop(false) // Display groups always, not in dropdown
            ->defaultGroup('page') // Set page as the default grouping
            ->filters([
                SelectFilter::make('page')
                    ->label('Podstrona')
                    ->options([
                        'index' => 'Strona Główna',
                        'onas' => 'O Nas',
                        'kontakt' => 'Kontakt',
                        'oferta' => 'Oferta',
                        'testimonials' => 'Testimonials',
                        'historie' => 'Wasze Historie',
                        'blogMain' => 'Blog',
                        'portfolio' => 'Portfolio',
                        'polecamy' => 'Polecamy',
                        'filmy' => 'Filmy',
                    ])
            ])
            ->reorderable('sort_order')

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function ($record) {
                        // Reorder remaining banners within the same page group
                        Banner::where('page', $record->page)
                            ->where('sort_order', '>', $record->sort_order)
                            ->get()
                            ->each(function ($banner) {
                                $banner->sort_order = $banner->sort_order - 1;
                                $banner->save();
                            });
                    }),
                Tables\Actions\Action::make('optimize')
                    ->icon('heroicon-s-pencil')
                    ->label('webp')
                    ->visible(function ($record) {
                        return !preg_match('/\.webp$/i', $record->image);
                    })
                    ->action(function ($record) {
                        dispatch(new \App\Jobs\OptimizeBannerJob($record));
                        \Filament\Notifications\Notification::make()
                            ->title('Optymalizacja została uruchomiona.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->after(function () {
                        // After bulk deleting, reorder all banners grouped by page
                        $pages = Banner::select('page')->distinct()->get()->pluck('page');
                        
                        foreach ($pages as $page) {
                            $banners = Banner::where('page', $page)
                                ->orderBy('sort_order')
                                ->get();
                                
                            foreach ($banners as $index => $banner) {
                                $banner->sort_order = $index + 1;
                                $banner->save();
                            }
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}