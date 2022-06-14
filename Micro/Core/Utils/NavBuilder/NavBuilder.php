<?php


namespace API\Core\Utils\NavBuilder;
use API\Models\NavEntry;
use API\Interfaces\{RenderInterface,RouterInterface};
use API\Core\Session\Session;
use API\Core\Utils\Translate;

class NavBuilder
{
    private RouterInterface $router;
    /**
     * @var RenderInterface
     */
    private RenderInterface $render;
    private array $config = [];
    private array $navigation = [];
    private bool $translate;
    private Translate $tr;
    private string $currentURL;

    private array $navLinks;
    private array $adminLinks;

    public function __construct(RouterInterface $router, RenderInterface $render, Translate $tr)
    {

        $this->router = $router;
        $this->render = $render;
        $this->translate = TRANSLATE;
        $this->tr = $tr;
        $this->currentURL = (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    }

    public function link($label, $url, $icon, $usage = null ): Link
    {
        return $this->navigation[] = new Link($label, $url, $icon, $usage);
    }
    public function drop($label): Drop
    {
        return $this->navigation[] = new Drop($label);
    }
    public function admin(): Auth
    {
        return $this->navigation[] = new Auth();
    }

    public function loadNavLinks() : void
    {
        $this->navLinks=[];
        $this->adminLinks=[];

        foreach ($this->config as $entry) {
            if ($entry->getType()!== "author") {
                $this->navLinks[] = $entry;
            } else {
                $this->adminLinks = $entry->getLink();
            }
        }
    }
    public function render(int $type = 0): string
    {


//        $include = './partials'.DIRECTORY_SEPARATOR.ACTIVE_CSS_TEMPLATES.DIRECTORY_SEPARATOR;
//        dump($include);
//        die;
//
        $this->config = [];
        $this->navigation = [];

        self::build();
        if (LOG_NAV_CONFIG) {
            self::logConfig($this->config);
        }
//        if (NAV_CONFIG_COOKIE) {
//            $user = new AppUser();
//            self::setNavCookie($user);
//            if(!RENDER){
//                return '';
//            }
//        }
        $templates = [];
        $this->loadNavLinks();

        // TODO Move to external config file
        if($type == 0) {  // Nav Bar
            $templates = [
                'link' => DIRECTORY_SEPARATOR.'navLink',
                'alink' => DIRECTORY_SEPARATOR.'avatarLink',
                'drop' => DIRECTORY_SEPARATOR.'navDrop',
                'RENDER' => DIRECTORY_SEPARATOR.'navBuilder',
            ];
        } elseif ($type == 1){  // Side Nav Bar
            $templates = [
                'link' => DIRECTORY_SEPARATOR.'sideNavLink',
                'alink' => DIRECTORY_SEPARATOR.'sideAvatarLink',
                'drop' => DIRECTORY_SEPARATOR.'sideNavDrop',
                'RENDER' => DIRECTORY_SEPARATOR.'sideNavBuilder',
            ];
        }


//        dump(ACTIVE_CSS_TEMPLATES.DIRECTORY_SEPARATOR .$templates['RENDER']);
//        die;
//

        $HTMLTemplateNav = '';
        $HTMLTemplateAdmin = '';

        foreach ($this->navLinks as $entry) {
            $HTMLTemplateNav .= $this->handleEntries($entry,$templates);
        }
        if (isset($this->adminLinks)) {
            foreach ($this->adminLinks as $entry) {
                $HTMLTemplateAdmin .= $this->handleEntries($entry,$templates);
            }
        }

        $viewData = [
            'active_flag' => substr(APP_ASSET_BASE, 0, -1) ."/images/" . Session::get('ACTIVE_LANG') . '.png',
            'active_language' => Session::get('ACTIVE_LANG'),
            'multi_language' => MULTI_LANGUAGE,
            'nav_admin_links' => $HTMLTemplateAdmin,
            'nav_links' => $HTMLTemplateNav,
            'isActive' => ACTIVE_NAV_LINK_CLASS,
            'translation' => 'partials'.DIRECTORY_SEPARATOR.BASE_CSS_TEMPLATES.DIRECTORY_SEPARATOR.'multiLanguage.twig'
        ];


//        dump($viewData['active_flag']);
//        die;
        return $this->render->template(BASE_CSS_TEMPLATES .$templates['RENDER'],$viewData);
    }
    private function handleEntries(NavEntry $entry, array $templates): string
    {
        switch ($entry->getType()) {
            case 'link':
                return (string)$this->render->template(BASE_CSS_TEMPLATES.$templates['link'], [
                    'route' => $entry->getLink()[0],
                    'label' => urldecode($entry->getLabel()),
                    'icon' => $entry->getIcon(),
                    'active' => $entry->getActive(),
                ]);
            case 'alink':
                return (string)$this->render->template(BASE_CSS_TEMPLATES.$templates['alink'], [
                    'route' => $entry->getLink()[0],
                    'avatar' => $entry->getIcon(),
                    'active' => $entry->getActive(),
                ]);
            case 'drop':
                $links = $entry->getLink();
                /**@var NavEntry $links */
                for ($i = 0; $i < count($links); $i++) {
                    $links[$i]->setLabel(urldecode($links[$i]->getLabel())) ;
                }
                return (string)$this->render->template(BASE_CSS_TEMPLATES.$templates['drop'], [
                    'entry' => $links,
                    'dropdownLabel' => urldecode($entry->getLabel()),
                    'active' => $entry->getActive()
                ]);
            default:
                return '';
        }
    }
    private function build(): void
    {

        $nav = $this;
        include(APP_NAVBAR_FILE);
        $this->config = [];
        foreach ($this->navigation as $link) {
            switch (get_class($link)) {
                case PSR4.'\Core\Utils\NavBuilder\Link':
                    $this->config[] = self::buildLink($link, $this->router);
                    break;
                case PSR4.'\Core\Utils\NavBuilder\Drop':
                    $this->config[] = self::buildDrop($link, $this->router);
                    break;
                case PSR4.'\Core\Utils\NavBuilder\Auth':
                    $this->config[] = self::buildAuth($link, $this->router);
                    break;
            }
        }
    }
    private function buildDrop(Drop $drop, RouterInterface $router) : NavEntry
    {
        $processed = [];
        $active = '';
        foreach ($drop->getLinks() as $link) {
            $processed[] = self::buildLink($link, $router);
        }
        foreach ($processed as $link) {
            if ($link->getActive() === ACTIVE_NAV_LINK_CLASS) {
                $active = ACTIVE_NAV_LINK_CLASS;
            }
        }

        return new NavEntry(
            'drop',
            $this->translate ?
                rawurlencode($this->tr->translate($drop->getLabel())) :
                rawurlencode($drop->getLabel()),
            $drop->getIcon(),
            $processed,
            $active
        );
    }
    private function buildLink(Link $link, RouterInterface $router) : NavEntry
    {
        return new NavEntry(
            'link',
            $this->translate ?
                rawurlencode($this->tr->translate($link->getLabel())) :
                rawurlencode($link->getLabel()),
            $link->getIcon(),
            $router->generateURI($link->getURL(), $link->getParams()),
            self::isActive($router->generateURI($link->getURL(), $link->getParams()))
        );

    }
    private function buildAuth(Auth $link, RouterInterface $router) : NavEntry
    {
        $usage = Session::get('loggedIn') ? 'USER' : 'GUEST';
//        $usage = 'USER';
        $authLinks = [];
        foreach ($link->getLinks() as $link_) {
            if ($link_->getUsage() === $usage) {
                $authLinks[] = $link_;
            };
        }
        $processed = [];

        for($i=0; $i<count($authLinks); $i++){
            switch (get_class($authLinks[$i])){
                case PSR4.'\Core\Utils\NavBuilder\ALink':
                    $newLink = new ALink(
                        $authLinks[$i]->avatar,
                        $authLinks[$i]->url,
                        $authLinks[$i]->params,
                        $authLinks[$i]->usage,
                    );
                    $processed[] = self::buildALink($newLink,$router );
                    break;
                case PSR4.'\Core\Utils\NavBuilder\Link':
                    $newLink = new Link(
                        $authLinks[$i]->label,
                        $authLinks[$i]->url,
                        $authLinks[$i]->icon,
                        $authLinks[$i]->params,
                        $authLinks[$i]->usage);
                    $processed[] = self::buildLink($newLink, $router);
                    break;
            }
        }

        $foo = [
            'type' => 'author',
            'links' => $processed
        ];

        return new NavEntry(
            'author',
            '',
            '',
            $processed,
            ''
        );



    }
    private function buildALink(ALink $link, RouterInterface $router) : NavEntry
    {

        return new NavEntry(
            'alink',
            '',
            $link->getAvatar(),
            $router->generateURI($link->getURL(), $link->getParams()),
            self::isActive($router->generateURI($link->getURL(), $link->getParams()))
        );
    }

    private function isActive(string $link): string
    {
        if ($link === $this->currentURL) {
            return ACTIVE_NAV_LINK_CLASS;
        }
        return '';
    }
    private function logConfig($config) : void
    {
        $configText = json_encode($config, JSON_PRETTY_PRINT);
        $configTMP = preg_replace('/"([^"]+)"\s*:\s*/', '$1:', $configText);
        $config_php = <<<EOT
export const config = $configTMP;
EOT;
        $f = fopen('nav_' . uniqid(time()) . '.log', 'w');
        fwrite($f, $config_php);
        fclose($f);
    }

//    public function setNavCookie(AppUser $user)
//    {
//        self::build();
//        $assetBase = substr(APP_ASSET_BASE, 0, -1);
//        $data = [
//            'parallax' => true,
//            'sticky_nav' => true,
//            'links' => $this->config,
//            'activeLanguage' => Session::get('ACTIVE_LANG'),
//            'activeFlag' => $assetBase ."/images/" . Session::get('ACTIVE_LANG') . '.png',
//            'baseURL' => substr(APP_ASSET_BASE, 0, -1),
//            'langSelector' => MULTI_LANGUAGE,
//            'user' =>[
//                'id' => $user->getId(),
//                'type' => $user->getId()===0?'GUEST':'USER',
//                'avatar' => $assetBase ."/users/user_{$user->getId()}/default_avatar.png",
//            ]
//        ];
//        $cookie = json_encode($data);
//        setcookie(
//            'microNav',
//            "{$cookie}",
//            time() + 5,
//            '/'
//        );
//        Logger::log('Nav Cookie is Set');
//    }

}
