<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Models\Product;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\TD;

class ProductEditScreen extends Screen
{
    public $product, $positions, $categories, $seo;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Product $product): array
    {
        return [
            'product' => $product,
            'seo' => $product->seo->first(),
            'positions'=> $product->productPositions,
            'categories' => $product->categories,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->product->exists ? 'Edit category '.$this->product->name : 'Creating a new category';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Create category')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->product->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->product->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->product->exists)
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::columns([
                Layout::rows([
                    Group::make([
                        Input::make('product.name')
                            ->title('Name')
                            ->placeholder('Enter product name')
        //                    ->help('Specify a short descriptive title for this post.')
                            ->required(),

                        Input::make('product.slug')
                            ->title('Slug')
                            ->placeholder('Brief description for preview'),
                    ]),

                    Input::make('product.price')
//                        ->type('number')
                        ->title('Price')
                        ->value(0)
                        ->mask([
                            'alias' => 'currency',
                            'groupSeparator' => ' ',

//                            'autoUnmask' => true,
                            'removeMaskOnSubmit' =>true,
                            'digitsOptional' => true,
                        ]),

                    Relation::make('categories')
                        ->fromModel(Category::class, 'name')
                        ->multiple()
                        ->title('Choose your ideas'),

                    Upload::make('images')
                        ->title('Upload files')
                        ->groups('photo'),

                    CheckBox::make('product.is_enabled')
                        ->placeholder('Is Enabled')
                        ->sendTrueOrFalse(),

                    /*Matrix::make('positions')
                        ->title('Product Positions')
                        ->columns([
                            'name',
                            'price',
                            'sort_order'
                        ])
                        ->fields([
                            'price'   => Input::make()->type('number'),
                            'sort_order'   => Input::make()->type('number'),
                            'name' => Input::make()
                        ]),*/
                ]),


                Layout::tabs([
                    'Description' => Layout::rows([
                        Input::make('seo.title')
                            ->title('Title')
                            ->placeholder('Brief description for preview'),

                        TextArea::make('seo.description')
                            ->title('Description')
                            ->placeholder('Brief description for preview'),
                    ]),
                    'Meta Tags' => Layout::rows([
                        Input::make('seo.meta-title')
                            ->title('Meta Title')
                            ->placeholder('Brief description for preview'),

                        TextArea::make('seo.meta-keywords')
                            ->title('Meta Keywords')
                            ->placeholder('Brief description for preview'),

                        TextArea::make('seo.meta-description')
                            ->title('Meta Description')
                            ->placeholder('Brief description for preview'),
                    ]),
                    'Open Graph' => Layout::rows([
                        Input::make('seo.og-type')
                            ->title('Object Type')
                            ->placeholder('The type of your object'),

                        Input::make('seo.og-image')
                            ->title('URL image')
                            ->placeholder('Image URL representing your object within the graph'),

                        Input::make('seo.og-url')
                            ->title('URL')
                            ->placeholder('Represented by the canonical URL of your object'),

                        Input::make('seo.og-site_name')
                            ->title('Site Description')
                            ->placeholder('The name which should be displayed for the overall site'),

                        Input::make('seo.og-locale')
                            ->title('Language Standard')
                            ->placeholder('The locale these tags are marked up in'),
                    ])
                ]),

            ]),
            Layout::table('positions', [
                TD::make('name'),
                TD::make('price'),
            ]),
        ];
    }
    /**
     * @param Product    $product
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
//        dd($this->product->productPositions(), $this->product->productPositions);
        $this->product->fill($request->get('product'))->save();

        if ($request->has('categories')) {
            $this->product->categories()->sync($request->get('categories'));
        } else {
            $this->product->categories()->detach();
        }

        /*$this->product->productPositions()->delete();

        if ($request->has('positions')) {
            // Массовое создание новых позиций товара
            $this->product->productPositions()->createMany($request->input('positions'));
        }*/

        foreach ($this->product->seo as $seo) {
            $seo->fill($request->get('seo'))->save();
        }

// Создать новую запись Seo, если ее еще нет
        if ($this->product->seo->isEmpty()) {
            $this->product->seo()->create($request->get('seo'));
        }

        Alert::info('You have successfully created a category.');

        return redirect()->route('platform.product.list');
    }

    public function remove(Product $product)
    {


        $product->seo()->delete();

        $product->delete();

        Alert::info('You have successfully deleted the category.');

        return redirect()->route('platform.product.list');
    }
}
