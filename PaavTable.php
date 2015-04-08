<?php
// TODO: do something with dir separators
require_once dirname(__FILE__). '/vendor/paavpager/PaavPager.php';

class PaavTable extends CWidget
{
    public $dataProvider;
    public $sort;
    public $classes = array();
    public $columns = array();

    protected $_app;

    public function init()
    {
        $this->sort = new CSort();

        $this->sort->attributes = array(
            'name',
            'address',
        );

        $this->sort->defaultOrder = [
            'name' => CSort::SORT_ASC,
        ];

        $this->dataProvider->sort = $this->sort;

        $this->classes = array(
            'sortLinkAsc' => 'paavTable-sortLink-asc',
            'sortLinkDesc' => 'paavTable-sortLink-desc',
        );

        $this->_app = Yii::app();

        $assetsPath = dirname(__FILE__) . '/assets';

        $cssFiles = [
            'main.css',
            'fontello.css',
        ];

        $am = $this->_app->assetManager;
        $cs = $this->_app->clientScript;

        $assetsUrl = $am->publish($assetsPath, false, -1, true);

        foreach ($cssFiles as $cssFile)
            $cs->registerCssFile($assetsUrl . '/css/' . $cssFile);
    }

    public function run()
    {
        $models = $this->dataProvider->getData();
        $pages = $this->dataProvider->getPagination();

        $attrLabels = $this->_getAttrLabels();

        $this->render('table', array(
            'pages' => $pages,
            'models' => $models,
            'attrLabels' => $attrLabels,
        ));
    }

	public function getAbsUrlByModel($model, $action, array $params = array())
	{
		$route = get_class($model) . '/' . $action;

		return $this->_app->createAbsoluteUrl($route, $params);
	}

    public function isSortable($attr)
    {
        $sortableAttrs = [
            'name',
            'address',
        ];
        
        return in_array($attr, $sortableAttrs);
    }

    public function createSortLink($name, $label)
    {
        $sort = $this->sort;

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

        $controller = $this->_app->getController();

        $url = $sort->createUrl($controller, array($name => $direction));

        return CHtml::link($label, $url, array('class' => $class));
    }

    protected function _getAttrLabels()
    {
        $model = $this->dataProvider->model;

        $attrs = $this->columns;

        foreach ($attrs as $attr)
           $attrLabels[$attr] = $model->getAttributeLabel($attr);

        return $attrLabels;
    }
}
