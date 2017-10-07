<?php

namespace InetStudio\Experts\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use InetStudio\Experts\Models\ExpertModel;
use InetStudio\AdminPanel\Traits\DatatablesTrait;
use InetStudio\Experts\Requests\SaveExpertRequest;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Experts\Transformers\ExpertTransformer;
use InetStudio\AdminPanel\Traits\MetaManipulationsTrait;
use InetStudio\AdminPanel\Traits\ImagesManipulationsTrait;

/**
 * Контроллер для управления экспертами.
 *
 * Class ContestByTagStatusesController
 */
class ExpertsController extends Controller
{
    use DatatablesTrait;
    use MetaManipulationsTrait;
    use ImagesManipulationsTrait;

    /**
     * Список экспертов.
     *
     * @param DataTables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(DataTables $dataTable)
    {
        $table = $this->generateTable($dataTable, 'experts', 'index');

        return view('admin.module.experts::pages.index', compact('table'));
    }

    /**
     * Datatables serverside.
     *
     * @return mixed
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
        $this->saveImages($item, $request, ['og_image', 'preview', 'content'], 'experts');

        Session::flash('success', 'Эксперт «'.$item->name.'» успешно '.$action);

        return redirect()->to(route('back.experts.edit', $item->fresh()->id));
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
        $data = [];

        if ($request->filled('type') and $request->get('type') == 'autocomplete') {
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

            $data['items'] = ExpertModel::select(['id', 'name'])->where('name', 'LIKE', '%'.$search.'%')->get()->toArray();
        }

        return response()->json($data);
    }
}
