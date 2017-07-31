<?php
class backend_controller_about extends backend_db_about{

    public $edit, $action, $tabs, $search;

    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $country, $language, $languages;
    public $content, $dataType, $refesh_lang, $enable_op, $send = array('openinghours' => '');

    /**
     * @var array, type of website allowed
     */
    public $type = array(
        'org' 		=> array(
            'schema' => 'Organization',
            'label' => 'Organisation'
        ),
        'corp' 		=> array(
            'schema' => 'LocalBusiness',
            'label' => 'Entreprise locale'
        ),
        'store' 	=> array(
            'schema' => 'Store',
            'label' => 'Magasin'
        ),
        'food' 		=> array(
            'schema' => 'FoodEstablishment',
            'label' => 'Restaurant'
        ),
        'place' 	=> array(
            'schema' => 'Place',
            'label' => 'Lieu'
        ),
        'person' 	=> array(
            'schema' => 'Person',
            'label' => 'Personne physique'
        )
    );

    /**
     * @var array, Company informations
     */
    public $company = array(
        'name' 		=> NULL,
        'desc'	    => NULL,
        'slogan'	=> NULL,
        'type' 		=> NULL,
        'eshop' 	=> '0',
        'tva' 		=> NULL,
        'contact' 	=> array(
            'mail' 			=> NULL,
            'click_to_mail' => '0',
            'crypt_mail' 	=> '1',
            'phone' 		=> NULL,
            'mobile' 		=> NULL,
            'click_to_call' => '1',
            'fax' 			=> NULL,
            'adress' 		=> array(
                'adress' 		=> NULL,
                'street' 		=> NULL,
                'postcode' 		=> NULL,
                'city' 			=> NULL
            ),
			'languages' => 'Français'
        ),
        'socials' => array(
            'facebook' 	=> NULL,
            'twitter' 	=> NULL,
            'google' 	=> NULL,
            'linkedin' 	=> NULL,
            'viadeo' 	=> NULL
        ),
        'openinghours' => '0',
        'specifications' => array(
            'Mo' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end' 		=> NULL
            ),
            'Tu' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time'	=> NULL,
                'noon_time' 	=> '0',
                'noon_start'	=> NULL,
                'noon_end'		=> NULL
            ),
            'We' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end' 		=> NULL
            ),
            'Th' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end' 		=> NULL
            ),
            'Fr' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end'		=> NULL
            ),
            'Sa' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end' 		=> NULL
            ),
            'Su' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end' 		=> NULL
            )
        )
    );

    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->language = new backend_controller_language();
        $this->languages = $this->language->setCollection();
        //var_dump($this->languages);

        // --- GET
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }
        /* Global about edition */
        if(http_request::isPost('data_type')){
            $this->dataType = $formClean->simpleClean($_POST['data_type']);
        }
        if(http_request::isPost('company_name')){
            $this->company['name'] = $formClean->simpleClean($_POST['company_name']);
        }
        /*
        if(http_request::isPost('company_slogan')){
            $this->company['slogan'] = $formClean->simpleClean($_POST['company_slogan']);
        }*/
        if(http_request::isPost('company_type')){
            $this->company['type'] = $formClean->simpleClean($_POST['company_type']);
        }
        if(http_request::isPost('company_tva')){
            $this->company['tva'] = $formClean->simpleClean($_POST['company_tva']);
        }
        if(http_request::isPost('company_eshop')){
            $this->company['eshop'] = '1';
        }else{
            $this->company['eshop'] = '0';
        }
        /* Contact about edition */
        if(http_request::isPost('company_mail')){
            $this->company['contact']['mail'] = $formClean->simpleClean($_POST['company_mail']);
        }
        if(http_request::isPost('company_phone')){
            $this->company['contact']['phone'] = $formClean->simpleClean($_POST['company_phone']);
        }
        if(http_request::isPost('company_mobile')){
            $this->company['contact']['mobile'] = $formClean->simpleClean($_POST['company_mobile']);
        }
        if(http_request::isPost('company_mail')){
            $this->company['contact']['fax'] = $formClean->simpleClean($_POST['company_fax']);
        }
        if(http_request::isPost('company_adress')){
            $this->company['contact']['adress'] = $formClean->arrayClean($_POST['company_adress']);
        }
        if(http_request::isPost('click_to_mail')){
            $this->company['contact']['click_to_mail'] = '1';
        }else{
            $this->company['contact']['click_to_mail'] = '0';
        }
        if(http_request::isPost('click_to_call')){
            $this->company['contact']['click_to_call'] = '1';
        }else{
            $this->company['contact']['click_to_call'] = '0';
        }
        if(http_request::isPost('crypt_mail')){
            $this->company['contact']['crypt_mail'] = '1';
        }else{
            $this->company['contact']['crypt_mail'] = '0';
        }
        
        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'company_content') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }

		/* Languages about refreshing */
		if(http_request::isPost('refesh_lang')){
			$this->refesh_lang = true;
		}

		/* Socials links edition */
        if(http_request::isPost('company_socials')){
            $this->company['socials'] = $formClean->arrayClean($_POST['company_socials']);
        }

        /* Opening Hours links edition */
        if(http_request::isPost('enable_op')){
            $this->enable_op = '1';
        }else{
            $this->enable_op = '0';
        }

        if(http_request::isPost('openinghours')){
            $this->send['openinghours'] = $formClean->arrayClean($_POST['openinghours']);
        }
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $context
     * @param string $type
     * @param string|int|null $id
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null) {
        return $this->data->getItems($type, $id, $context);
    }

    /**
     * getTypes
     */
    private function getTypes()
    {
        $this->template->assign('schemaTypes', $this->type);
    }

    /**
     * save data
     */
    private function save(){
        if($this->dataType === 'company'){
            parent::update(array('type'=>'company'),$this->company);
            $this->header->set_json_headers();
            $this->message->json_post_response(true, 'update', $this->company);
        }elseif($this->dataType === 'text'){

            foreach ($this->content as $lang => $content) {
                if (parent::fetchData(array('context' => 'unique', 'type' => 'content'), array('id_lang' => $lang)) != null) {
                    parent::update(array('type' => 'content'), array(
                            'desc'      => $content['company_desc'],
                            'slogan'    => $content['company_slogan'],
                            'content'   => $content['company_content'],
                            'id_lang'   => $lang
                        )
                    );
                }else{
                    parent::insert(array('type' => 'newContent'), array(
                            'desc'      => $content['company_desc'],
                            'slogan'    => $content['company_slogan'],
                            'content'   => $content['company_content'],
                            'id_lang'   => $lang
                        )
                    );
                }
            }

            $this->header->set_json_headers();
            $this->message->json_post_response(true, 'update', $this->content);

        }elseif($this->dataType === 'contact'){

            $this->company['contact']['adress']['adress'] = $this->company['contact']['adress']['street'].', '.$this->company['contact']['adress']['postcode'].' '.$this->company['contact']['adress']['city'];
            parent::update(
                array(
                    'type'=>'contact'
                ),
                $this->company
            );
            $this->header->set_json_headers();
            $this->message->json_post_response(true, 'update', $this->company['contact']['adress']);

        }elseif($this->dataType === 'socials'){
            parent::update(
                array(
                    'type'=>'socials'
                ),
                $this->company
            );
            $this->header->set_json_headers();
            $this->message->json_post_response(true, 'update', $this->company['socials']);

        }elseif($this->dataType === 'enable_op'){
            /* Enable OP */
            parent::update(
                array(
                    'type'=>'enable_op'
                ),
                array(
                    'enable_op' => $this->enable_op
                )
            );
        }elseif($this->dataType === 'openinghours'){
            /* Update openinghours */
            foreach ($this->company['specifications'] as $day => $opt) {
                if(isset($this->send['openinghours'][$day])) {
                    $this->company['specifications'][$day]['open_day'] = '1';

                    if(isset($this->send['openinghours'][$day]['noon_time'])) {
                        $this->company['specifications'][$day]['noon_time'] = '1';

                        $this->company['specifications'][$day]['noon_start'] 	= ($this->send['openinghours'][$day]['noon_start']['hh'] ? ($this->send['openinghours'][$day]['noon_start']['hh'].':'.$this->send['openinghours'][$day]['noon_start']['mm']) : null);
                        $this->company['specifications'][$day]['noon_end'] 	= ($this->send['openinghours'][$day]['noon_end']['hh'] ? ($this->send['openinghours'][$day]['noon_end']['hh'].':'.$this->send['openinghours'][$day]['noon_end']['mm']) : null);
                    } else {
                        $this->company['specifications'][$day]['noon_time'] = '0';
                    }

                    $this->company['specifications'][$day]['open_time'] 	= ($this->send['openinghours'][$day]['open']['hh'] ? ($this->send['openinghours'][$day]['open']['hh'].':'.$this->send['openinghours'][$day]['open']['mm']) : null);
                    $this->company['specifications'][$day]['close_time'] 	= ($this->send['openinghours'][$day]['close']['hh'] ? ($this->send['openinghours'][$day]['close']['hh'].':'.$this->send['openinghours'][$day]['close']['mm']) : null);
                } else {
                    $this->company['specifications'][$day]['open_day'] = '0';
                }
            }

            parent::update(
                array(
                    'type'=>'openinghours'
                ),
                $this->company
            );

            $this->header->set_json_headers();
            $this->message->json_post_response(true, 'update', $this->company['specifications']);
        }
    }
    private function setData($about)
    {
        $schedule = array();

        foreach ($this->company as $info => $value) {
            if($info == 'contact') {
                foreach ($value as $contact_info => $val) {
                    if($contact_info == 'adress') {
                        $this->company['contact'][$contact_info]['adress'] = $about['adress'];
                        $this->company['contact'][$contact_info]['street'] = $about['street'];
                        $this->company['contact'][$contact_info]['postcode'] = $about['postcode'];
                        $this->company['contact'][$contact_info]['city'] = $about['city'];
                    } elseif ($contact_info == 'languages') {
						$this->company['contact'][$contact_info] = $this->getActiveLang();
					} else {
                        $this->company['contact'][$contact_info] = $about[$contact_info];
                    }
                }
            }
            elseif($info == 'socials') {
                foreach ($value as $social_name => $link) {
                    $this->company['socials'][$social_name] = $about[$social_name];
                }
            }
            elseif($info == 'specifications') {
                foreach ($value as $day => $op_info) {
                    foreach ($op_info as $t => $v) {
                        $this->company['specifications'][$day][$t] = $schedule[$day][$t];
                    }
                }
            }
            elseif($info == 'openinghours') {
                $this->company[$info] = $about['openinghours'];

                $op = parent::fetchData(array('context'=>'all','type'=>'op'));
                foreach ($op as $d) {
                    $schedule[$d['day_abbr']] = $d;
                    array_shift($schedule[$d['day_abbr']]);

                    $schedule[$d['day_abbr']]['open_time'] = explode(':',$d['open_time']);
                    $schedule[$d['day_abbr']]['close_time'] = explode(':',$d['close_time']);
                    $schedule[$d['day_abbr']]['noon_start'] = explode(':',$d['noon_start']);
                    $schedule[$d['day_abbr']]['noon_end'] = explode(':',$d['noon_end']);
                }
            }
            else {
                $this->company[$info] = $about[$info];
            }
        }

        return $this->company;
    }
    /**
     * @return array
     */
    private function setItemsData(){
        $data = parent::fetchData(array('context'=>'all','type'=>'content'));
        $newArr = array();
        foreach ($data as $item) {
            $newArr[$item['id_lang']][$item['name_info']] = $item['value_info'];
        }
        return $newArr;
    }

	/**
	 *
	 */
	private function updateLanguages()
	{
		//$langs = parent::getIso();
		$langs = $this->getItems('iso',null,'return');

		$iso = array();
		foreach ($langs as $lang) {
			$iso[] = ucfirst($this->languages[$lang['iso_lang']]);
		}

		if(count($iso) > 1) {
			$languages = implode(',',$iso);
		} else {
			$languages = $iso[0];
		}

		parent::update(
			array(
				'type'=>'languages'
			),
			array('languages' => $languages)
		);
		$this->header->set_json_headers();
		$this->message->json_post_response(true, 'refresh_lang');
	}

	/**
	 * @return array|string
	 */
	private function getActiveLang()
	{
		//$langs = parent::getLanguages();
		$langs = $this->getItems('languages',null,'return');

		$list = array();
		foreach ($langs as $lang) {
			$list[] = ucfirst($lang['name_lang']);
		}

		$langs = implode(', ',$list);

		return $langs;
	}

    /**
     *
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'edit':
                	if(isset($this->refesh_lang) && $this->refesh_lang) {
						$this->updateLanguages();
					} else {
						$this->save();
					}
                    break;
            }
        }
        else {
            $this->modelLanguage->getLanguage();
            $this->getTypes();
            $setInfoData = parent::fetchData(array('context'=>'all','type'=>'info'));
            $newArr = array();
            foreach ($setInfoData as $item) {
                $newArr[$item['name_info']] = $item['value_info'];
            }
            $this->template->assign('contentData',$this->setItemsData());
            $this->template->assign('companyData',$this->setData($newArr));
            $this->template->display('about/index.tpl');
        }
    }
}
?>