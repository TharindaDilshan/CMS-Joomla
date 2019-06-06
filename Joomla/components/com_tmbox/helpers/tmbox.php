<?php

// No direct access
defined( '_JEXEC' ) or die;

/**
 * Component helper
 * @author olejenya
 */
class TmboxSiteHelper
{
	/**
	* @var array $menuIds  List Id depending of view component
	*/
	static $menuIds = array();
	
	/**
	* Create sef links
	* @param $option string
	* @param $view string
	* @param $query string
	* @return string link
	* @throws Exception
	*/
	static function getRoute( $option, $view, $query = '' )
	{
		if ( empty( self::$menuIds[$option . '.' . $view] ) ) {
			$items = JMenuSite::getInstance( 'site' )->getItems( 'component', $option );
			foreach ( $items as $item ) {
				if ( isset( $item->query['view'] ) && $item->query['view'] === $view ) {
					self::$menuIds[$option . '.' . $view] = $item->id;
				}
			}
		}
		return JRoute::_( 'index.php?view=' . $view . $query . '&Itemid=' . self::$menuIds[$option . '.' . $view] );
	}

	/**
	 * set meta tags
	 * @param string $title
	 * @param string $metaDesc
	 * @param string $metaKey
	 * @throws Exception
	 */
	static function setDocument( $title = '', $metaDesc = '', $metaKey = '' )
	{
		$baseUrl = JUri::base();
		$doc = JFactory::getDocument();
		$doc->addScript( $baseUrl . 'components/com_tmbox/assets/scripts/test.js' )
			->addStyleSheet( $baseUrl . 'components/com_tmbox/assets/styles/test.css' );
		$app = JFactory::getApplication();
		if ( empty( $title ) ) {
			$title = $app->get( 'sitename' );
		}
		elseif ( $app->get( 'sitename_pagetitles', 0 ) == 1 ) {
			$title = JText::sprintf( 'JPAGETITLE', $app->get( 'sitename' ), $title );
		}
		elseif ( $app->get( 'sitename_pagetitles', 0 ) == 2 ) {
			$title = JText::sprintf( 'JPAGETITLE', $title, $app->get( 'sitename' ) );
		}
		$doc->setTitle( $title );
		if ( trim( $metaDesc ) ) {
			$doc->setDescription( $metaDesc );
		}
		if ( trim( $metaKey ) ) {
			$doc->setMetaData( 'keywords', $metaKey );
		}
	}
	public static function loadVMLibrary()
    {
        if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
        if (!class_exists('calculationHelper')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/calculationh.php');
        if (!class_exists('CurrencyDisplay')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/currencydisplay.php');
        if (!class_exists('VirtueMartModelVendor')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/models/vendor.php');
        if (!class_exists('VmImage')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/image.php');
        if (!class_exists('shopFunctionsF')) require(JPATH_SITE . '/components/com_virtuemart/helpers/shopfunctionsf.php');
        if (!class_exists('calculationHelper')) require(JPATH_COMPONENT_SITE . '/helpers/cart.php');
        if (!class_exists('VirtueMartModelProduct')) {
            JLoader::import('product', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models');
        }
        if (!class_exists('VirtueMartModelRatings')) {
            JLoader::import('ratings', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models');
        }
    }

	/**
     * Class hepler
     * return $itemid
     * @param String $view
     */
    public static function getItemId($view)
    {
        $itemid = '';
        $items = JFactory::getApplication()->getMenu('site')->getItems('component', 'com_tmbox');
        foreach ($items as $item) {
            if ($item->query['view'] == $view) {
                $itemid = $item->id;
            }
        }
        return $itemid;
    }
}