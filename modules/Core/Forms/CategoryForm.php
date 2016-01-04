<?php
namespace App\Core\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\Identical;

class CategoryForm extends Form
{
    private $edit = false;
    public $locales;

    public function initialize($categoryEntity = null, $options = null)
    {
        //The $options['edit'] variable is passed true though the CategoriesController editAction.
        //To create a new category null is passed to $options though addAction
        if (isset($options['edit']) && $options['edit'] === true) {
            $this->edit = true;
        }

        //Get all the locales from the global configuration
        $locales = $this->getDI()->get('config')->i18n->locales->toArray();

        //Creates a name and slug text for each locale specified in our global configuration
        //The category names will be returned from the post request as 
        //translations['en'][category_translation_name], translations['gr'][category_translation_name] etc...
        foreach ($locales as $locale => $language) {

            if (true === $this->edit) {
                //Ex: Queries category_translation table for category_translation_lang = 'en'
                $translations = $categoryEntity->getTranslations(["category_translation_lang = '$locale'"])->toArray();
            }

            $category_name[$locale] = new Text ("translations[$locale][category_translation_name]", [
                'value' => $this->edit === true ? $translations[0]['category_translation_name'] : null
            ]);

            $category_slug[$locale] = new Text ( "translations[$locale][category_translation_slug]", [
                'value' => $this->edit === true ? $translations[0]['category_translation_slug'] : null
            ]);

            $category_lang[$locale] = new Hidden ( "translations[$locale][category_translation_lang]", [
                'value' => $locale
            ]);

            $this->add( $category_name[$locale] );
            $this->add( $category_slug[$locale] );
            $this->add( $category_lang[$locale] );
        }

        //CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(
            new Identical(array(
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed',
            ))
        );

        $this->add($csrf);

        $this->add(new Submit('save', array(
            'class' => 'btn btn-lg btn-primary btn-block',
        )));
    }
}
