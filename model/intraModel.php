<?php
namespace MODEL;

/**
 * Class IntraModel
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * IntraModel is a model for granting access and creating a menu for the intra.intelrobotics.local website
 *
 */
class IntraModel extends Model {

    private $intraPages = [
        "news" => false,
        "users" => ["Administration_SG"],
        "webnews" => ["IT_SG"],
        "addwebnews" => ["IT_SG"],
        "webproducts" => ["IT_SG"],
        "addwebproducts" => ["IT_SG"],
        "webabout" => ["IT_SG"]
    ];

    /**
     * getMenu() uses $intraPages to create a menu array and it varies based by the users permissions
     * @param array $permissions
     * @return array
     */
    public function getMenu(array $permissions) : array {
        $menu = [];
        foreach($this->intraPages as $intraPage => $intraPermissions) {
            $access = false;

            if($intraPermissions !== false) {
                foreach($intraPermissions as $intraPermission) {
                    if(array_search($intraPermission, $permissions) !== false) {
                        $access = true;
                        break;
                    }
                }
            }else {
                $access = true;
            }

            if($access) {
                $menu[] = $intraPage;
            }
        }

        return $menu;
    }

    /**
     * checkAccess() is used for making sure the user has the correct permissions to load a specific webpage
     * @param string $page
     * @param array $permissions
     * @return bool
     */
    public function checkAccess(string $page, array $permissions) : bool {
        $menu = $this->getMenu($permissions);

        return (array_search($page, $menu) !== false) ? true : false;
    }


}