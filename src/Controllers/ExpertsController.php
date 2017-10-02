<?php

namespace InetStudio\Experts\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use InetStudio\Experts\Models\ExpertModel;
use InetStudio\Experts\Requests\SaveExpertRequest;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Experts\Transformers\ExpertTransformer;

/**
 * Контроллер для управления экспертами.
 *
 * Class ContestByTagStatusesController
 */
class ExpertsController extends Controller
{
    /**
     * Список экспертов.
     *
     * @param Datatables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Datatables $dataTable)
    {
        $table = $dataTable->getHtmlBuilder();

        $table->columns($this->getColumns());
        $table->ajax($this->getAjaxOptions());
        $table->parameters($this->getTableParameters());

        return view('admin.module.experts::pages.index', compact('table'));
    }

    /**
     * Свойства колонок datatables.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            ['data' => 'name', 'name' => 'name', 'title' => 'Имя'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Дата создания'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Дата обновления'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
        ];
    }

    /**
     * Свойства ajax datatables.
     *
     * @return array
     */
    private function getAjaxOptions()
    {
        return [
            'url' => route('back.experts.data'),
            'type' => 'POST',
            'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
        ];
    }

    /**
     * Свойства datatables.
     *
     * @return array
     */
    private function getTableParameters()
    {
        return [
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => [
                'url' => asset('admin/js/plugins/datatables/locales/russian.json'),
            ],
        ];
    }

    /**
     * Datatables serverside.
     *
     * @return mixed
     */
    public function data()
    {
        $items = ExpertModel::query();

        return Datatables::of($items)
            ->setTransformer(new ExpertTransformer)
            ->escapeColumns(['actions'])
            ->make();
    }

    /**
     * Добавление эксперта.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.module.experts::pages.form', [
            'item' => new ExpertModel(),
        ]);
    }

    /**
     * Создание эксперта.
     *
     * @param SaveExpertRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SaveExpertRequest $request)
    {
        return $this->save($request);
    }

    /**
     * Редактирование эксперта.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null)
    {
        if (! is_null($id) && $id > 0 && $item = ExpertModel::find($id)) {

            return view('admin.module.experts::pages.form', [
                'item' => $item,
            ]);
        } else {
            abort(404);
        }
    }

    /**
     * Обновление эксперта.
     *
     * @param SaveExpertRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SaveExpertRequest $request, $id = null)
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение эксперта.
     *
     * @param SaveExpertRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function save($request, $id = null)
    {
        if (! is_null($id) && $id > 0 && $item = ExpertModel::find($id)) {
            $action = 'отредактирован';
        } else {
            $action = 'создан';
            $item = new ExpertModel();
        }

        $item->name = strip_tags($request->get('name'));
        $item->slug = strip_tags($request->get('slug'));
        $item->post = strip_tags($request->input('post.text'));
        $item->description = strip_tags($request->input('description.text'));
        $item->content = $request->input('content.text');
        $item->save();

        $this->saveMeta($item, $request);
        $this->saveImages($item, $request, ['og_image', 'preview', 'content']);

        Session::flash('success', 'Эксперт «'.$item->name.'» успешно '.$action);

        return redirect()->to(route('back.experts.edit', $item->fresh()->id));
    }

    /**
     * Сохраняем мета теги.
     *
     * @param ExpertModel $item
     * @param SaveExpertRequest $request
     */
    private function saveMeta($item, $request)
    {
        if ($request->has('meta')) {
            foreach ($request->get('meta') as $key => $value) {
                $item->updateMeta($key, $value);
            }

            \Event::fire('inetstudio.seo.cache.clear', $item);
        }
    }

    /**
     * Сохраняем изображения.
     *
     * @param ExpertModel $item
     * @param SaveExpertRequest $request
     * @param array $images
     */
    private function saveImages($item, $request, $images)
    {
        foreach ($images as $name) {
            $properties = $request->get($name);

            \Event::fire('inetstudio.images.cache.clear', $name.'_'.md5(get_class($item).$item->id));

            if (isset($properties['images'])) {
                $item->clearMediaCollectionExcept($name, $properties['images']);

                foreach ($properties['images'] as $image) {
                    if ($image['id']) {
                        $media = $item->media->find($image['id']);
                        $media->custom_properties = $image['properties'];
                        $media->save();
                    } else {
                        $filename = $image['filename'];

                        $file = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix().$image['tempname'];

                        $media = $item->addMedia($file)
                            ->withCustomProperties($image['properties'])
                            ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                            ->usingFileName($image['tempname'])
                            ->toMediaCollection($name, 'experts');
                    }

                    $item->update([
                        $name => str_replace($image['src'], $media->getFullUrl('content_front'), $item[$name]),
                    ]);
                }
            } else {
                $manipulations = [];

                if (isset($properties['crop']) and config('experts.images.conversions')) {
                    foreach ($properties['crop'] as $key => $cropJSON) {
                        $cropData = json_decode($cropJSON, true);

                        foreach (config('experts.images.conversions.'.$name.'.'.$key) as $conversion) {

                            \Event::fire('inetstudio.images.cache.clear', $conversion['name'].'_'.md5(get_class($item).$item->id));

                            $manipulations[$conversion['name']] = [
                                'manualCrop' => implode(',', [
                                    round($cropData['width']),
                                    round($cropData['height']),
                                    round($cropData['x']),
                                    round($cropData['y']),
                                ]),
                            ];
                        }
                    }
                }

                if (isset($properties['tempname']) && isset($properties['filename'])) {
                    $image = $properties['tempname'];
                    $filename = $properties['filename'];

                    $item->clearMediaCollection($name);

                    array_forget($properties, ['tempname', 'temppath', 'filename']);
                    $properties = array_filter($properties);

                    $file = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix().$image;

                    $media = $item->addMedia($file)
                        ->withCustomProperties($properties)
                        ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                        ->usingFileName($image)
                        ->toMediaCollection($name, 'experts');

                    $media->manipulations = $manipulations;
                    $media->save();
                } else {
                    $properties = array_filter($properties);

                    $media = $item->getFirstMedia($name);

                    if ($media) {
                        $media->custom_properties = $properties;
                        $media->manipulations = $manipulations;
                        $media->save();
                    }
                }
            }
        }
    }

    /**
     * Удаление эксперта.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null)
    {
        if (! is_null($id) && $id > 0 && $item = ExpertModel::find($id)) {
            $item->delete();

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    /**
     * Получаем slug для модели по строке.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSlug(Request $request)
    {
        $name = $request->get('name');
        $slug = SlugService::createSlug(ExpertModel::class, 'slug', $name);

        return response()->json($slug);
    }

    /**
     * Возвращаем ингредиенты для поля.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions(Request $request)
    {
        if ($request->has('type') and $request->get('type') == 'autocomplete') {
            $search = $request->get('query');
            $data['suggestions'] = [];

            $experts = ExpertModel::where('name', 'LIKE', '%'.$search.'%')->get();

            foreach ($experts as $expert) {
                $data['suggestions'][] = [
                    'value' => $expert->name,
                    'data' => [
                        'id' => $expert->id,
                        'name' => $expert->name,
                        'href' => url($expert->href),
                        'preview' => ($expert->getFirstMedia('preview')) ? url($expert->getFirstMedia('preview')->getUrl('preview_default')) : '',
                    ]
                ];
            }
        } else {
            $search = $request->get('q');
            $data = [];

            $data['items'] = ExpertModel::select(['id', 'name'])->where('name', 'LIKE', '%'.$search.'%')->get()->toArray();
        }

        return response()->json($data);
    }
}
