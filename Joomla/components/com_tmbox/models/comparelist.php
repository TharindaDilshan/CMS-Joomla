<?php

defined('_JEXEC') or die;

class TmboxModelComparelist extends JModelLegacy
{
    /**
     * Class constructor
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    /**
     *
     * @param null
     * @return Array object
     */
    public function getProducts()
    {
        $mainframe = JFactory::getApplication();
        $compareIds = $mainframe->getUserState( "com_tmbox.site.compareIds", array() );

        $productModel = VmModel::getModel('product');
        $ratingModel = VmModel::getModel('ratings');
        $showRating = $ratingModel->showRating();

        $productModel->withRating = $showRating;
       
        $prods = $productModel->getProducts($compareIds);

        $productModel->addImages($prods, 1);

        return $prods;
    }
}
