<?php

class App_Base_Object  {

	/**
	 * Istanza della classe (Singleton)
	 *
	 * @var App_Base_Base
	 */
	private static $_istanza = null;	
		
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
    
    public static function toArray($rows){
        if(is_object($rows))
            return (count($rows))? $rows->toArray() : $rows;
        else
            return $rows;
        
    }

	
}
