<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\TextArea;

class CategoryEditScreen extends Screen
{
    public $category;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Category $category): array
    {
        return [
            'category' => $category,
            'seo' => $category->seo->first()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->category->exists ? 'Edit category '.$this->category->name : 'Creating a new category';
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
                ->canSee(!$this->category->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->category->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->category->exists),

/*          Button::make('Cancel')
                ->icon('x-lg')
                ->method('cancel'),
*/
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
                    Input::make('category.name')
                        ->title('Name')
                        ->placeholder('Attractive but mysterious title')
    //                    ->help('Specify a short descriptive title for this post.')
                        ->required(),

                    Input::make('category.slug')
                        ->title('Slug')
                        ->placeholder('Brief description for preview'),

                    Group::make([
                        Select::make('category.category_id')
                            ->fromQuery(Category::where('id','!=', $this->category->id), 'name')
                            ->empty('Не выбрано')
                            ->title('Parent category'),

                        Input::make('category.sort_order')
                            ->type('number')
                            ->title('Sort Order')
                            ->value(0),
                    ]),

                    Group::make([
                        CheckBox::make('category.menu')
                            ->placeholder('Display in menu')
                            ->sendTrueOrFalse(),

                        CheckBox::make('category.is_enabled')
                            ->placeholder('Is Enabled')
                            ->sendTrueOrFalse(),
                    ])
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
            ])
        ];
    }
    /**
     * @param Category    $category
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
//        dd($request->get('category'));
        $this->category->fill($request->get('category'))->save();

        foreach ($this->category->seo as $seo) {
            $seo->fill($request->get('seo'))->save();
        }

// Создать новую запись Seo, если ее еще нет
        if ($this->category->seo->isEmpty()) {
            $this->category->seo()->create($request->get('seo'));
        }

        Alert::info('You have successfully created a category.');

        return redirect()->route('platform.category.list');
    }

    public function remove(Category $category)
    {

        if (!$category->childrenCategories->isEmpty()){
            foreach ($category->childrenCategories as $children){
                $children->delete();
            }
        }

        foreach ($this->category->seo as $seo) {
            $seo->delete();
        }
        $category->delete();

        Alert::info('You have successfully deleted the category.');

        return redirect()->route('platform.category.list');
    }
    /*
    public function cancel(Category $category)
    {

        return redirect()->route('platform.category.list');
    }
    */
}
