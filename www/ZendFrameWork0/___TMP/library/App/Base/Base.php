<?php

class App_Base_Base  {

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
     * Stampa un header per un debug visivo migliore .
     *
     * @return void
     */
     
    public function _print_header( $type = 'txt', $source = 0) 
    {
		
		
		if (!headers_sent()) {
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
			if ($type == 'xml') {
			header("Content-Type: text/xml; charset=UTF-8");
			} else {
			header("Content-Type: text/plain; charset=UTF-8");
		   }
		
	    } else {
			echo "Headers already sent by ".$source."\n";
		}
		
		 if (XDEBUG == 1) {
			echo "Start from source:".$source." \n";
		    echo "Headers from Client Browser: \n";
		   foreach (getallheaders() as $name => $value) {
				echo "$name: $value\n";
			}
		 }
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
    
	/**
	 * Scrittura HTML barra delle lingue in amministrazione
	 *
	 * @param int $lingua_admin
	 * @return string $html_return
	 */
	public function barralingue($lingua_admin) 
	{	
		$selection = '';
		$select = '';
		$lingua = new Lingue();
		$lingua = $lingua->fetchAll();
		$lingue = $lingua->toArray();
		
		foreach ($lingue as $row){
			if($lingua_admin != $row['id']){
				$selection .= '<a href="/admin/'.$row['id'].'">';
				$selection .= '<img src="/public/images/lingue/'.$row['nome'].'.png" title="'.$row['nome'].'" alt="'.$row['nome'].'"></a>';
			} else {
				$select = 'Sei in: ';
				$select .= '<img src="/public/images/lingue/'.$row['nome'].'.png" title="'.$row['nome'].'" alt="'.$row['nome'].'">';
			}					
		}
		if(count($lingue)!=1)
			return $select." Seleziona la lingua: ".$selection;
		else
			return $select;
	}
	
	/**
	 * Scrittura HTML barra delle lingue nel frontend
	 *
	 * @param int $lingua_admin
	 * @return string $html_return
	 */
	public function barralingue_front() 
	{	
		$selection = '';
		$lingua = new Lingue();
		$lingua = $lingua->fetchAll("abilitata!='No'");
		$lingue = $lingua->toArray();
		
		foreach ($lingue as $row){			
				$selection .= '<a href="/cambia-lingua/'.$row['id'].'">';
				$selection .= '<img src="/public/images/lingue/'.$row['nome'].'.png" title="'.$row['nome'].'" alt="'.$row['nome'].'"></a>';
		}
		
		return (count($lingue) > 1) ? $selection : null;
	}
	
	/**
	 * Funzione per il caricamento di informazioni (header, footer, etc) 
	 * attraverso l'id associato alla pagina	
	 * 
	 * @param int $info_id
	 * @return array $info
	 */
	public function caricamentoInfo($info_id = 1, $infos = 0)
	{
		$info = new Info();
		if($info_id)
			$info = $info->fetchRow("id='$info_id'");
		else 
			$info = $info->fetchAll("id != {$infos}");
		$info = $info->toArray();
		if (isset($_GET['xcaricamentoInfo'])) {
        $this->_print_header("txt","file:" . __FILE__." line:".__LINE__);
		print_r($info);
		exit;
	    }
		return $info;
	}
	
	/**
	 * Funzione per il caricamento dei controller dei moduli installati
	 *
	 * @return array $ret['nome_modulo'] = 'percorso'
	 */
	public function caricamentoModuli() 
	{
		$ret = array();
		$controller = new Resource();
		$controller = $controller->fetchAll("Carica != 'No'");
		$controller = $controller->toArray();
		
		$this->_modulicaricati = count($controller);
		
		foreach ($controller as $row)
			if($row['base']=="Si")
				$ret[$row['modulo']] = '../application/'.$row['modulo'].'/controllers';
			else 
				$ret[$row['modulo']] = '../application/moduli/'.$row['modulo'].'/controllers';
				
		return $ret;
	}
	
	/**
	 * Funzione per il setting delle regole di routing, lette da db
	 *
	 * @param Zend_Controller_Front $router
	 */
	public function routing($router)
	{
		$controller = new Routing(); 
		$controller = $controller->fetchAll();
		$controller = $controller->toArray();
		
		if (isset($_GET['xrouting'])) {
        $this->_print_header("txt","file:" . __FILE__." line:".__LINE__);
		print_r($controller);
		exit;
	    }
		
		
		
		foreach($controller as $row){
			if($row['tipo']!="Dinamica"){
				$router->addRoute(
					$row['etichetta'],
		    		new Zend_Controller_Router_Route_Static($row['url'], array(
		    															'module' => $row['modulo'], 
		    															'controller' => $row['controller'], 
		    															'action' => $row['azione'])));
			} else {
				$router->addRoute(
					$row['etichetta'],
		    		new Zend_Controller_Router_Route($row['url'], array(
	    															'module' => $row['modulo'], 
	    															'controller' => $row['controller'], 
	    															'action' => $row['azione'])));
			}
		}			
	}
	
	/**
	 * Funzione per il setting delle regole ACL, lette da db
	 *
	 * @param Zend_Acl $myAcl
	 * @param int $modalita
	 */
	public function aclResourceAllow($myAcl, $modalita=1)
	{
		$acl = new acl();
				
		switch ($modalita)
		{
			case 1:
				$acl = $acl->fetchAll("special_allow!='Si'");
				$acl = $acl->toArray();								
				foreach($acl as $row)
					if(!preg_match("/:/",$row['risorsa']))
						$myAcl->add(new Zend_Acl_Resource($row['risorsa']));
					else 
					{
						$split = explode(":",$row['risorsa']);
						$myAcl->add(new Zend_Acl_Resource($row['risorsa'], $split[0]));	
					}										
			break;
			case 2:
				$acl = $acl->fetchAll();
				$acl = $acl->toArray();
				foreach($acl as $row)
					if($row['eccezioni']==null)						
						$myAcl->Allow($row['ruolo'], $row['risorsa']);
					else 
					{
						$eccezioni = explode(",",$row['eccezioni']);
						$myAcl->Allow($row['ruolo'], $row['risorsa'], $eccezioni[0]);
					}				
			break;
		}
	}
	
	/**
	 * Funzione che permette la creazione del menu di amministrazione
	 * per i menu secondari
	 *
	 * @return array menu
	 */
	public function caricamentoMenuAggiuntivi()
	{
		$menu = new Menu();		
		$menus = $menu->fetchAll("id != 1 AND id != 2");					
			
		return (isset($menus)) ? $menus->toArray() : NULL; 	
	}
	
	public function generaMenu2($lingua_id = 1, $id = 0, $style_ul="", $link = "", $link_sub_menu = "/pagine/statiche/", $menu_id = 0, $info_id = 1) {
		
		/////echo "file:" . __FILE__." line:".__LINE__;
		exit;
	}
	
	/**
	 * Funzione per la generazione del menu di navigazione frontend del cms
	 *
	 * @param int $lingua_id
	 * @param int $id
	 * @param string $style_ul
	 * @param string $link_sub_menu
	 * @return string
	 */
	public function generaMenu($lingua_id = 1, $id = 0, $style_ul="", $link = "", $link_sub_menu = "/pagine/statiche/", $menu_id = 0, $info_id = 1)
	{
		/*svuoto link*/
		$str = '';		
		
		/*???*/
		($id) ? $style_ul = '' : $style_ul;
		
		/*tabella VociMenu*/
		$menu = new VociMenu();
		
		/*costrusico query*/
		(!$menu_id) ? $menu_id = "menu_id = 1" : $menu_id = "menu_id = {$menu_id}";
		$info_id_menu = (!$info_id) ? 1 : $info_id;
		(!$info_id) ? $info_id = "info_id = 1" : $info_id = "info_id = {$info_id}";
		
		/*lancio query*/
		$menu = $menu->fetchAll("{$info_id} AND {$menu_id} AND padre_id = {$id} AND lingua_id = ".$lingua_id,'posizione');
		
		/*traformo in array*/
		$padri = $menu->toArray();				
		
		/*apro il menu UL*/
		$str .= '<ul'.$style_ul.'>';
		
		/*per ogni riga del databse ritornata*/
		foreach($padri as $row)
		{
			if($row["nome"]!="Responsabili marchi" && $row["nome"]!="Nuovi Prodotti"){
				
			
			if($row["agenti"]=="Si")
				$links = "/moduli/news/lista/1/".$row["info_id"];
			else {									
				if($row["home"]=="Si")
					$links = "/index";
				else{
					$links = $link_sub_menu.$row['link'];
				}						
			}
			
			if($row["ar"]=="Si")
                            $links=$row['link'];
                        
			if($row["prodotto"]=="Si")
				$links = "/moduli/catalogo/lista/1/".$row["info_id"]."/0";
                        else if($row["cmegastore"]=="Si")
				$links = "/catalogo-megastore";
				//else
					//$links = "/moduli/prodotti/famiglie/".$row['link'];
					
			if($row['sezione'] != NULL){
				$links = "/{$row['sezione']}/{$row['link']}";
			}
									
			$str .= '<li><a';
			
			if($row['link'] == $link)
				$str .= ' id="item_selezionato"';
			
			$str .= ' href="'.$links.'">'.$row['nome'].'</a>';

			if($row['ha_figli']!='No')
				$str .= $this->generaMenu($lingua_id, $row['id'], $style_ul, $link).'</li>';
			else
				$str .= '</li>';
				
			if($info_id_menu!=1){
				//$str .= $info_id;
				$str .= '<img src="/public/images/index/pezzi_comuni/riga.jpg" />';
			}
			
			}
		}
		
		$str .= '</ul>';
		
		////print($str);
		/////exit;
		return $str;
	}
	
	/**
	 * Funzione per la generazione della barra di navigazione (Front/Back)end
	 *
	 * @param int $id
	 * @param int $lingua_id
	 * @param string $ret
	 * @return string
	 */
	public function barraNavigazione($id, $lingua_id, $link="/pagine/statiche/", $ret=array())
	{		
		$menu = new VociMenu();
		$menu = $menu->fetchRow('id = '.$id.' AND lingua_id = '.$lingua_id, 'posizione');
		$ret2 = '';
		$clean_ret = '';
			
		if (!isset($menu->padre_id))
		{		
			if ($ret==null)
				return "";
			else
			{
				for($i=0;$i<count($ret);$i++)				
					$clean_ret .= ' &raquo; <a href="'.$link.$ret[count($ret)-$i-1]['link'].'">'.$ret[count($ret)-$i-1]['nome']."</a>";
				
				return $clean_ret;
			}
		}
		else
		{
			array_push($ret,array('link'=>$menu->link,'nome'=>$menu->nome));
			$ret2 = $this->barraNavigazione($menu->padre_id, $lingua_id, $link, $ret);
			
			if (isset($ret2))			
				return $ret2;
			else 
				return false;			
		}		
	}
	
	/**
	 * Funzione che conta quanti figli ha una voce
	 *
	 * @param int $id
	 * @return int
	 */
	public function modificaParentelaMenu($id,$tabella="voci_menu")
	{
		$registry_instance = Zend_Registry::getInstance();
	    $db = $registry_instance->get('db');
	    $row = $db->fetchRow("SELECT count(id) as figli 
	    					  FROM ".$tabella." 
	    					  WHERE padre_id=".$id);
	    return $row['figli'];
	}

	/**
	 * Funzione che permette il caricamento di una eventuale 
	 * interazione modulo_caricato <-> core
	 *
	 * @param string $modulo
	 * @param string $sezione
	 * @return mixed
	 */
	public function checkandloadModuli($modulo='news',$sezione='home')
	{
		$resource = new Resource();
		$resource = $resource->fetchRow("modulo = '".$modulo."' AND Carica='Si'");
		
		if(isset($resource))
			$resources = $resource->toArray();
			
		$nome_classe = $modulo."_addon";
		
		if(isset($resources)){
			if(class_exists($nome_classe))
			{								
				$obj = new $nome_classe;    	
			    $istanza = $obj->getInstance();
			    unset($obj);			    
			    if(method_exists($istanza,$sezione))
			    	return $istanza->$sezione();
			    else 
			    	return null;
		    } else {
		    	return null;
		    }
		} else {
			return null;
		}
	}
	
	public function checkModulo($modulo)
	{
		$resource = new Resource();
		$resource = $resource->fetchRow("modulo = '".$modulo."' AND Carica='Si'");
		
		if(isset($resource))
			return true;
		return false;
		
	}
	
	public function retriveHome($lingua = 1)
	{
		$voci = new VociMenu();
		$ret = $voci->fetchRow("Home = 'Si' AND lingua_id = {$lingua}");
		
		return (isset($ret)) ? $ret->id : null;
	}
	
	public function fetchModuliMenu()
	{
		$controller = new Resource();
		$controller = $controller->fetchAll("Carica != 'No' && Base != 'Si'");
				
		return (isset($controller)) ? $controller->toArray() : null;
	}
	
	public function getInfoIDfromRole($role)
	{
		switch($role){
			case "home":
							$ret = 2;
							break;
			case "carsystem":
							$ret = 3;
							break;
			case "energy":
							$ret = 4;
							break;
			case "electronics":
							$ret = 5;
							break;
			case "industry":
							$ret = 6;
							break;
			default: $ret = 1;				
		}
		
		return $ret;
	}
	
	/**
	 * Funzione che ritorna il percorso dell'immagine scelta
	 *
	 * array("modifica","elimina","pagina","sposta"
	 * 		 ,"chiave","amministra","cartella","warning")
	 * 
	 * @param string $azione
	 * @param string $path
	 * @return string
	 */
	public function registroIcone($azione="modifica",$path="/public/images/icone/")
	{
		$registro = array();
		
		$registro["modifica"]	= $path."modifica.png";
		$registro["elimina"]	= $path."delete.png";
		$registro["pagina"]		= $path."pagina.png";
		$registro["sposta"]		= $path."sposta.png";
		$registro["chiave"]		= $path."key.png";
		$registro["amministra"] = $path."amministrazione.png";
		$registro["cartella"]	= $path."cartella.png";
		$registro["warning"]	= $path."warning.png";
		
		return $registro[$azione];
	}
	
public function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth )
	{
	    $info = pathinfo($pathToImages);
	    // continue only if this is a JPEG image
	    if ( strtolower($info['extension']) == 'jpg' )
	    {
	      // load image and get image size
	      $img = imagecreatefromjpeg( "{$pathToImages}" );
	      $width = imagesx( $img );
	      $height = imagesy( $img );
	
	      // calculate thumbnail size
	      $new_width = $thumbWidth;
	      $new_height = floor( $height * ( $thumbWidth / $width ) );
	
	      // create a new temporary image
	      $tmp_img = imagecreatetruecolor( $new_width, $new_height );
	
	      // copy and resize old image into new image
	      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
	
	      // save thumbnail into a file
	      imagejpeg( $tmp_img, "{$pathToThumbs}" );
	    }
	}
	
	public function createThumbsHeight( $pathToImages, $pathToThumbs, $thumbHeight )
	{
	    $info = pathinfo($pathToImages);
	    // continue only if this is a JPEG image
	    if ( strtolower($info['extension']) == 'jpg' )
	    {
	      // load image and get image size
	      $img = imagecreatefromjpeg( "{$pathToImages}" );
	      $width = imagesx( $img );
	      $height = imagesy( $img );
	
	      // calculate thumbnail size
	      $new_height = $thumbHeight;
	      $new_width = floor( $width * ( $thumbHeight / $height ) );
	
	      // create a new temporary image
	      $tmp_img = imagecreatetruecolor( $new_width, $new_height );
	
	      // copy and resize old image into new image
	      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
	
	      // save thumbnail into a file
	      imagejpeg( $tmp_img, "{$pathToThumbs}" );
	    }
	}
	
	public function convertiMese($key){
		$mesi=array(
			"January"=>"Gennaio",
			"Febraury"=>"Febbraio",
			"March"=>"Marzo",
			"April"=>"Aprile",
			"May"=>"Maggio",
			"June"=>"Giugno",
			"July"=>"Luglio",
			"August"=>"Agosto",
			"September"=>"Settembre",
			"October"=>"Ottobre",
			"November"=>"Novembre",
			"December"=>"Dicembre"
		);

		return $mesi[$key];
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
