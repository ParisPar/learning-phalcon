<?php

namespace App\Core\Models;

use \Phalcon\Mvc\Model\Behavior\Timestampable;

class Article extends Base
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $article_user_id;

    /**
     *
     * @var integer
     */
    protected $article_is_published;

    /**
     *
     * @var string
     */
    protected $article_created_at;

    /**
     *
     * @var string
     */
    protected $article_updated_at;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
      $this->id = $id;

      return $this;
    }

    /**
     * Method to set the value of field article_user_id
     *
     * @param integer $article_user_id
     * @return $this
     */
    public function setArticleUserId($article_user_id)
    {
      $this->article_user_id = $article_user_id;

      return $this;
    }

    /**
     * Method to set the value of field article_is_published
     *
     * @param integer $article_is_published
     * @return $this
     */
    public function setArticleIsPublished($article_is_published)
    {
      $this->article_is_published = $article_is_published;

      return $this;
    }

    /**
     * Method to set the value of field article_created_at
     *
     * @param string $article_created_at
     * @return $this
     */
    public function setArticleCreatedAt($article_created_at)
    {
      $this->article_created_at = $article_created_at;

      return $this;
    }

    /**
     * Method to set the value of field article_updated_at
     *
     * @param string $article_updated_at
     * @return $this
     */
    public function setArticleUpdatedAt($article_updated_at)
    {
      $this->article_updated_at = $article_updated_at;

      return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
      return $this->id;
    }

    /**
     * Returns the value of field article_user_id
     *
     * @return integer
     */
    public function getArticleUserId()
    {
      return $this->article_user_id;
    }

    /**
     * Returns the value of field article_is_published
     *
     * @return integer
     */
    public function getArticleIsPublished()
    {
      return $this->article_is_published;
    }

    /**
     * Returns the value of field article_created_at
     *
     * @return string
     */
    public function getArticleCreatedAt()
    {
      return $this->article_created_at;
    }

    /**
     * Returns the value of field article_updated_at
     *
     * @return string
     */
    public function getArticleUpdatedAt()
    {
      return $this->article_updated_at;
    }

    public function getSource()
    {
      return 'article';
    }

    /**
     * @return Article[]
     */
    public static function find($parameters = array())
    {
      return parent::find($parameters);
    }

    /**
     * @return Article
     */
    public static function findFirst($parameters = array())
    {
      return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
      return array(
        'id' => 'id',
        'article_user_id' => 'article_user_id',
        'article_is_published' => 'article_is_published',
        'article_created_at' => 'article_created_at',
        'article_updated_at' => 'article_updated_at'
      );
    }

    public function initialize()
    {


      $this->hasMany('id', 'App\Core\Models\ArticleTranslation', 'article_translation_article_id', array(
            'alias' => 'translations',//We can access it like $article->translations
            'foreignKey' => array(
              'action' => \Phalcon\Mvc\Model\Relation::ACTION_CASCADE
            )
          ));

          $this->hasOne('article_user_id', 'App\Core\Models\User', 'id', array(
            'alias' => 'user',
            'reusable' => true
          ));

        //Many to many relationships require 3 models
        //hasManyToMany(var fields, string! intermediateModel, var intermediateFields, var intermediateReferencedFields,
        //              string! referenceModel, var referencedFields, options = null)

          $this->hasManyToMany(
            "id",
            "App\Core\Models\ArticleHashtagArticle",
            "article_id",
            "hashtag_id",
            "App\Core\Models\Hashtag",
            "id",
            array(
              'alias' => 'hashtags'
            )
          );

          $this->hasManyToMany(
            "id",
            "App\Core\Models\ArticleCategoryArticle",
            "article_id",
            "category_id",
            "App\Core\Models\Category",
            "id",
            array(
              'alias' => 'categories'
            )
          );

          $this->addBehavior(new Timestampable(array(
            'beforeValidationOnCreate' => array(
              'field' => 'article_created_at',
              'format' => 'Y-m-d H:i:s'
            ),
            'beforeValidationOnUpdate' => array(
              'field' => 'article_updated_at',
              'format' => 'Y-m-d H:i:s'
            ),
          )));
        }

        public function getTranslations($arguments = null) {
          return $this->getRelated('translations', $arguments);
        }

        public function getCategories($arguments = null) {
          return $this->getRelated('categories', $arguments);
        }

        public function getHashtags($arguments = null) {
          return $this->getRelated('hashtags', $arguments);
        }

        public function getUser($arguments = null) {
          return $this->getRelated('user', $arguments);
        }

        //Overwrite toArray() method to include all related information for an article (translation, author, categories, hashtags)
        public function toArray($columns = null) {
          $output = parent::toArray($columns);//Add the columns from 'article' table to the output

          $output['article_translations'] = $this->getTranslations()->toArray();//We can pass ['columns'=>[...]] to specify which columns to include

          $output['article_categories'] = $this->getCategories()->filter(function($category) {//Pass each category through a filter and call it's toArray() function
            return $category->toArray();
          });

          $output['article_author'] = $this->getUser(array(
            'columns' => array(
              'user_first_name',
              'user_last_name',
              'user_email',
            )
          ))->toArray();

          $output['article_hashtags'] = $this->getHashtags()->filter(function($hashtag){
            return $hashtag->toArray();
          });


          return $output;
        }


  }
