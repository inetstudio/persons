<?php

namespace InetStudio\Experts\Http\Requests\Back;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use InetStudio\Uploads\Validation\Rules\CropSize;
use InetStudio\Experts\Contracts\Http\Requests\Back\SaveExpertRequestContract;

class SaveExpertRequest extends FormRequest implements SaveExpertRequestContract
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Сообщения об ошибках.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'meta.title.max' => 'Поле «Title» не должно превышать 255 символов',
            'meta.description.max' => 'Поле «Description» не должно превышать 255 символов',
            'meta.keywords.max' => 'Поле «Keywords» не должно превышать 255 символов',

            'meta.og:title.max' => 'Поле «og:itle» не должно превышать 255 символов',
            'meta.og:description.max' => 'Поле «og:description» не должно превышать 255 символов',
            
            'og_image.crop.default.json' => 'Область отображения должна быть представлена в виде JSON',

            'name.required' => 'Поле «ФИО» обязательно для заполнения',
            'name.max' => 'Поле «ФИО» не должно превышать 255 символов',

            'slug.required' => 'Поле «URL» обязательно для заполнения',
            'slug.alpha_dash' => 'Поле «URL» может содержать только латинские символы, цифры, дефисы и подчеркивания',
            'slug.max' => 'Поле «URL» не должно превышать 255 символов',
            'slug.unique' => 'Такое значение поля «URL» уже существует',

            'preview.crop.default.required' => 'Необходимо выбрать область отображения',
            'preview.crop.default.json' => 'Область отображения должна быть представлена в виде JSON',
            'preview.description.max' => 'Поле «Описание» не должно превышать 255 символов',
            'preview.copyright.max' => 'Поле «Copyright» не должно превышать 255 символов',
            'preview.alt.required' => 'Поле «Alt» обязательно для заполнения',
            'preview.alt.max' => 'Поле «Alt» не должно превышать 255 символов',
        ];
    }

    /**
     * Правила проверки запроса.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request): array
    {
        return [
            'meta.title' => 'max:255',
            'meta.description' => 'max:255',
            'meta.keywords' => 'max:255',
            'meta.og:title' => 'max:255',
            'meta.og:description' => 'max:255',

            'og_image.crop.default' => [
                'nullable', 'json',
                new CropSize(968,475,'min', ''),
            ],

            'name' => 'required|max:255',
            'slug' => 'required|alpha_dash|max:255|unique:experts,slug,'.$request->get('expert_id'),

            'preview.crop.default' => [
                'nullable', 'json',
                new CropSize(86,86,'min', ''),
            ],
            'preview.description' => 'max:255',
            'preview.copyright' => 'max:255',
            'preview.alt' => 'required|max:255',
        ];
    }
}
