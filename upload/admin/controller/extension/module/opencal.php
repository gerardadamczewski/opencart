<?php
class ControllerExtensionModuleOpenCal extends Controller {
	private $error = array();

	public function index() {
                $this->document->addStyle('view/javascript/fullcalendar/fullcalendar.min.css');
                $this->document->addStyle('view/javascript/fullcalendar/fullcalendar.print.min.css', 'stylesheet', 'print');
                $this->document->addScript('view/javascript/fullcalendar/fullcalendar.min.js');
                
                $this->document->addStyle('view/javascript/jquery-timepicker-addon/jquery-ui.min.css');
                $this->document->addScript('view/javascript/jquery-timepicker-addon/jquery-ui.min.js');
                $this->document->addStyle('view/javascript/jquery-timepicker-addon/jquery-ui-timepicker-addon.min.css');
                $this->document->addScript('view/javascript/jquery-timepicker-addon/jquery-ui-timepicker-addon.min.js');
                
		$this->load->language('extension/module/opencal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_opencal', $_POST);

			$this->session->data['success'] = $this->language->get('text_success');
                        
                        $this->response->redirect($this->url->link('extension/module/opencal', 'user_token=' . $this->session->data['user_token']));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/opencal', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/module/opencal', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		if (isset($this->request->post['module_opencal_code'])) {
			$data['module_opencal_code'] = $this->request->post['module_opencal_code'];
		} else {
			$data['module_opencal_code'] = $this->config->get('module_opencal_code');
		}

		if (isset($this->request->post['module_opencal_status'])) {
			$data['module_opencal_status'] = $this->request->post['module_opencal_status'];
		} else {
			$data['module_opencal_status'] = $this->config->get('module_opencal_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/opencal', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/opencal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['module_opencal_code']) {
			$this->error['code'] = $this->language->get('error_code');
		}

		return !$this->error;
	}
}