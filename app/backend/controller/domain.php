<?php
class backend_controller_domain extends backend_db_domain
{
    public $edit, $action, $tabs, $search;
    protected $message, $template, $header, $data;
    public $id_domain,$url_domain,$default_domain;

    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();

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

        // --- POST

        if (http_request::isPost('id')) {
            $this->id_domain = $formClean->simpleClean($_POST['id']);
        }
        if (http_request::isPost('url_domain')) {
            $this->url_domain = $formClean->simpleClean($_POST['url_domain']);
        }
        if (http_request::isPost('default_domain')) {
            $this->default_domain = $formClean->numeric($_POST['default_domain']);
        }

        // --- Search
        if (http_request::isGet('search')) {
            $this->search = $formClean->arrayClean($_GET['search']);
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
     * @param null $id_domain
     */
    private function getItemsDomain($id_domain = null){
        if($id_domain) {
            $data = parent::fetchData(array('context'=>'unique','type'=>'domain'),array('id' => $id_domain));
            $this->template->assign('domain',$data);
        }else{
            $this->getItems('domain');
        }
    }
    /**
     * Insertion de données
     * @param $data
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'newDomain':
                parent::insert(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'url_domain'      => $this->url_domain,
                        'default_domain'  => $this->default_domain
                    )
                );
                $this->header->set_json_headers();
                $this->message->json_post_response(true,'add_redirect');
                break;
        }
    }

    /**
     * Mise a jour des données
     * @param $data
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'domain':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'id_domain'       => $this->id_domain,
                        'url_domain'      => $this->url_domain,
                        'default_domain'  => $this->default_domain
                    )
                );
                break;
        }
    }
    /**
     * Insertion de données
     * @param $data
     */
    private function del($data){
        switch($data['type']){
            case 'delDomain':
                parent::delete(
                    array(
                        'context'   =>    'domain',
                        'type'      =>    $data['type']
                    ),
                    $data['data']
                );
                $this->header->set_json_headers();
                $this->message->json_post_response(true,'delete',$data['data']);
                break;
        }
    }
    /**
     *
     */
    public function run()
    {
        if (isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->url_domain)){
                        $this->add(
                            array(
                                'type'=>'newDomain'
                            )
                        );
                    }else{
                        $this->template->display('domain/add.tpl');
                    }
                    break;
                case 'edit':
                    if (isset($this->url_domain)) {
                        $this->upd(
                            array(
                                'type' => 'domain'
                            )
                        );
                        $this->header->set_json_headers();
                        $this->message->json_post_response(true,'update',$this->id_domain);
                    }else{
                        $this->getItemsDomain($this->edit);
                        $this->template->display('domain/edit.tpl');
                    }
                    break;
                case 'delete':
                    if(isset($this->id_domain)) {
                        $this->del(
                            array(
                                'type'=>'delDomain',
                                'data'=>array(
                                    'id' => $this->id_domain
                                )
                            )
                        );
                    }
                    break;
            }
        }else{
            $this->getItemsDomain();
            $this->template->display('domain/index.tpl');
        }
    }

}