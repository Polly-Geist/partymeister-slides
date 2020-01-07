<?php

namespace Partymeister\Slides\Services;

use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Arr;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\ChromeProcess;
use Laravel\Dusk\ElementResolver;
use Motor\Backend\Services\BaseService;
use Motor\Core\Filter\Renderers\SelectRenderer;
use Partymeister\Slides\Helpers\ScreenshotHelper;
use Partymeister\Slides\Models\SlideTemplate;
use Symfony\Component\Process\Process;

/**
 * Class SlideTemplateService
 * @package Partymeister\Slides\Services
 */
class SlideTemplateService extends BaseService
{

    /**
     * @var string
     */
    protected $model = SlideTemplate::class;


    public function filters()
    {
        $this->filter->add(new SelectRenderer('template_for'))
            ->setOptionPrefix(trans('partymeister-slides::backend/slide_templates.template_for'))
            ->setEmptyOption('-- '.trans('partymeister-slides::backend/slide_templates.template_for').' --')
            ->setOptions(trans('partymeister-slides::backend/slide_templates.template_for_types'));
    }


    public function beforeUpdate()
    {
        $this->beforeCreate();
    }


    public function beforeCreate()
    {
        $this->data[ 'definitions' ] = stripslashes($this->request->get('definitions'));
    }


    public function afterCreate()
    {
        $this->generatePreview();
    }


    protected function generatePreview()
    {
        $browser = new ScreenshotHelper();
        $browser->screenshot(config('app.url').route('backend.slide_templates.show', [ $this->record->id ], false).'?preview=true',
            storage_path().'/preview_'.$this->record->id.'.png');
        $browser->screenshot(config('app.url').route('backend.slide_templates.show', [ $this->record->id ], false),
            storage_path().'/final_'.$this->record->id.'.png');


        $this->record->clearMediaCollection('preview');
        $this->record->clearMediaCollection('final');

        if (file_exists(storage_path().'/preview_'.$this->record->id.'.png')) {
            $this->record->addMedia(storage_path().'/preview_'.$this->record->id.'.png')
                ->toMediaCollection('preview', 'media');
        }

        if (file_exists(storage_path().'/final_'.$this->record->id.'.png')) {
            $this->record->addMedia(storage_path().'/final_'.$this->record->id.'.png')
                ->toMediaCollection('final', 'media');
        }

    }


    public function afterUpdate()
    {
        $this->generatePreview();
    }
}
