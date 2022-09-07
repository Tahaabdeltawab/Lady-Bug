<?php

namespace App\Traits;

use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;
use Spatie\Translatable\Events\TranslationHasBeenSet;

/**
 * 1-
 * only overriding the line
 * $this->attributes[$key] = $this->asJson($translations);
 * to
 * $this->attributes[$key] = json_encode($translations, JSON_UNESCAPED_UNICODE);
 * on setTranslation() method on \Spatie\Translatable\HasTranslations
 * to save Arabic translations on db as is without encoding
 * 2-
 * overriding the default Model toArray() method to by default get the models translated
 */
trait SpatieHasTranslations
{
    use HasTranslations;
    
    public function setTranslation(string $key, string $locale, $value): self
    {
        $this->guardAgainstNonTranslatableAttribute($key);

        $translations = $this->getTranslations($key);

        $oldValue = $translations[$locale] ?? '';

        if ($this->hasSetMutator($key)) {
            $method = 'set'.Str::studly($key).'Attribute';

            $this->{$method}($value, $locale);

            $value = $this->attributes[$key];
        }

        $translations[$locale] = $value;

        // $this->attributes[$key] = $this->asJson($translations);
        $this->attributes[$key] = json_encode($translations, JSON_UNESCAPED_UNICODE);

        event(new TranslationHasBeenSet($this, $key, $locale, $oldValue, $value));

        return $this;
    }


    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, \App::getLocale());
        }
        return $attributes;
    }
   

}
