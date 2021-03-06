<?php
// TODO: do something with dir separators
require_once dirname(__FILE__). '/vendor/paavpager/PaavPager.php';

class PaavTable extends CWidget
{
    public $dataProvider;
    public $classes = array();
    public $columns = array();
    public $view;
    public $data = array();

    protected $app;
    protected $sort;

    public function init()
    {
        if (!isset($this->dataProvider))
            throw new CException(
                'You must provide `dataProvider` property initial value');

        if (empty($this->columns))
            $this->columns = $this->dataProvider->model->attributeNames();

        if (empty($this->classes))
            $this->classes = array(
                'sortLinkAsc' => 'paavTable-sortLink-asc',
                'sortLinkDesc' => 'paavTable-sortLink-desc',
            );

        if (!isset($this->view))
            $this->view = 'table';

        if (!empty($this->data))
            $this->data = (object) $this->data;

        $this->app = Yii::app();

        $assetsPath = dirname(__FILE__) . '/assets';

        $cssFiles = [
            'main.css',
            'fontello.css',
        ];

        $am = $this->app->assetManager;
        $cs = $this->app->clientScript;

        $assetsUrl = $am->publish($assetsPath, false, -1, true);

        foreach ($cssFiles as $cssFile)
            $cs->registerCssFile($assetsUrl . '/css/' . $cssFile);
    }

    public function run()
    {
        $models = $this->dataProvider->getData();
        $pages = $this->dataProvider->getPagination();

        $attrLabels = $this->getAttrLabels();

        $this->render($this->view, array(
            'pages' => $pages,
            'models' => $models,
            'attrLabels' => $attrLabels,
            'data' => $this->data,
        ));
    }

	public function getAbsUrlByModel($model, $action, array $params = array())
	{
		$route = get_class($model) . '/' . $action;

		return $this->app->createAbsoluteUrl($route, $params);
	}

    public function isSortable($attr)
    {
        $sortableAttrs = array(
            'name',
            'address',
        );
        
        return in_array($attr, $sortableAttrs);
    }

    public function createSortLink($name, $label)
    {
        $sort = $this->dataProvider->sort;

        if (!in_array($name, $sort->attributes))
            return $label;

        $class = null;
        $direction = CSort::SORT_ASC;
        $directions = $sort->directions;

        if (isset($directions[$name])) {

            switch ($directions[$name]) {

                case CSort::SORT_ASC:
                    $class = $this->classes['sortLinkDesc'];
                    $direction = CSort::SORT_DESC;
                    break;

                case CSort::SORT_DESC:
                    $class = $this->classes['sortLinkAsc'];
                    $direction = CSort::SORT_ASC;
                    break;
            }
        }

        $controller = $this->app->getController();

        $url = $sort->createUrl($controller, array($name => $direction));

        return CHtml::link($label, $url, array('class' => $class));
    }

    protected function getAttrLabels()
    {
        $model = $this->dataProvider->model;

        $attrs = $this->columns;

        foreach ($attrs as $attr)
           $attrLabels[$attr] = $model->getAttributeLabel($attr);

        return $attrLabels;
    }
}
