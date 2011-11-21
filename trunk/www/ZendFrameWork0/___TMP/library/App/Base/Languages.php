<?php

class App_Base_Languages {

	/**
	 * Istanza della classe (Singleton)
	 *
	 * @var App_Base_Base
	 */
	private static $_istanza = null;
	
	/**
	 * Counter dei moduli caricati
	 *
	 * @var int
	 */
	public $_modulicaricati = null;
	
    // LANGUAGE DATA
        private static $_icons_path = "/public/images/lingue/";

        private static $_lang_id = null;
        private static $_lang_format = null;
        private static $_admin_lang_id = null;
        private static $_admin_lang_format = null;

        private static $_available_formats = null;
        private static $_available_names = null;

        private static $_lang_icon = null;
        private static $_admin_lang_icon = null;

    /**
     * Ritorna l'istanza della classe.
     *
     * @return App_Base_Base
     */
    public static function getInstance()
    {
        if (self::$_istanza === null) {
            self::init();
        }
                
        return self::$_istanza;
    }
	
    /**
     * Inizializza un'istanza della classe.
     *
     * @return void
     */
    protected static function init()
    {
        self::$_istanza = new self();
    }

    public function startUp(){
        if(!self::$_lang_id || !self::$_lang_format)
            self::setMyAppActualLang();

    }

    public static function RetrieveAvailableLanguages(){
        
        $langs = NULL;
        $lang_table = new Lingue();
        $langs = $lang_table->fetchAll("abilitata LIKE 'Si'")->toArray();
        unset($lang_table);

        $formats = NULL;
        $names = NULL;
        if(count($langs))
            foreach($langs as $l){
                $formats[$l['id']] = $l['format'];
                $names[$l['id']] = $l['nome'];
            }

        self::$_available_formats = $formats;
        self::$_available_names = $names;
        unset($formats);
        return $langs;

    }

    private static function setMyAppActualLang(){
        if(!self::$_lang_id || !self::$_lang_format){
            App_Base_Languages::RetrieveAvailableLanguages();
            $app = Zend_Session::namespaceGet('myApplication');

            self::$_lang_id = $app['lingua'];
            self::$_lang_format = self::$_available_formats[$app['lingua']];

            self::$_admin_lang_id = $app['lingua_admin'];
            self::$_admin_lang_format = self::$_available_formats[$app['lingua_admin']];

            self::$_lang_icon = self::$_available_names[$app['lingua']];
            self::$_admin_lang_icon = self::$_available_names[$app['lingua_admin']];
            unset($app);
        }

    }

    public static function GetActiveLanguageValue(){
        self::setMyAppActualLang();
        return self::$_lang_id;
    }

    /*!@function GetActiveLanguageFormat
     *
     * @short - Short 2 chars format value
     *
     */
    public static function GetActiveLanguageFormat($short=FALSE){
        self::setMyAppActualLang();
        if($short)
            return substr (self::$_lang_format,0,2);
        return self::$_lang_format;
    }

    public static function GetActiveLanguageIcon($with_extension=FALSE,$with_path=FALSE,$extension="png"){
        self::setMyAppActualLang();
        $icon = NULL;
        if($with_extension){
            $icon = self::$_lang_icon . "." . $extension;
            if($with_path)
               $icon = self::$_icons_path . $icon ;
           return $icon;
        }
        return self::$_lang_icon;
    }

    public static function GetAdminActiveLanguageValue(){
        self::setMyAppActualLang();
        return self::$_admin_lang_id;
    }

    /*!@function GetActiveLanguageFormat
     *
     * @short - Short 2 chars format value
     *
     */
    public static function GetAdminActiveLanguageFormat($short=FALSE){
        self::setMyAppActualLang();
        if($short)
            return substr (self::$_admin_lang_format,0,2);
        return self::$_admin_lang_format;
    }

    public static function GetAdminActiveLanguageIcon($with_extension=FALSE,$with_path=FALSE,$extension="png"){
        self::setMyAppActualLang();
        $icon = NULL;
        if($with_extension){
            $icon = self::$_admin_lang_icon . "." . $extension;
            if($with_path)
               $icon = self::$_icons_path . $icon ;
           return $icon;
        }
        return self::$_admin_lang_icon;
    }
	
	
}
