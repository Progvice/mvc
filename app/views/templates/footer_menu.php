<?php
namespace Core\App\Template;
class footer_menu extends \Core\App\Template {
    static protected $template;
    public function __construct($values) {
        $menu = '';
        foreach(MENU['footer'] as $menuitem) {
            if(isset($menuitem['submenu'])) {
                $menu .= '<li class="hassubmenu"><a href="' . $menuitem['link'] . '">' . LANG[$menuitem['lang']] . '</a><ul class="submenu">';
                foreach($menuitem['submenu'] as $submenuitem) {
                    $menu .= '<li><a href="' . $submenuitem['link'] . '">' . LANG[$submenuitem['lang']] . '</a></li>';
                }
                $menu .= '</ul></li>';
            }
            else {
                $menu .= '<li><a href="' . $menuitem['link'] . '">' . LANG[$menuitem['lang']] . '</a></li>';
            }
        }
        self::$template = $menu;
        return self::$template;

    }
}
?>