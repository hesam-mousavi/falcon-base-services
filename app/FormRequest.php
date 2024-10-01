<?php

namespace FalconBaseServices;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use FalconBaseServices\Helper\Response;
use FalconBaseServices\Exception\AuthorizationException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use FalconBaseServices\Services\CurrentUser;

class FormRequest
{
    protected array $rules = [];
    protected array $messages = [];
    protected $validated;
    protected array $request;
    private SymfonyRequest $raw_request;
    private array $req;
    private array $files;
    private array $queries;

    public function __construct()
    {
        $this->raw_request = SymfonyRequest::createFromGlobals();

        $this->req = ($this->prepareRequest($this->raw_request->request));
        $this->files = $this->prepareFiles($this->raw_request->files);
        $this->queries = $this->prepareQuery($this->raw_request->query);

        $this->request = array_merge($this->req, $this->files, $this->queries);

        try {
            $this->validate();
        } catch (AuthorizationException $e) {
            LOGGER->alert(
                'unAuthorization request!',
                [
                    'data' => [
                        'request' => $this->request,
                        'user' => CurrentUser::summaryProfile(),
                        'message' => $e->getMessage(),
                    ],
                ],
            );

            Response::unauthorized();
        } catch (ValidationException $e) {
            LOGGER->warning(
                'validation-exception',
                [
                    'data' => [
                        'request' => $this->request,
                        'user' => CurrentUser::summaryProfile(),
                        'message' => $e->getMessage(),
                    ],
                ],
            );

            Response::json(data: ['errors' => $e->errors()], status: $e->status);
        }
    }

    private function prepareRequest($requests): array
    {
        $output = [];
        foreach ($requests as $key => $value) {
            if ($key != 'password' && $key != 'password_confirmation') {
                $arr = [];
                if (is_array($value)) {
                    foreach ($value as $v) {
                        $arr[] = trim(sanitize_textarea_field($v));
                    }
                } else {
                    $value = trim(sanitize_textarea_field($value));
                }
            }

            $output[$key] = count($arr) ? $arr : $value;
        }

        return $output;
    }

    private function prepareFiles($files): array
    {
        $output = [];

        foreach ($files as $key => $file_data) {
            $output['files'][$key] = [
                'name' => sanitize_text_field($file_data->getClientOriginalName()),
                'type' => $file_data->getType(),
                'size' => $file_data->getSize(),
                'extension' => $file_data->getClientOriginalExtension(),
                'path' => $file_data->getPathname(),
                'mime_type' => $file_data->getMimeType(),
            ];
        }

        return $output;
    }

    private function prepareQuery($requests): array
    {
        $output = [];
        foreach ($requests as $key => $value) {
            $value = trim($value);
            $output[$key] = sanitize_textarea_field($value);
        }

        return $output;
    }

    public function validate(array $rules = null): void
    {
        if (!$this->passesAuthorization()) {
            throw new AuthorizationException();
        }

        if (is_null($rules)) {
            $rules = !empty($this->rules()) ? $this->rules() : $this->rules;
        }

        $messages = !empty($this->messages()) ? $this->messages()
            : $this->messages;

        if (!empty($rules)) {
            $filesystem = new Filesystem();
            $loader = new FileLoader($filesystem, plugin_dir_path(__FILE__).'../lang');

            $loader->addNamespace('lang', plugin_dir_path(__FILE__).'/../lang');
            $loader->load(get_locale(), 'validation', 'lang');
            $translator = new Translator($loader, get_locale());

            $this->validated = (new Factory($translator))->validate(
                $this->request,
                $rules,
                $messages,
            );
        }
    }

    protected function passesAuthorization(): bool
    {
        if (method_exists($this, 'authorize')) {
            return $this->authorize();
        }

        return true;
    }

    public function rules(): array
    {
        $rules = [];
        foreach ($this->request as $item => $value) {
            $rules[$item] = 'nullable';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [];
    }

    public function __get(string $str)
    {
        return ($this->validated()[$str] ?? null);
    }

    public function validated()
    {
        return $this->validated;
    }

}
