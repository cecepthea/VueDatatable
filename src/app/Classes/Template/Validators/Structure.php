<?php

namespace LaravelEnso\VueDatatable\app\Classes\Template\Validators;

use LaravelEnso\VueDatatable\app\Exceptions\TemplateException;
use LaravelEnso\VueDatatable\app\Classes\Attributes\Structure as Attributes;

class Structure
{
    private $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function validate()
    {
        $this->checkMandatoryAttributes()
            ->checkOptionalAttributes()
            ->checkFormat();
    }

    private function checkMandatoryAttributes()
    {
        $diff = collect(Attributes::Mandatory)
            ->diff(collect($this->template)->keys());

        if ($diff->isNotEmpty()) {
            throw new TemplateException(__(sprintf(
                'Mandatory Attribute(s) Missing: "%s"',
                $diff->implode('", "')
            )));
        }

        return $this;
    }

    private function checkOptionalAttributes()
    {
        $attributes = collect(Attributes::Mandatory)
            ->merge(Attributes::Optional);

        $diff = collect($this->template)
            ->keys()
            ->diff($attributes);

        if ($diff->isNotEmpty()) {
            throw new TemplateException(__(sprintf(
                'Unknown Attribute(s) Found: "%s"',
                $diff->implode('", "')
            )));
        }

        return $this;
    }

    private function checkFormat()
    {
        if (property_exists($this->template, 'lengthMenu') && !is_array($this->template->lengthMenu)) {
            throw new TemplateException(__('"lengthMenu" attribute must be an array'));
        }

        if (property_exists($this->template, 'appends') && !is_array($this->template->appends)) {
            throw new TemplateException(__('"appends" attribute must be an array'));
        }
    }
}
