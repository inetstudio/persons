<?php

namespace InetStudio\Experts\Http\Controllers\Back;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use InetStudio\Experts\Models\ExpertModel;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Experts\Transformers\ExpertTransformer;
use InetStudio\Experts\Http\Requests\Back\SaveExpertRequest;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\DatatablesTrait;
use InetStudio\Meta\Http\Controllers\Back\Traits\MetaManipulationsTrait;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\ImagesManipulationsTrait;

/**
 * Контроллер для управления экспертами.
 *
 * Class ExpertsController
 * @package InetStudio\Experts\Http\Controllers\Back
 */
class ExpertsController extends Controller
{
    use DatatablesTrait;
    use MetaManipulationsTrait;
    use ImagesManipulationsTrait;

    /**
     * Список экспертов.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(): View
    {
        $table = $this->generateTable('experts', 'index');

        return view('admin.module.experts::back.pages.index', compact('table'));
    }

    /**
     * DataTables ServerSide.
     *
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $items = ExpertModel::query();

        return DataTables::of($items)
            ->setTransformer(new ExpertTransformer)
            ->rawColumns(['actions'])
            ->make();
    }

    /**
     * Добавление эксперта.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin.module.experts::back.pages.form', [
            'item' => new ExpertModel(),
        ]);
    }

    /**
     * Создание эксперта.
     *
     * @param SaveExpertRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SaveExpertRequest $request): RedirectResponse
    {
        return $this->save($request);
    }

    /**
     * Редактирование эксперта.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null): View
    {
        if (! is_null($id) && $id > 0 && $item = ExpertModel::find($id)) {
            return view('admin.module.experts::back.pages.form', [
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
    public function update(SaveExpertRequest $request, $id = null): RedirectResponse
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
    private function save($request, $id = null): RedirectResponse
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
        $this->saveImages($item, $request, ['og_image', 'preview', 'content'], 'experts');

        Session::flash('success', 'Эксперт «'.$item->name.'» успешно '.$action);

        return response()->redirectToRoute('back.experts.edit', [
            $item->fresh()->id,
        ]);
    }

    /**
     * Удаление эксперта.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null): JsonResponse
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
    public function getSlug(Request $request): JsonResponse
    {
        $name = $request->get('name');
        $slug = SlugService::createSlug(ExpertModel::class, 'slug', $name);

        return response()->json($slug);
    }

    /**
     * Возвращаем экспертов для поля.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $search = $request->get('q');

        $items = ExpertModel::select(['id', 'name', 'post', 'slug'])->where('name', 'LIKE', '%'.$search.'%')->get();

        if ($request->filled('type') and $request->get('type') == 'autocomplete') {
            $type = get_class(new ExpertModel());

            $data = $items->mapToGroups(function ($item) use ($type) {
                return [
                    'suggestions' => [
                        'value' => $item->name,
                        'data' => [
                            'id' => $item->id,
                            'type' => $type,
                            'name' => $item->name,
                            'post' => $item->post,
                            'path' => parse_url($item->href, PHP_URL_PATH),
                            'href' => $item->href,
                            'preview' => ($item->getFirstMedia('preview')) ? url($item->getFirstMedia('preview')->getUrl('preview_default')) : '',
                        ],
                    ],
                ];
            });
        } else {
            $data = $items->mapToGroups(function ($item) {
                return [
                    'items' => [
                        'id' => $item->id,
                        'name' => $item->name,
                    ],
                ];
            });
        }

        return response()->json($data);
    }
}
